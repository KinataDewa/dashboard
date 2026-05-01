<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use App\Models\Absensi;
use App\Models\Kelas;
use Carbon\Carbon;

class HistorisKinataSeeder extends Seeder
{
    public function run(): void
    {
        $kinata = Mahasiswa::where('nim', '2341720099')->firstOrFail();
        $kelas  = Kelas::where('nama', 'TI3D')->firstOrFail();

        $grade = fn(float $n): string => match(true) {
            $n >= 80 => 'A',
            $n >= 70 => 'B',
            $n >= 60 => 'C',
            $n >= 50 => 'D',
            default  => 'E',
        };

        $matkulHistoris = [

            // ── Semester 3 ────────────────────────────────────
            ['kode'=>'TDH301','nama'=>'Algoritma & Pemrograman',    'sks'=>3,'semester'=>3],
            ['kode'=>'TDH302','nama'=>'Sistem Operasi',             'sks'=>3,'semester'=>3],
            ['kode'=>'TDH303','nama'=>'Jaringan Komputer Dasar',    'sks'=>3,'semester'=>3],
            ['kode'=>'TDH304','nama'=>'Basis Data',                 'sks'=>3,'semester'=>3],
            ['kode'=>'TDH305','nama'=>'Matematika Rekayasa',        'sks'=>2,'semester'=>3],
            ['kode'=>'TDH306','nama'=>'Etika Profesi IT',           'sks'=>2,'semester'=>3],

            // ── Semester 4 ────────────────────────────────────
            ['kode'=>'TDH401','nama'=>'Pemrograman Berorientasi Objek','sks'=>3,'semester'=>4],
            ['kode'=>'TDH402','nama'=>'Administrasi Jaringan',      'sks'=>3,'semester'=>4],
            ['kode'=>'TDH403','nama'=>'Pengembangan Web',           'sks'=>3,'semester'=>4],
            ['kode'=>'TDH404','nama'=>'Sistem Informasi',           'sks'=>3,'semester'=>4],
            ['kode'=>'TDH405','nama'=>'Statistika & Probabilitas',  'sks'=>2,'semester'=>4],
            ['kode'=>'TDH406','nama'=>'Keamanan Sistem',            'sks'=>2,'semester'=>4],

            // ── Semester 5 ────────────────────────────────────
            ['kode'=>'TDH501','nama'=>'Pemrograman Mobile',         'sks'=>3,'semester'=>5],
            ['kode'=>'TDH502','nama'=>'Cloud Computing',            'sks'=>3,'semester'=>5],
            ['kode'=>'TDH503','nama'=>'Data Mining',                'sks'=>3,'semester'=>5],
            ['kode'=>'TDH504','nama'=>'Rekayasa Perangkat Lunak',   'sks'=>3,'semester'=>5],
            ['kode'=>'TDH505','nama'=>'Sistem Terdistribusi',       'sks'=>2,'semester'=>5],
            ['kode'=>'TDH506','nama'=>'Kerja Praktek',              'sks'=>2,'semester'=>5],
        ];

        $matkuls = [];
        foreach ($matkulHistoris as $m) {
            $mk = MataKuliah::updateOrCreate(
                ['kode' => $m['kode']],
                [
                    'nama'     => $m['nama'],
                    'sks'      => $m['sks'],
                    'semester' => $m['semester'],
                    'kelas_id' => $kelas->id,
                    'dosen_id' => null, // historis tidak perlu dosen
                ]
            );
            $matkuls[$m['kode']] = $mk;
        }

        $nilaiHistoris = [

            // ── Semester 3 — Bagus, semangat ─────────────────
            'TDH301' => [88, 85, 87], // A
            'TDH302' => [82, 80, 83], // A
            'TDH303' => [85, 82, 84], // A
            'TDH304' => [80, 78, 81], // A
            'TDH305' => [78, 75, 77], // B
            'TDH306' => [90, 88, 89], // A

            // ── Semester 4 — Mulai sibuk, nilai turun ─────────
            'TDH401' => [75, 72, 74], // B
            'TDH402' => [72, 68, 70], // B
            'TDH403' => [78, 75, 76], // B
            'TDH404' => [65, 62, 63], // C ← mulai ada C
            'TDH405' => [70, 68, 69], // B
            'TDH406' => [62, 58, 60], // C ← C lagi

            // ── Semester 5 — Mulai bermasalah ─────────────────
            'TDH501' => [72, 68, 70], // B
            'TDH502' => [65, 60, 62], // C
            'TDH503' => [58, 52, 55], // D ← pertama kali D
            'TDH504' => [70, 65, 67], // B
            'TDH505' => [60, 55, 57], // C
            'TDH506' => [75, 70, 72], // B (KP masih oke)
        ];

        $absensiHistoris = [

            // ── Semester 3 — Rajin ────────────────────────────
            'TDH301' => [40, 2, 0, 0],
            'TDH302' => [38, 2, 2, 0],
            'TDH303' => [40, 2, 0, 0],
            'TDH304' => [40, 0, 2, 0],
            'TDH305' => [28, 0, 0, 0],
            'TDH306' => [28, 0, 0, 0],

            // ── Semester 4 — Mulai bolos ──────────────────────
            'TDH401' => [36, 2, 2, 2],
            'TDH402' => [34, 2, 2, 4],
            'TDH403' => [36, 2, 0, 4],
            'TDH404' => [32, 2, 2, 6],
            'TDH405' => [22, 0, 2, 4],
            'TDH406' => [24, 0, 0, 4],

            // ── Semester 5 — Sering bolos ─────────────────────
            'TDH501' => [30, 2, 2, 8],
            'TDH502' => [28, 2, 2, 10],
            'TDH503' => [26, 2, 0, 14], // ⚠ waspada
            'TDH504' => [30, 2, 2, 8],
            'TDH505' => [18, 0, 2, 8],
            'TDH506' => [26, 0, 0, 2], // KP tetap hadir
        ];

        $tahunMap = [3 => '2023/2024', 4 => '2023/2024', 5 => '2024/2025'];
        $genap    = [3, 5]; // semester ganjil/genap
        $startMap = [
            3 => Carbon::create(2024, 2, 5),
            4 => Carbon::create(2024, 8, 5),
            5 => Carbon::create(2025, 2, 3),
        ];

        // Insert nilai historis
        foreach ($nilaiHistoris as $kode => $nilaiArr) {
            if (!isset($matkuls[$kode])) continue;

            $mk    = $matkuls[$kode];
            $sem   = $mk->semester;
            [$tugas, $uts, $uas] = $nilaiArr;
            $akhir = round(($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);

            Nilai::updateOrCreate(
                [
                    'mahasiswa_id'   => $kinata->id,
                    'mata_kuliah_id' => $mk->id,
                    'tahun_akademik' => $tahunMap[$sem],
                ],
                [
                    'semester'    => $sem,
                    'nilai_tugas' => $tugas,
                    'nilai_uts'   => $uts,
                    'nilai_uas'   => $uas,
                    'nilai_akhir' => $akhir,
                    'grade'       => $grade($akhir),
                ]
            );
        }

        // Insert absensi historis
        foreach ($absensiHistoris as $kode => $absenArr) {
            if (!isset($matkuls[$kode])) continue;

            $mk  = $matkuls[$kode];
            $sem = $mk->semester;
            [$hadir, $izin, $sakit, $alpha] = $absenArr;

            Absensi::updateOrCreate(
                [
                    'mahasiswa_id'   => $kinata->id,
                    'mata_kuliah_id' => $mk->id,
                    'tahun_akademik' => $tahunMap[$sem],
                ],
                [
                    'semester'     => $sem,
                    'tanggal'      => $startMap[$sem]->copy()->addWeeks(13)->format('Y-m-d'),
                    'pertemuan_ke' => 14,
                    'jam_hadir'    => $hadir,
                    'jam_izin'     => $izin,
                    'jam_sakit'    => $sakit,
                    'jam_alpha'    => $alpha,
                ]
            );
        }

        // ── Summary di console ────────────────────────────────
        $this->command->info('');
        $this->command->info('✅ Data historis Kinata Dewa berhasil di-seed!');
        $this->command->info('');
        $this->command->info('   Semester 3 (2023/2024 Genap)');
        $this->command->info('   → Nilai  : A semua, rajin kuliah');
        $this->command->info('   → Absensi: Alpha 0-2 jam per matkul');
        $this->command->info('');
        $this->command->info('   Semester 4 (2023/2024 Ganjil)');
        $this->command->info('   → Nilai  : B dominan, mulai ada C');
        $this->command->info('   → Absensi: Alpha 2-6 jam per matkul');
        $this->command->info('');
        $this->command->info('   Semester 5 (2024/2025 Genap)');
        $this->command->info('   → Nilai  : Ada D pertama (Data Mining)');
        $this->command->info('   → Absensi: Alpha 8-14 jam, mulai waspada');
        $this->command->info('');
        $this->command->info('   Semester 6 (sudah ada di seeder utama)');
        $this->command->info('   → Berisiko: nilai D + alpha 14j waspada');
    }
}