<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Mail\MahasiswaBerisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $semesterAktif  = KelasMahasiswa::max('semester') ?? 0;
        $totalDosen     = Dosen::count();
        $totalMatkul    = MataKuliah::count();
        $totalKelas     = Kelas::count();
        $angkatanList   = Mahasiswa::distinct()->orderBy('angkatan')->pluck('angkatan');

        $totalMahasiswa = $semesterAktif > 0
            ? KelasMahasiswa::where('semester', $semesterAktif)->distinct('mahasiswa_id')->count('mahasiswa_id')
            : Mahasiswa::where('status', 'aktif')->count();

        // Load semua mahasiswa aktif semester ini sekali, reuse untuk semua metrik
        $mhsAktif = $semesterAktif > 0
            ? Mahasiswa::whereHas('kelasMahasiswas', fn($q) => $q->where('semester', $semesterAktif))
                ->with([
                    'nilais',
                    'absensis',
                    'kompensasis',
                    'kelasMahasiswas' => fn($q) => $q->where('semester', $semesterAktif),
                ])
                ->where('status', 'aktif')
                ->get()
            : collect();

        $mahasiswaBerisiko = $mhsAktif->filter(fn($m) => $m->getKategoriRisiko($semesterAktif) !== [])->count();

        // Distribusi risiko per kategori (satu mahasiswa bisa masuk beberapa kategori)
        $distribusiRisiko = ['sp1' => 0, 'sp2' => 0, 'sp3' => 0, 'ps' => 0, 'nilai_e' => 0, 'nilai_d' => 0, 'ips_rendah' => 0];
        foreach ($mhsAktif as $mhs) {
            foreach ($mhs->getKategoriRisiko($semesterAktif) as $kat) {
                if (isset($distribusiRisiko[$kat])) {
                    $distribusiRisiko[$kat]++;
                }
            }
        }

        $distribusiGrade  = $this->queryDistribusiGrade($semesterAktif);
        $ringkasanKelas   = $this->hitungRingkasanKelas($mhsAktif, $semesterAktif);

        return view('admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalMatkul', 'totalKelas',
            'mahasiswaBerisiko', 'semesterAktif', 'angkatanList',
            'distribusiRisiko', 'distribusiGrade', 'ringkasanKelas'
        ));
    }

    // ── API: distribusi grade per semester (+ opsional angkatan) ──
    public function apiDistribusiGrade(Request $request)
    {
        $semester = $request->integer('semester', KelasMahasiswa::max('semester') ?? 0);
        $angkatan = $request->get('angkatan');
        return response()->json($this->queryDistribusiGrade($semester, $angkatan));
    }

    // ── API: ringkasan per kelas per semester ─────────────────────
    public function apiRingkasanKelas(Request $request)
    {
        $semester = $request->integer('semester', KelasMahasiswa::max('semester') ?? 0);
        $mhsAktif = $semester > 0
            ? Mahasiswa::whereHas('kelasMahasiswas', fn($q) => $q->where('semester', $semester))
                ->with([
                    'nilais', 'absensis', 'kompensasis',
                    'kelasMahasiswas' => fn($q) => $q->where('semester', $semester),
                ])
                ->where('status', 'aktif')
                ->get()
            : collect();
        return response()->json($this->hitungRingkasanKelas($mhsAktif, $semester));
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
    private function hitungRingkasanKelas($mhsAktif, int $semesterAktif): array
    {
        if ($mhsAktif->isEmpty()) return [];

        $kelasIds = KelasMahasiswa::where('semester', $semesterAktif)->distinct()->pluck('kelas_id');
        $allKelas = Kelas::whereIn('id', $kelasIds)->get()->keyBy('id');
        $byKelas  = [];

        foreach ($mhsAktif as $mhs) {
            $kelasId = $mhs->kelasMahasiswas->first()?->kelas_id;
            if (!$kelasId || !isset($allKelas[$kelasId])) continue;
            if (!isset($byKelas[$kelasId])) $byKelas[$kelasId] = collect();
            $byKelas[$kelasId]->push($mhs);
        }

        $ringkasan = [];
        foreach ($byKelas as $kelasId => $group) {
            $total    = $group->count();
            $berisiko = $group->filter(fn($m) => $m->getKategoriRisiko($semesterAktif) !== [])->count();
            $ipk      = $total > 0 ? round($group->avg(fn($m) => $m->ipk), 2) : 0.0;
            $ringkasan[] = [
                'kelas_id'   => $kelasId,
                'kelas'      => $allKelas[$kelasId]->nama,
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
