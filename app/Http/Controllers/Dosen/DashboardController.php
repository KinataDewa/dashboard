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

        // Ambil kelas yang PA-nya adalah dosen ini
        // (bukan relasi dosen->kelas, tapi query ke tabel kelas)
        $kelas = Kelas::where('dosen_pa_id', $dosen->id)
            ->with('mahasiswas')
            ->get();

        // Semua mahasiswa bimbingan dosen ini
        $mahasiswas = Mahasiswa::where('dosen_pa_id', $dosen->id)
            ->with([
                'kelas',
                'nilais.mataKuliah',
                'absensis.mataKuliah',
                'user',
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

        $totalNilaiDE = $mahasiswas->sum(
            fn($m) => $m->nilais->whereIn('grade', ['D','E'])->count()
        );

        // Hitung untuk chart
        $totalH = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_hadir'));
        $totalI = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_izin'));
        $totalS = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_sakit'));
        $totalA = $mahasiswas->sum(fn($m) => $m->absensis->sum('jam_alpha'));

        return view('dosen.dashboard', compact(
            'dosen', 'kelas', 'mahasiswas', 'mahasiswaBerisiko',
            'totalMahasiswa', 'totalBerisiko', 'rataRataIpk', 'totalNilaiDE',
            'totalH', 'totalI', 'totalS', 'totalA'
        ));
    }
}