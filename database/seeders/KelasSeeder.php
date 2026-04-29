<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Dosen;
 
class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $dosenBudi  = Dosen::where('nip', '197501012005011001')->first();
        $dosenAgus  = Dosen::where('nip', '197803152006041002')->first();
        $dosenSiti  = Dosen::where('nip', '198204202008012003')->first();
 
        $kelas = [
            // Semester 6
            ['nama'=>'TI3A', 'semester'=>6, 'dosen_pa_id'=>$dosenBudi->id,  'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI3B', 'semester'=>6, 'dosen_pa_id'=>$dosenAgus->id,  'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI3C', 'semester'=>6, 'dosen_pa_id'=>$dosenBudi->id,  'tahun_akademik'=>'2024/2025'],
            // Semester 4
            ['nama'=>'TI2A', 'semester'=>4, 'dosen_pa_id'=>$dosenAgus->id,  'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI2B', 'semester'=>4, 'dosen_pa_id'=>$dosenSiti->id,  'tahun_akademik'=>'2024/2025'],
            // Semester 2
            ['nama'=>'TI1A', 'semester'=>2, 'dosen_pa_id'=>$dosenSiti->id,  'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI1B', 'semester'=>2, 'dosen_pa_id'=>$dosenBudi->id,  'tahun_akademik'=>'2024/2025'],
            // Semester 8
            ['nama'=>'TI4A', 'semester'=>8, 'dosen_pa_id'=>$dosenSiti->id,  'tahun_akademik'=>'2024/2025'],
        ];
 
        foreach ($kelas as $k) {
            Kelas::create($k);
        }
    }
}