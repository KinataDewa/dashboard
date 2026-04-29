<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Http\Request;
 
class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with(['dosenPa', 'mahasiswas'])
            ->orderBy('semester')
            ->orderBy('nama')
            ->paginate(15);
        return view('admin.kelas.index', compact('kelas'));
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
        $kela->load(['dosenPa', 'mahasiswas', 'mataKuliahs']);
        return view('admin.kelas.show', compact('kela'));
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