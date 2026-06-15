<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kompensasi;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class KompensasiController extends Controller
{
    // ── Index: daftar semua kompensasi ───────────────
    public function index(Request $request)
    {
        $query = Kompensasi::with(['mahasiswa.kelas', 'mahasiswa.dosenPa'])
            ->join('mahasiswas', 'kompensasis.mahasiswa_id', '=', 'mahasiswas.id')
            ->orderBy('mahasiswas.nama')
            ->select('kompensasis.*');
 
        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
 
        // Filter semester
        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }
 
        // Filter search nama/NIM
        if ($request->filled('search')) {
            $query->whereHas('mahasiswa', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('nim', 'like', '%' . $request->search . '%');
            });
        }
 
        $kompensasis = $query->paginate(15)->withQueryString();
 
        $semesterList = Kompensasi::distinct('semester')
            ->pluck('semester')
            ->sort()
            ->values();
 
        // Statistik ringkas
        $totalPending = Kompensasi::where('status', 'pending')->count();
        $totalLunas   = Kompensasi::where('status', 'lunas')->count();
        $totalSemua   = Kompensasi::count();
 
        return view('admin.kompensasi.index', compact(
            'kompensasis', 'semesterList',
            'totalPending', 'totalLunas', 'totalSemua'
        ));
    }
 
    // ── Create: form buat kompen baru ────────────────
    public function create(Request $request)
    {
        // Bisa pre-fill dari mahasiswa tertentu
        $mahasiswaId = $request->get('mahasiswa_id');
        $mahasiswa   = $mahasiswaId
            ? Mahasiswa::with(['kelas', 'absensis'])->findOrFail($mahasiswaId)
            : null;
 
        // Gunakan semester terbaru dari pivot agar akurat untuk mahasiswa yang pindah kelas
        $semesterAktif = $mahasiswaId
            ? (\App\Models\KelasMahasiswa::where('mahasiswa_id', $mahasiswaId)->max('semester') ?? ($mahasiswa?->kelas?->semester ?? 1))
            : 1;
        $tahunAkademik = $mahasiswa?->kelas->tahun_akademik ?? '2024/2025';
        $jamAlpha      = $mahasiswa
            ? $mahasiswa->absensis->where('semester', $semesterAktif)->sum('jam_alpha')
            : 0;
 
        $mahasiswas = Mahasiswa::with('kelas')
            ->orderBy('nama')
            ->get();
 
        return view('admin.kompensasi.create', compact(
            'mahasiswas', 'mahasiswa',
            'semesterAktif', 'tahunAkademik', 'jamAlpha'
        ));
    }
 
    // ── Store: simpan kompen baru ─────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'mahasiswa_id'  => 'required|exists:mahasiswas,id',
            'semester'      => 'required|integer|min:1|max:8',
            'tahun_akademik'=> 'required|string',
            'jam_alpha'     => 'required|integer|min:1',
            'multiplier'    => 'required|integer|min:1',
            'catatan_tugas' => 'nullable|string|max:1000',
        ]);
 
        $jamKompen = Kompensasi::hitungJamKompen(
            $request->jam_alpha,
            $request->multiplier
        );
 
        Kompensasi::create([
            'mahasiswa_id'    => $request->mahasiswa_id,
            'semester'        => $request->semester,
            'tahun_akademik'  => $request->tahun_akademik,
            'jam_alpha'       => $request->jam_alpha,
            'multiplier'      => $request->multiplier,
            'jam_kompen_wajib'=> $jamKompen,
            'catatan_tugas'   => $request->catatan_tugas,
            'status'          => 'pending',
            'ttd_admin'       => false,
            'ttd_kajur'       => false,
            'created_by'      => auth()->id(),
        ]);
 
        return redirect()->route('admin.kompensasi.index')
            ->with('success', 'Kompensasi berhasil dibuat.');
    }
 
    // ── Show: detail kompensasi ───────────────────────
    public function show(Kompensasi $kompensasi)
    {
        $kompensasi->load(['mahasiswa.kelas', 'mahasiswa.dosenPa', 'createdBy']);
        return view('admin.kompensasi.show', compact('kompensasi'));
    }
 
    // ── Update TTD Admin ──────────────────────────────
    public function ttdAdmin(Kompensasi $kompensasi)
    {
        $kompensasi->update(['ttd_admin' => true]);
 
        return back()->with('success', 'Tanda tangan admin berhasil dicatat.');
    }
 
    // ── Update TTD Kajur → status jadi Lunas ─────────
    public function ttdKajur(Kompensasi $kompensasi)
    {
        if (!$kompensasi->ttd_admin) {
            return back()->withErrors(['error' => 'Admin harus tanda tangan terlebih dahulu.']);
        }
 
        $kompensasi->update([
            'ttd_kajur'     => true,
            'status'        => 'lunas',
            'tanggal_lunas' => now(),
        ]);
 
        return back()->with('success', 'Kompensasi telah lunas. Status mahasiswa diperbarui.');
    }
 
    // ── Destroy ───────────────────────────────────────
    public function destroy(Kompensasi $kompensasi)
    {
        if ($kompensasi->status === 'lunas') {
            return back()->withErrors(['error' => 'Kompensasi yang sudah lunas tidak bisa dihapus.']);
        }
 
        $kompensasi->delete();
        return redirect()->route('admin.kompensasi.index')
            ->with('success', 'Kompensasi berhasil dihapus.');
    }
}