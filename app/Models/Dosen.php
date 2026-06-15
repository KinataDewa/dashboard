<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nip', 'nama', 'nama_normalized', 'no_hp'];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function (Dosen $dosen) {
            $dosen->nama_normalized = static::normalizasiNama($dosen->nama ?? '');
        });
    }

    // Normalisasi nama: hapus gelar, lowercase, trim spasi
    public static function normalizasiNama(string $nama): string
    {
        // 1. Hapus gelar prefiks (Prof., Dr., Ir., Drs., Dra., Hj., H.)
        $nama = preg_replace('/\b(Prof|Dr|Ir|Drs|Dra|Hj|H)\.?\s+/iu', '', $nama);

        // 2. Ambil bagian sebelum koma pertama (gelar sufiks biasanya setelah koma)
        if (strpos($nama, ',') !== false) {
            $nama = explode(',', $nama)[0];
        }

        // 3. Hapus singkatan gelar tersisa (e.g. S.Kom., M.T., Ph.D., M.B.A., S.Tr.)
        $nama = preg_replace('/\b[A-Za-z]{1,5}(\.[A-Za-z]{1,5}){1,3}\.?/', '', $nama);

        // 4. Lowercase dan bersihkan spasi berlebih
        return trim(preg_replace('/\s+/', ' ', mb_strtolower($nama)));
    }

    // ── Relasi ───────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'dosen_pa_id');
    }

    public function mahasiswaBimbingan()
    {
        return $this->hasMany(Mahasiswa::class, 'dosen_pa_id');
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }
}
