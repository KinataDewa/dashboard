<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id', 'mata_kuliah_id', 'semester', 'tahun_akademik',
        'jam_hadir', 'jam_izin', 'jam_sakit', 'jam_alpha'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    // Total jam keseluruhan
    public function getTotalJamAttribute(): int
    {
        return $this->jam_hadir + $this->jam_izin + $this->jam_sakit + $this->jam_alpha;
    }

    // Persentase kehadiran
    public function getPersenHadirAttribute(): float
    {
        $total = $this->total_jam;
        return $total > 0 ? round(($this->jam_hadir / $total) * 100, 1) : 0;
    }

    // Cek apakah alpha sudah kritis (>= 18 jam)
    public function getIsAlphaKritisAttribute(): bool
    {
        return $this->jam_alpha >= 18;
    }
}