<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dosen;
use Illuminate\Support\Facades\Hash;
 
class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = [
            ['name'=>'Ir. Budi Santoso, M.T.',  'email'=>'budi.santoso@polinema.ac.id',  'nip'=>'197501012005011001'],
            ['name'=>'Dr. Agus Pramono',         'email'=>'agus.pramono@polinema.ac.id',  'nip'=>'197803152006041002'],
            ['name'=>'Siti Rahayu, M.Kom.',      'email'=>'siti.rahayu@polinema.ac.id',   'nip'=>'198204202008012003'],
            ['name'=>'Hendra Wijaya, M.T.',      'email'=>'hendra.wijaya@polinema.ac.id', 'nip'=>'197912102007011004'],
            ['name'=>'Prof. Mulyadi, M.Si.',     'email'=>'mulyadi@polinema.ac.id',       'nip'=>'196805201993031005'],
            ['name'=>'Dewi Kusuma, M.B.A.',      'email'=>'dewi.kusuma@polinema.ac.id',   'nip'=>'198107252009012006'],
        ];
 
        foreach ($dosens as $d) {
            $user = User::create([
                'name'     => $d['name'],
                'email'    => $d['email'],
                'password' => Hash::make('password'),
            ]);
            $user->assignRole('dosen');
 
            Dosen::create([
                'user_id' => $user->id,
                'nip'     => $d['nip'],
                'nama'    => $d['name'],
            ]);
        }
    }
}