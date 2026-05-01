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
 
        // Mahasiswa berisiko (nilai D/E atau alpha >= 18)
        $mahasiswaBerisiko = Mahasiswa::with(['nilais', 'absensis'])
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
        ])->get();

        $terkirim = 0;
        $gagal    = 0;

        foreach ($mahasiswas as $mhs) {
            $nilaiDE    = $mhs->nilais->whereIn('grade', ['D', 'E']);
            $totalAlpha = $mhs->absensis->sum('jam_alpha');
            $isBerisiko = $nilaiDE->count() > 0 || $totalAlpha >= 14;

            if (!$isBerisiko || !$mhs->user) continue;

            $email = $mhs->user->email ?? null;
            if (!$email) continue;

            try {
                Mail::to($email)->send(new MahasiswaBerisiko($mhs));
                $terkirim++;
                sleep(1);
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