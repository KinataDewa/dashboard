<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Models\EmailLog;
use App\Mail\MahasiswaBerisiko;
use App\Services\BerisikoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class KirimPeringatanController extends Controller
{
    public function index(Request $request)
    {
        $kelasId     = $request->get('kelas_id');
        $filterJenis = $request->get('jenis', 'semua');
        $angkatan    = $request->get('angkatan', '');

        $angkatanList  = Kelas::distinct()->orderByDesc('angkatan')->pluck('angkatan');
        $semesterList  = Kelas::distinct()->orderBy('semester')->pluck('semester');
        $semInput      = $request->get('semester', '');
        $semesterAktif = $semInput !== '' ? (int) $semInput : 0; // 0 = semua semester

        $kelasList = Kelas::when($semesterAktif, fn($q) => $q->where('semester', $semesterAktif))
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->orderBy('semester')->orderBy('nama')->get();

        $kelasAktifIds = Kelas::when($semesterAktif, fn($q) => $q->where('semester', $semesterAktif))
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->when($kelasId,  fn($q) => $q->where('id', $kelasId))
            ->pluck('id');

        $query = Mahasiswa::with([
            'user', 'kelas', 'dosenPa', 'nilais.mataKuliah', 'absensis', 'kompensasis',
        ])->where('status', 'aktif');

        if ($semesterAktif || $angkatan || $kelasId) {
            $query->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasAktifIds));
        }

        $semuaMahasiswa    = $query->orderBy('nama')->get();
        $mahasiswaBerisiko = BerisikoService::filterAndMap($semuaMahasiswa, $filterJenis, $semesterAktif);

        // Summary per kategori sesuai Pedoman Akademik D4 TI Polinema
        $summary = [
            'total_berisiko'  => $mahasiswaBerisiko->count(),
            'berisiko_alpha'  => $mahasiswaBerisiko->filter(
                fn($m) => array_intersect(['sp1','sp2','sp3','ps'], $m['kategori']) !== []
            )->count(),
            'berisiko_nilai'  => $mahasiswaBerisiko->filter(
                fn($m) => array_intersect(['nilai_e','nilai_d'], $m['kategori']) !== []
            )->count(),
            'berisiko_ips'    => $mahasiswaBerisiko->filter(
                fn($m) => in_array('ips_rendah', $m['kategori'])
            )->count(),
            'sp1'             => $mahasiswaBerisiko->filter(fn($m) => in_array('sp1',        $m['kategori']))->count(),
            'sp2'             => $mahasiswaBerisiko->filter(fn($m) => in_array('sp2',        $m['kategori']))->count(),
            'sp3'             => $mahasiswaBerisiko->filter(fn($m) => in_array('sp3',        $m['kategori']))->count(),
            'ps'              => $mahasiswaBerisiko->filter(fn($m) => in_array('ps',         $m['kategori']))->count(),
            'nilai_e'         => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai_e',    $m['kategori']))->count(),
            'nilai_d'         => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai_d',    $m['kategori']))->count(),
            'ips_rendah'      => $mahasiswaBerisiko->filter(fn($m) => in_array('ips_rendah', $m['kategori']))->count(),
        ];

        return view('admin.kirim-peringatan.index', compact(
            'mahasiswaBerisiko', 'summary', 'kelasList', 'kelasId', 'filterJenis',
            'semesterList', 'semesterAktif', 'angkatanList', 'angkatan'
        ));
    }

    // ── Halaman History ─────────────────────────────────────
    public function history(Request $request)
    {
        $filterStatus  = $request->get('status', 'semua');
        $filterKelas   = $request->get('kelas', '');
        $cari          = $request->get('cari', '');

        $query = EmailLog::with(['mahasiswa.kelas', 'pengirim'])
            ->orderBy('created_at', 'desc');

        if ($filterStatus !== 'semua') {
            $query->where('status', $filterStatus);
        }

        if ($filterKelas) {
            $query->where('kelas', $filterKelas);
        }

        if ($cari) {
            $query->where(function($q) use ($cari) {
                $q->where('nama_mahasiswa', 'like', "%{$cari}%")
                  ->orWhere('email_tujuan', 'like', "%{$cari}%");
            });
        }

        $logs       = $query->paginate(20)->withQueryString();
        $kelasList  = EmailLog::distinct()->pluck('kelas')->filter()->sort()->values();

        $totalLogs      = EmailLog::count();
        $totalBerhasil  = EmailLog::where('status', 'berhasil')->count();
        $totalGagal     = EmailLog::where('status', 'gagal')->count();
        $terakhirKirim  = EmailLog::latest()->first()?->created_at;

        return view('admin.kirim-peringatan.history', compact(
            'logs', 'kelasList', 'filterStatus', 'filterKelas', 'cari',
            'totalLogs', 'totalBerhasil', 'totalGagal', 'terakhirKirim'
        ));
    }

    // ── Kirim ke SATU mahasiswa ──────────────────────────────
    public function kirimSatu(Request $request)
    {
        $mahasiswaId = $request->input('mahasiswa_id');
        $mahasiswa   = Mahasiswa::with([
            'user', 'nilais.mataKuliah', 'absensis', 'kelas', 'kompensasis',
        ])->findOrFail($mahasiswaId);

        $kategori   = $mahasiswa->getKategoriRisiko();
        $semRef     = max($mahasiswa->nilais->max('semester') ?? 0, $mahasiswa->absensis->max('semester') ?? 0);
        $nilaiDE    = $semRef > 0
            ? $mahasiswa->nilais->where('semester', $semRef)->whereIn('grade', ['D', 'E'])
            : collect();
        $totalAlpha = $semRef > 0
            ? $mahasiswa->absensis->where('semester', $semRef)->sum('jam_alpha')
            : 0;

        try {
            Mail::to($mahasiswa->user->email)->send(new MahasiswaBerisiko($mahasiswa));

            EmailLog::create([
                'mahasiswa_id'    => $mahasiswa->id,
                'email_tujuan'    => $mahasiswa->user->email,
                'nama_mahasiswa'  => $mahasiswa->nama ?? $mahasiswa->user->name,
                'kelas'           => $mahasiswa->kelas->nama ?? '-',
                'kategori_risiko' => $kategori,
                'jumlah_nilai_de' => $nilaiDE->count(),
                'total_alpha'     => $totalAlpha,
                'status'          => 'berhasil',
                'dikirim_oleh'    => Auth::id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim ke ' . ($mahasiswa->nama ?? $mahasiswa->user->name),
            ]);
        } catch (\Exception $e) {
            EmailLog::create([
                'mahasiswa_id'    => $mahasiswa->id,
                'email_tujuan'    => $mahasiswa->user->email,
                'nama_mahasiswa'  => $mahasiswa->nama ?? $mahasiswa->user->name,
                'kelas'           => $mahasiswa->kelas->nama ?? '-',
                'kategori_risiko' => $kategori,
                'jumlah_nilai_de' => $nilaiDE->count(),
                'total_alpha'     => $totalAlpha,
                'status'          => 'gagal',
                'pesan_error'     => $e->getMessage(),
                'dikirim_oleh'    => Auth::id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal kirim ke ' . ($mahasiswa->nama ?? $mahasiswa->user->name) . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Kirim MASSAL ─────────────────────────────────────────
    public function kirimMassal(Request $request)
    {
        $ids        = $request->input('ids', []);
        $berhasil   = 0;
        $gagal      = 0;
        $pesanGagal = [];

        // Batch-load semua mahasiswa sekaligus untuk menghindari N+1 query
        $mahasiswas = Mahasiswa::with([
            'user', 'nilais.mataKuliah', 'absensis', 'kelas', 'kompensasis',
        ])->whereIn('id', $ids)->get()->keyBy('id');

        foreach ($ids as $id) {
            $mahasiswa = $mahasiswas[$id] ?? null;
            if (!$mahasiswa) continue;

            $kategori   = $mahasiswa->getKategoriRisiko();
            $semRef     = max($mahasiswa->nilais->max('semester') ?? 0, $mahasiswa->absensis->max('semester') ?? 0);
            $nilaiDE    = $semRef > 0
                ? $mahasiswa->nilais->where('semester', $semRef)->whereIn('grade', ['D', 'E'])
                : collect();
            $totalAlpha = $semRef > 0
                ? $mahasiswa->absensis->where('semester', $semRef)->sum('jam_alpha')
                : 0;

            try {
                Mail::to($mahasiswa->user->email)->queue(new MahasiswaBerisiko($mahasiswa));
                EmailLog::create([
                    'mahasiswa_id'    => $mahasiswa->id,
                    'email_tujuan'    => $mahasiswa->user->email,
                    'nama_mahasiswa'  => $mahasiswa->nama ?? $mahasiswa->user->name,
                    'kelas'           => $mahasiswa->kelas->nama ?? '-',
                    'kategori_risiko' => $kategori,
                    'jumlah_nilai_de' => $nilaiDE->count(),
                    'total_alpha'     => $totalAlpha,
                    'status'          => 'berhasil',
                    'dikirim_oleh'    => Auth::id(),
                ]);
                $berhasil++;
            } catch (\Exception $e) {
                EmailLog::create([
                    'mahasiswa_id'    => $mahasiswa->id,
                    'email_tujuan'    => $mahasiswa->user->email,
                    'nama_mahasiswa'  => $mahasiswa->nama ?? $mahasiswa->user->name,
                    'kelas'           => $mahasiswa->kelas->nama ?? '-',
                    'kategori_risiko' => $kategori,
                    'jumlah_nilai_de' => $nilaiDE->count(),
                    'total_alpha'     => $totalAlpha,
                    'status'          => 'gagal',
                    'pesan_error'     => $e->getMessage(),
                    'dikirim_oleh'    => Auth::id(),
                ]);
                $gagal++;
                $pesanGagal[] = ($mahasiswa->nama ?? $mahasiswa->user->name) . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'success'     => true,
            'berhasil'    => $berhasil,
            'gagal'       => $gagal,
            'pesan_gagal' => $pesanGagal,
            'message'     => $berhasil . ' email berhasil dikirim' . ($gagal > 0 ? ', ' . $gagal . ' gagal.' : '.'),
        ]);
    }
}
