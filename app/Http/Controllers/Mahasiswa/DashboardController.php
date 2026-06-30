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

        $semesterAktif = $mahasiswa->kelas->semester ?? $mahasiswa->nilais->max('semester') ?? 1;
        $tahunAkademik = $mahasiswa->kelas->tahun_akademik ?? config('akademik.tahun_akademik');

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
        $kategoriRisiko = $mahasiswa->getKategoriRisiko($semesterAktif);

        // Nilai D/E semester aktif (untuk stat card)
        $nilaiDE = $nilais->whereIn('grade', ['D', 'E']);

        // Absensi kritis: semester aktif dengan alpha >= 18 jam (batas SP I)
        $absensiKritis = $absensis->where('jam_alpha', '>=', 18);

        // Rata-rata nilai per matkul untuk semua mahasiswa (satu query GROUP BY)
        $matkulIds     = $nilais->pluck('mata_kuliah_id')->unique()->values();
        $rataRataKelas = $matkulIds->isNotEmpty()
            ? \App\Models\Nilai::whereIn('mata_kuliah_id', $matkulIds)
                ->selectRaw('mata_kuliah_id, ROUND(AVG(nilai_akhir), 2) as rata')
                ->groupBy('mata_kuliah_id')
                ->pluck('rata', 'mata_kuliah_id')
                ->all()
            : [];

        $kompenAktif = $mahasiswa->getKompensasiSemester($semesterAktif);

        $kompensasis = $mahasiswa->kompensasis()->orderBy('semester', 'desc')->get();

        $semesterListNilai = $mahasiswa->nilais
            ->pluck('semester')->unique()->sort()->values();

        return view('mahasiswa.dashboard', compact(
            'mahasiswa', 'nilais', 'absensis', 'allAbsensis',
            'ipSemester', 'ipk', 'nilaiDE', 'absensiKritis',
            'semesterAktif', 'tahunAkademik', 'rataRataKelas',
            'kompenAktif', 'absensiPerSemester', 'semesterListAbsensi',
            'totalAlpha', 'kategoriRisiko', 'kompensasis',
            'semesterListNilai'
        ));
    }

    public function apiNilai(Request $request)
    {
        $user      = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)
            ->with(['nilais.mataKuliah'])
            ->firstOrFail();

        $semester = (int) $request->query('semester', 0);

        $nilais = $mahasiswa->nilais
            ->when($semester > 0, fn ($c) => $c->where('semester', $semester))
            ->values();

        return response()->json(
            $nilais->map(fn ($n) => [
                'nama_mk'    => $n->mataKuliah->nama  ?? '—',
                'kode_mk'    => $n->mataKuliah->kode  ?? '',
                'sks'        => $n->mataKuliah->sks   ?? 0,
                'nilai_akhir'=> round((float) $n->nilai_akhir, 1),
                'grade'      => $n->grade,
            ])->values()
        );
    }
}
