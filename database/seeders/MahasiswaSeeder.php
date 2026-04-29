<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
 
class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $kelasTI3C  = Kelas::where('nama', 'TI3C')->first();
        $dosenBudi  = Dosen::where('nip', '197501012005011001')->first();
 
        // Mahasiswa kelas TI3C (data sample untuk testing)
        $mahasiswas = [
            ['nim'=>'2341720001','nama'=>'Kinata Dewa',    'email'=>'kinata.dewa@student.polinema.ac.id'],
            ['nim'=>'2341720002','nama'=>'Bella Novita',   'email'=>'bella.novita@student.polinema.ac.id'],
            ['nim'=>'2341720003','nama'=>'Candra Putra',   'email'=>'candra.putra@student.polinema.ac.id'],
            ['nim'=>'2341720004','nama'=>'Dea Maharani',   'email'=>'dea.maharani@student.polinema.ac.id'],
            ['nim'=>'2341720005','nama'=>'Dina Sari',      'email'=>'dina.sari@student.polinema.ac.id'],
            ['nim'=>'2341720006','nama'=>'Eko Prasetyo',   'email'=>'eko.prasetyo@student.polinema.ac.id'],
            ['nim'=>'2341720007','nama'=>'Fajar Nugroho',  'email'=>'fajar.nugroho@student.polinema.ac.id'],
            ['nim'=>'2341720008','nama'=>'Galih Satrio',   'email'=>'galih.satrio@student.polinema.ac.id'],
            ['nim'=>'2341720009','nama'=>'Rizky Pratama',  'email'=>'rizky.pratama@student.polinema.ac.id'],
            ['nim'=>'2341720010','nama'=>'Hana Putri',     'email'=>'hana.putri@student.polinema.ac.id'],
            ['nim'=>'2341720011','nama'=>'Laila Husna',    'email'=>'laila.husna@student.polinema.ac.id'],
            ['nim'=>'2341720012','nama'=>'Imam Santoso',   'email'=>'imam.santoso@student.polinema.ac.id'],
            ['nim'=>'2341720013','nama'=>'Joko Widodo',    'email'=>'joko.widodo@student.polinema.ac.id'],
            ['nim'=>'2341720014','nama'=>'Kiki Amalia',    'email'=>'kiki.amalia@student.polinema.ac.id'],
            ['nim'=>'2341720015','nama'=>'Nadia Aulia',    'email'=>'nadia.aulia@student.polinema.ac.id'],
            ['nim'=>'2341720016','nama'=>'Luthfi Arif',    'email'=>'luthfi.arif@student.polinema.ac.id'],
            ['nim'=>'2341720017','nama'=>'Maya Sari',      'email'=>'maya.sari@student.polinema.ac.id'],
            ['nim'=>'2341720018','nama'=>'Fajar Putra',    'email'=>'fajar.putra@student.polinema.ac.id'],
            ['nim'=>'2341720019','nama'=>'Nurul Hidayah',  'email'=>'nurul.hidayah@student.polinema.ac.id'],
            ['nim'=>'2341720020','nama'=>'Oscar Pratama',  'email'=>'oscar.pratama@student.polinema.ac.id'],
            // Akun khusus untuk testing login mahasiswa
            ['nim'=>'2341720099','nama'=>'Kinata',  'email'=>'kinata@student.polinema.ac.id'],
        ];
 
        foreach ($mahasiswas as $m) {
            $user = User::create([
                'name'     => $m['nama'],
                'email'    => $m['email'],
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('mahasiswa');
 
            Mahasiswa::create([
                'user_id'     => $user->id,
                'nim'         => $m['nim'],
                'nama'        => $m['nama'],
                'kelas_id'    => $kelasTI3C->id,
                'angkatan'    => 2023,
                'status'      => 'aktif',
                'dosen_pa_id' => $dosenBudi->id,
            ]);
        }
    }
}