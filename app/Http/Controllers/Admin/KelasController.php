<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Http\Request;
 
class KelasController extends Controller
{
    public function index(Request $request)
    {
        $tahunList   = Kelas::distinct()->orderByDesc('tahun_akademik')->pluck('tahun_akademik');
        $angkatanList = Kelas::distinct()->orderByDesc('angkatan')->pluck('angkatan');

        $tahun    = $request->input('tahun', $tahunList->first());
        $angkatan = $request->input('angkatan', '');

        $query = Kelas::with(['dosenPa', 'kelasMahasiswas'])
            ->when($tahun,    fn($q) => $q->where('tahun_akademik', $tahun))
            ->when($angkatan, fn($q) => $q->where('angkatan', $angkatan))
            ->orderBy('nama')->orderBy('semester');

        $kelas = $query->paginate(15)->appends([
            'tahun'    => $tahun,
            'angkatan' => $angkatan,
        ]);

        return view('admin.kelas.index', compact('kelas', 'tahunList', 'angkatanList', 'tahun', 'angkatan'));
    }
 
    public function create()
    {
        $dosenList = Dosen::orderBy('nama')->get();
        return view('admin.kelas.create', compact('dosenList'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:20',
            'semester'       => 'required|integer|min:1|max:8',
            'prodi'          => 'required|string|max:50',
            'dosen_pa_id'    => 'required|exists:dosens,id',
            'tahun_akademik' => 'required|string|max:10',
        ]);
 
        Kelas::create($request->only(['nama','semester','prodi','dosen_pa_id','tahun_akademik']));
 
        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas ' . $request->nama . ' berhasil ditambahkan.');
    }
 
    public function show(Kelas $kela)
    {
        $kela->load(['dosenPa']);

        $mahasiswas = \App\Models\Mahasiswa::whereHas('kelasMahasiswas', fn($q) => $q->where('kelas_id', $kela->id))
            ->with(['nilais', 'absensis', 'kompensasis'])
            ->orderBy('nama')
            ->get()
            ->map(function ($m) use ($kela) {
                $m->ipk_val      = $m->ipk;
                $m->ips_val      = $m->getIpSemester($kela->semester);
                $m->alpha_val    = (int) $m->absensis->where('semester', $kela->semester)->sum('jam_alpha');
                $m->kategori     = $m->getKategoriRisiko($kela->semester);
                $m->is_berisiko  = !empty($m->kategori);
                return $m;
            });

        $totalBerisiko = $mahasiswas->where('is_berisiko', true)->count();
        $rataIpk       = $mahasiswas->count() > 0 ? round($mahasiswas->avg('ipk_val'), 2) : 0;

        return view('admin.kelas.show', compact('kela', 'mahasiswas', 'totalBerisiko', 'rataIpk'));
    }
 
    public function edit(Kelas $kela)
    {
        $dosenList = Dosen::orderBy('nama')->get();
        return view('admin.kelas.edit', compact('kela', 'dosenList'));
    }
 
    public function update(Request $request, Kelas $kela)
    {
        $request->validate([
            'nama'           => 'required|string|max:20',
            'semester'       => 'required|integer|min:1|max:8',
            'prodi'          => 'required|string|max:50',
            'dosen_pa_id'    => 'required|exists:dosens,id',
            'tahun_akademik' => 'required|string|max:10',
        ]);
 
        $kela->update($request->only(['nama','semester','prodi','dosen_pa_id','tahun_akademik']));
 
        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas ' . $kela->nama . ' berhasil diperbarui.');
    }
 
    public function destroy(Kelas $kela)
    {
        $nama = $kela->nama;
        $kela->delete();
        return redirect()->route('admin.kelas.index')
            ->with('success', 'Kelas ' . $nama . ' berhasil dihapus.');
    }
}