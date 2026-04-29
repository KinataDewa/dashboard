<?php
namespace App\Imports;
 
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
 
class MatkulImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;
 
    private int $imported = 0;
    private int $skipped  = 0;
 
    public function model(array $row)
    {
        $kelas = Kelas::where('nama', trim($row['kelas']))->first();
        $dosen = Dosen::where('nip', trim($row['nip_dosen']))->first();
 
        if (!$kelas || !$dosen) {
            $this->skipped++;
            return null;
        }
 
        $this->imported++;
 
        return MataKuliah::updateOrCreate(
            ['kode' => trim($row['kode'])],
            [
                'nama'     => trim($row['nama']),
                'sks'      => intval($row['sks']),
                'semester' => intval($row['semester']),
                'kelas_id' => $kelas->id,
                'dosen_id' => $dosen->id,
            ]
        );
    }
 
    public function rules(): array
    {
        return [
            'kode'      => 'required',
            'nama'      => 'required',
            'sks'       => 'required|integer|min:1|max:6',
            'semester'  => 'required|integer|min:1|max:8',
            'kelas'     => 'required',
            'nip_dosen' => 'required',
        ];
    }
 
    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int  { return $this->skipped; }
}