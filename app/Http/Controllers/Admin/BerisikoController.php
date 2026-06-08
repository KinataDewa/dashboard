<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Services\BerisikoService;
use Illuminate\Http\Request;

class BerisikoController extends Controller
{
    public function index(Request $request)
    {
        $kelasId     = $request->get('kelas_id');
        $filterJenis = $request->get('jenis', 'semua');
        $kelasList   = Kelas::orderBy('nama')->get();

        $query = Mahasiswa::with([
            'user', 'kelas', 'dosen', 'nilais.mataKuliah', 'absensis'
        ])->where('status', 'aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $semuaMahasiswa    = $query->get();
        $mahasiswaBerisiko = BerisikoService::filterAndMap($semuaMahasiswa, $filterJenis);

        // Summary per kategori
        $summary = [
            'total_mahasiswa'  => $semuaMahasiswa->count(),
            'total_berisiko'   => $mahasiswaBerisiko->count(),
            'ps'               => $mahasiswaBerisiko->filter(fn($m) => in_array('ps', $m['kategori']))->count(),
            'sp3'              => $mahasiswaBerisiko->filter(fn($m) => in_array('sp3', $m['kategori']))->count(),
            'sp2'              => $mahasiswaBerisiko->filter(fn($m) => in_array('sp2', $m['kategori']))->count(),
            'sp1'              => $mahasiswaBerisiko->filter(fn($m) => in_array('sp1', $m['kategori']))->count(),
            'nilai_e'          => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai_e', $m['kategori']))->count(),
            'nilai_d'          => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai_d', $m['kategori']))->count(),
            'ips_rendah'       => $mahasiswaBerisiko->filter(fn($m) => in_array('ips_rendah', $m['kategori']))->count(),
        ];

        return view('admin.berisiko.index', compact(
            'mahasiswaBerisiko', 'summary', 'kelasList', 'kelasId', 'filterJenis'
        ));
    }
}
