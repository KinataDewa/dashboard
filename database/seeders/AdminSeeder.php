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

        $user2 = User::updateOrCreate(
            ['email' => 'hendra.pradibta@polinema.ac.id'],
            [
                'name'     => 'Hendra Pradibta',
                'password' => Hash::make('password'),
            ]
        );
        $user2->syncRoles([$role]);
    }
}