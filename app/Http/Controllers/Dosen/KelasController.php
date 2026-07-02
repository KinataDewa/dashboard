<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\CatatanDpa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::where('user_id', auth()->id())->firstOrFail();

        $kelasIds     = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');
        $semesterList  = Kelas::whereIn('id', $kelasIds)->distinct()->orderBy('semester')->pluck('semester');
        $semesterAktif = (int) $request->get('semester', $semesterList->max() ?? 1);

        $kelasAktifIds = Kelas::whereIn('id', $kelasIds)->where('semester', $semesterAktif)->pluck('id');
        $mahasiswas = Mahasiswa::whereHas('kelasMahasiswas', function ($q) use ($kelasAktifIds) {
            $q->whereIn('kelas_id', $kelasAktifIds);
        })
            ->with(['kelas', 'nilais.mataKuliah', 'absensis', 'kompensasis'])
            ->orderBy('nama')
            ->get()
            ->map(function ($m) use ($semesterAktif) {
                $m->ipk_val     = $m->ipk;
                $m->is_berisiko = $m->getKategoriRisiko($semesterAktif) !== [];
                return $m;
            });

        return view('dosen.kelas.index', compact(
            'mahasiswas', 'dosen', 'semesterList', 'semesterAktif'
        ));
    }

    public function detail(Request $request, int $id)
    {
        $dosen    = Dosen::where('user_id', auth()->id())->firstOrFail();
        $kelasIds = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');

        $mahasiswa = Mahasiswa::where('id', $id)
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with(['kelas', 'nilais.mataKuliah', 'absensis', 'kompensasis'])
            ->firstOrFail();

        // Semester dari query param (dikirim dari halaman asal).
        // Fallback: semester tertinggi dari kelas milik dosen ini yang diikuti mahasiswa.
        $semesterAktif = (int) $request->get('semester',
            Kelas::whereIn('id', $kelasIds)
                ->whereHas('kelasMahasiswas', fn($q) => $q->where('mahasiswa_id', $mahasiswa->id))
                ->max('semester') ?? 1
        );

        // Ambil tahun_akademik dari kelas dosen agar nilai tidak campur lintas tahun
        $tahunAkad = Kelas::whereIn('id', $kelasIds)
            ->whereHas('kelasMahasiswas', fn($q) => $q->where('mahasiswa_id', $mahasiswa->id))
            ->where('semester', $semesterAktif)
            ->value('tahun_akademik');

        $nilais   = $mahasiswa->nilais->where('semester', $semesterAktif);
        $absensis = $mahasiswa->absensis->where('semester', $semesterAktif);
        if ($tahunAkad) {
            $nilais   = $nilais->where('tahun_akademik', $tahunAkad);
            $absensis = $absensis->where('tahun_akademik', $tahunAkad);
        }

        $ip  = $mahasiswa->getIpSemester($semesterAktif, $tahunAkad);
        $ipk = $mahasiswa->ipk;

        // Daftar semester yang tersedia berdasarkan nilai mahasiswa
        $semesterList = $mahasiswa->nilais->pluck('semester')->unique()->sort()->values();

        // Catatan DPA untuk mahasiswa ini
        $catatanList = CatatanDpa::where('mahasiswa_id', $mahasiswa->id)
            ->where('dosen_id', $dosen->id)
            ->with('dosen')
            ->latest()
            ->get();

        return view('dosen.kelas.detail', compact(
            'mahasiswa', 'nilais', 'absensis', 'ip', 'ipk', 'semesterAktif', 'semesterList', 'catatanList'
        ));
    }
}
