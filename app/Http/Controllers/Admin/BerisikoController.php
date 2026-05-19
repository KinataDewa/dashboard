<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
 
class BerisikoController extends Controller
{
    public function index(Request $request)
    {
        $kelasId    = $request->get('kelas_id');
        $filterJenis = $request->get('jenis', 'semua'); // semua|nilai|absensi
 
        // Ambil semua kelas untuk dropdown filter
        $kelasList = Kelas::orderBy('nama')->get();
 
        // Query mahasiswa berisiko
        $query = Mahasiswa::with([
            'user',
            'kelas',
            'dosen',
            'nilais',
            'absensis',
        ]);
 
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }
 
        $semuaMahasiswa = $query->get();
 
        // Filter & kategorisasi berisiko
        $mahasiswaBerisiko = $semuaMahasiswa->filter(function ($mhs) use ($filterJenis) {
            $punya_nilai_de  = $mhs->nilais->whereIn('grade', ['D', 'E'])->count() > 0;
            $total_alpha     = $mhs->absensis->sum('jam_alpha');
            $punya_alpha     = $total_alpha >= 18;
 
            if ($filterJenis === 'nilai')    return $punya_nilai_de;
            if ($filterJenis === 'absensi')  return $punya_alpha;
            return $punya_nilai_de || $punya_alpha; // semua
        })->map(function ($mhs) {
            $nilaiDE    = $mhs->nilais->whereIn('grade', ['D', 'E']);
            $totalAlpha = $mhs->absensis->sum('jam_alpha');
            $ipk        = $mhs->ipk ?? 0;
 
            $kategori = [];
            if ($nilaiDE->count() > 0)  $kategori[] = 'nilai';
            if ($totalAlpha >= 18)      $kategori[] = 'absensi';
 
            return [
                'id'          => $mhs->id,
                'nim'         => $mhs->nim,
                'nama'        => $mhs->nama ?? $mhs->user->name ?? '-',
                'kelas'       => $mhs->kelas->nama ?? '-',
                'dosen_pa'    => optional($mhs->dosen->first())->nama ?? '-',
                'ipk'         => number_format($ipk, 2),
                'jumlah_de'   => $nilaiDE->count(),
                'matkul_de'   => $nilaiDE->pluck('mata_kuliah_id')->toArray(),
                'total_alpha' => $totalAlpha,
                'kategori'    => $kategori, // ['nilai', 'absensi'] atau salah satu
                'nilais'      => $mhs->nilais,
            ];
        })->sortByDesc(fn($m) => count($m['kategori']))->values();
 
        // Summary statistik
        $summary = [
            'total_berisiko'      => $mahasiswaBerisiko->count(),
            'berisiko_nilai'      => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai', $m['kategori']))->count(),
            'berisiko_absensi'    => $mahasiswaBerisiko->filter(fn($m) => in_array('absensi', $m['kategori']))->count(),
            'berisiko_keduanya'   => $mahasiswaBerisiko->filter(fn($m) => count($m['kategori']) >= 2)->count(),
            'total_mahasiswa'     => $semuaMahasiswa->count(),
        ];
 
        return view('admin.berisiko.index', compact(
            'mahasiswaBerisiko', 'summary', 'kelasList', 'kelasId', 'filterJenis'
        ));
    }
}