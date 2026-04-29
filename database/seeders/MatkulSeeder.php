<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Dosen;
 
class MatkulSeeder extends Seeder
{
    public function run(): void
    {
        $kelasTI3C = Kelas::where('nama', 'TI3C')->first();
        $dosens    = Dosen::all()->keyBy('nip');
 
        $matkuls = [
            [
                'kode'     => 'TI601',
                'nama'     => 'Pemrograman Web Lanjut',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['197501012005011001']->id,
            ],
            [
                'kode'     => 'TI602',
                'nama'     => 'Keamanan Jaringan',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['197803152006041002']->id,
            ],
            [
                'kode'     => 'TI603',
                'nama'     => 'Kecerdasan Buatan',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['198204202008012003']->id,
            ],
            [
                'kode'     => 'TI604',
                'nama'     => 'Basis Data Lanjut',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['197912102007011004']->id,
            ],
            [
                'kode'     => 'TI605',
                'nama'     => 'Matematika Diskrit',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['196805201993031005']->id,
            ],
            [
                'kode'     => 'TI606',
                'nama'     => 'Manajemen Proyek TI',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['198107252009012006']->id,
            ],
            [
                'kode'     => 'TI607',
                'nama'     => 'Tugas Akhir 1',
                'sks'      => 3,
                'semester' => 6,
                'kelas_id' => $kelasTI3C->id,
                'dosen_id' => $dosens['197501012005011001']->id,
            ],
        ];
 
        foreach ($matkuls as $mk) {
            MataKuliah::create($mk);
        }
    }
}