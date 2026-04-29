<?php
namespace App\Imports;
 
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
 
class MahasiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;
 
    private int $imported = 0;
    private int $skipped  = 0;
 
    public function model(array $row)
    {
        $kelas  = Kelas::where('nama', trim($row['kelas']))->first();
        $dosenPa = Dosen::where('nip', trim($row['nip_dosen_pa']))->first();
 
        if (!$kelas || !$dosenPa) {
            $this->skipped++;
            return null;
        }
 
        // Cek apakah user sudah ada
        $user = User::firstOrCreate(
            ['email' => trim($row['email'])],
            [
                'name'     => trim($row['nama']),
                'password' => Hash::make(trim($row['nim'])), // default password = nim
            ]
        );
 
        if ($user->wasRecentlyCreated) {
            $user->assignRole('mahasiswa');
        }
 
        $this->imported++;
 
        return Mahasiswa::updateOrCreate(
            ['nim' => trim($row['nim'])],
            [
                'user_id'     => $user->id,
                'nama'        => trim($row['nama']),
                'kelas_id'    => $kelas->id,
                'dosen_pa_id' => $dosenPa->id,
                'angkatan'    => intval($row['angkatan']),
                'status'      => 'aktif',
            ]
        );
    }
 
    public function rules(): array
    {
        return [
            'nim'          => 'required',
            'nama'         => 'required',
            'email'        => 'required|email',
            'kelas'        => 'required',
            'angkatan'     => 'required|integer',
            'nip_dosen_pa' => 'required',
        ];
    }
 
    public function getImportedCount(): int { return $this->imported; }
    public function getSkippedCount(): int  { return $this->skipped; }
}