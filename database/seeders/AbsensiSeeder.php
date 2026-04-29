<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Absensi;
 
class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswas = Mahasiswa::where('kelas_id', function($q) {
            $q->select('id')->from('kelas')->where('nama', 'TI3C');
        })->get();
 
        $matkuls = MataKuliah::where('semester', 6)->get();
 
        // Absensi khusus Aldi (normal kecuali Matdis alpha banyak)
        $aldiAbsen = [
            'TI601' => ['hadir'=>38, 'izin'=>2, 'sakit'=>2, 'alpha'=>0],
            'TI602' => ['hadir'=>40, 'izin'=>2, 'sakit'=>0, 'alpha'=>0],
            'TI603' => ['hadir'=>40, 'izin'=>0, 'sakit'=>2, 'alpha'=>0],
            'TI604' => ['hadir'=>38, 'izin'=>2, 'sakit'=>2, 'alpha'=>0],
            'TI605' => ['hadir'=>24, 'izin'=>2, 'sakit'=>0, 'alpha'=>16], // peringatan alpha!
            'TI606' => ['hadir'=>40, 'izin'=>2, 'sakit'=>0, 'alpha'=>0],
            'TI607' => ['hadir'=>20, 'izin'=>0, 'sakit'=>0, 'alpha'=>0],
        ];
 
        // Absensi Ahmad Fauzi (sangat bermasalah)
        $fauziAbsen = [
            'TI601' => ['hadir'=>20, 'izin'=>2, 'sakit'=>2, 'alpha'=>18], // batas!
            'TI602' => ['hadir'=>22, 'izin'=>0, 'sakit'=>0, 'alpha'=>20], // lewat batas
            'TI603' => ['hadir'=>30, 'izin'=>2, 'sakit'=>2, 'alpha'=>8],
            'TI604' => ['hadir'=>25, 'izin'=>5, 'sakit'=>0, 'alpha'=>12],
            'TI605' => ['hadir'=>18, 'izin'=>0, 'sakit'=>2, 'alpha'=>22], // sangat bermasalah
            'TI606' => ['hadir'=>32, 'izin'=>4, 'sakit'=>0, 'alpha'=>6],
            'TI607' => ['hadir'=>15, 'izin'=>0, 'sakit'=>0, 'alpha'=>5],
        ];
 
        foreach ($mahasiswas as $mhs) {
            foreach ($matkuls as $mk) {
                if ($mhs->nim === '2341720099') {
                    $a = $aldiAbsen[$mk->kode] ?? ['hadir'=>38,'izin'=>2,'sakit'=>2,'alpha'=>0];
                } elseif ($mhs->nim === '2341720001') {
                    $a = $fauziAbsen[$mk->kode] ?? ['hadir'=>20,'izin'=>0,'sakit'=>0,'alpha'=>22];
                } else {
                    $totalJam = 42;
                    $alpha    = rand(0, 10);
                    $sakit    = rand(0, 4);
                    $izin     = rand(0, 4);
                    $hadir    = $totalJam - $alpha - $sakit - $izin;
                    $a = ['hadir'=>max(0,$hadir),'izin'=>$izin,'sakit'=>$sakit,'alpha'=>$alpha];
                }
 
                Absensi::create([
                    'mahasiswa_id'   => $mhs->id,
                    'mata_kuliah_id' => $mk->id,
                    'semester'       => 6,
                    'tahun_akademik' => '2024/2025',
                    'jam_hadir'      => $a['hadir'],
                    'jam_izin'       => $a['izin'],
                    'jam_sakit'      => $a['sakit'],
                    'jam_alpha'      => $a['alpha'],
                ]);
            }
        }
    }
}
 