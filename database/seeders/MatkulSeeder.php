<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Dosen;
 
class MatkulSeeder extends Seeder
{
    public function run(): void
    {
        $imam   = Dosen::where('nip','197901012005011001')->first();
        $ariadi = Dosen::where('nip','198003152006042001')->first();
        $dian   = Dosen::where('nip','199001012018011001')->first();
        $elok   = Dosen::where('nip','198505152010012001')->first();
        $zawar  = Dosen::where('nip','198712202015031001')->first();
        $ridwan = Dosen::where('nip','197803152006041002')->first();
        $budi   = Dosen::where('nip','197501012005011001')->first();
        $siti   = Dosen::where('nip','198204202008012003')->first();
 
        $ti3c = Kelas::where('nama','TI3C')->first();
        $ti3d = Kelas::where('nama','TI3D')->first();
        $ti3a = Kelas::where('nama','TI3A')->first();
        $ti3b = Kelas::where('nama','TI3B')->first();
 
        $matkuls = [
            // Untuk TI3C
            ['kode'=>'TI601','nama'=>'Pemrograman Web Lanjut',   'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$budi?->id],
            ['kode'=>'TI602','nama'=>'Keamanan Jaringan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$imam?->id],
            ['kode'=>'TI603','nama'=>'Kecerdasan Buatan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$dian?->id],
            ['kode'=>'TI604','nama'=>'Basis Data Lanjut',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$ariadi?->id],
            ['kode'=>'TI605','nama'=>'Matematika Diskrit',        'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$zawar?->id],
            ['kode'=>'TI606','nama'=>'Manajemen Proyek TI',       'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$ridwan?->id],
            ['kode'=>'TI607','nama'=>'Tugas Akhir 1',             'sks'=>3,'semester'=>6,'kelas_id'=>$ti3c?->id,'dosen_id'=>$budi?->id],
 
            // Untuk TI3D (Kinata Dewa)
            ['kode'=>'TD601','nama'=>'Pemrograman Web Lanjut',   'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$elok?->id],
            ['kode'=>'TD602','nama'=>'Keamanan Jaringan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$imam?->id],
            ['kode'=>'TD603','nama'=>'Kecerdasan Buatan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$dian?->id],
            ['kode'=>'TD604','nama'=>'Basis Data Lanjut',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$ariadi?->id],
            ['kode'=>'TD605','nama'=>'Matematika Diskrit',        'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$zawar?->id],
            ['kode'=>'TD606','nama'=>'Manajemen Proyek TI',       'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$ridwan?->id],
            ['kode'=>'TD607','nama'=>'Tugas Akhir 1',             'sks'=>3,'semester'=>6,'kelas_id'=>$ti3d?->id,'dosen_id'=>$elok?->id],
 
            // Untuk TI3A
            ['kode'=>'TA601','nama'=>'Pemrograman Web Lanjut',   'sks'=>3,'semester'=>6,'kelas_id'=>$ti3a?->id,'dosen_id'=>$zawar?->id],
            ['kode'=>'TA602','nama'=>'Keamanan Jaringan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3a?->id,'dosen_id'=>$imam?->id],
            ['kode'=>'TA603','nama'=>'Kecerdasan Buatan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3a?->id,'dosen_id'=>$dian?->id],
 
            // Untuk TI3B
            ['kode'=>'TB601','nama'=>'Pemrograman Web Lanjut',   'sks'=>3,'semester'=>6,'kelas_id'=>$ti3b?->id,'dosen_id'=>$ridwan?->id],
            ['kode'=>'TB602','nama'=>'Keamanan Jaringan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3b?->id,'dosen_id'=>$imam?->id],
            ['kode'=>'TB603','nama'=>'Kecerdasan Buatan',         'sks'=>3,'semester'=>6,'kelas_id'=>$ti3b?->id,'dosen_id'=>$dian?->id],
        ];
 
        foreach ($matkuls as $m) {
            MataKuliah::updateOrCreate(['kode' => $m['kode']], $m);
        }
    }
}