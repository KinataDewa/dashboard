<?php
namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\CatatanDpa;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class CatatanDpaController extends Controller
{
    public function store(Request $request, int $mahasiswaId)
    {
        $request->validate(['catatan' => 'required|string|max:2000']);

        $dosen = Dosen::where('user_id', auth()->id())->firstOrFail();

        // Pastikan mahasiswa ini memang bimbingan dosen login
        $kelasIds = Kelas::where('dosen_pa_id', $dosen->id)->pluck('id');
        $mahasiswa = Mahasiswa::where('id', $mahasiswaId)
            ->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds))
            ->firstOrFail();

        $semester = (int) $request->get('semester', 1);

        CatatanDpa::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id'     => $dosen->id,
            'semester'     => $semester,
            'catatan'      => $request->catatan,
        ]);

        return back()->with('success', 'Catatan berhasil disimpan.');
    }

    public function destroy(int $mahasiswaId, int $catatanId)
    {
        $dosen = Dosen::where('user_id', auth()->id())->firstOrFail();

        CatatanDpa::where('id', $catatanId)
            ->where('dosen_id', $dosen->id)
            ->where('mahasiswa_id', $mahasiswaId)
            ->firstOrFail()
            ->delete();

        return back()->with('success', 'Catatan dihapus.');
    }
}
