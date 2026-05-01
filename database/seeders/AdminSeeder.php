<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
 
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
 
        $user = User::updateOrCreate(
            ['email' => 'admin@polinema.ac.id'],
            [
                'name'     => 'Sri Kusuma',
                'password' => Hash::make('password'),
            ]
        );
        $user->syncRoles([$role]);
    }
}