<?php
namespace App\Http\Controllers\Dosen;
 
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Mahasiswa;
 
class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();
 
        // Kelas yang dibimbing dosen ini
        $kelas = $dosen->kelas()->with('mahasiswas')->get();
 
        // Semua mahasiswa bimbingan
        $mahasiswas = Mahasiswa::where('dosen_pa_id', $dosen->id)
            ->with(['kelas', 'nilais.mataKuliah', 'absensis'])
            ->get();
 
        // Identifikasi mahasiswa berisiko
        $mahasiswaBerisiko = $mahasiswas->filter(fn($m) => $m->isBerisiko());
 
        // Statistik kelas
        $totalMahasiswa   = $mahasiswas->count();
        $totalBerisiko    = $mahasiswaBerisiko->count();
        $rataRataIpk      = $mahasiswas->avg(fn($m) => $m->ipk);
 
        // Hitung nilai D/E keseluruhan
        $totalNilaiDE = $mahasiswas->sum(function ($m) {
            return $m->nilais->whereIn('grade', ['D', 'E'])->count();
        });
 
        return view('dosen.dashboard', compact(
            'dosen', 'kelas', 'mahasiswas', 'mahasiswaBerisiko',
            'totalMahasiswa', 'totalBerisiko', 'rataRataIpk', 'totalNilaiDE'
        ));
    }
}