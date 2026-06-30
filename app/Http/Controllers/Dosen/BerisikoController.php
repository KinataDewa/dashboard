<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
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
        $kelasIds    = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');

        $semesterList  = Kelas::whereIn('id', $kelasIds)->distinct()->orderBy('semester')->pluck('semester');
        $semesterAktif = (int) $request->get('semester', $semesterList->max() ?? 1);

        $kelasAktifIds = Kelas::whereIn('id', $kelasIds)->where('semester', $semesterAktif)->pluck('id');
        $semuaMahasiswa = Mahasiswa::whereHas('kelasMahasiswas', function ($q) use ($kelasAktifIds) {
            $q->whereIn('kelas_id', $kelasAktifIds);
        })
            ->with([
                'user', 'kelas', 'dosenPa', 'nilais.mataKuliah', 'absensis', 'kompensasis',
            ])
            ->orderBy('nama')
            ->get();

        $mahasiswaBerisiko = BerisikoService::filterAndMap($semuaMahasiswa, $filterJenis, $semesterAktif);

        $summary = [
            'total_mahasiswa' => $semuaMahasiswa->count(),
            'total_berisiko'  => $mahasiswaBerisiko->count(),
            'ps'              => $mahasiswaBerisiko->filter(fn($m) => in_array('ps',        $m['kategori']))->count(),
            'sp3'             => $mahasiswaBerisiko->filter(fn($m) => in_array('sp3',       $m['kategori']))->count(),
            'sp2'             => $mahasiswaBerisiko->filter(fn($m) => in_array('sp2',       $m['kategori']))->count(),
            'sp1'             => $mahasiswaBerisiko->filter(fn($m) => in_array('sp1',       $m['kategori']))->count(),
            'nilai_e'         => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai_e',   $m['kategori']))->count(),
            'nilai_d'         => $mahasiswaBerisiko->filter(fn($m) => in_array('nilai_d',   $m['kategori']))->count(),
            'ips_rendah'      => $mahasiswaBerisiko->filter(fn($m) => in_array('ips_rendah',$m['kategori']))->count(),
        ];

        return view('dosen.berisiko', [
            'dosen'             => $dosen,
            'mahasiswaBerisiko' => $mahasiswaBerisiko,
            'summary'           => $summary,
            'filterJenis'       => $filterJenis,
            'semesterList'      => $semesterList,
            'semesterAktif'     => $semesterAktif,
        ]);
    }
}
