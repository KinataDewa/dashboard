<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        $dosens = [
            // Dosen yang diminta
            ['nip'=>'197901012005011001', 'nama'=>'Imam Fahrur Rozi, ST., MT.',                    'email'=>'imam.fahrur@polinema.ac.id',   'no_hp'=>'081234560001'],
            ['nip'=>'198003152006042001', 'nama'=>'Ariadi Retno Tri Hayati Ririd, S.Kom., M.Kom.', 'email'=>'ariadi.retno@polinema.ac.id',   'no_hp'=>'081234560002'],
            ['nip'=>'199001012018011001', 'nama'=>'Dian Hanifudin Subhi, S.Kom., M.Kom.',          'email'=>'dian.hanifudin@polinema.ac.id', 'no_hp'=>'081234560003'],
            ['nip'=>'198505152010012001', 'nama'=>'Elok Nur Hamdana, S.T., M.T.',                  'email'=>'elok.nur@polinema.ac.id',       'no_hp'=>'081234560004'],
            ['nip'=>'198712202015031001', 'nama'=>'Moch Zawaruddin Abdullah, S.ST., M.Kom.',       'email'=>'moch.zawaruddin@polinema.ac.id','no_hp'=>'081234560005'],
            ['nip'=>'197803152006041002', 'nama'=>'Ridwan Rismanto, S.ST., M.Kom., Ph.D.',         'email'=>'ridwan.rismanto@polinema.ac.id','no_hp'=>'081234560006'],
            // Tambahan
            ['nip'=>'197501012005011001', 'nama'=>'Ir. Budi Santoso, M.T.',                        'email'=>'budi.santoso@polinema.ac.id',   'no_hp'=>'081234560007'],
            ['nip'=>'198204202008012003', 'nama'=>'Siti Rahayu, M.Kom.',                           'email'=>'siti.rahayu@polinema.ac.id',    'no_hp'=>'081234560008'],
        ];

        $role = Role::firstOrCreate(['name' => 'dosen']);

        foreach ($dosens as $d) {
            $user = User::updateOrCreate(
                ['email' => $d['email']],
                [
                    'name'     => $d['nama'],
                    'password' => Hash::make('password'),
                ]
            );
            $user->syncRoles([$role]);

            Dosen::updateOrCreate(
                ['nip' => $d['nip']],
                [
                    'user_id' => $user->id,
                    'nama'    => $d['nama'],
                    'no_hp'   => $d['no_hp'],
                ]
            );
        }
    }
}