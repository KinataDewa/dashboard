<?php
namespace App\Http\Controllers\Mahasiswa;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
 
class DashboardController extends Controller
{
    public function index()
    {
        $user      = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)
            ->with(['kelas', 'dosenPa'])
            ->firstOrFail();
 
        $semesterAktif  = $mahasiswa->kelas->semester ?? 6;
        $tahunAkademik  = $mahasiswa->kelas->tahun_akademik ?? '2024/2025';
 
        // Nilai semester aktif
        $nilais = $mahasiswa->nilais()
            ->where('semester', $semesterAktif)
            ->with('mataKuliah')
            ->get();
 
        // Absensi semester aktif
        $absensis = $mahasiswa->absensis()
            ->where('semester', $semesterAktif)
            ->with('mataKuliah')
            ->get();
 
        // IP semester aktif
        $ipSemester = $mahasiswa->getIpSemester($semesterAktif);
 
        // IPK kumulatif
        $ipk = $mahasiswa->ipk;
 
        // Peringatan: nilai D/E
        $nilaiDE = $nilais->whereIn('grade', ['D', 'E']);
 
        // Peringatan: absensi >= 18 jam alpha
        $absensiKritis = $absensis->where('jam_alpha', '>=', 18);
 
        // Rata-rata nilai per matkul di kelas (untuk perbandingan)
        $mataKuliahs = MataKuliah::where('kelas_id', $mahasiswa->kelas_id)
            ->where('semester', $semesterAktif)
            ->get();
 
        $rataRataKelas = [];
        foreach ($mataKuliahs as $mk) {
            $rataRataKelas[$mk->id] = $mk->getRataRataNilai();
        }
 
        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 'nilais', 'absensis',
            'ipSemester', 'ipk', 'nilaiDE', 'absensiKritis',
            'semesterAktif', 'tahunAkademik', 'rataRataKelas'
        ));
    }
}