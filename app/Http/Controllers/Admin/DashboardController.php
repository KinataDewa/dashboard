<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Mail\MahasiswaBerisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $semesterAktif = Kelas::max('semester') ?? 0;
        $angkatanList  = Mahasiswa::distinct()->orderBy('angkatan')->pluck('angkatan');
        $semesterList  = Kelas::distinct()->orderBy('semester')->pluck('semester');

        $totalDosen     = Dosen::count();
        $totalMatkul    = MataKuliah::count();
        $totalKelas     = Kelas::count();
        $totalMahasiswa = Mahasiswa::where('status', 'aktif')->count();

        $kelasAktifIds = Kelas::where('semester', $semesterAktif)->pluck('id');

        $mhsAktif = Mahasiswa::where('status', 'aktif')
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasAktifIds))
            ->with(['nilais', 'absensis', 'kompensasis'])
            ->get();

        $mahasiswaBerisiko = $mhsAktif->filter(fn($m) => $m->getKategoriRisiko($semesterAktif) !== [])->count();

        $distribusiRisiko = ['sp1' => 0, 'sp2' => 0, 'sp3' => 0, 'ps' => 0, 'nilai_e' => 0, 'nilai_d' => 0, 'ips_rendah' => 0];
        foreach ($mhsAktif as $mhs) {
            foreach ($mhs->getKategoriRisiko($semesterAktif) as $kat) {
                if (isset($distribusiRisiko[$kat])) $distribusiRisiko[$kat]++;
            }
        }

        $distribusiGrade = $this->queryDistribusiGrade($semesterAktif);
        $ringkasanKelas  = $this->hitungRingkasanKelas($mhsAktif, $semesterAktif, $kelasAktifIds);

        return view('admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalMatkul', 'totalKelas',
            'mahasiswaBerisiko', 'semesterAktif', 'angkatanList',
            'distribusiRisiko', 'distribusiGrade', 'ringkasanKelas', 'semesterList'
        ));
    }

    // ── API: distribusi grade per semester (+ opsional angkatan) ──
    public function apiDistribusiGrade(Request $request)
    {
        $semester = $request->integer('semester', Kelas::max('semester') ?? 0);
        $angkatan = $request->get('angkatan');
        return response()->json($this->queryDistribusiGrade($semester, $angkatan));
    }

    // ── API: distribusi risiko per semester (+ opsional angkatan) ─
    public function apiDistribusiRisiko(Request $request)
    {
        $semester = $request->integer('semester', Kelas::max('semester') ?? 0);
        $angkatan = $request->get('angkatan', '');

        $kelasIds = Kelas::where('semester', $semester)
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->pluck('id');

        $mhsAktif = Mahasiswa::where('status', 'aktif')
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with(['nilais', 'absensis', 'kompensasis'])
            ->get();

        $distribusi = ['sp1' => 0, 'sp2' => 0, 'sp3' => 0, 'ps' => 0, 'nilai_e' => 0, 'nilai_d' => 0, 'ips_rendah' => 0];
        $totalBerisiko = 0;
        foreach ($mhsAktif as $mhs) {
            $kat = $mhs->getKategoriRisiko($semester);
            if (!empty($kat)) $totalBerisiko++;
            foreach ($kat as $k) {
                if (isset($distribusi[$k])) $distribusi[$k]++;
            }
        }

        return response()->json(['distribusi' => $distribusi, 'total_berisiko' => $totalBerisiko]);
    }

    // ── API: HTML partial tabel ringkasan kelas ───────────────────
    public function apiRingkasanKelasHtml(Request $request)
    {
        $semester  = $request->integer('semester', Kelas::max('semester') ?? 0);
        $angkatan  = $request->get('angkatan', '');
        $kelasIds  = Kelas::where('semester', $semester)
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->pluck('id');
        $mhsAktif = Mahasiswa::where('status', 'aktif')
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with(['nilais', 'absensis', 'kompensasis'])
            ->get();
        $ringkasanKelas = $this->hitungRingkasanKelas($mhsAktif, $semester, $kelasIds);
        return view('admin.dashboard._kelas_table', compact('ringkasanKelas'));
    }

    // ── API: ringkasan per kelas per semester ─────────────────────
    public function apiRingkasanKelas(Request $request)
    {
        $semester  = $request->integer('semester', Kelas::max('semester') ?? 0);
        $angkatan  = $request->get('angkatan', '');
        $kelasIds  = Kelas::where('semester', $semester)
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->pluck('id');
        $mhsAktif = Mahasiswa::where('status', 'aktif')
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with(['nilais', 'absensis', 'kompensasis'])
            ->get();
        return response()->json($this->hitungRingkasanKelas($mhsAktif, $semester, $kelasIds));
    }

    // ── Helper: query distribusi grade ────────────────────────────
    private function queryDistribusiGrade(int $semester, ?string $angkatan = null): array
    {
        $q = DB::table('nilais')
            ->join('mahasiswas', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
            ->where('nilais.semester', $semester)
            ->whereNotNull('nilais.grade')
            ->where('nilais.grade', '!=', '');

        if ($angkatan) {
            $q->where('mahasiswas.angkatan', $angkatan);
        }

        $raw = $q->selectRaw('nilais.grade, COUNT(*) as total')
            ->groupBy('nilais.grade')
            ->pluck('total', 'grade');

        $result = [];
        foreach (['A', 'B+', 'B', 'C+', 'C', 'D', 'E'] as $g) {
            $result[$g] = (int) ($raw[$g] ?? 0);
        }
        return $result;
    }

    // ── Helper: ringkasan akademik per kelas ──────────────────────
    private function hitungRingkasanKelas($mhsAktif, int $semesterAktif, $kelasAktifIds = null): array
    {
        if ($mhsAktif->isEmpty()) return [];

        // Tentukan kelas yang relevan dari kelasAktifIds (filter aktif) atau fallback ke kelas_id mahasiswa
        if ($kelasAktifIds && $kelasAktifIds->isNotEmpty()) {
            $allKelas = Kelas::whereIn('id', $kelasAktifIds)->get()->keyBy('id');
        } else {
            $kelasIds = $mhsAktif->pluck('kelas_id')->filter()->unique();
            $allKelas = Kelas::whereIn('id', $kelasIds)->get()->keyBy('id');
        }

        if ($allKelas->isEmpty()) return [];

        // Grup via pivot kelas_mahasiswa agar akurat (bukan kelas_id shortcut)
        $mhsById = $mhsAktif->keyBy('id');
        $pivots  = \App\Models\KelasMahasiswa::whereIn('mahasiswa_id', $mhsAktif->pluck('id'))
            ->whereIn('kelas_id', $allKelas->keys())
            ->get();

        $byKelas = [];
        foreach ($pivots as $pivot) {
            $kid = $pivot->kelas_id;
            if (!isset($allKelas[$kid]) || !isset($mhsById[$pivot->mahasiswa_id])) continue;
            $byKelas[$kid] ??= collect();
            $byKelas[$kid]->push($mhsById[$pivot->mahasiswa_id]);
        }

        $ringkasan = [];
        foreach ($byKelas as $kelasId => $group) {
            $total    = $group->count();
            $berisiko = $group->filter(fn($m) => $m->getKategoriRisiko($semesterAktif) !== [])->count();
            $ipk      = $total > 0 ? round($group->avg(fn($m) => $m->ipk), 2) : 0.0;
            $ringkasan[] = [
                'kelas_id'   => $kelasId,
                'kelas'      => $allKelas[$kelasId]->nama,
                'angkatan'   => $allKelas[$kelasId]->angkatan,
                'total'      => $total,
                'berisiko'   => $berisiko,
                'ipk'        => $ipk,
                'pct_risiko' => $total > 0 ? (int) round($berisiko / $total * 100) : 0,
            ];
        }

        usort($ringkasan, fn($a, $b) => $b['pct_risiko'] <=> $a['pct_risiko']);
        return $ringkasan;
    }

    public function kirimPeringatan()
    {
        $mahasiswas = \App\Models\Mahasiswa::with([
            'nilais.mataKuliah',
            'absensis',
            'kelas', 'dosenPa', 'user', 'kompensasis',
        ])->where('status', 'aktif')->get();

        $terkirim = 0;
        $gagal    = 0;

        foreach ($mahasiswas as $mhs) {
            // Gunakan isBerisiko() agar konsisten dengan Pedoman Akademik D4 TI Polinema
            if (!$mhs->isBerisiko() || !$mhs->user) continue;

            $email = $mhs->user->email ?? null;
            if (!$email) continue;

            try {
                Mail::to($email)->queue(new MahasiswaBerisiko($mhs));
                $terkirim++;
            } catch (\Exception $e) {
                $gagal++;
                \Log::error("Email gagal ke {$mhs->nama}: " . $e->getMessage());
            }
        }

        $pesan = "✅ Email peringatan berhasil dikirim ke {$terkirim} mahasiswa";
        if ($gagal > 0) $pesan .= " ({$gagal} gagal)";

        return back()->with('success', $pesan);
    }
}
