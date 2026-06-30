<?php 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Kelas;
use App\Models\Dosen;
use App\Models\User;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
 
class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $search   = trim($request->input('search', ''));
        $angkatan = $request->input('angkatan', '');
        $semester = $request->input('semester', '');
        $status   = $request->input('status', '');

        $angkatanList = Mahasiswa::distinct()->orderByDesc('angkatan')->pluck('angkatan');
        $semesterList = Kelas::distinct()->orderBy('semester')->pluck('semester');

        $query = Mahasiswa::with(['kelas', 'dosenPa', 'user']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        if ($angkatan) {
            $query->where('angkatan', $angkatan);
        }

        if ($semester) {
            $kelasIds = Kelas::where('semester', $semester)
                ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
                ->pluck('id');
            $query->whereHas('kelasMahasiswas', fn($q) => $q->whereIn('kelas_id', $kelasIds));
        }

        if ($status) {
            $query->where('status', $status);
        }

        $mahasiswas = $query->orderBy('nama')->paginate(15)->appends([
            'search'   => $search,
            'angkatan' => $angkatan,
            'semester' => $semester,
            'status'   => $status,
        ]);

        return view('admin.mahasiswa.index', compact(
            'mahasiswas', 'angkatanList', 'semesterList', 'angkatan', 'semester', 'search', 'status'
        ));
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

            $mhs = Mahasiswa::create([
                'user_id'     => $user->id,
                'nim'         => $request->nim,
                'nama'        => $request->nama,
                'kelas_id'    => $request->kelas_id,
                'dosen_pa_id' => $request->dosen_pa_id,
                'angkatan'    => $request->angkatan,
                'status'      => $request->status,
            ]);

            // Buat entri pivot kelasMahasiswas agar mahasiswa muncul di filter semester
            $kelas = Kelas::find($request->kelas_id);
            if ($kelas) {
                KelasMahasiswa::create([
                    'mahasiswa_id'   => $mhs->id,
                    'kelas_id'       => $kelas->id,
                    'semester'       => $kelas->semester,
                    'tahun_akademik' => $kelas->tahun_akademik,
                ]);
            }
        });
 
        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa ' . $request->nama . ' berhasil ditambahkan.');
    }
 
    public function show(Mahasiswa $mahasiswa)
    {
        $mahasiswa->load(['kelas', 'dosenPa', 'user', 'nilais.mataKuliah', 'absensis', 'kompensasis']);

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
            // Soft-delete mahasiswa — data nilai & absensi tetap tersimpan
            $mahasiswa->delete();
            // Cabut role agar user tidak bisa login ke area mahasiswa
            $mahasiswa->user?->removeRole('mahasiswa');
        });

        return redirect()->route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa ' . $nama . ' berhasil dihapus.');
    }
}