<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\NilaiImport;
use App\Imports\AbsensiImport;
use App\Imports\MahasiswaImport;
use App\Imports\DosenImport;
use App\Imports\MatkulImport;
use App\Imports\RaporImport;
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

    // ── RAPOR ────────────────────────────────────────
    public function rapor(Request $request)
    {
        $request->validate([
            'files'    => 'required|array|min:1',
            'files.*'  => 'file|mimes:xlsx,xls|max:10240',
            'angkatan' => 'required|digits:4|integer|min:2000|max:' . date('Y'),
        ]);

        $files          = $request->file('files');
        $angkatan       = (string) $request->input('angkatan');
        $totalMahasiswa = 0;
        $totalCreated   = 0;
        $totalNilai     = 0;
        $totalAbsensi   = 0;
        $totalSkipped   = 0;
        $errors         = [];

        foreach ($files as $file) {
            try {
                $import = new RaporImport();
                $import->angkatan = $angkatan;
                $import->import($file->getRealPath());

                $totalMahasiswa += $import->getImportedMahasiswaCount();
                $totalCreated   += $import->getCreatedMahasiswaCount();
                $totalNilai     += $import->getImportedNilaiCount();
                $totalAbsensi   += $import->getImportedAbsensiCount();
                $totalSkipped   += $import->getSkippedCount();

            } catch (\Exception $e) {
                Log::error('Import rapor error [' . $file->getClientOriginalName() . ']: ' . $e->getMessage());
                $errors[] = $file->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            return back()->with('error', 'Gagal import beberapa file: ' . implode('; ', $errors));
        }

        $msg = sprintf(
            'Berhasil import %d file: total %d mahasiswa (%d baru), %d nilai, %d absensi diimport.',
            count($files),
            $totalMahasiswa,
            $totalCreated,
            $totalNilai,
            $totalAbsensi
        );

        if ($totalSkipped > 0) {
            $msg .= ' ' . $totalSkipped . ' baris dilewati (lihat log).';
        }

        return back()->with('success', $msg);
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
        // Generate template langsung tanpa file fisik
        // menggunakan array of arrays → CSV download
        $templates = [
            'nilai' => [
                'filename' => 'template_nilai.xlsx',
                'headers'  => ['nim','kode_matkul','semester','tahun_akademik','nilai_tugas','nilai_uts','nilai_uas'],
                'sample'   => ['2341720099','TI601','6','2024/2025','85','78','82'],
            ],
            'absensi' => [
                'filename' => 'template_absensi.xlsx',
                'headers'  => ['nim','semester','jam_hadir','jam_izin','jam_sakit','jam_alpha'],
                'sample'   => ['2341720099','6','52','0','2','3'],
            ],
            'mahasiswa' => [
                'filename' => 'template_mahasiswa.xlsx',
                'headers'  => ['nim','nama','email','kelas','angkatan','nip_dosen_pa'],
                'sample'   => ['2341720050','Nama Mahasiswa','email@student.polinema.ac.id','TI3C','2023','197501012005011001'],
            ],
            'dosen' => [
                'filename' => 'template_dosen.xlsx',
                'headers'  => ['nip','nama','email','no_hp'],
                'sample'   => ['199001012020121001','Nama Dosen','dosen@polinema.ac.id','08123456789'],
            ],
            'matkul' => [
                'filename' => 'template_matkul.xlsx',
                'headers'  => ['kode','nama','sks','semester','kelas','nip_dosen'],
                'sample'   => ['TI608','Internet of Things','3','6','TI3C','197803152006041002'],
            ],
            'jadwal' => [
                'filename' => 'template_jadwal.xlsx',
                'headers'  => ['kode_matkul','kelas','hari','jam_mulai','jam_selesai','ruangan'],
                'sample'   => ['TI601','TI3C','Senin','08:00','10:30','GKB1-301'],
            ],
            'kelas' => [
                'filename' => 'template_kelas.xlsx',
                'headers'  => ['nama','semester','prodi','nip_dosen_pa','tahun_akademik'],
                'sample'   => ['TI3D','6','Teknologi Informasi','197501012005011001','2024/2025'],
            ],
        ];
 
        if (!isset($templates[$type])) {
            return back()->with('error', 'Template tidak ditemukan.');
        }
 
        $tpl      = $templates[$type];
        $filename = str_replace('.xlsx', '.csv', $tpl['filename']);
 
        // Build CSV content
        $rows = [];
        $rows[] = implode(',', $tpl['headers']);
        $rows[] = implode(',', $tpl['sample']);
        $content = implode("\n", $rows);
 
        return response($content, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
