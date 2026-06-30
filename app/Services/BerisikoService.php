<?php
namespace App\Services;

use Illuminate\Support\Collection;

class BerisikoService
{
    // Label & warna untuk setiap kategori risiko
    public static function labelKategori(string $kategori): array
    {
        return match($kategori) {
            'sp1'       => ['label' => 'SP I (Alpha ≥18j)',   'color' => '#F59E0B', 'bg' => '#FEF9C3', 'icon' => 'bi-clock-history'],
            'sp2'       => ['label' => 'SP II (Alpha ≥36j)',  'color' => '#EA580C', 'bg' => '#FEF3C7', 'icon' => 'bi-clock-fill'],
            'sp3'       => ['label' => 'SP III (Alpha ≥47j)', 'color' => '#DC2626', 'bg' => '#FEE2E2', 'icon' => 'bi-alarm-fill'],
            'ps'        => ['label' => 'Putus Studi (≥56j)',  'color' => '#7F1D1D', 'bg' => '#FEE2E2', 'icon' => 'bi-x-octagon-fill'],
            'nilai_e'   => ['label' => 'Nilai E',             'color' => '#991B1B', 'bg' => '#FEE2E2', 'icon' => 'bi-x-circle-fill'],
            'nilai_d'   => ['label' => 'Nilai D >3 Matkul',  'color' => '#B45309', 'bg' => '#FEF9C3', 'icon' => 'bi-exclamation-circle-fill'],
            'ips_rendah'=> ['label' => 'IPS < 2.00',          'color' => '#7C3AED', 'bg' => '#EDE9FE', 'icon' => 'bi-graph-down-arrow'],
            default     => ['label' => $kategori,             'color' => '#64748B', 'bg' => '#F1F5F9', 'icon' => 'bi-exclamation'],
        };
    }

    // Tingkat keparahan (untuk sorting)
    public static function tingkatKeparahan(array $kategori): int
    {
        $skor = 0;
        foreach ($kategori as $k) {
            $skor += match($k) {
                'ps'         => 100,
                'sp3'        => 80,
                'nilai_e'    => 70,
                'sp2'        => 60,
                'nilai_d'    => 50,
                'ips_rendah' => 40,
                'sp1'        => 30,
                default      => 10,
            };
        }
        return $skor;
    }

    // Filter dan map mahasiswa berisiko
    // $semesterAktif: jika > 0, evaluasi menggunakan semester tersebut (bukan max per mahasiswa)
    public static function filterAndMap(Collection $semuaMahasiswa, string $filterJenis = 'semua', int $semesterAktif = 0): Collection
    {
        return $semuaMahasiswa
            ->map(fn ($mhs) => ['mhs' => $mhs, 'kategori' => $mhs->getKategoriRisiko($semesterAktif)])
            ->filter(function ($item) use ($filterJenis) {
                $k = $item['kategori'];
                if (empty($k)) return false;
                return match ($filterJenis) {
                    'nilai_e'    => in_array('nilai_e', $k),
                    'nilai_d'    => in_array('nilai_d', $k),
                    'ips_rendah' => in_array('ips_rendah', $k),
                    'sp1'        => in_array('sp1', $k),
                    'sp2'        => in_array('sp2', $k),
                    'sp3'        => in_array('sp3', $k),
                    'ps'         => in_array('ps', $k),
                    'alpha'      => array_intersect(['sp1','sp2','sp3','ps'], $k) !== [],
                    'nilai'      => array_intersect(['nilai_e','nilai_d'], $k) !== [],
                    default      => true,
                };
            })
            ->map(function ($item) use ($semesterAktif) {
                $mhs      = $item['mhs'];
                $kategori = $item['kategori'];

                $semNilai = $semesterAktif > 0 ? $semesterAktif : ($mhs->nilais->max('semester') ?? 0);
                $semAlpha = $semesterAktif > 0 ? $semesterAktif : ($mhs->absensis->max('semester') ?? 0);

                $totalAlpha = $semAlpha > 0
                    ? (int) $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha')
                    : 0;
                $nilaiDE = $semNilai > 0
                    ? $mhs->nilais->where('semester', $semNilai)->whereIn('grade', ['D', 'E'])
                    : collect();
                $jumlahD = $semNilai > 0
                    ? $mhs->nilais->where('semester', $semNilai)->where('grade', 'D')->count()
                    : 0;
                $ips = $mhs->getIpSemester($semNilai);

                $jamKompenSelesai = $mhs->relationLoaded('kompensasis')
                    ? (int) $mhs->kompensasis->where('semester', $semAlpha)->where('status', 'lunas')->sum('jam_kompen_wajib')
                    : 0;
                $alphaEfektif = max(0, $totalAlpha - (int) ($jamKompenSelesai / 2));

                // Resolve kelas dari pivot → kelas.semester
                $kelasNama = $semesterAktif > 0
                    ? (\App\Models\KelasMahasiswa::where('mahasiswa_id', $mhs->id)
                        ->whereHas('kelas', fn($q) => $q->where('semester', $semesterAktif))
                        ->with('kelas')
                        ->first()?->kelas?->nama ?? $mhs->kelas?->nama ?? '-')
                    : ($mhs->kelas?->nama ?? '-');

                return [
                    'id'            => $mhs->id,
                    'nim'           => $mhs->nim,
                    'nama'          => $mhs->nama ?? $mhs->user->name ?? '-',
                    'kelas'         => $kelasNama,
                    'dosen_pa'      => optional($mhs->dosenPa)->nama ?? '-',
                    'ipk'           => number_format($mhs->ipk ?? 0, 2),
                    'ips'           => number_format($ips, 2),
                    'jumlah_de'     => $nilaiDE->count(),
                    'jumlah_d'      => $jumlahD,
                    'total_alpha'   => $totalAlpha,
                    'alpha_efektif' => $alphaEfektif,
                    'kategori'      => $kategori,
                    'skor'          => self::tingkatKeparahan($kategori),
                ];
            })
            ->sortBy('nama', SORT_NATURAL | SORT_FLAG_CASE)
            ->values();
    }

    // Konversi nilai angka ke grade sesuai pedoman Polinema D4 TI
    public static function nilaiToGrade(float $nilai): string
    {
        if ($nilai >= 80) return 'A';
        if ($nilai >= 73) return 'B+';
        if ($nilai >= 65) return 'B';
        if ($nilai >= 60) return 'C+';
        if ($nilai >= 50) return 'C';
        if ($nilai >= 39) return 'D';
        return 'E';
    }

    // Konversi grade ke nilai setara (bobot)
    public static function gradeToBobot(string $grade): float
    {
        return match($grade) {
            'A'  => 4.0,
            'B+' => 3.5,
            'B'  => 3.0,
            'C+' => 2.5,
            'C'  => 2.0,
            'D'  => 1.0,
            default => 0.0,
        };
    }
}
