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
            ->with(['kelas', 'dosenPa', 'kompensasis'])
            ->firstOrFail();
 
        $semesterAktif  = $mahasiswa->kelas->semester ?? 6;
        $tahunAkademik  = $mahasiswa->kelas->tahun_akademik ?? '2024/2025';
 
        // Nilai semester aktif
        $nilais = $mahasiswa->nilais()
            ->where('semester', $semesterAktif)
            ->with('mataKuliah')
            ->get();
 
        // Load all absensis (all semesters) — needed for donut filter
        $allAbsensis = $mahasiswa->absensis()->with('mataKuliah')->get();

        // Absensi semester aktif (for table and footer)
        $absensis = $allAbsensis->where('semester', $semesterAktif)->values();

        // Pre-aggregate per semester for the donut chart filter
        $semesterListAbsensi = $allAbsensis->pluck('semester')->unique()->sort()->values()->toArray();
        $absensiPerSemester  = [];
        foreach ($semesterListAbsensi as $sem) {
            $semData = $allAbsensis->where('semester', $sem);
            $absensiPerSemester[(int) $sem] = [
                'hadir' => (int) $semData->sum('jam_hadir'),
                'izin'  => (int) $semData->sum('jam_izin'),
                'sakit' => (int) $semData->sum('jam_sakit'),
                'alpha' => (int) $semData->sum('jam_alpha'),
            ];
        }
 
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
 
        $kompenAktif = $mahasiswa->getKompensasiSemester($semesterAktif);

        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 'nilais', 'absensis',
            'ipSemester', 'ipk', 'nilaiDE', 'absensiKritis',
            'semesterAktif', 'tahunAkademik', 'rataRataKelas',
            'kompenAktif', 'absensiPerSemester', 'semesterListAbsensi'
        ));
    }
}