<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\KelasMahasiswa;
use App\Mail\MahasiswaBerisiko;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $semesterAktif  = KelasMahasiswa::max('semester') ?? 0;
        $totalMahasiswa = $semesterAktif > 0
            ? KelasMahasiswa::where('semester', $semesterAktif)->distinct('mahasiswa_id')->count('mahasiswa_id')
            : Mahasiswa::where('status', 'aktif')->count();
        $totalDosen     = Dosen::count();
        $totalMatkul    = MataKuliah::count();
        $totalKelas     = Kelas::count();
 
        // Mahasiswa aktif berisiko di semester aktif
        $mahasiswaBerisiko = Mahasiswa::whereHas('kelasMahasiswas', fn($q) => $q->where('semester', $semesterAktif))
            ->with(['nilais', 'absensis', 'kompensasis'])
            ->where('status', 'aktif')
            ->get()
            ->filter(fn($m) => $m->getKategoriRisiko($semesterAktif) !== [])
            ->count();
 
        return view('admin.dashboard', compact(
            'totalMahasiswa', 'totalDosen', 'totalMatkul',
            'totalKelas', 'mahasiswaBerisiko'
        ));
    }

    public function kirimPeringatan()
    {
        $mahasiswas = \App\Models\Mahasiswa::with([
            'nilais.mataKuliah',
            'absensis',
            'kelas', 'dosenPa', 'user', 'kompensasis',
        ])->where('status', 'aktif')->get();

        $terkirim = 0;
        $gagal    = 0;

        foreach ($mahasiswas as $mhs) {
            // Gunakan isBerisiko() agar konsisten dengan Pedoman Akademik D4 TI Polinema
            if (!$mhs->isBerisiko() || !$mhs->user) continue;

            $email = $mhs->user->email ?? null;
            if (!$email) continue;

            try {
                Mail::to($email)->queue(new MahasiswaBerisiko($mhs));
                $terkirim++;
            } catch (\Exception $e) {
                $gagal++;
                \Log::error("Email gagal ke {$mhs->nama}: " . $e->getMessage());
            }
        }

        $pesan = "✅ Email peringatan berhasil dikirim ke {$terkirim} mahasiswa";
        if ($gagal > 0) $pesan .= " ({$gagal} gagal)";

        return back()->with('success', $pesan);
    }
}