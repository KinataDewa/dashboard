<?php 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
 
class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['kelas', 'dosenPa', 'user']);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nim', 'like', '%' . $request->search . '%')
                ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->kelas) {
            $query->where('kelas_id', $request->kelas);
        }

        $mahasiswas = $query->orderBy('nim')->paginate(15);
        $kelasList  = Kelas::orderBy('nama')->get();

        return view('admin.mahasiswa.index', compact('mahasiswas', 'kelasList'));
    }
 
    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $dosenList = Dosen::orderBy('nama')->get();
        return view('admin.mahasiswa.create', compact('kelasList', 'dosenList'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nim'         => 'required|string|unique:mahasiswas,nim',
            'nama'        => 'required|string|max:100',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6',
            'kelas_id'    => 'required|exists:kelas,id',
            'dosen_pa_id' => 'required|exists:dosens,id',
            'angkatan'    => 'required|integer|min:2000|max:' . date('Y'),
            'status'      => 'required|in:aktif,cuti,lulus,keluar',
        ]);
 
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole('mahasiswa');
 
            Mahasiswa::create([
                'user_id'     => $user->id,
                'nim'         => $request->nim,
                'nama'        => $request->nama,
                'kelas_id'    => $request->kelas_id,
                'dosen_pa_id' => $request->dosen_pa_id,
                'angkatan'    => $request->angkatan,
                'status'      => $request->status,
            ]);
        });
 
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa ' . $request->nama . ' berhasil ditambahkan.');
    }
 
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['kelas', 'dosenPa', 'user', 'nilais.mataKuliah', 'absensis.mataKuliah']);

        $semesterAktif = request('semester')
            ? (int) request('semester')
            : ($mahasiswa->kelas->semester ?? 6);

        $nilais   = $mahasiswa->nilais->where('semester', $semesterAktif)->values();
        $absensis = $mahasiswa->absensis->where('semester', $semesterAktif)->values();

        $ip  = $mahasiswa->getIpSemester($semesterAktif);
        $ipk = $mahasiswa->ipk;

        $sumHadir = $absensis->sum('jam_hadir');
        $sumAlpha = $absensis->sum('jam_alpha');
        $sumTotal = $absensis->sum(fn($a) => $a->jam_hadir + $a->jam_izin + $a->jam_sakit + $a->jam_alpha);
        $pctHadir = $sumTotal > 0 ? round($sumHadir / $sumTotal * 100) : 0;

        $semesterList = $mahasiswa->nilais->pluck('semester')->unique()->sort()->values();

        $allSemesters = $semesterList;
        $trendIp      = $allSemesters->map(fn($s) => $mahasiswa->getIpSemester($s));
        $trendAlpha   = $allSemesters->map(
            fn($s) => $mahasiswa->absensis->where('semester', $s)->sum('jam_alpha')
        );

        return view('admin.mahasiswa.show', compact(
            'mahasiswa', 'nilais', 'absensis', 'ip', 'ipk',
            'semesterAktif', 'sumHadir', 'sumAlpha', 'pctHadir',
            'allSemesters', 'trendIp', 'trendAlpha', 'semesterList'
        ));
    }
 
    public function edit(Mahasiswa $mahasiswa)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $dosenList = Dosen::orderBy('nama')->get();
        return view('admin.mahasiswa.edit', compact('mahasiswa', 'kelasList', 'dosenList'));
    }
 
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $request->validate([
            'nim'         => 'required|string|unique:mahasiswas,nim,' . $mahasiswa->id,
            'nama'        => 'required|string|max:100',
            'kelas_id'    => 'required|exists:kelas,id',
            'dosen_pa_id' => 'required|exists:dosens,id',
            'angkatan'    => 'required|integer|min:2000|max:' . date('Y'),
            'status'      => 'required|in:aktif,cuti,lulus,keluar',
        ]);
 
        DB::transaction(function () use ($request, $mahasiswa) {
            $mahasiswa->update([
                'nim'         => $request->nim,
                'nama'        => $request->nama,
                'kelas_id'    => $request->kelas_id,
                'dosen_pa_id' => $request->dosen_pa_id,
                'angkatan'    => $request->angkatan,
                'status'      => $request->status,
            ]);
 
            // Update nama di tabel users juga
            $mahasiswa->user->update(['name' => $request->nama]);
        });
 
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa ' . $mahasiswa->nama . ' berhasil diperbarui.');
    }
 
    public function destroy(Mahasiswa $mahasiswa)
    {
        $nama = $mahasiswa->nama;
 
        DB::transaction(function () use ($mahasiswa) {
            $userId = $mahasiswa->user_id;
            $mahasiswa->delete();
            User::find($userId)?->delete();
        });
 
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa ' . $nama . ' berhasil dihapus.');
    }
}