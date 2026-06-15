<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\MataKuliah;
use Illuminate\Http\Request;

class MatkulController extends Controller
{
    public function index(Request $request)
    {
        $query = MataKuliah::query();
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }
        $matkuls = $query->orderBy('nama')->paginate(15);
        return view('admin.matkul.index', compact('matkuls'));
    }

    public function create()
    {
        return view('admin.matkul.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:mata_kuliahs,kode',
            'nama' => 'required|string|max:100',
            'sks'  => 'required|integer|min:1|max:6',
        ]);

        MataKuliah::create($request->only(['kode','nama','sks']));
 
        return redirect()->route('admin.matkul.index')
            ->with('success', 'Mata kuliah ' . $request->nama . ' berhasil ditambahkan.');
    }
 
    public function show(MataKuliah $matkul)
    {
        return view('admin.matkul.show', compact('matkul'));
    }
 
    public function edit(MataKuliah $matkul)
    {
        return view('admin.matkul.edit', compact('matkul'));
    }

    public function update(Request $request, MataKuliah $matkul)
    {
        $request->validate([
            'kode' => 'required|string|unique:mata_kuliahs,kode,' . $matkul->id,
            'nama' => 'required|string|max:100',
            'sks'  => 'required|integer|min:1|max:6',
        ]);

        $matkul->update($request->only(['kode','nama','sks']));
 
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