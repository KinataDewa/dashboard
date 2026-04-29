<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }
 
    // Placeholder — akan diisi di step Import Excel nanti
    public function nilai(Request $request)
    {
        return back()->with('success', 'Import nilai berhasil!');
    }
 
    public function absensi(Request $request)
    {
        return back()->with('success', 'Import absensi berhasil!');
    }
 
    public function jadwal(Request $request)
    {
        return back()->with('success', 'Import jadwal berhasil!');
    }
 
    public function mahasiswa(Request $request)
    {
        return back()->with('success', 'Import mahasiswa berhasil!');
    }
 
    public function dosen(Request $request)
    {
        return back()->with('success', 'Import dosen berhasil!');
    }
 
    public function matkul(Request $request)
    {
        return back()->with('success', 'Import matkul berhasil!');
    }
 
    public function kelas(Request $request)
    {
        return back()->with('success', 'Import kelas berhasil!');
    }
}
 