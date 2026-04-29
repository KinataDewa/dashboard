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
 
        $semesterAktif = $mahasiswa->kelas->semester ?? 6;
 
        $absensis = $mahasiswa->absensis()
            ->where('semester', $semesterAktif)
            ->with('mataKuliah')
            ->get();
 
        $totalHadir = $absensis->sum('jam_hadir');
        $totalIzin  = $absensis->sum('jam_izin');
        $totalSakit = $absensis->sum('jam_sakit');
        $totalAlpha = $absensis->sum('jam_alpha');
 
        $absensiKritis = $absensis->where('jam_alpha', '>=', 18);
 
        return view('mahasiswa.absensi.index', compact(
            'mahasiswa', 'absensis', 'semesterAktif',
            'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpha',
            'absensiKritis'
        ));
    }
}