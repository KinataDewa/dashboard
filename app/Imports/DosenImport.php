<?php
namespace App\Imports;
 
use App\Models\Dosen;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
 
class DosenImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;
 
    private int $imported = 0;
 
    public function model(array $row)
    {
        $user = User::firstOrCreate(
            ['email' => trim($row['email'])],
            [
                'name'     => trim($row['nama']),
                'password' => Hash::make(Str::random(12)),
            ]
        );

        if ($user->wasRecentlyCreated) {
            $user->assignRole('dosen');
            Password::sendResetLink(['email' => $user->email]);
        }
 
        $this->imported++;
 
        return Dosen::updateOrCreate(
            ['nip' => trim($row['nip'])],
            [
                'user_id' => $user->id,
                'nama'    => trim($row['nama']),
                'no_hp'   => trim($row['no_hp'] ?? ''),
            ]
        );
    }
 
    public function rules(): array
    {
        return [
            'nip'   => 'required',
            'nama'  => 'required',
            'email' => 'required|email',
        ];
    }
 
    public function getImportedCount(): int { return $this->imported; }
}