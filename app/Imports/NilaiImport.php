<?php
namespace App\Imports;
 
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Nilai;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithSkipDuplicates;
 
class NilaiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;
 
    private int $imported = 0;
    private int $skipped  = 0;
 
    public function model(array $row)
    {
        $mahasiswa = Mahasiswa::where('nim', trim($row['nim']))->first();
        $matkul    = MataKuliah::where('kode', trim($row['kode_matkul']))->first();
 
        if (!$mahasiswa || !$matkul) {
            $this->skipped++;
            return null;
        }
 
        // Hitung nilai akhir & grade
        $tugas  = floatval($row['nilai_tugas']);
        $uts    = floatval($row['nilai_uts']);
        $uas    = floatval($row['nilai_uas']);
        $akhir  = ($tugas * 0.3) + ($uts * 0.3) + ($uas * 0.4);
        $grade  = match(true) {
            $akhir >= 80 => 'A',
            $akhir >= 70 => 'B',
            $akhir >= 60 => 'C',
            $akhir >= 50 => 'D',
            default      => 'E',
        };
 
        $this->imported++;
 
        // Upsert: update jika sudah ada, insert jika belum
        return Nilai::updateOrCreate(
            [
                'mahasiswa_id'   => $mahasiswa->id,
                'mata_kuliah_id' => $matkul->id,
                'tahun_akademik' => trim($row['tahun_akademik']),
            ],
            [
                'semester'     => intval($row['semester']),
                'nilai_tugas'  => $tugas,
                'nilai_uts'    => $uts,
                'nilai_uas'    => $uas,
                'nilai_akhir'  => round($akhir, 2),
                'grade'        => $grade,
            ]
        );
    }
 
    public function rules(): array
    {
        return [
            'nim'            => 'required',
            'kode_matkul'    => 'required',
            'semester'       => 'required|integer',
            'tahun_akademik' => 'required',
            'nilai_tugas'    => 'required|numeric|min:0|max:100',
            'nilai_uts'      => 'required|numeric|min:0|max:100',
            'nilai_uas'      => 'required|numeric|min:0|max:100',
        ];
    }
 
    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int  { return $this->skipped; }
}