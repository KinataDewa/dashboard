<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\NilaiImport;
use App\Imports\AbsensiImport;
use App\Imports\MahasiswaImport;
use App\Imports\DosenImport;
use App\Imports\MatkulImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class ImportController extends Controller
{
    public function index()
    {
        return view('admin.import.index');
    }

    // ── NILAI ────────────────────────────────────────
    public function nilai(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new NilaiImport();
            Excel::import($import, $request->file('file'));

            $msg = 'Import nilai berhasil! '
                 . $import->getImportedCount() . ' data diproses';

            if ($import->getSkippedCount() > 0) {
                $msg .= ', ' . $import->getSkippedCount() . ' baris dilewati (NIM/kode matkul tidak ditemukan).';
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Import nilai error: ' . $e->getMessage());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // ── ABSENSI ──────────────────────────────────────
    public function absensi(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new AbsensiImport();
            Excel::import($import, $request->file('file'));

            $msg = 'Import absensi berhasil! '
                 . $import->getImportedCount() . ' data diproses';

            if ($import->getSkippedCount() > 0) {
                $msg .= ', ' . $import->getSkippedCount() . ' baris dilewati.';
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Import absensi error: ' . $e->getMessage());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // ── MAHASISWA ────────────────────────────────────
    public function mahasiswa(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new MahasiswaImport();
            Excel::import($import, $request->file('file'));

            $msg = 'Import mahasiswa berhasil! '
                 . $import->getImportedCount() . ' mahasiswa diproses';

            if ($import->getSkippedCount() > 0) {
                $msg .= ', ' . $import->getSkippedCount() . ' baris dilewati (kelas/dosen PA tidak ditemukan).';
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Import mahasiswa error: ' . $e->getMessage());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // ── DOSEN ────────────────────────────────────────
    public function dosen(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new DosenImport();
            Excel::import($import, $request->file('file'));

            return back()->with('success',
                'Import dosen berhasil! ' . $import->getImportedCount() . ' dosen diproses.'
            );

        } catch (\Exception $e) {
            Log::error('Import dosen error: ' . $e->getMessage());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // ── MATKUL ───────────────────────────────────────
    public function matkul(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new MatkulImport();
            Excel::import($import, $request->file('file'));

            $msg = 'Import mata kuliah berhasil! '
                 . $import->getImportedCount() . ' matkul diproses';

            if ($import->getSkippedCount() > 0) {
                $msg .= ', ' . $import->getSkippedCount() . ' baris dilewati.';
            }

            return back()->with('success', $msg);

        } catch (\Exception $e) {
            Log::error('Import matkul error: ' . $e->getMessage());
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    // ── JADWAL (placeholder) ─────────────────────────
    public function jadwal(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);
        return back()->with('success', 'Import jadwal berhasil!');
    }

    // ── KELAS (placeholder) ──────────────────────────
    public function kelas(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);
        return back()->with('success', 'Import kelas berhasil!');
    }

    // ── DOWNLOAD TEMPLATE ────────────────────────────
    public function downloadTemplate(string $type)
    {
        $templates = [
            'nilai'     => 'templates/template_nilai.xlsx',
            'absensi'   => 'templates/template_absensi.xlsx',
            'mahasiswa' => 'templates/template_mahasiswa.xlsx',
            'dosen'     => 'templates/template_dosen.xlsx',
            'matkul'    => 'templates/template_matkul.xlsx',
        ];

        if (!isset($templates[$type])) {
            return back()->with('error', 'Template tidak ditemukan.');
        }

        $path = storage_path('app/' . $templates[$type]);
        if (!file_exists($path)) {
            return back()->with('error', 'File template belum tersedia.');
        }

        return response()->download($path);
    }
}
