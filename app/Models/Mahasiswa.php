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

    // ── Relasi ───────────────────────────────────────────────
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

    public function kompensasis()
    {
        return $this->hasMany(Kompensasi::class);
    }

    // ── Helper: IP semester tertentu ─────────────────────────
    public function getIpSemester(int $semester): float
    {
        if ($semester === 0) return 0.0;

        $nilais = $this->relationLoaded('nilais')
            ? $this->nilais->where('semester', $semester)
            : $this->nilais()->where('semester', $semester)->with('mataKuliah')->get();

        return $this->hitungIpDariKoleksi($nilais);
    }

    // ── Helper: IPK kumulatif ─────────────────────────────────
    public function getIpkAttribute(): float
    {
        $nilais = $this->relationLoaded('nilais')
            ? $this->nilais
            : $this->nilais()->with('mataKuliah')->get();

        return $this->hitungIpDariKoleksi($nilais);
    }

    // ── Helper: hitung IP dari koleksi nilai ─────────────────
    // Support grade B+ dan C+ sesuai Pedoman Akademik D4 TI Polinema
    private function hitungIpDariKoleksi($nilais): float
    {
        if ($nilais->isEmpty()) return 0.0;

        $totalBobot = 0;
        $totalSks   = 0;

        foreach ($nilais as $n) {
            $bobot = match($n->grade) {
                'A'  => 4.0,
                'B+' => 3.5,
                'B'  => 3.0,
                'C+' => 2.5,
                'C'  => 2.0,
                'D'  => 1.0,
                default => 0.0, // E dan null
            };
            $sks         = $n->mataKuliah->sks ?? 0;
            $totalBobot += $bobot * $sks;
            $totalSks   += $sks;
        }

        return $totalSks > 0 ? round($totalBobot / $totalSks, 2) : 0.0;
    }

    // ── Helper: cek berisiko (boolean) ───────────────────────
    // Berdasarkan Pedoman Akademik D4 TI Polinema 2022/2023
    public function isBerisiko(): bool
    {
        return !empty($this->getKategoriRisiko());
    }

    
    public function getKategoriRisiko(): array
    {
        $semNilai   = $this->nilais->max('semester') ?? 0;
        $semAlpha   = $this->absensis->max('semester') ?? 0;
        $totalAlpha = $semAlpha > 0
            ? $this->absensis->where('semester', $semAlpha)->sum('jam_alpha')
            : 0;

        $kategori = [];

        // ── Kategori Alpha (level SP) ────────────────────────
        if ($totalAlpha >= 56) {
            $kategori[] = 'ps';
        } elseif ($totalAlpha >= 47) {
            $kategori[] = 'sp3';
        } elseif ($totalAlpha >= 36) {
            $kategori[] = 'sp2';
        } elseif ($totalAlpha >= 18) {
            $kategori[] = 'sp1';
        }

        if ($semNilai > 0) {
            $nilaiSemIni = $this->nilais->where('semester', $semNilai);

            // ── Cek nilai E ──────────────────────────────────
            if ($nilaiSemIni->contains('grade', 'E')) {
                $kategori[] = 'nilai_e';
            }

            // ── Cek nilai D > 3 matkul ───────────────────────
            if ($nilaiSemIni->where('grade', 'D')->count() > 3) {
                $kategori[] = 'nilai_d';
            }

            // ── Cek IPS < 2.00 ───────────────────────────────
            $ips = $this->getIpSemester($semNilai);
            if ($ips > 0 && $ips < 2.00) {
                $kategori[] = 'ips_rendah';
            }
        }

        return $kategori;
    }

    // ── Helper: total jam alpha per semester ─────────────────
    public function getTotalAlpha(int $semester): int
    {
        return $this->absensis()
            ->where('semester', $semester)
            ->sum('jam_alpha');
    }

    // ── Helper: daftar matkul dengan grade D/E ───────────────
    public function getMataKuliahDE(int $semester)
    {
        return $this->nilais()
            ->where('semester', $semester)
            ->whereIn('grade', ['D', 'E'])
            ->with('mataKuliah')
            ->get();
    }

    // ── Helper: kompensasi semester tertentu ─────────────────
    public function getKompensasiSemester(int $semester, ?string $tahunAkademik = null)
    {
        $tahunAkademik ??= config('akademik.tahun_akademik');

        return $this->kompensasis
            ->where('semester', $semester)
            ->where('tahun_akademik', $tahunAkademik)
            ->first();
    }

    // ── Helper: cek kompen pending ───────────────────────────
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
