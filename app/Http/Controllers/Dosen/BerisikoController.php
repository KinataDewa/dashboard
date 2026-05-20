<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BerisikoController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::where('user_id', Auth::id())->first();

        if (!$dosen) {
            abort(403, 'Data dosen tidak ditemukan.');
        }

        $filterJenis = $request->get('jenis', 'semua');

        $kelasIds = \App\Models\Kelas::where('dosen_pa_id', $dosen->id)
            ->pluck('id');

        $semuaMahasiswa = Mahasiswa::with([
            'user',
            'kelas',
            'dosen',
            'nilais',
            'absensis',
        ])
        ->whereIn('kelas_id', $kelasIds)
        ->get();

        $mahasiswaBerisiko = $semuaMahasiswa->filter(function ($mhs) use ($filterJenis) {
            // Semester terakhir berdasarkan nilai
            $semesterTerakhirNilai = $mhs->nilais->sortByDesc('semester')->first()->semester ?? 0;
            $punya_nilai_de        = $mhs->nilais
                ->where('semester', $semesterTerakhirNilai)
                ->whereIn('grade', ['D', 'E'])
                ->count() > 0;

            // Semester terakhir berdasarkan absensi
            $semesterTerakhirAlpha = $mhs->absensis->sortByDesc('semester')->first()->semester ?? 0;
            $total_alpha           = $mhs->absensis
                ->where('semester', $semesterTerakhirAlpha)
                ->sum('jam_alpha');
            $punya_alpha = $total_alpha >= 18;

            if ($filterJenis === 'nilai')   return $punya_nilai_de;
            if ($filterJenis === 'absensi') return $punya_alpha;
            return $punya_nilai_de || $punya_alpha;

        })->map(function ($mhs) {
            // Semester terakhir nilai
            $semesterTerakhirNilai = $mhs->nilais->sortByDesc('semester')->first()->semester ?? 0;
            $nilaiDE               = $mhs->nilais
                ->where('semester', $semesterTerakhirNilai)
                ->whereIn('grade', ['D', 'E']);

            // Semester terakhir absensi
            $semesterTerakhirAlpha = $mhs->absensis->sortByDesc('semester')->first()->semester ?? 0;
            $totalAlpha            = $mhs->absensis
                ->where('semester', $semesterTerakhirAlpha)
                ->sum('jam_alpha');

            $ipk = $mhs->ipk ?? 0;

            $kategori = [];
            if ($nilaiDE->count() > 0) $kategori[] = 'nilai';
            if ($totalAlpha >= 18)     $kategori[] = 'absensi';

            return [
                'id'          => $mhs->id,
                'nim'         => $mhs->nim,
                'nama'        => $mhs->nama ?? $mhs->user->name ?? '-',
                'kelas'       => $mhs->kelas->nama ?? '-',
                'ipk'         => number_format($ipk, 2),
                'jumlah_de'   => $nilaiDE->count(),
                'total_alpha' => $totalAlpha,
                'kategori'    => $kategori,
            ];
        })
        ->sortByDesc(fn($m) => count($m['kategori']))
        ->values();

        $summary = [
            'total_mahasiswa'   => $semuaMahasiswa->count(),
            'total_berisiko'    => $mahasiswaBerisiko->count(),
            'berisiko_nilai'    => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai', $m['kategori']))->count(),
            'berisiko_absensi'  => $mahasiswaBerisiko->filter(fn($m) => in_array('absensi', $m['kategori']))->count(),
            'berisiko_keduanya' => $mahasiswaBerisiko->filter(fn($m) => count($m['kategori']) >= 2)->count(),
        ];

        return view('dosen.berisiko', [
            'dosen'             => $dosen,
            'mahasiswaBerisiko' => $mahasiswaBerisiko,
            'summary'           => $summary,
            'filterJenis'       => $filterJenis,
        ]);
    }
}
