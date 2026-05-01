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
 
        // Ambil semua semester yang punya nilai (untuk dropdown)
        $semesterList = $mahasiswa->nilais()
            ->distinct('semester')
            ->pluck('semester')
            ->sort()
            ->values();
 
        // Default = semester terbaru, bisa di-override via ?semester=X
        $semesterAktif = $mahasiswa->kelas->semester ?? 6;
        $semester = (int) request('semester', $semesterList->last() ?? $semesterAktif);
 
        $nilais = $mahasiswa->nilais()
            ->where('semester', $semester)
            ->with('mataKuliah')
            ->get();
 
        $ip = $mahasiswa->getIpSemester($semester);
 
        // Hitung IPK kumulatif sampai semester ini
        $ipk = $mahasiswa->ipk;
 
        return view('mahasiswa.nilai.index', compact(
            'mahasiswa', 'nilais', 'ip', 'ipk',
            'semester', 'semesterList', 'semesterAktif'
        ));
    }
}