<?php
namespace App\Services;

use Illuminate\Support\Collection;

class BerisikoService
{
    /**
     * Filter dan transform koleksi mahasiswa menjadi daftar berisiko.
     * Menggunakan semester terakhir masing-masing untuk nilai D/E dan alpha.
     * Relasi nilais, absensis, kelas, dosen, user harus sudah di-eager-load.
     */
    public static function filterBerisiko(Collection $mahasiswas, string $filterJenis): Collection
    {
        return $mahasiswas->filter(function ($mhs) use ($filterJenis) {
            [$punya_de, $punya_alpha] = self::hitungRisiko($mhs);
            return match ($filterJenis) {
                'nilai'   => $punya_de,
                'absensi' => $punya_alpha,
                default   => $punya_de || $punya_alpha,
            };
        })->map(function ($mhs) {
            $semNilai   = $mhs->nilais->max('semester') ?? 0;
            $nilaiDE    = $semNilai > 0
                ? $mhs->nilais->where('semester', $semNilai)->whereIn('grade', ['D', 'E'])
                : collect();

            $semAlpha   = $mhs->absensis->max('semester') ?? 0;
            $totalAlpha = $semAlpha > 0
                ? (int) $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha')
                : 0;

            $kategori = [];
            if ($nilaiDE->isNotEmpty()) $kategori[] = 'nilai';
            if ($totalAlpha >= 18)      $kategori[] = 'absensi';

            return [
                'id'          => $mhs->id,
                'nim'         => $mhs->nim,
                'nama'        => $mhs->nama ?? $mhs->user->name ?? '-',
                'kelas'       => $mhs->kelas->nama ?? '-',
                'dosen_pa'    => optional($mhs->dosen)->nama ?? '-',
                'ipk'         => number_format($mhs->ipk ?? 0, 2),
                'jumlah_de'   => $nilaiDE->count(),
                'total_alpha' => $totalAlpha,
                'kategori'    => $kategori,
            ];
        })->sortByDesc(fn($m) => count($m['kategori']))->values();
    }

    public static function buildSummary(Collection $semua, Collection $berisiko): array
    {
        return [
            'total_mahasiswa'   => $semua->count(),
            'total_berisiko'    => $berisiko->count(),
            'berisiko_nilai'    => $berisiko->filter(fn($m) => in_array('nilai', $m['kategori']))->count(),
            'berisiko_absensi'  => $berisiko->filter(fn($m) => in_array('absensi', $m['kategori']))->count(),
            'berisiko_keduanya' => $berisiko->filter(fn($m) => count($m['kategori']) >= 2)->count(),
        ];
    }

    private static function hitungRisiko($mhs): array
    {
        $semNilai = $mhs->nilais->max('semester') ?? 0;
        $punya_de = $semNilai > 0 && $mhs->nilais
            ->where('semester', $semNilai)
            ->whereIn('grade', ['D', 'E'])
            ->isNotEmpty();

        $semAlpha   = $mhs->absensis->max('semester') ?? 0;
        $totalAlpha = $semAlpha > 0
            ? $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha')
            : 0;

        return [$punya_de, $totalAlpha >= 18];
    }
}
