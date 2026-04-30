<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Absensi;
use Carbon\Carbon;
 
class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas = Mahasiswa::where('kelas_id', function($q) {
            $q->select('id')->from('kelas')->where('nama', 'TI3C');
        })->get();
 
        $matkuls = MataKuliah::where('semester', 6)->get();
 
        // Absensi khusus Aldi (mahasiswa test utama)
        $aldiAbsen = [
            'TI601' => ['hadir'=>38,'izin'=>2,'sakit'=>2,'alpha'=>0],
            'TI602' => ['hadir'=>40,'izin'=>2,'sakit'=>0,'alpha'=>0],
            'TI603' => ['hadir'=>40,'izin'=>0,'sakit'=>2,'alpha'=>0],
            'TI604' => ['hadir'=>38,'izin'=>2,'sakit'=>2,'alpha'=>0],
            'TI605' => ['hadir'=>24,'izin'=>2,'sakit'=>0,'alpha'=>16], // waspada
            'TI606' => ['hadir'=>40,'izin'=>2,'sakit'=>0,'alpha'=>0],
            'TI607' => ['hadir'=>20,'izin'=>0,'sakit'=>0,'alpha'=>0],
        ];
 
        // Absensi khusus mahasiswa 001 (berisiko)
        $riskyAbsen = [
            'TI601' => ['hadir'=>24,'izin'=>2,'sakit'=>0,'alpha'=>16],
            'TI602' => ['hadir'=>22,'izin'=>0,'sakit'=>2,'alpha'=>18],
            'TI603' => ['hadir'=>30,'izin'=>2,'sakit'=>2,'alpha'=>8],
            'TI604' => ['hadir'=>28,'izin'=>2,'sakit'=>0,'alpha'=>12],
            'TI605' => ['hadir'=>20,'izin'=>0,'sakit'=>2,'alpha'=>20],
            'TI606' => ['hadir'=>32,'izin'=>4,'sakit'=>0,'alpha'=>6],
            'TI607' => ['hadir'=>15,'izin'=>0,'sakit'=>0,'alpha'=>5],
        ];
 
        $startDate = Carbon::create(2025, 2, 3);
 
        foreach ($mahasiswas as $mhs) {
            foreach ($matkuls as $idx => $mk) {
                if ($mhs->nim === '2341720099') {
                    $a = $aldiAbsen[$mk->kode] ?? ['hadir'=>38,'izin'=>2,'sakit'=>2,'alpha'=>0];
                } elseif ($mhs->nim === '2341720001') {
                    $a = $riskyAbsen[$mk->kode] ?? ['hadir'=>30,'izin'=>2,'sakit'=>2,'alpha'=>8];
                } else {
                    // Realistis: sebagian besar hadir, alpha max 8 jam
                    $alpha = rand(0, 6);
                    $sakit = rand(0, 4);
                    $izin  = rand(0, 4);
                    $hadir = 42 - $alpha - $sakit - $izin;
                    $a = [
                        'hadir' => max(20, $hadir),
                        'izin'  => $izin,
                        'sakit' => $sakit,
                        'alpha' => $alpha,
                    ];
                }
 
                $tanggal = $startDate->copy()->addWeeks(13)->addDays($idx % 5);
 
                Absensi::updateOrCreate(
                    [
                        'mahasiswa_id'   => $mhs->id,
                        'mata_kuliah_id' => $mk->id,
                        'tahun_akademik' => '2024/2025',
                    ],
                    [
                        'semester'     => 6,
                        'tanggal'      => $tanggal->format('Y-m-d'),
                        'pertemuan_ke' => 14,
                        'jam_hadir'    => $a['hadir'],
                        'jam_izin'     => $a['izin'],
                        'jam_sakit'    => $a['sakit'],
                        'jam_alpha'    => $a['alpha'],
                    ]
                );
            }
        }
    }
}