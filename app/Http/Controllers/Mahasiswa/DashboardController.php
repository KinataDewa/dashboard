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
            ->with(['kelas', 'dosenPa', 'kompensasis', 'nilais.mataKuliah', 'absensis'])
            ->firstOrFail();

        $semesterAktif = $mahasiswa->kelasMahasiswas()->max('semester') ?? ($mahasiswa->kelas->semester ?? 1);
        $tahunAkademik = $mahasiswa->kelas->tahun_akademik ?? '2024/2025';

        // Nilai semester aktif (dari relation yang sudah di-load)
        $nilais = $mahasiswa->nilais->where('semester', $semesterAktif)->values();

        // Absensi semua semester (dari relation yang sudah di-load)
        $allAbsensis = $mahasiswa->absensis;

        // Absensi semester aktif
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

        // IP semester aktif (sudah support grade B+ dan C+ via hitungIpDariKoleksi)
        $ipSemester = $mahasiswa->getIpSemester($semesterAktif);

        // IPK kumulatif
        $ipk = $mahasiswa->ipk;

        // Total alpha semester aktif
        $totalAlpha = (int) $absensis->sum('jam_alpha');

        // Kategori risiko sesuai Pedoman Akademik D4 TI Polinema 2022/2023
        $kategoriRisiko = $mahasiswa->getKategoriRisiko();

        // Nilai D/E semester aktif (untuk stat card)
        $nilaiDE = $nilais->whereIn('grade', ['D', 'E']);

        // Absensi kritis: semester aktif dengan alpha >= 18 jam (batas SP I)
        $absensiKritis = $absensis->where('jam_alpha', '>=', 18);

        // Rata-rata nilai per matkul (dari nilai mahasiswa semester aktif)
        $matkulIds = $nilais->pluck('mata_kuliah_id')->unique();
        $rataRataKelas = [];
        foreach ($matkulIds as $mkId) {
            $rataRataKelas[$mkId] = round(
                \App\Models\Nilai::where('mata_kuliah_id', $mkId)->avg('nilai_akhir') ?? 0,
                2
            );
        }

        $kompenAktif = $mahasiswa->getKompensasiSemester($semesterAktif);

        $kompensasis = $mahasiswa->kompensasis()->orderBy('semester', 'desc')->get();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 'nilais', 'absensis', 'allAbsensis',
            'ipSemester', 'ipk', 'nilaiDE', 'absensiKritis',
            'semesterAktif', 'tahunAkademik', 'rataRataKelas',
            'kompenAktif', 'absensiPerSemester', 'semesterListAbsensi',
            'totalAlpha', 'kategoriRisiko', 'kompensasis'
        ));
    }
}
