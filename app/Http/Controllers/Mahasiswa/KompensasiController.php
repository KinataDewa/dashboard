<?php
namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;

class KompensasiController extends Controller
{
    public function index()
    {
        $user      = auth()->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)
            ->with(['absensis', 'kompensasis'])
            ->firstOrFail();

        $absensiBySemester     = $mahasiswa->absensis->groupBy('semester');
        $kompensasisBySemester = $mahasiswa->kompensasis->groupBy('semester');

        $dataSemester = collect();

        foreach ($absensiBySemester as $semester => $absensis) {
            $jamAlpha        = (int) $absensis->sum('jam_alpha');
            $jamKompenWajib  = $jamAlpha * 2;
            $kompenSem       = $kompensasisBySemester->get($semester, collect());
            $jamKompenSelesai = (int) $kompenSem->where('status', 'lunas')->sum('jam_kompen_wajib');
            $jamKompenSisa   = max(0, $jamKompenWajib - $jamKompenSelesai);

            if ($jamAlpha < 18) {
                $status = 'aman';
            } elseif ($jamKompenSisa === 0) {
                $status = 'lunas';
            } else {
                $status = 'pending';
            }

            $dataSemester->push((object) [
                'semester'          => (int) $semester,
                'jam_alpha'         => $jamAlpha,
                'jam_kompen_wajib'  => $jamKompenWajib,
                'jam_kompen_selesai' => $jamKompenSelesai,
                'jam_kompen_sisa'   => $jamKompenSisa,
                'status'            => $status,
            ]);
        }

        $dataSemester = $dataSemester->sortByDesc('semester')->values();

        $totalJamAlpha    = $dataSemester->sum('jam_alpha');
        $totalWajibKompen = $dataSemester->where('status', '!=', 'aman')->sum('jam_kompen_wajib');
        $totalSisaKompen  = $dataSemester->sum('jam_kompen_sisa');

        return view('mahasiswa.kompensasi', compact(
            'mahasiswa', 'dataSemester',
            'totalJamAlpha', 'totalWajibKompen', 'totalSisaKompen'
        ));
    }
}
