<?php
namespace App\Imports;
 
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
 
class AbsensiImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
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
 
        $this->imported++;
 
        return Absensi::updateOrCreate(
            [
                'mahasiswa_id'   => $mahasiswa->id,
                'mata_kuliah_id' => $matkul->id,
                'tahun_akademik' => trim($row['tahun_akademik']),
            ],
            [
                'semester'     => intval($row['semester']),
                'tanggal'      => $row['tanggal'] ?? now()->format('Y-m-d'),
                'pertemuan_ke' => intval($row['pertemuan_ke'] ?? 1),
                'jam_hadir'    => intval($row['jam_hadir']),
                'jam_izin'     => intval($row['jam_izin']),
                'jam_sakit'    => intval($row['jam_sakit']),
                'jam_alpha'    => intval($row['jam_alpha']),
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
            'jam_hadir'      => 'required|integer|min:0',
            'jam_izin'       => 'required|integer|min:0',
            'jam_sakit'      => 'required|integer|min:0',
            'jam_alpha'      => 'required|integer|min:0',
        ];
    }
 
    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int  { return $this->skipped; }
}