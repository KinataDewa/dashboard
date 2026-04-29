<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
 
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name'     => 'Sri Kusuma',
            'email'    => 'admin@polinema.ac.id',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');
    }
}