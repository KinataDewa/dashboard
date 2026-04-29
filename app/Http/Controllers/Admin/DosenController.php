<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
 
class DosenController extends Controller
{
    public function index(Request $request)
    {
        $query = Dosen::with('user');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('nip', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }
        $dosens = $query->orderBy('nama')->paginate(15);
        return view('admin.dosen.index', compact('dosens'));
    }
 
    public function create()
    {
        return view('admin.dosen.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'nip'      => 'required|string|unique:dosens,nip',
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_hp'    => 'nullable|string|max:20',
        ]);
 
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $user->assignRole('dosen');
 
            Dosen::create([
                'user_id' => $user->id,
                'nip'     => $request->nip,
                'nama'    => $request->nama,
                'no_hp'   => $request->no_hp,
            ]);
        });
 
        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen ' . $request->nama . ' berhasil ditambahkan.');
    }
 
    public function show(Dosen $dosen)
    {
        $dosen->load(['kelas.mahasiswas', 'mataKuliah']);
        return view('admin.dosen.show', compact('dosen'));
    }
 
    public function edit(Dosen $dosen)
    {
        return view('admin.dosen.edit', compact('dosen'));
    }
 
    public function update(Request $request, Dosen $dosen)
    {
        $request->validate([
            'nip'   => 'required|string|unique:dosens,nip,' . $dosen->id,
            'nama'  => 'required|string|max:100',
            'no_hp' => 'nullable|string|max:20',
        ]);
 
        DB::transaction(function () use ($request, $dosen) {
            $dosen->update([
                'nip'   => $request->nip,
                'nama'  => $request->nama,
                'no_hp' => $request->no_hp,
            ]);
            $dosen->user->update(['name' => $request->nama]);
        });
 
        return redirect()->route('admin.dosen.index')
            ->with('success', 'Data dosen ' . $dosen->nama . ' berhasil diperbarui.');
    }
 
    public function destroy(Dosen $dosen)
    {
        $nama = $dosen->nama;
        DB::transaction(function () use ($dosen) {
            $userId = $dosen->user_id;
            $dosen->delete();
            User::find($userId)?->delete();
        });
        return redirect()->route('admin.dosen.index')
            ->with('success', 'Dosen ' . $nama . ' berhasil dihapus.');
    }
}