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
            return back()->with('error', 'Terjadi kesalahan saat memproses file nilai. Pastikan format file sesuai template, lalu coba lagi.');
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
            return back()->with('error', 'Terjadi kesalahan saat memproses file absensi. Pastikan format file sesuai template, lalu coba lagi.');
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
            return back()->with('error', 'Terjadi kesalahan saat memproses file mahasiswa. Pastikan format file sesuai template, lalu coba lagi.');
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
            return back()->with('error', 'Terjadi kesalahan saat memproses file dosen. Pastikan format file sesuai template, lalu coba lagi.');
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
            return back()->with('error', 'Terjadi kesalahan saat memproses file mata kuliah. Pastikan format file sesuai template, lalu coba lagi.');
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
                $errors[] = $file->getClientOriginalName() . ' gagal diproses (periksa format file)';
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

    // ── DOWNLOAD TEMPLATE RAPOR (_CONVERTED.xlsx) ────────
    public function downloadTemplateRapor()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $spreadsheet->removeSheetByIndex(0);

        $boldWhite = new \PhpOffice\PhpSpreadsheet\Style\Font();
        $boldWhite->setBold(true)->getColor()->setRGB('FFFFFF');

        $fills = [
            'INFO'        => 'FF374151',
            'MAHASISWA'   => 'FF1E3A8A',
            'NILAI'       => 'FF166534',
            'ABSENSI'     => 'FF7C3AED',
            'MATA_KULIAH' => 'FFB45309',
        ];

        // ── INFO ──────────────────────────────────────────
        $ws = $spreadsheet->createSheet()->setTitle('INFO');
        $ws->setCellValue('A1', 'Template Import Rapor Polinema (_CONVERTED.xlsx)');
        $ws->setCellValue('A2', 'File ini memiliki 4 sheet yang harus diisi:');
        $ws->setCellValue('A3', '1. MAHASISWA — data identitas mahasiswa');
        $ws->setCellValue('A4', '2. NILAI     — nilai per mata kuliah per mahasiswa');
        $ws->setCellValue('A5', '3. ABSENSI   — total jam absensi per semester');
        $ws->setCellValue('A6', '4. MATA_KULIAH — daftar mata kuliah');
        $ws->setCellValue('A8', 'PETUNJUK:');
        $ws->setCellValue('A9', '• Hapus sheet INFO ini sebelum upload, atau biarkan (akan diabaikan)');
        $ws->setCellValue('A10', '• Gunakan script Python (download via tombol "Tutorial Colab") untuk konversi otomatis');
        $ws->setCellValue('A11', '• Baris pertama setiap sheet adalah HEADER — jangan diubah');
        $ws->getColumnDimension('A')->setWidth(70);
        $ws->getStyle('A1')->getFont()->setBold(true)->setSize(12);
        $ws->getStyle('A8')->getFont()->setBold(true);

        // ── MAHASISWA ──────────────────────────────────────
        $ws = $spreadsheet->createSheet()->setTitle('MAHASISWA');
        $ws->fromArray([
            ['NIM', 'NAMA', 'KELAS', 'ANGKATAN', 'DPA', 'PRODI', 'TAHUN_AKADEMIK'],
            ['2241760001', 'Ahmad Rizki Pratama', 'TI3A', '2022', 'Dr. Budi Santoso, M.Kom', 'Teknologi Informasi', '2024/2025'],
            ['2241760002', 'Siti Nur Aisyah', 'TI3A', '2022', 'Dr. Budi Santoso, M.Kom', 'Teknologi Informasi', '2024/2025'],
        ]);
        $this->styleHeader($ws, 'A1:G1', '1E3A8A');
        foreach (['A'=>15,'B'=>30,'C'=>10,'D'=>10,'E'=>40,'F'=>25,'G'=>12] as $col => $w) {
            $ws->getColumnDimension($col)->setWidth($w);
        }

        // ── NILAI ──────────────────────────────────────────
        $ws = $spreadsheet->createSheet()->setTitle('NILAI');
        $ws->fromArray([
            ['NIM', 'KODE_MK', 'NAMA_MK', 'SKS', 'SEMESTER', 'NILAI_AKHIR', 'GRADE'],
            ['2241760001', 'TI601', 'Pemrograman Web', '3', '5', '87.5', 'A'],
            ['2241760001', 'TI602', 'Basis Data Lanjut', '3', '5', '73.0', 'B+'],
            ['2241760002', 'TI601', 'Pemrograman Web', '3', '5', '65.0', 'B'],
        ]);
        $this->styleHeader($ws, 'A1:G1', '166534');
        foreach (['A'=>15,'B'=>10,'C'=>35,'D'=>5,'E'=>10,'F'=>12,'G'=>8] as $col => $w) {
            $ws->getColumnDimension($col)->setWidth($w);
        }

        // ── ABSENSI ────────────────────────────────────────
        $ws = $spreadsheet->createSheet()->setTitle('ABSENSI');
        $ws->fromArray([
            ['NIM', 'SEMESTER', 'JAM_HADIR', 'JAM_IZIN', 'JAM_SAKIT', 'JAM_ALPHA'],
            ['2241760001', '5', '52', '2', '0', '3'],
            ['2241760002', '5', '48', '0', '4', '5'],
        ]);
        $this->styleHeader($ws, 'A1:F1', '7C3AED');
        foreach (['A'=>15,'B'=>10,'C'=>12,'D'=>10,'E'=>10,'F'=>10] as $col => $w) {
            $ws->getColumnDimension($col)->setWidth($w);
        }

        // ── MATA_KULIAH ────────────────────────────────────
        $ws = $spreadsheet->createSheet()->setTitle('MATA_KULIAH');
        $ws->fromArray([
            ['KODE', 'NAMA', 'SKS'],
            ['TI601', 'Pemrograman Web', '3'],
            ['TI602', 'Basis Data Lanjut', '3'],
        ]);
        $this->styleHeader($ws, 'A1:C1', 'B45309');
        foreach (['A'=>10,'B'=>45,'C'=>5] as $col => $w) {
            $ws->getColumnDimension($col)->setWidth($w);
        }

        $spreadsheet->setActiveSheetIndex(1); // aktifkan sheet MAHASISWA

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="template_rapor_CONVERTED.xlsx"',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    // ── DOWNLOAD SCRIPT COLAB (.ipynb) ───────────────────
    public function downloadColabScript()
    {
        $pythonScript = <<<'PYTHON'
import sys, os, re
from openpyxl import load_workbook, Workbook
from openpyxl.styles import Font, PatternFill, Alignment

def parse_jam(val):
    if not val: return 0
    s = str(val).strip()
    if ':' in s:
        try: return int(s.split(':')[0])
        except: return 0
    try: return int(float(s))
    except: return 0

def romawi_ke_int(romawi):
    m = {'I':1,'II':2,'III':3,'IV':4,'V':5,'VI':6,'VII':7,'VIII':8}
    return m.get(str(romawi).strip().upper(), 1)

def parse_semester_header(teks):
    if not teks: return 1, ''
    teks = str(teks).strip()
    parts = teks.split('/')
    semester = romawi_ke_int(parts[0])
    tahun_match = re.search(r'(\d{4}/\d{4})', teks)
    tahun = tahun_match.group(1) if tahun_match else ''
    return semester, tahun

def konversi_file(input_path):
    print(f"\n{'='*60}")
    print(f"Memproses: {os.path.basename(input_path)}")
    print('='*60)
    try:
        wb_in = load_workbook(input_path, data_only=True)
    except Exception as e:
        print(f"  ERROR: Tidak bisa buka file -> {e}")
        return None
    ws = wb_in.active
    rows = list(ws.iter_rows(values_only=True))
    jurusan  = str(rows[1][2]).strip()  if rows[1][2]  else ''
    prodi    = str(rows[2][2]).strip()  if rows[2][2]  else ''
    teks_sem = str(rows[4][2]).strip()  if rows[4][2]  else ''
    kelas    = str(rows[5][2]).strip()  if rows[5][2]  else ''
    dpa      = str(rows[6][2]).strip()  if rows[6][2]  else ''
    semester, tahun = parse_semester_header(teks_sem)
    print(f"  Prodi: {prodi} | Semester: {semester} | Kelas: {kelas}")
    print(f"  Tahun Akademik: {tahun} | DPA: {dpa}")
    baris_kode = rows[10]
    baris_nama = rows[1]
    baris_sks  = rows[11]
    baris_hdr  = rows[15]
    mks = []
    col = 3
    while col < len(baris_kode):
        kode = baris_kode[col]
        if kode and str(kode).strip() not in ['', 'None']:
            nama = str(baris_nama[col]).strip() if baris_nama[col] else str(kode)
            sks  = int(baris_sks[col]) if baris_sks[col] else 2
            mks.append({'kode': str(kode).strip(), 'nama': nama, 'sks': sks,
                        'col_ns': col, 'col_nh': col + 1})
            col += 2
        else:
            col += 1
    print(f"  Mata Kuliah: {len(mks)} MK ditemukan")
    col_alpha = col_izin = col_sakit = col_total = None
    for ci, val in enumerate(baris_hdr):
        if val == 'A' and col_alpha is None:
            col_alpha = ci
        elif val == 'I' and col_alpha is not None and col_izin is None:
            col_izin = ci
        elif val == 'S' and col_izin is not None and col_sakit is None:
            col_sakit = ci
        elif val == '∑ AIS' and col_sakit is not None and col_total is None:
            col_total = ci
            break
    data_mhs = []; data_nil = []; data_abs = []
    for row in rows[16:]:
        nim = row[1]
        if not nim: continue
        try: nim_int = int(str(nim).strip())
        except: continue
        nama = str(row[2]).strip() if row[2] else ''
        if not nama or nama == 'None': continue
        nim_str  = str(nim_int)
        angkatan = f"20{nim_str[:2]}"
        data_mhs.append({'nim': nim_str, 'nama': nama, 'kelas': kelas,
                         'angkatan': angkatan, 'dpa': dpa,
                         'prodi': prodi, 'tahun_akademik': tahun})
        for mk in mks:
            ns = row[mk['col_ns']]
            nh = row[mk['col_nh']]
            if ns is None and nh is None: continue
            grade = str(nh).strip() if nh else ''
            if grade and grade not in ['None', '-', '']:
                data_nil.append({'nim': nim_str, 'kode_mk': mk['kode'], 'nama_mk': mk['nama'],
                                 'sks': mk['sks'], 'semester': semester,
                                 'nilai_akhir': float(ns) if ns else 0, 'grade': grade})
        ja = parse_jam(row[col_alpha]) if col_alpha and col_alpha < len(row) else 0
        ji = parse_jam(row[col_izin])  if col_izin  and col_izin  < len(row) else 0
        js = parse_jam(row[col_sakit]) if col_sakit and col_sakit < len(row) else 0
        jt = parse_jam(row[col_total]) if col_total and col_total < len(row) else 0
        jh = max(0, jt - ja - ji - js)
        data_abs.append({'nim': nim_str, 'semester': semester,
                         'jam_hadir': jh, 'jam_izin': ji,
                         'jam_sakit': js, 'jam_alpha': ja})
    print(f"  Hasil: {len(data_mhs)} mahasiswa, {len(data_nil)} nilai, {len(data_abs)} absensi")
    wb_out = Workbook()
    hf = Font(bold=True, color='FFFFFF')
    ws_info = wb_out.active; ws_info.title = 'INFO'
    ws_info.append(['File konversi rapor Polinema untuk Dashboard BI'])
    ws_info.append(['Prodi', prodi]); ws_info.append(['Semester', semester])
    ws_info.append(['Tahun Akademik', tahun]); ws_info.append(['Kelas', kelas])
    ws_info.append(['DPA', dpa]); ws_info.append(['Total Mahasiswa', len(data_mhs)])
    ws_info.append(['Total Nilai', len(data_nil)]); ws_info.append(['Total Absensi', len(data_abs)])
    ws_mhs = wb_out.create_sheet('MAHASISWA')
    headers_m = ['NIM','NAMA','KELAS','ANGKATAN','DPA','PRODI','TAHUN_AKADEMIK']
    ws_mhs.append(headers_m)
    for c in ws_mhs[1]:
        c.font = hf; c.fill = PatternFill('solid', fgColor='1E3A8A')
        c.alignment = Alignment(horizontal='center')
    for m in data_mhs:
        ws_mhs.append([m['nim'],m['nama'],m['kelas'],m['angkatan'],m['dpa'],m['prodi'],m['tahun_akademik']])
    ws_nil = wb_out.create_sheet('NILAI')
    headers_n = ['NIM','KODE_MK','NAMA_MK','SKS','SEMESTER','NILAI_AKHIR','GRADE']
    ws_nil.append(headers_n)
    for c in ws_nil[1]:
        c.font = hf; c.fill = PatternFill('solid', fgColor='166534')
        c.alignment = Alignment(horizontal='center')
    for n in data_nil:
        ws_nil.append([n['nim'],n['kode_mk'],n['nama_mk'],n['sks'],n['semester'],n['nilai_akhir'],n['grade']])
    ws_abs = wb_out.create_sheet('ABSENSI')
    headers_a = ['NIM','SEMESTER','JAM_HADIR','JAM_IZIN','JAM_SAKIT','JAM_ALPHA']
    ws_abs.append(headers_a)
    for c in ws_abs[1]:
        c.font = hf; c.fill = PatternFill('solid', fgColor='7C3AED')
        c.alignment = Alignment(horizontal='center')
    for a in data_abs:
        ws_abs.append([a['nim'],a['semester'],a['jam_hadir'],a['jam_izin'],a['jam_sakit'],a['jam_alpha']])
    ws_mk = wb_out.create_sheet('MATA_KULIAH')
    headers_k = ['KODE','NAMA','SKS']
    ws_mk.append(headers_k)
    for c in ws_mk[1]:
        c.font = hf; c.fill = PatternFill('solid', fgColor='B45309')
        c.alignment = Alignment(horizontal='center')
    seen = set()
    for mk in mks:
        if mk['kode'] not in seen:
            ws_mk.append([mk['kode'], mk['nama'], mk['sks']])
            seen.add(mk['kode'])
    base = os.path.splitext(os.path.basename(input_path))[0]
    out_path = f"/content/{base}_CONVERTED.xlsx"
    wb_out.save(out_path)
    print(f"  Berhasil! Output: {out_path}")
    from google.colab import files
    files.download(out_path)
    return out_path
PYTHON;

        $notebook = [
            'nbformat'       => 4,
            'nbformat_minor' => 5,
            'metadata'       => [
                'colab' => ['name' => 'konversi_rapor_polinema.ipynb', 'provenance' => []],
                'kernelspec' => ['display_name' => 'Python 3', 'name' => 'python3'],
                'language_info' => ['name' => 'python'],
            ],
            'cells' => [
                [
                    'cell_type' => 'markdown',
                    'id'        => 'cell-intro',
                    'metadata'  => [],
                    'source'    => [
                        "# 📊 Konversi Rapor Polinema → Format Import Dashboard BI\n",
                        "\n",
                        "**Cara pakai:**\n",
                        "1. Jalankan cell **Install Library** (Ctrl+Enter)\n",
                        "2. Jalankan cell **Upload File** → pilih file rapor `.xlsx` dari SIPA/Polinema\n",
                        "3. Jalankan cell **Jalankan Konversi** → file `_CONVERTED.xlsx` akan otomatis terdownload\n",
                        "4. Upload file `_CONVERTED.xlsx` ke halaman **Admin → Import Data → Rapor Polinema**\n",
                        "\n",
                        "> Mendukung format rapor ganjil dan genap Polinema.",
                    ],
                ],
                [
                    'cell_type'       => 'code',
                    'id'              => 'cell-install',
                    'metadata'        => [],
                    'source'          => ["# Langkah 1: Install library yang dibutuhkan\n", "!pip install openpyxl -q\n", "print('✅ Library siap!')"],
                    'outputs'         => [],
                    'execution_count' => null,
                ],
                [
                    'cell_type'       => 'code',
                    'id'              => 'cell-upload',
                    'metadata'        => [],
                    'source'          => ["# Langkah 2: Upload file rapor Polinema (.xlsx)\n", "from google.colab import files\n", "uploaded = files.upload()\n", "input_file = list(uploaded.keys())[0]\n", "print(f'File dipilih: {input_file}')"],
                    'outputs'         => [],
                    'execution_count' => null,
                ],
                [
                    'cell_type'       => 'code',
                    'id'              => 'cell-script',
                    'metadata'        => [],
                    'source'          => ["# Langkah 3: Jalankan konversi — file _CONVERTED.xlsx akan terdownload otomatis\n", "import sys, os, re\n", "from openpyxl import load_workbook, Workbook\n", "from openpyxl.styles import Font, PatternFill, Alignment\n\n", $pythonScript . "\n\n", "# Jalankan konversi\n", "konversi_file(input_file)"],
                    'outputs'         => [],
                    'execution_count' => null,
                ],
            ],
        ];

        return response(json_encode($notebook, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="konversi_rapor_polinema.ipynb"',
        ]);
    }

    // ── Helper: style header row ──────────────────────────
    private function styleHeader(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $ws, string $range, string $hex): void
    {
        $ws->getStyle($range)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => $hex]],
            'alignment' => ['horizontal' => 'center'],
        ]);
    }
}
