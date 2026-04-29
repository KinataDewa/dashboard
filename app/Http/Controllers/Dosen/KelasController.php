<?php
namespace App\Http\Controllers\Dosen;
 
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
 
class KelasController extends Controller
{
    public function index()
    {
        $dosen = Dosen::where('user_id', auth()->id())->firstOrFail();
 
        $mahasiswas = Mahasiswa::where('dosen_pa_id', $dosen->id)
            ->with(['kelas', 'nilais.mataKuliah', 'absensis'])
            ->get()
            ->map(function ($m) {
                $m->ipk_val      = $m->ipk;
                $m->is_berisiko  = $m->isBerisiko();
                return $m;
            });
 
        return view('dosen.kelas.index', compact('mahasiswas', 'dosen'));
    }
 
    public function detail(int $id)
    {
        $dosen     = Dosen::where('user_id', auth()->id())->firstOrFail();
        $mahasiswa = Mahasiswa::where('id', $id)
            ->where('dosen_pa_id', $dosen->id)
            ->with(['kelas', 'nilais.mataKuliah', 'absensis.mataKuliah'])
            ->firstOrFail();
 
        $semesterAktif = $mahasiswa->kelas->semester ?? 6;
        $nilais        = $mahasiswa->nilais->where('semester', $semesterAktif);
        $absensis      = $mahasiswa->absensis->where('semester', $semesterAktif);
        $ip            = $mahasiswa->getIpSemester($semesterAktif);
        $ipk           = $mahasiswa->ipk;
 
        return view('dosen.kelas.detail', compact(
            'mahasiswa', 'nilais', 'absensis', 'ip', 'ipk', 'semesterAktif'
        ));
    }
}