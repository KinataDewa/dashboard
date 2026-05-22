<?php
// app/Http/Controllers/Admin/KirimPeringatanController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Mail\MahasiswaBerisiko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class KirimPeringatanController extends Controller
{
    public function index(Request $request)
    {
        $kelasId     = $request->get('kelas_id');
        $filterJenis = $request->get('jenis', 'semua');
        $kelasList   = Kelas::orderBy('nama')->get();

        $query = Mahasiswa::with([
            'user', 'kelas', 'dosen', 'nilais', 'absensis'
        ])->where('status', 'aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $semuaMahasiswa = $query->get();

        $mahasiswaBerisiko = $semuaMahasiswa->filter(function ($mhs) use ($filterJenis) {
            $semNilai       = $mhs->nilais->max('semester') ?? 0;
            $punya_nilai_de = $semNilai > 0 && $mhs->nilais
                ->where('semester', $semNilai)
                ->whereIn('grade', ['D', 'E'])
                ->isNotEmpty();

            $semAlpha    = $mhs->absensis->max('semester') ?? 0;
            $total_alpha = $semAlpha > 0
                ? $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha')
                : 0;
            $punya_alpha = $total_alpha >= 18;

            if ($filterJenis === 'nilai')   return $punya_nilai_de;
            if ($filterJenis === 'absensi') return $punya_alpha;
            return $punya_nilai_de || $punya_alpha;

        })->map(function ($mhs) {
            $semNilai   = $mhs->nilais->max('semester') ?? 0;
            $nilaiDE    = $semNilai > 0
                ? $mhs->nilais->where('semester', $semNilai)->whereIn('grade', ['D', 'E'])
                : collect();

            $semAlpha   = $mhs->absensis->max('semester') ?? 0;
            $totalAlpha = $semAlpha > 0
                ? $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha')
                : 0;

            $kategori = [];
            if ($nilaiDE->isNotEmpty()) $kategori[] = 'nilai';
            if ($totalAlpha >= 18)      $kategori[] = 'absensi';

            return [
                'id'          => $mhs->id,
                'nim'         => $mhs->nim,
                'nama'        => $mhs->nama ?? $mhs->user->name ?? '-',
                'kelas'       => $mhs->kelas->nama ?? '-',
                'dosen_pa'    => optional($mhs->dosen)->nama ?? '-',
                'ipk'         => number_format($mhs->ipk ?? 0, 2),
                'jumlah_de'   => $nilaiDE->count(),
                'total_alpha' => $totalAlpha,
                'kategori'    => $kategori,
            ];
        })->sortBy('nama')->values();

        $summary = [
            'total_berisiko'    => $mahasiswaBerisiko->count(),
            'berisiko_nilai'    => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai', $m['kategori']))->count(),
            'berisiko_absensi'  => $mahasiswaBerisiko->filter(fn($m) => in_array('absensi', $m['kategori']))->count(),
            'berisiko_keduanya' => $mahasiswaBerisiko->filter(fn($m) => count($m['kategori']) >= 2)->count(),
        ];

        return view('admin.kirim-peringatan.index', compact(
            'mahasiswaBerisiko', 'summary', 'kelasList', 'kelasId', 'filterJenis'
        ));
    }

    // Kirim email ke SATU mahasiswa
    public function kirimSatu(Request $request)
    {
        $mahasiswaId = $request->input('mahasiswa_id');
        $mahasiswa   = Mahasiswa::with(['user', 'nilais.mataKuliah', 'absensis'])->findOrFail($mahasiswaId);

        try {
            Mail::to($mahasiswa->user->email)->send(new MahasiswaBerisiko($mahasiswa));
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim ke ' . $mahasiswa->nama,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal kirim ke ' . $mahasiswa->nama . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    // Kirim email ke BANYAK mahasiswa (massal)
    public function kirimMassal(Request $request)
    {
        $ids      = $request->input('ids', []);
        $berhasil = 0;
        $gagal    = 0;
        $pesanGagal = [];

        foreach ($ids as $id) {
            $mahasiswa = Mahasiswa::with(['user', 'nilais.mataKuliah', 'absensis'])->find($id);
            if (!$mahasiswa) continue;

            try {
                Mail::to($mahasiswa->user->email)->send(new MahasiswaBerisiko($mahasiswa));
                $berhasil++;
            } catch (\Exception $e) {
                $gagal++;
                $pesanGagal[] = $mahasiswa->nama . ': ' . $e->getMessage();
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
