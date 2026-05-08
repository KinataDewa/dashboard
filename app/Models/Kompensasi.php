<?php
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Kompensasi extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'semester',
        'tahun_akademik',
        'jam_alpha',
        'multiplier',
        'jam_kompen_wajib',
        'status',
        'catatan_tugas',
        'ttd_admin',
        'ttd_kajur',
        'tanggal_lunas',
        'created_by',
    ];
 
    protected $casts = [
        'ttd_admin'      => 'boolean',
        'ttd_kajur'      => 'boolean',
        'tanggal_lunas'  => 'datetime',
    ];
 
    // ── Relasi ───────────────────────────────────────
    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(Mahasiswa::class);
    }
 
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
 
    // ── Accessor ─────────────────────────────────────
 
    // Label SP berdasarkan jam alpha semester
    public function getSpLabelAttribute(): string
    {
        return match(true) {
            $this->jam_alpha >= 72 => 'SP 3',
            $this->jam_alpha >= 36 => 'SP 2',
            $this->jam_alpha >= 18 => 'SP 1',
            default                => '-',
        };
    }
 
    // Warna SP untuk UI
    public function getSpColorAttribute(): string
    {
        return match(true) {
            $this->jam_alpha >= 72 => '#7F1D1D', // merah tua
            $this->jam_alpha >= 36 => '#DC2626', // merah
            $this->jam_alpha >= 18 => '#F59E0B', // kuning
            default                => '#22C55E',
        };
    }
 
    // Warna background SP
    public function getSpBgAttribute(): string
    {
        return match(true) {
            $this->jam_alpha >= 72 => '#FEE2E2',
            $this->jam_alpha >= 36 => '#FEE2E2',
            $this->jam_alpha >= 18 => '#FEF3C7',
            default                => '#DCFCE7',
        };
    }
 
    // Apakah sudah lunas
    public function isLunas(): bool
    {
        return $this->status === 'lunas';
    }
 
    // ── Static Helper ─────────────────────────────────
 
    // Hitung multiplier berdasarkan selisih semester
    // semesterAlpha = semester saat alpha terjadi
    // semesterSekarang = semester saat kompen dibuat
    public static function hitungMultiplier(int $semesterAlpha, int $semesterSekarang): int
    {
        $selisih = max(0, $semesterSekarang - $semesterAlpha);
        return (int) pow(2, $selisih); // 2^0=1, 2^1=2, 2^2=4, dst
    }
 
    // Hitung jam kompen wajib
    public static function hitungJamKompen(int $jamAlpha, int $multiplier): int
    {
        return $jamAlpha * 2 * $multiplier;
        // contoh: 4 jam alpha, semester ini (×1) = 4 × 2 × 1 = 8 jam
        // contoh: 4 jam alpha, semester depan (×2) = 4 × 2 × 2 = 16 jam
    }
}