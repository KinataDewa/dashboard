<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use App\Models\Kelas;
use App\Models\Dosen;
use Illuminate\Http\Request;
 
class MatkulController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::with(['kelas', 'dosen']);
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->kelas) {
            $query->where('kelas_id', $request->kelas);
        }
        $matkuls   = $query->orderBy('semester')->orderBy('nama')->paginate(15);
        $kelasList = Kelas::orderBy('nama')->get();
        return view('admin.matkul.index', compact('matkuls', 'kelasList'));
    }
 
    public function create()
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $dosenList = Dosen::orderBy('nama')->get();
        return view('admin.matkul.create', compact('kelasList', 'dosenList'));
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'kode'     => 'required|string|unique:mata_kuliahs,kode',
            'nama'     => 'required|string|max:100',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'kelas_id' => 'required|exists:kelas,id',
            'dosen_id' => 'required|exists:dosens,id',
        ]);
 
        MataKuliah::create($request->only(['kode','nama','sks','semester','kelas_id','dosen_id']));
 
        return redirect()->route('admin.matkul.index')
            ->with('success', 'Mata kuliah ' . $request->nama . ' berhasil ditambahkan.');
    }
 
    public function show(MataKuliah $matkul)
    {
        return view('admin.matkul.show', compact('matkul'));
    }
 
    public function edit(MataKuliah $matkul)
    {
        $kelasList = Kelas::orderBy('nama')->get();
        $dosenList = Dosen::orderBy('nama')->get();
        return view('admin.matkul.edit', compact('matkul', 'kelasList', 'dosenList'));
    }
 
    public function update(Request $request, MataKuliah $matkul)
    {
        $request->validate([
            'kode'     => 'required|string|unique:mata_kuliahs,kode,' . $matkul->id,
            'nama'     => 'required|string|max:100',
            'sks'      => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8',
            'kelas_id' => 'required|exists:kelas,id',
            'dosen_id' => 'required|exists:dosens,id',
        ]);
 
        $matkul->update($request->only(['kode','nama','sks','semester','kelas_id','dosen_id']));
 
        return redirect()->route('admin.matkul.index')
            ->with('success', 'Mata kuliah ' . $matkul->nama . ' berhasil diperbarui.');
    }
 
    public function destroy(MataKuliah $matkul)
    {
        $nama = $matkul->nama;
        $matkul->delete();
        return redirect()->route('admin.matkul.index')
            ->with('success', 'Mata kuliah ' . $nama . ' berhasil dihapus.');
    }
}