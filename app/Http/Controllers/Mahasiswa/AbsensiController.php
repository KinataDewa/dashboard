<?php
namespace App\Http\Controllers\Mahasiswa;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
 
class AbsensiController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::where('user_id', auth()->id())
            ->with('kelas')
            ->firstOrFail();
 
        // Ambil semua semester yang punya absensi (untuk dropdown)
        $semesterList = $mahasiswa->absensis()
            ->distinct('semester')
            ->pluck('semester')
            ->sort()
            ->values();
 
        // Default = semester terbaru, bisa di-override via ?semester=X
        $semesterAktif = $mahasiswa->kelas->semester ?? 6;
        $semester = (int) request('semester', $semesterList->last() ?? $semesterAktif);
 
        $absensis = $mahasiswa->absensis()
            ->where('semester', $semester)
            ->with('mataKuliah')
            ->get();
 
        $sumHadir = $absensis->sum('jam_hadir');
        $sumIzin  = $absensis->sum('jam_izin');
        $sumSakit = $absensis->sum('jam_sakit');
        $sumAlpha = $absensis->sum('jam_alpha');
        $sumAll   = $sumHadir + $sumIzin + $sumSakit + $sumAlpha;
        $pctHadir = $sumAll > 0 ? round($sumHadir / $sumAll * 100) : 0;
 
        $absensiKritis = $absensis->where('jam_alpha', '>=', 18);
 
        return view('mahasiswa.absensi.index', compact(
            'mahasiswa', 'absensis', 'semesterAktif', 'semester', 'semesterList',
            'sumHadir', 'sumIzin', 'sumSakit', 'sumAlpha', 'pctHadir',
            'absensiKritis'
        ));
    }
}