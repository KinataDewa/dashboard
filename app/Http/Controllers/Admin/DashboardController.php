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
        ])->get();

        $terkirim = 0;
        $gagal    = 0;

        foreach ($mahasiswas as $mhs) {
            // Gunakan semester terakhir agar penilaian risiko konsisten
            $semNilai   = $mhs->nilais->max('semester') ?? 0;
            $nilaiDE    = $semNilai > 0
                ? $mhs->nilais->where('semester', $semNilai)->whereIn('grade', ['D', 'E'])
                : collect();

            $semAlpha   = $mhs->absensis->max('semester') ?? 0;
            $totalAlpha = $semAlpha > 0
                ? $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha')
                : 0;

            $isBerisiko = $nilaiDE->isNotEmpty() || $totalAlpha >= 18;

            if (!$isBerisiko || !$mhs->user) continue;

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