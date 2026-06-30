<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
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

    public function detail(int $id)
    {
        $dosen     = Dosen::where('user_id', auth()->id())->firstOrFail();
        $kelasIds  = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');

        $mahasiswa = Mahasiswa::where('id', $id)
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->with(['kelas', 'nilais.mataKuliah', 'absensis', 'kompensasis'])
            ->firstOrFail();

        $semesterAktif = $mahasiswa->kelas->semester ?? 1;
        $nilais        = $mahasiswa->nilais->where('semester', $semesterAktif);
        $absensis      = $mahasiswa->absensis->where('semester', $semesterAktif);
        $ip            = $mahasiswa->getIpSemester($semesterAktif);
        $ipk           = $mahasiswa->ipk;

        return view('dosen.kelas.detail', compact(
            'mahasiswa', 'nilais', 'absensis', 'ip', 'ipk', 'semesterAktif'
        ));
    }
}
