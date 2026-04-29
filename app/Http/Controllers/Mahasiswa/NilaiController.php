<?php
namespace App\Http\Controllers\Mahasiswa;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
 
class NilaiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())
            ->with('kelas')
            ->firstOrFail();
 
        $semesterAktif = $mahasiswa->kelas->semester ?? 6;
 
        return $this->bySemester($semesterAktif);
    }
 
    public function bySemester(int $semester)
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())
            ->with('kelas')
            ->firstOrFail();
 
        $nilais = $mahasiswa->nilais()
            ->where('semester', $semester)
            ->with('mataKuliah')
            ->get();
 
        $ip = $mahasiswa->getIpSemester($semester);
 
        // Ambil semua semester yang punya nilai
        $semesterList = $mahasiswa->nilais()
            ->distinct('semester')
            ->pluck('semester')
            ->sort()
            ->values();
 
        return view('mahasiswa.nilai.index', compact(
            'mahasiswa', 'nilais', 'ip', 'semester', 'semesterList'
        ));
    }
}