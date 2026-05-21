<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use HasFactory, SoftDeletes;

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

    public function dosen()
    {
        return $this->belongsTo(\App\Models\Dosen::class, 'dosen_pa_id');
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
        // Pakai koleksi yang sudah di-eager-load jika tersedia, hindari query tambahan
        if ($this->relationLoaded('nilais')) {
            $nilais = $this->nilais->where('semester', $semester);
        } else {
            $nilais = $this->nilais()->where('semester', $semester)
                           ->with('mataKuliah')->get();
        }

        return $this->hitungIpDariKoleksi($nilais);
    }

    // ── Helper: IPK kumulatif ────────────────────────
    public function getIpkAttribute(): float
    {
        $nilais = $this->relationLoaded('nilais')
            ? $this->nilais
            : $this->nilais()->with('mataKuliah')->get();

        return $this->hitungIpDariKoleksi($nilais);
    }

    private function hitungIpDariKoleksi($nilais): float
    {
        if ($nilais->isEmpty()) return 0.0;

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($nilais as $n) {
            $bobot = match($n->grade) {
                'A' => 4, 'B' => 3, 'C' => 2, 'D' => 1, default => 0,
            };
            $sks         = $n->mataKuliah->sks ?? 0;
            $totalBobot += $bobot * $sks;
            $totalSks   += $sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    // ── Helper: cek mahasiswa berisiko (semester terakhir saja, grade D/E) ─
    public function isBerisiko(): bool
    {
        $semNilai    = $this->nilais->max('semester') ?? 0;
        $nilaiRendah = $semNilai > 0
            ? $this->nilais->where('semester', $semNilai)->whereIn('grade', ['D', 'E'])->count()
            : 0;

        $semAlpha   = $this->absensis->max('semester') ?? 0;
        $totalAlpha = $semAlpha > 0
            ? $this->absensis->where('semester', $semAlpha)->sum('jam_alpha')
            : 0;

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

    public function kompensasis()
    {
        return $this->hasMany(Kompensasi::class);
    }
 
    // Ambil kompensasi semester tertentu
    public function getKompensasiSemester(int $semester, ?string $tahunAkademik = null)
    {
        $tahunAkademik ??= config('akademik.tahun_akademik');

        return $this->kompensasis
            ->where('semester', $semester)
            ->where('tahun_akademik', $tahunAkademik)
            ->first();
    }

    // Cek apakah punya kompen pending di semester tertentu
    public function hasPendingKompen(int $semester, ?string $tahunAkademik = null): bool
    {
        $tahunAkademik ??= config('akademik.tahun_akademik');

        return $this->kompensasis
            ->where('semester', $semester)
            ->where('tahun_akademik', $tahunAkademik)
            ->where('status', 'pending')
            ->isNotEmpty();
    }
    
}

