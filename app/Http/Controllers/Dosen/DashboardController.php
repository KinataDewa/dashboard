<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user  = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $kelasIds     = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');
        $semesterList = Kelas::whereIn('id', $kelasIds)->distinct()->orderBy('semester')->pluck('semester');
        $semesterAktif = (int) $request->get('semester', $semesterList->max() ?? 1);

        $kelas = Kelas::where('dosen_pa_id', $dosen->id)->where('semester', $semesterAktif)->get();

        $kelasAktifIds = Kelas::whereIn('id', $kelasIds)->where('semester', $semesterAktif)->pluck('id');

        $mahasiswas = Mahasiswa::whereHas('kelasMahasiswas', function ($q) use ($kelasAktifIds) {
            $q->whereIn('kelas_id', $kelasAktifIds);
        })
            ->with([
                'kelas',
                'nilais.mataKuliah',
                'absensis',
                'user',
                'kompensasis',
            ])
            ->orderBy('nama')
            ->get();

        $mahasiswaBerisiko = $mahasiswas->filter(fn($m) => $m->getKategoriRisiko($semesterAktif) !== []);

        $totalMahasiswa = $mahasiswas->count();
        $totalBerisiko  = $mahasiswaBerisiko->count();
        $rataRataIpk    = $mahasiswas->count() > 0
            ? round($mahasiswas->avg(fn($m) => $m->ipk), 2)
            : 0;

        $totalNilaiDE = $mahasiswas->sum(
            fn ($m) => $m->nilais->where('semester', $semesterAktif)->whereIn('grade', ['D', 'E'])->count()
        );

        $gradeDistribusi = ['A' => 0, 'B+' => 0, 'B' => 0, 'C+' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
        foreach ($mahasiswas as $mhs) {
            foreach ($mhs->nilais->where('semester', $semesterAktif) as $nilai) {
                if (array_key_exists($nilai->grade, $gradeDistribusi)) {
                    $gradeDistribusi[$nilai->grade]++;
                }
            }
        }

        $totalI = $totalS = $totalA = 0;
        foreach ($mahasiswas as $mhs) {
            foreach ($mhs->absensis->where('semester', $semesterAktif) as $abs) {
                $totalI += (int) $abs->jam_izin;
                $totalS += (int) $abs->jam_sakit;
                $totalA += (int) $abs->jam_alpha;
            }
        }

        $kompensasiPending = $mahasiswas->filter(
            fn($m) => $m->kompensasis->where('status', 'pending')->isNotEmpty()
        );

        return view('dosen.dashboard', compact(
            'dosen', 'kelas', 'mahasiswas', 'mahasiswaBerisiko',
            'totalMahasiswa', 'totalBerisiko', 'rataRataIpk', 'totalNilaiDE',
            'totalI', 'totalS', 'totalA',
            'gradeDistribusi', 'kompensasiPending',
            'semesterList', 'semesterAktif'
        ));
    }
}
