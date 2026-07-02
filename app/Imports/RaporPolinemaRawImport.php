<?php
namespace App\Imports;

use App\Models\Mahasiswa;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Parses raw Polinema rapor Excel format (same layout as output of konversi_rapor_polinema.py)
 * and imports directly to database without needing the Python conversion step.
 *
 * Row layout (0-indexed):
 *   rows[2][2]  = Prodi
 *   rows[4][2]  = Semester header text (e.g. "I / AKHIR GANJIL 2022/2023")
 *   rows[5][2]  = Kelas
 *   rows[6][2]  = DPA (nama dosen)
 *   rows[1]     = MK name row (read per MK column)
 *   rows[10]    = MK kode row (starts at col 3)
 *   rows[11]    = MK SKS row
 *   rows[15]    = Absensi header row (A, I, S, ∑AIS)
 *   rows[16]+   = Student data rows
 */
class RaporPolinemaRawImport extends RaporImport
{
    public function import(string $filePath): void
    {
        $spreadsheet = IOFactory::load($filePath);
        $ws          = $spreadsheet->getActiveSheet();
        $rows        = $ws->toArray(null, true, false, false);

        // Parse header
        $prodi   = isset($rows[2][2]) ? trim((string) ($rows[2][2] ?? '')) : '';
        $teksSem = isset($rows[4][2]) ? trim((string) ($rows[4][2] ?? '')) : '';
        $kelas   = isset($rows[5][2]) ? trim((string) ($rows[5][2] ?? '')) : '';
        $dpa     = isset($rows[6][2]) ? trim((string) ($rows[6][2] ?? '')) : '';

        [$semester, $tahunAkad] = $this->parseSemHeader($teksSem);

        // Parse mata kuliah: kode in rows[10], name in rows[1], SKS in rows[11]
        $barisKode = $rows[10] ?? [];
        $barisNama = $rows[1]  ?? [];
        $barisSks  = $rows[11] ?? [];

        $mks = [];
        for ($col = 3; $col < count($barisKode); ) {
            $kode = trim((string) ($barisKode[$col] ?? ''));
            if ($kode !== '' && strtolower($kode) !== 'null') {
                $nama = (isset($barisNama[$col]) && $barisNama[$col] !== null)
                    ? trim((string) $barisNama[$col])
                    : $kode;
                $sks = isset($barisSks[$col]) ? (int) $barisSks[$col] : 2;
                $mks[] = [
                    'kode'   => $kode,
                    'nama'   => ($nama !== '' && strtolower($nama) !== 'null') ? $nama : $kode,
                    'sks'    => max(1, $sks),
                    'col_ns' => $col,
                    'col_nh' => $col + 1,
                ];
                $col += 2;
            } else {
                $col++;
            }
        }

        // Detect absensi columns from rows[15] — take FIRST set only
        $barisHdr = $rows[15] ?? [];
        $colA = $colI = $colS = $colT = null;
        foreach ($barisHdr as $ci => $hval) {
            $v = $hval !== null ? trim((string) $hval) : '';
            if ($v === 'A' && $colA === null) {
                $colA = $ci;
            } elseif ($v === 'I' && $colA !== null && $colI === null) {
                $colI = $ci;
            } elseif ($v === 'S' && $colI !== null && $colS === null) {
                $colS = $ci;
            } elseif (($v === '∑AIS' || $v === '∑ AIS') && $colS !== null && $colT === null) {
                $colT = $ci;
                break;
            }
        }

        // Build structured data arrays matching parent's importX() expectations
        $mkRows        = [];
        $mahasiswaRows = [];
        $nilaiRows     = [];
        $absensiRows   = [];

        foreach ($mks as $mk) {
            $mkRows[] = ['KODE' => $mk['kode'], 'NAMA' => $mk['nama'], 'SKS' => $mk['sks']];
        }

        // Process students from rows[16]+
        foreach (array_slice($rows, 16) as $row) {
            $nim = preg_replace('/\s+/', '', (string) ($row[1] ?? ''));
            if (!$nim || !is_numeric($nim)) continue;

            $nama = trim((string) ($row[2] ?? ''));
            if ($nama === '' || strtolower($nama) === 'null') continue;

            // Derive angkatan from NIM first 2 digits, overridden by form value if provided
            $mhsAngkatan = $this->angkatan ?: ('20' . substr($nim, 0, 2));

            $mahasiswaRows[] = [
                'NIM'            => $nim,
                'NAMA'           => $nama,
                'KELAS'          => $kelas,
                'ANGKATAN'       => $mhsAngkatan,
                'DPA'            => $dpa,
                'PRODI'          => $prodi,
                'TAHUN_AKADEMIK' => $tahunAkad,
            ];

            foreach ($mks as $mk) {
                $nh = isset($row[$mk['col_nh']]) ? trim((string) $row[$mk['col_nh']]) : '';
                if ($nh === '' || strtolower($nh) === 'null' || $nh === '-') continue;
                $ns = (isset($row[$mk['col_ns']]) && is_numeric($row[$mk['col_ns']]))
                    ? (float) $row[$mk['col_ns']] : 0.0;

                $nilaiRows[] = [
                    'NIM'         => $nim,
                    'KODE_MK'     => $mk['kode'],
                    'NAMA_MK'     => $mk['nama'],
                    'SKS'         => $mk['sks'],
                    'SEMESTER'    => $semester,
                    'NILAI_AKHIR' => $ns,
                    'GRADE'       => strtoupper($nh),
                ];
            }

            $ja = $colA !== null ? $this->parseAbsJam($row[$colA] ?? null) : 0;
            $ji = $colI !== null ? $this->parseAbsJam($row[$colI] ?? null) : 0;
            $js = $colS !== null ? $this->parseAbsJam($row[$colS] ?? null) : 0;
            $jt = $colT !== null ? $this->parseAbsJam($row[$colT] ?? null) : 0;

            $absensiRows[] = [
                'NIM'       => $nim,
                'SEMESTER'  => $semester,
                'JAM_HADIR' => max(0, $jt - $ja - $ji - $js),
                'JAM_IZIN'  => $ji,
                'JAM_SAKIT' => $js,
                'JAM_ALPHA' => $ja,
            ];
        }

        if (empty($mahasiswaRows)) return;

        // If angkatan not set via form, derive from first NIM so resolveOrCreateKelas works
        if (!$this->angkatan && isset($mahasiswaRows[0])) {
            $this->angkatan = $mahasiswaRows[0]['ANGKATAN'];
        }

        // Import using parent's methods (same DB logic as converted-format import)
        $this->importMataKuliah($mkRows);

        $nimSemesterMap  = [];
        $nimTahunAkadMap = [];
        foreach ($nilaiRows as $nrow) {
            $nimSemesterMap[$nrow['NIM']] ??= $nrow['SEMESTER'];
        }
        foreach ($mahasiswaRows as $mrow) {
            $nimTahunAkadMap[$mrow['NIM']] = $mrow['TAHUN_AKADEMIK'];
        }

        $this->importMahasiswa($mahasiswaRows, $nimSemesterMap);

        $nimCache = Mahasiswa::whereIn('nim', array_column($mahasiswaRows, 'NIM'))
            ->get()->keyBy('nim');

        $this->importNilai($nilaiRows, $nimCache, $nimTahunAkadMap);
        $this->importAbsensi($absensiRows, $nimCache, $nimTahunAkadMap);
    }

    // ── Private helpers (raw format specific) ─────────────

    private function parseSemHeader(string $teks): array
    {
        if ($teks === '') return [1, ''];
        $parts    = explode('/', $teks, 2);
        $semester = $this->romawiToInt(strtoupper(trim($parts[0])));
        preg_match('/(\d{4}\/\d{4})/', $teks, $m);
        return [$semester, $m[1] ?? ''];
    }

    private function romawiToInt(string $s): int
    {
        return ['I'=>1,'II'=>2,'III'=>3,'IV'=>4,'V'=>5,'VI'=>6,'VII'=>7,'VIII'=>8][trim($s)] ?? 1;
    }

    private function parseAbsJam($val): int
    {
        if ($val === null || $val === '') return 0;
        $s = trim((string) $val);
        if (str_contains($s, ':')) return (int) explode(':', $s)[0];
        return is_numeric($s) ? (int) $s : 0;
    }
}
