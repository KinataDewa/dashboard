<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama', 'sks', 'semester', 'kelas_id', 'dosen_id'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    // Rata-rata nilai akhir seluruh mahasiswa di matkul ini
    public function getRataRataNilai(): float
    {
        return round($this->nilais()->avg('nilai_akhir') ?? 0, 2);
    }
}
