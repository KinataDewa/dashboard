<?php
// database/seeders/DatabaseSeeder.php
// Ganti seluruh isi file ini

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminSeeder::class,
            DosenSeeder::class,
            KelasSeeder::class,
            MahasiswaSeeder::class,
            MatkulSeeder::class,
            NilaiSeeder::class,
            AbsensiSeeder::class,
        ]);
    }
}