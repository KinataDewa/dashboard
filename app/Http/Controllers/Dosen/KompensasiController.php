<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;

class KompensasiController extends Controller
{
    public function index()
    {
        $dosen = Dosen::where('user_id', Auth::id())->firstOrFail();

        $kelasIds = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');

        $mahasiswas = Mahasiswa::whereIn('kelas_id', $kelasIds)
            ->with(['absensis', 'kompensasis', 'kelas', 'user'])
            ->get();

        $dataMahasiswa = collect();

        foreach ($mahasiswas as $mhs) {
            $absensiBySemester     = $mhs->absensis->groupBy('semester');
            $kompensasisBySemester = $mhs->kompensasis->groupBy('semester');

            $semesterData = collect();

            foreach ($absensiBySemester as $semester => $absensis) {
                $jamAlpha = (int) $absensis->sum('jam_alpha');

                if ($jamAlpha < 18) continue;

                $jamKompenWajib   = $jamAlpha * 2;
                $kompenSem        = $kompensasisBySemester->get($semester, collect());
                $jamKompenSelesai = (int) $kompenSem->where('status', 'lunas')->sum('jam_kompen_wajib');
                $jamSisa          = max(0, $jamKompenWajib - $jamKompenSelesai);

                $status = $jamSisa === 0 ? 'lunas' : 'pending';

                $semesterData->push((object) [
                    'semester'            => (int) $semester,
                    'jam_alpha'           => $jamAlpha,
                    'jam_kompen_wajib'    => $jamKompenWajib,
                    'jam_kompen_selesai'  => $jamKompenSelesai,
                    'jam_sisa'            => $jamSisa,
                    'status'              => $status,
                ]);
            }

            if ($semesterData->isEmpty()) continue;

            $semesterData = $semesterData->sortBy('semester')->values();
            $totalSisa    = $semesterData->sum('jam_sisa');

            $dataMahasiswa->push((object) [
                'mahasiswa'   => $mhs,
                'semesters'   => $semesterData,
                'total_sisa'  => $totalSisa,
                'semua_lunas' => $totalSisa === 0,
            ]);
        }

        $dataMahasiswa = $dataMahasiswa->sortByDesc('total_sisa')->values();

        return view('dosen.kompensasi', compact('dataMahasiswa', 'dosen'));
    }
}
