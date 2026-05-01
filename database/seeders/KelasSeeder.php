<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Dosen;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil dosen by NIP
        $imam      = Dosen::where('nip','197901012005011001')->first();
        $ariadi    = Dosen::where('nip','198003152006042001')->first();
        $dian      = Dosen::where('nip','199001012018011001')->first();
        $elok      = Dosen::where('nip','198505152010012001')->first();
        $zawarudin = Dosen::where('nip','198712202015031001')->first();
        $ridwan    = Dosen::where('nip','197803152006041002')->first();
        $budi      = Dosen::where('nip','197501012005011001')->first();
        $siti      = Dosen::where('nip','198204202008012003')->first();

        $kelas = [
            // Semester 2
            ['nama'=>'TI1A','semester'=>2,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$siti?->id,    'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI1B','semester'=>2,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$dian?->id,    'tahun_akademik'=>'2024/2025'],
            // Semester 4
            ['nama'=>'TI2A','semester'=>4,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$imam?->id,    'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI2B','semester'=>4,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$ariadi?->id,  'tahun_akademik'=>'2024/2025'],
            // Semester 6
            ['nama'=>'TI3A','semester'=>6,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$zawarudin?->id,'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI3B','semester'=>6,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$ridwan?->id,  'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI3C','semester'=>6,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$budi?->id,    'tahun_akademik'=>'2024/2025'],
            ['nama'=>'TI3D','semester'=>6,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$elok?->id,    'tahun_akademik'=>'2024/2025'], // ← Kinata Dewa
            // Semester 8
            ['nama'=>'TI4A','semester'=>8,'prodi'=>'Teknologi Informasi','dosen_pa_id'=>$siti?->id,    'tahun_akademik'=>'2024/2025'],
        ];

        foreach ($kelas as $k) {
            Kelas::updateOrCreate(
                ['nama' => $k['nama'], 'tahun_akademik' => $k['tahun_akademik']],
                $k
            );
        }
    }
}