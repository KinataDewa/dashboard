<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Kelas;
 
class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::where('status', 'aktif')->count();
        $totalDosen     = Dosen::count();
        $totalMatkul    = MataKuliah::count();
        $totalKelas     = Kelas::count();
 
        // Mahasiswa berisiko (nilai D/E atau alpha >= 18)
        $mahasiswaBerisiko = Mahasiswa::with(['nilais', 'absensis'])
            ->get()
            ->filter(fn($m) => $m->isBerisiko())
            ->count();
 
        return view('admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalMatkul',
            'totalKelas', 'mahasiswaBerisiko'
        ));
    }
}