<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Services\BerisikoService;
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

        // Query konsisten: pakai dosen_pa_id langsung (sama seperti DashboardController)
        $semuaMahasiswa = Mahasiswa::with([
            'user',
            'kelas',
            'dosen',
            'nilais.mataKuliah',
            'absensis',
        ])
        ->where('dosen_pa_id', $dosen->id)
        ->where('status', 'aktif')
        ->get();

        $mahasiswaBerisiko = BerisikoService::filterBerisiko($semuaMahasiswa, $filterJenis);
        $summary           = BerisikoService::buildSummary($semuaMahasiswa, $mahasiswaBerisiko);

        return view('dosen.berisiko', [
            'dosen'             => $dosen,
            'mahasiswaBerisiko' => $mahasiswaBerisiko,
            'summary'           => $summary,
            'filterJenis'       => $filterJenis,
        ]);
    }
}
