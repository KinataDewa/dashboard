<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class, // 1. Buat roles dulu
            AdminSeeder::class,               // 2. Admin
            DosenSeeder::class,               // 3. Dosen (butuh roles)
            KelasSeeder::class,               // 4. Kelas (butuh dosen)
            MahasiswaSeeder::class,           // 5. Mahasiswa (butuh kelas + dosen)
            MatkulSeeder::class,              // 6. Matkul (butuh kelas + dosen)
            NilaiSeeder::class,               // 7. Nilai (butuh mahasiswa + matkul)
            AbsensiSeeder::class,             // 8. Absensi (butuh mahasiswa + matkul)
        ]);
    }
}