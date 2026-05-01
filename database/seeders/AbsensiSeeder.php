<?php
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Absensi;
use Carbon\Carbon;
 
class AbsensiSeeder extends Seeder
{
    public function run(): void
    {
        $alphaMap = [
            // TI3D — beberapa dengan alpha tinggi di satu matkul
            '2341720099' => ['TD605' => 14], // waspada
            '2341720103' => ['TD603' => 18], // kritis
            '2341720114' => ['TD602' => 18], // kritis
            '2341720116' => ['TD604' => 18], // kritis
 
            // TI3C
            '2341720001' => ['TI605' => 14], // waspada
            '2341720005' => ['TI602' => 18], // kritis
            '2341720009' => ['TI603' => 18], // kritis
            '2341720012' => ['TI601' => 18], // kritis
 
            // TI3A
            '2341730003' => ['TA602' => 18], // kritis
            '2341730006' => ['TA603' => 18], // kritis
            '2341730012' => ['TA601' => 18], // kritis
            '2341730014' => ['TA602' => 18], // kritis
 
            // TI3B
            '2341740003' => ['TB602' => 18], // kritis
            '2341740006' => ['TB601' => 18], // kritis
            '2341740008' => ['TB603' => 18], // kritis
            '2341740010' => ['TB602' => 18], // kritis
        ];
 
        $startDate  = Carbon::create(2025, 2, 3);
        $mahasiswas = Mahasiswa::with(['kelas'])->get();
 
        foreach ($mahasiswas as $mhs) {
            $matkuls = MataKuliah::where('kelas_id', $mhs->kelas_id)
                ->where('semester', 6)->get();
 
            foreach ($matkuls as $idx => $mk) {
                // Cek apakah matkul ini punya alpha khusus
                $alphaKhusus = $alphaMap[$mhs->nim][$mk->kode] ?? null;
 
                if ($alphaKhusus !== null) {
                    $alpha = $alphaKhusus;
                    $izin  = rand(0, 2);
                    $sakit = rand(0, 2);
                    $hadir = max(20, 42 - $alpha - $izin - $sakit);
                } else {
                    // Normal: alpha 0-4 jam
                    $alpha = rand(0, 4);
                    $izin  = rand(0, 3);
                    $sakit = rand(0, 3);
                    $hadir = max(32, 42 - $alpha - $izin - $sakit);
                }
 
                $tanggal = $startDate->copy()->addWeeks(13)->addDays($idx % 5);
 
                Absensi::updateOrCreate(
                    ['mahasiswa_id'=>$mhs->id,'mata_kuliah_id'=>$mk->id,'tahun_akademik'=>'2024/2025'],
                    ['semester'=>6,'tanggal'=>$tanggal->format('Y-m-d'),'pertemuan_ke'=>14,'jam_hadir'=>$hadir,'jam_izin'=>$izin,'jam_sakit'=>$sakit,'jam_alpha'=>$alpha]
                );
            }
        }
 
        $this->command->info('✅ AbsensiSeeder selesai — sistem yang menentukan siapa berisiko dari data alpha.');
    }
}