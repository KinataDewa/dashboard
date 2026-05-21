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

        $kelas = Kelas::where('dosen_pa_id', $dosen->id)->get();

        // Semua mahasiswa bimbingan dosen ini
        $mahasiswas = Mahasiswa::where('dosen_pa_id', $dosen->id)
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

        // Hitung untuk chart — gunakan semester terakhir absensi
        $totalH = $mahasiswas->sum(function ($m) {
            $sem = $m->absensis->max('semester') ?? 0;
            return $sem > 0 ? $m->absensis->where('semester', $sem)->sum('jam_hadir') : 0;
        });
        $totalI = $mahasiswas->sum(function ($m) {
            $sem = $m->absensis->max('semester') ?? 0;
            return $sem > 0 ? $m->absensis->where('semester', $sem)->sum('jam_izin') : 0;
        });
        $totalS = $mahasiswas->sum(function ($m) {
            $sem = $m->absensis->max('semester') ?? 0;
            return $sem > 0 ? $m->absensis->where('semester', $sem)->sum('jam_sakit') : 0;
        });
        $totalA = $mahasiswas->sum(function ($m) {
            $sem = $m->absensis->max('semester') ?? 0;
            return $sem > 0 ? $m->absensis->where('semester', $sem)->sum('jam_alpha') : 0;
        });

        return view('dosen.dashboard', compact(
            'dosen', 'kelas', 'mahasiswas', 'mahasiswaBerisiko',
            'totalMahasiswa', 'totalBerisiko', 'rataRataIpk', 'totalNilaiDE',
            'totalH', 'totalI', 'totalS', 'totalA'
        ));
    }
}