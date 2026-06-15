<?php
namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\Dosen;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RaporImport
{
    public string $angkatan = '';

    private int $importedMahasiswa = 0;
    private int $createdMahasiswa  = 0;
    private int $importedNilai     = 0;
    private int $importedAbsensi   = 0;
    private int $skipped           = 0;
    private array $errors          = [];

    public function import(string $filePath): void
    {
        $spreadsheet = IOFactory::load($filePath);

        $mkRows       = $this->sheetToAssoc($spreadsheet, 'MATA_KULIAH');
        $mahasiswaRows = $this->sheetToAssoc($spreadsheet, 'MAHASISWA');
        $nilaiRows    = $this->sheetToAssoc($spreadsheet, 'NILAI');
        $absensiRows  = $this->sheetToAssoc($spreadsheet, 'ABSENSI');

        // 1. Upsert mata kuliah
        $this->importMataKuliah($mkRows);

        // 2. Build NIM → semester map dari NILAI untuk keperluan kelas
        $nimSemesterMap  = [];
        $nimTahunAkadMap = [];
        foreach ($nilaiRows as $row) {
            $nim = trim((string) ($row['NIM'] ?? ''));
            $sem = (int) ($row['SEMESTER'] ?? 0);
            if ($nim && $sem && !isset($nimSemesterMap[$nim])) {
                $nimSemesterMap[$nim] = $sem;
            }
        }

        // 3. Import mahasiswa (sekaligus buat dosen & kelas jika belum ada)
        //    Simpan NIM → tahun_akademik untuk import nilai
        foreach ($mahasiswaRows as $row) {
            $nim       = trim((string) ($row['NIM'] ?? ''));
            $tahunAkad = trim((string) ($row['TAHUN_AKADEMIK'] ?? ''));
            if ($nim && $tahunAkad) {
                $nimTahunAkadMap[$nim] = $tahunAkad;
            }
        }
        $this->importMahasiswa($mahasiswaRows, $nimSemesterMap);

        // 4. Build NIM → Mahasiswa cache (setelah semua mahasiswa sudah ada di DB)
        $nimCache = Mahasiswa::whereIn('nim', array_column($mahasiswaRows, 'NIM'))
            ->get()->keyBy('nim');

        // 5. Import nilai
        $this->importNilai($nilaiRows, $nimCache, $nimTahunAkadMap);

        // 6. Import absensi
        $this->importAbsensi($absensiRows, $nimCache);
    }

    // ── Sheet parser ──────────────────────────────────────────

    private function sheetToAssoc(\PhpOffice\PhpSpreadsheet\Spreadsheet $spreadsheet, string $sheetName): array
    {
        $sheet = $spreadsheet->getSheetByName($sheetName);
        if (!$sheet) return [];

        $raw = $sheet->toArray(null, true, true, false);
        if (count($raw) < 2) return [];

        $headers = array_map(fn($h) => strtoupper(trim((string) $h)), $raw[0]);
        $result  = [];

        for ($i = 1; $i < count($raw); $i++) {
            $row = $raw[$i];
            if (empty(array_filter(array_map('strval', $row)))) continue;

            $assoc = [];
            foreach ($headers as $j => $header) {
                $assoc[$header] = $row[$j] ?? null;
            }
            $result[] = $assoc;
        }

        return $result;
    }

    // ── Import methods ────────────────────────────────────────

    private function importMataKuliah(array $rows): void
    {
        foreach ($rows as $row) {
            $kode = trim((string) ($row['KODE'] ?? ''));
            $nama = trim((string) ($row['NAMA'] ?? ''));
            $sks  = (int) ($row['SKS'] ?? 0);

            if (!$kode || !$nama) continue;

            MataKuliah::firstOrCreate(
                ['kode' => $kode],
                ['nama' => $nama, 'sks' => $sks]
            );
        }
    }

    private function importMahasiswa(array $rows, array $nimSemesterMap): void
    {
        foreach ($rows as $row) {
            $nim       = trim((string) ($row['NIM'] ?? ''));
            $nama      = trim((string) ($row['NAMA'] ?? ''));
            $kelasNama = trim((string) ($row['KELAS'] ?? ''));
            $angkatan  = trim((string) ($row['ANGKATAN'] ?? ''));
            $namaDosen = trim((string) ($row['DPA'] ?? ''));
            $prodi     = trim((string) ($row['PRODI'] ?? ''));
            $tahunAkad = trim((string) ($row['TAHUN_AKADEMIK'] ?? ''));

            if (!$nim || !$nama) continue;

            $semester = $nimSemesterMap[$nim] ?? 1;

            $dosen = $namaDosen ? $this->resolveOrCreateDosen($namaDosen) : null;
            $kelas = $kelasNama
                ? $this->resolveOrCreateKelas($kelasNama, $semester, $prodi, $tahunAkad, $dosen)
                : null;

            // Gunakan angkatan dari input form jika tersedia, fallback ke kolom Excel
            $angkatanFinal = $this->angkatan ?: $angkatan;
            $mhs = $this->resolveOrCreateMahasiswa($nim, $nama, $angkatanFinal, $kelas, $dosen);
            if ($mhs) {
                // Buat/update pivot kelas_mahasiswa per semester
                if ($kelas && $tahunAkad && $semester) {
                    KelasMahasiswa::updateOrCreate(
                        [
                            'mahasiswa_id'   => $mhs->id,
                            'semester'       => $semester,
                            'tahun_akademik' => $tahunAkad,
                        ],
                        ['kelas_id' => $kelas->id]
                    );
                }

                // Update mahasiswas.kelas_id ke kelas semester terbesar
                $latestPivot = KelasMahasiswa::where('mahasiswa_id', $mhs->id)
                    ->orderBy('semester', 'desc')
                    ->first();
                if ($latestPivot) {
                    $mhs->update([
                        'kelas_id'    => $latestPivot->kelas_id,
                        'dosen_pa_id' => $dosen?->id ?? $mhs->dosen_pa_id,
                    ]);
                }

                $this->importedMahasiswa++;
            } else {
                $this->skipped++;
            }
        }
    }

    private function importNilai(array $rows, $nimCache, array $nimTahunAkadMap): void
    {
        foreach ($rows as $row) {
            $nim        = trim((string) ($row['NIM'] ?? ''));
            $kodeMk     = trim((string) ($row['KODE_MK'] ?? ''));
            $namaMk     = trim((string) ($row['NAMA_MK'] ?? ''));
            $sks        = (int) ($row['SKS'] ?? 0);
            $semester   = (int) ($row['SEMESTER'] ?? 0);
            $nilaiAkhir = floatval(str_replace(',', '.', $row['NILAI_AKHIR'] ?? 0));
            $grade      = strtoupper(trim((string) ($row['GRADE'] ?? '')));

            if (!$nim || !$kodeMk || $semester <= 0) continue;

            $mahasiswa = $nimCache[$nim] ?? Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                $this->errors[] = "NILAI: NIM {$nim} tidak ditemukan.";
                continue;
            }

            $matkul = MataKuliah::firstOrCreate(
                ['kode' => $kodeMk],
                ['nama' => $namaMk ?: $kodeMk, 'sks' => $sks]
            );

            Nilai::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id, 'mata_kuliah_id' => $matkul->id, 'semester' => $semester],
                [
                    'nilai_akhir'   => round($nilaiAkhir, 2),
                    'grade'         => $grade ?: $this->gradeFromNilai($nilaiAkhir),
                    'nilai_tugas'   => round($nilaiAkhir, 2),
                    'nilai_uts'     => round($nilaiAkhir, 2),
                    'nilai_uas'     => round($nilaiAkhir, 2),
                    'tahun_akademik' => $nimTahunAkadMap[$nim] ?? null,
                ]
            );
            $this->importedNilai++;
        }
    }

    private function importAbsensi(array $rows, $nimCache): void
    {
        foreach ($rows as $row) {
            $nim      = trim((string) ($row['NIM'] ?? ''));
            $semester = (int) ($row['SEMESTER'] ?? 0);
            $hadir    = (int) ($row['JAM_HADIR'] ?? 0);
            $izin     = (int) ($row['JAM_IZIN'] ?? 0);
            $sakit    = (int) ($row['JAM_SAKIT'] ?? 0);
            $alpha    = (int) ($row['JAM_ALPHA'] ?? 0);

            if (!$nim || $semester <= 0) continue;

            $mahasiswa = $nimCache[$nim] ?? Mahasiswa::where('nim', $nim)->first();
            if (!$mahasiswa) {
                $this->errors[] = "ABSENSI: NIM {$nim} tidak ditemukan.";
                continue;
            }

            Absensi::updateOrCreate(
                ['mahasiswa_id' => $mahasiswa->id, 'semester' => $semester],
                ['jam_hadir' => $hadir, 'jam_izin' => $izin, 'jam_sakit' => $sakit, 'jam_alpha' => $alpha]
            );
            $this->importedAbsensi++;
        }
    }

    // ── Resolve / Create helpers ──────────────────────────────

    private function resolveOrCreateDosen(string $nama): ?Dosen
    {
        if (!$nama) return null;

        $normalized = Dosen::normalizasiNama($nama);

        if ($normalized === '') return null;

        // Cari berdasarkan nama yang sudah dinormalisasi (exact match setelah normalisasi)
        $dosen = Dosen::where('nama_normalized', $normalized)->first();

        if ($dosen) return $dosen;

        // Fallback: cari 3 kata pertama nama (antisipasi kolom belum ter-populate)
        $words     = explode(' ', $normalized);
        $firstPart = implode(' ', array_slice($words, 0, 3));
        if (strlen($firstPart) >= 5) {
            $dosen = Dosen::where('nama_normalized', 'LIKE', $firstPart . '%')->first();
        }

        if ($dosen) return $dosen;

        return DB::transaction(function () use ($nama, $normalized) {
            $slug  = preg_replace('/[^a-z0-9]+/', '.', $normalized);
            $email = $this->uniqueEmail($slug . '@dosen.polinema.ac.id');

            $user = User::firstOrCreate(
                ['email' => $email],
                ['name' => $nama, 'password' => Hash::make(Str::random(16))]
            );
            if ($user->wasRecentlyCreated) {
                $user->assignRole('dosen');
            }

            $nip = 'RAPOR-' . strtoupper(substr(md5($normalized), 0, 8));

            return Dosen::firstOrCreate(
                ['nip' => $nip],
                [
                    'user_id'         => $user->id,
                    'nama'            => $nama,
                    'nama_normalized' => $normalized,
                    'no_hp'           => '',
                ]
            );
        });
    }

    private function resolveOrCreateKelas(string $nama, int $semester, string $prodi, string $tahunAkad, ?Dosen $dosen): ?Kelas
    {
        if (!$nama) return null;

        return Kelas::firstOrCreate(
            [
                'nama'           => $nama,
                'semester'       => $semester,
                'tahun_akademik' => $tahunAkad,
            ],
            [
                'prodi'       => $prodi ?: 'Teknologi Informasi',
                'dosen_pa_id' => $dosen?->id,
            ]
        );
    }

    private function resolveOrCreateMahasiswa(string $nim, string $nama, string $angkatan, ?Kelas $kelas, ?Dosen $dosen): ?Mahasiswa
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            $updates = [];
            if (!$mahasiswa->kelas_id && $kelas)    $updates['kelas_id']    = $kelas->id;
            if (!$mahasiswa->dosen_pa_id && $dosen) $updates['dosen_pa_id'] = $dosen->id;
            // Override angkatan jika diberikan dari form import
            if ($this->angkatan && $mahasiswa->angkatan !== $this->angkatan) {
                $updates['angkatan'] = $this->angkatan;
            }
            if ($updates) $mahasiswa->update($updates);
            return $mahasiswa;
        }

        try {
            return DB::transaction(function () use ($nim, $nama, $angkatan, $kelas, $dosen) {
                $email = $this->uniqueEmail($nim . '@student.polinema.ac.id');

                $user = User::firstOrCreate(
                    ['email' => $email],
                    ['name' => $nama ?: $nim, 'password' => Hash::make($nim)]
                );
                if ($user->wasRecentlyCreated) {
                    $user->assignRole('mahasiswa');
                }

                $mhs = Mahasiswa::create([
                    'user_id'     => $user->id,
                    'nim'         => $nim,
                    'nama'        => $nama ?: $nim,
                    'kelas_id'    => $kelas?->id,
                    'dosen_pa_id' => $dosen?->id,
                    'angkatan'    => $angkatan ?: ($this->angkatan ?: substr($nim, 0, 4)),
                    'status'      => 'aktif',
                ]);

                $this->createdMahasiswa++;
                return $mhs;
            });
        } catch (\Exception $e) {
            Log::warning("RaporImport: gagal buat mahasiswa NIM {$nim}: " . $e->getMessage());
            $this->errors[] = "Gagal buat mahasiswa NIM {$nim}: " . $e->getMessage();
            return null;
        }
    }

    // ── Helpers ───────────────────────────────────────────────

    private function gradeFromNilai(float $ns): string
    {
        return match(true) {
            $ns >= 80 => 'A',
            $ns >= 73 => 'B+',
            $ns >= 65 => 'B',
            $ns >= 60 => 'C+',
            $ns >= 50 => 'C',
            $ns >= 39 => 'D',
            default   => 'E',
        };
    }

    private function uniqueEmail(string $email): string
    {
        if (!User::where('email', $email)->exists()) return $email;
        [$local, $domain] = explode('@', $email, 2);
        $i = 2;
        while (User::where('email', "{$local}{$i}@{$domain}")->exists()) $i++;
        return "{$local}{$i}@{$domain}";
    }

    // ── Getters ───────────────────────────────────────────────

    public function getImportedMahasiswaCount(): int { return $this->importedMahasiswa; }
    public function getCreatedMahasiswaCount(): int  { return $this->createdMahasiswa; }
    public function getImportedNilaiCount(): int     { return $this->importedNilai; }
    public function getImportedAbsensiCount(): int   { return $this->importedAbsensi; }
    public function getSkippedCount(): int           { return $this->skipped; }
    public function getErrors(): array               { return $this->errors; }
}
