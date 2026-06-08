<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Mail\MahasiswaBerisiko;
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = Mahasiswa::where('status', 'aktif')->count();
        $totalDosen     = Dosen::count();
        $totalMatkul    = MataKuliah::count();
        $totalKelas     = Kelas::count();
 
        // Mahasiswa aktif berisiko (nilai D/E atau alpha >= 18 di semester terakhir)
        $mahasiswaBerisiko = Mahasiswa::with(['nilais', 'absensis'])
            ->where('status', 'aktif')
            ->get()
            ->filter(fn($m) => $m->isBerisiko())
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
            'absensis.mataKuliah',
            'kelas', 'dosenPa', 'user',
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