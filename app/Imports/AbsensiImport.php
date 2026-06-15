<?php
namespace App\Imports;

use App\Models\Mahasiswa;
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

        if (!$mahasiswa) {
            $this->skipped++;
            return null;
        }

        $this->imported++;

        return Absensi::updateOrCreate(
            [
                'mahasiswa_id' => $mahasiswa->id,
                'semester'     => intval($row['semester']),
            ],
            [
                'jam_hadir' => intval($row['jam_hadir']),
                'jam_izin'  => intval($row['jam_izin']),
                'jam_sakit' => intval($row['jam_sakit']),
                'jam_alpha' => intval($row['jam_alpha']),
            ]
        );
    }

    public function rules(): array
    {
        return [
            'nim'       => 'required',
            'semester'  => 'required|integer|min:1|max:8',
            'jam_hadir' => 'required|integer|min:0',
            'jam_izin'  => 'required|integer|min:0',
            'jam_sakit' => 'required|integer|min:0',
            'jam_alpha' => 'required|integer|min:0',
        ];
    }

    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int  { return $this->skipped; }
}