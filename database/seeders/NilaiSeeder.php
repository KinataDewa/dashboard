<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
 
class NilaiSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas = Mahasiswa::where('kelas_id', function($q) {
            $q->select('id')->from('kelas')->where('nama', 'TI3C');
        })->get();
 
        $matkuls = MataKuliah::where('semester', 6)->get();
 
        // Nilai spesifik Aldi (mahasiswa test)
        $aldiNilais = [
            'TI601' => ['tugas'=>85,'uts'=>78,'uas'=>82],
            'TI602' => ['tugas'=>80,'uts'=>75,'uas'=>79],
            'TI603' => ['tugas'=>88,'uts'=>82,'uas'=>86],
            'TI604' => ['tugas'=>72,'uts'=>70,'uas'=>68],
            'TI605' => ['tugas'=>55,'uts'=>48,'uas'=>50], // grade D
            'TI606' => ['tugas'=>78,'uts'=>76,'uas'=>80],
            'TI607' => ['tugas'=>0, 'uts'=>0, 'uas'=>0],
        ];
 
        // Nilai spesifik mahasiswa 001 (berisiko)
        $riskyNilais = [
            'TI601' => ['tugas'=>52,'uts'=>48,'uas'=>45],
            'TI602' => ['tugas'=>48,'uts'=>42,'uas'=>40],
            'TI603' => ['tugas'=>58,'uts'=>52,'uas'=>50],
            'TI604' => ['tugas'=>45,'uts'=>40,'uas'=>38],
            'TI605' => ['tugas'=>35,'uts'=>30,'uas'=>28], // grade E
            'TI606' => ['tugas'=>50,'uts'=>45,'uas'=>42],
            'TI607' => ['tugas'=>0, 'uts'=>0, 'uas'=>0],
        ];
 
        foreach ($mahasiswas as $mhs) {
            foreach ($matkuls as $mk) {
                if ($mhs->nim === '2341720099') {
                    $n = $aldiNilais[$mk->kode] ?? ['tugas'=>75,'uts'=>72,'uas'=>73];
                } elseif ($mhs->nim === '2341720001') {
                    $n = $riskyNilais[$mk->kode] ?? ['tugas'=>50,'uts'=>45,'uas'=>42];
                } else {
                    // Realistis: rata-rata 65-85
                    $base = rand(65, 85);
                    $n = [
                        'tugas' => min(100, $base + rand(-5, 10)),
                        'uts'   => min(100, $base + rand(-8, 8)),
                        'uas'   => min(100, $base + rand(-6, 10)),
                    ];
                }
 
                // Hitung nilai akhir & grade
                $akhir = ($n['tugas'] * 0.3) + ($n['uts'] * 0.3) + ($n['uas'] * 0.4);
                $grade = match(true) {
                    $akhir >= 80 => 'A',
                    $akhir >= 70 => 'B',
                    $akhir >= 60 => 'C',
                    $akhir >= 50 => 'D',
                    default      => 'E',
                };
 
                Nilai::updateOrCreate(
                    [
                        'mahasiswa_id'   => $mhs->id,
                        'mata_kuliah_id' => $mk->id,
                        'tahun_akademik' => '2024/2025',
                    ],
                    [
                        'semester'    => 6,
                        'nilai_tugas' => $n['tugas'],
                        'nilai_uts'   => $n['uts'],
                        'nilai_uas'   => $n['uas'],
                        'nilai_akhir' => round($akhir, 2),
                        'grade'       => $grade,
                    ]
                );
            }
        }
    }
}