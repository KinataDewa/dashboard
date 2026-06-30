<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Services\BerisikoService;
use Illuminate\Http\Request;

class BerisikoController extends Controller
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

        return view('admin.berisiko.index', compact(
            'mahasiswaBerisiko', 'summary', 'kelasList', 'kelasId', 'filterJenis',
            'semesterList', 'semesterAktif', 'angkatanList', 'angkatan'
        ));
    }
}
