<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;

class DashboardController extends Controller
{
    public function index()
    {
        $user  = auth()->user();
        $dosen = Dosen::where('user_id', $user->id)->firstOrFail();

        $kelas    = Kelas::where('dosen_pa_id', $dosen->id)->get();
        $kelasIds = $kelas->pluck('id');
        $mahasiswas = Mahasiswa::whereIn('kelas_id', $kelasIds)
            ->with([
                'kelas',
                'nilais.mataKuliah',
                'absensis.mataKuliah',
                'user',
                'kompensasis',
            ])
            ->get();

        // Tandai berisiko
        $mahasiswaBerisiko = $mahasiswas->filter(fn($m) => $m->isBerisiko());

        // Statistik
        $totalMahasiswa = $mahasiswas->count();
        $totalBerisiko  = $mahasiswaBerisiko->count();
        $rataRataIpk    = $mahasiswas->count() > 0
            ? round($mahasiswas->avg(fn($m) => $m->ipk), 2)
            : 0;

        // Hitung D/E dan alpha dari semester terakhir masing-masing mahasiswa
        $totalNilaiDE = $mahasiswas->sum(function ($m) {
            $sem = $m->nilais->max('semester') ?? 0;
            return $sem > 0
                ? $m->nilais->where('semester', $sem)->whereIn('grade', ['D', 'E'])->count()
                : 0;
        });


        $gradeDistribusi = ['A' => 0, 'B+' => 0, 'B' => 0, 'C+' => 0, 'C' => 0, 'D' => 0, 'E' => 0];
        foreach ($mahasiswas as $mhs) {
            $maxSem = (int) $mhs->nilais->max('semester');
            if ($maxSem === 0) continue;

            foreach ($mhs->nilais as $nilai) {
                if ((int) $nilai->semester !== $maxSem) continue;
                if (array_key_exists($nilai->grade, $gradeDistribusi)) {
                    $gradeDistribusi[$nilai->grade]++;
                }
            }
        }

        $totalH = $totalI = $totalS = $totalA = 0;
        foreach ($mahasiswas as $mhs) {
            $maxSem = (int) $mhs->absensis->max('semester');
            if ($maxSem === 0) continue;

            foreach ($mhs->absensis as $abs) {
                if ((int) $abs->semester !== $maxSem) continue;
                $totalH += (int) $abs->jam_hadir;
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
            'totalH', 'totalI', 'totalS', 'totalA',
            'gradeDistribusi', 'kompensasiPending'
        ));
    }
}