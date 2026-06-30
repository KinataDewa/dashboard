<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KompensasiController extends Controller
{
    public function index(Request $request)
    {
        $dosen = Dosen::where('user_id', Auth::id())->firstOrFail();

        $kelasIds = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');

        $semesterList  = Kelas::whereIn('id', $kelasIds)->distinct()->orderBy('semester')->pluck('semester');
        $semesterAktif = (int) $request->get('semester', $semesterList->max() ?? 1);

        $kelasAktifIds = Kelas::whereIn('id', $kelasIds)->where('semester', $semesterAktif)->pluck('id');

        $mahasiswas = Mahasiswa::whereHas('kelasMahasiswas', function ($q) use ($kelasAktifIds) {
            $q->whereIn('kelas_id', $kelasAktifIds);
        })
            ->with(['absensis', 'kompensasis', 'kelas'])
            ->orderBy('nama')
            ->get();

        $dataMahasiswa = collect();

        foreach ($mahasiswas as $mhs) {
            $jamAlpha = (int) $mhs->absensis->where('semester', $semesterAktif)->sum('jam_alpha');

            if ($jamAlpha < 18) continue;

            $jamKompenWajib   = $jamAlpha * 2;
            $kompenSem        = $mhs->kompensasis->where('semester', $semesterAktif);
            $jamKompenSelesai = (int) $kompenSem->where('status', 'lunas')->sum('jam_kompen_wajib');
            $jamSisa          = max(0, $jamKompenWajib - $jamKompenSelesai);
            $status           = $jamSisa === 0 ? 'lunas' : 'pending';
            $kompenRecord     = $kompenSem->first();

            $dataMahasiswa->push((object) [
                'mahasiswa'          => $mhs,
                'semester'           => $semesterAktif,
                'jam_alpha'          => $jamAlpha,
                'jam_kompen_wajib'   => $jamKompenWajib,
                'jam_kompen_selesai' => $jamKompenSelesai,
                'jam_sisa'           => $jamSisa,
                'status'             => $status,
                'ttd_admin'          => $kompenRecord?->ttd_admin ?? false,
                'ttd_kajur'          => $kompenRecord?->ttd_kajur ?? false,
            ]);
        }

        $dataMahasiswa = $dataMahasiswa->sortByDesc('jam_sisa')->values();

        return view('dosen.kompensasi', compact(
            'dataMahasiswa', 'dosen', 'semesterAktif', 'semesterList'
        ));
    }
}
