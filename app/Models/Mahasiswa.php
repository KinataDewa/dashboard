<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nim', 'nama', 'kelas_id',
        'angkatan', 'status', 'dosen_pa_id', 'no_hp', 'alamat'
    ];

    // ── Relasi ──────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function dosenPa()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pa_id');
    }

    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    // ── Helper: IP semester tertentu ─────────────────
    public function getIpSemester(int $semester): float
    {
        $nilais = $this->nilais()
            ->where('semester', $semester)
            ->with('mataKuliah')
            ->get();

        if ($nilais->isEmpty()) return 0.0;

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($nilais as $n) {
            $bobot = match($n->grade) {
                'A'     => 4,
                'B'     => 3,
                'C'     => 2,
                'D'     => 1,
                default => 0,
            };
            $sks         = $n->mataKuliah->sks ?? 0;
            $totalBobot += $bobot * $sks;
            $totalSks   += $sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    // ── Helper: IPK kumulatif ────────────────────────
    public function getIpkAttribute(): float
    {
        $nilais = $this->nilais()->with('mataKuliah')->get();

        if ($nilais->isEmpty()) return 0.0;

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($nilais as $n) {
            $bobot = match($n->grade) {
                'A'     => 4,
                'B'     => 3,
                'C'     => 2,
                'D'     => 1,
                default => 0,
            };
            $sks         = $n->mataKuliah->sks ?? 0;
            $totalBobot += $bobot * $sks;
            $totalSks   += $sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    // ── Helper: cek mahasiswa berisiko ───────────────
    public function isBerisiko(): bool
    {
        // Sistem otomatis cek dari data nilai dan absensi
        $nilaiRendah = $this->nilais->whereIn('grade', ['C', 'D', 'E'])->count();
        $totalAlpha  = $this->absensis->sum('jam_alpha');

        return $nilaiRendah > 0 || $totalAlpha >= 18;
    }

    // ── Helper: total jam alpha semua matkul ─────────
    public function getTotalAlpha(int $semester): int
    {
        return $this->absensis()
            ->where('semester', $semester)
            ->sum('jam_alpha');
    }

    // ── Helper: daftar matkul dengan grade D/E ───────
    public function getMataKuliahDE(int $semester)
    {
        return $this->nilais()
            ->where('semester', $semester)
            ->whereIn('grade', ['D', 'E'])
            ->with('mataKuliah')
            ->get();
    }
}

