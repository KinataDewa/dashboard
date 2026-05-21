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

        $kelasList = Kelas::orderBy('nama')->get();

        $query = Mahasiswa::with([
            'user',
            'kelas',
            'dosen',
            'nilais.mataKuliah',
            'absensis',
        ])->where('status', 'aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $semuaMahasiswa    = $query->get();
        $mahasiswaBerisiko = BerisikoService::filterBerisiko($semuaMahasiswa, $filterJenis);
        $summary           = BerisikoService::buildSummary($semuaMahasiswa, $mahasiswaBerisiko);

        return view('admin.berisiko.index', compact(
            'mahasiswaBerisiko', 'summary', 'kelasList', 'kelasId', 'filterJenis'
        ));
    }
}
