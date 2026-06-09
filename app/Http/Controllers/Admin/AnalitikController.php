<?php
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
 
class AnalitikController extends Controller
{
    public function index()
    {
        // Ambil semua angkatan yang ada
        $angkatanList = Mahasiswa::distinct('angkatan')
            ->orderBy('angkatan')
            ->pluck('angkatan');
 
        // Hitung tren + prediksi untuk setiap angkatan
        $trendData = [];
        foreach ($angkatanList as $angkatan) {
            $trendData[$angkatan] = $this->hitungTrenAngkatan($angkatan);
        }
 
        // Ringkasan per angkatan (untuk tabel)
        $ringkasan = [];
        foreach ($angkatanList as $angkatan) {
            $data = $trendData[$angkatan];
            if (empty($data['historis'])) continue;
 
            $nilai  = array_values($data['historis']);
            $last   = end($nilai);
            $trend  = $this->deteksiTren($nilai);
 
            $ringkasan[] = [
                'angkatan'   => $angkatan,
                'rata_saat_ini' => $last,
                'prediksi'   => $data['prediksi'],
                'trend'      => $trend,
                'semester_count' => count($nilai),
            ];
        }
 
        return view('admin.analitik.index', compact(
            'angkatanList', 'trendData', 'ringkasan'
        ));
    }
 
    // ── Hitung tren dan prediksi ARIMA(0,1,1) per angkatan ──
    private function hitungTrenAngkatan(int $angkatan): array
    {
        // Ambil rata-rata nilai per semester untuk angkatan ini
        $rows = DB::table('nilais')
            ->join('mahasiswas', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
            ->where('mahasiswas.angkatan', $angkatan)
            ->select('nilais.semester', DB::raw('AVG(nilais.nilai_akhir) as rata'))
            ->groupBy('nilais.semester')
            ->orderBy('nilais.semester')
            ->get();
 
        if ($rows->isEmpty()) {
            return ['historis' => [], 'prediksi' => null, 'differencing' => []];
        }
 
        // Susun data historis
        $historis = [];
        foreach ($rows as $row) {
            $historis[$row->semester] = round((float) $row->rata, 2);
        }
 
        // Konversi ke IPK skala 4 jika perlu (nilai 0-100 → 0-4)
        // Jika nilai sudah dalam skala 100, konversi ke skala 4
        $maxVal = max($historis);
        if ($maxVal > 4) {
            // Nilai dalam skala 100, konversi ke grade point (A=4, B=3, C=2, D=1, E=0)
            foreach ($historis as $sem => $val) {
                $historis[$sem] = round($this->nilaiToGradePoint($val), 2);
            }
        }
 
        // ARIMA (0,1,1) — Differencing d=1
        $values = array_values($historis);
        $differencing = $this->hitungDifferencing($values);
        $prediksi     = $this->hitungPrediksi($values, $differencing);
 
        return [
            'historis'     => $historis,
            'differencing' => $differencing,
            'prediksi'     => $prediksi,
        ];
    }
 
    // ── Differencing d=1 ──────────────────────────────────
    private function hitungDifferencing(array $values): array
    {
        $diff = [];
        for ($i = 1; $i < count($values); $i++) {
            $diff[] = round($values[$i] - $values[$i - 1], 4);
        }
        return $diff;
    }
 
    // ── Prediksi ARIMA(0,1,1): last + mean(diff) ─────────
    private function hitungPrediksi(array $values, array $diff): ?float
    {
        if (empty($diff) || empty($values)) return null;
 
        $meanDiff = array_sum($diff) / count($diff);
        $last     = end($values);
        $prediksi = $last + $meanDiff;
 
        // Clamp ke range 0–4
        $prediksi = max(0, min(4, $prediksi));
 
        return round($prediksi, 2);
    }
 
    // ── Nilai 0-100 ke grade point 0-4 ───────────────────
    private function nilaiToGradePoint(float $nilai): float
    {
        if ($nilai > 80) return 4.0;  // A
        if ($nilai > 73) return 3.5;  // B+
        if ($nilai > 65) return 3.0;  // B
        if ($nilai > 60) return 2.5;  // C+
        if ($nilai > 50) return 2.0;  // C
        if ($nilai > 39) return 1.0;  // D
        return 0.0;                   // E
    }
 
    // ── Deteksi tren: naik/turun/stabil ──────────────────
    private function deteksiTren(array $values): string
    {
        if (count($values) < 2) return 'stabil';
        $first = $values[0];
        $last  = end($values);
        $diff  = $last - $first;
 
        if ($diff > 0.05) return 'naik';
        if ($diff < -0.05) return 'turun';
        return 'stabil';
    }
 
    // ── API: data chart per angkatan ─────────────────────
    public function chartData(Request $request)
    {
        $angkatan = $request->get('angkatan');
        $data     = $this->hitungTrenAngkatan((int) $angkatan);
 
        $labels   = [];
        $values   = [];
 
        foreach ($data['historis'] as $sem => $val) {
            $labels[] = 'Semester ' . $sem;
            $values[] = $val;
        }
 
        // Tambah prediksi sebagai titik terakhir
        if ($data['prediksi'] !== null) {
            $semAkhir = max(array_keys($data['historis']));
            $labels[] = 'Sem ' . ($semAkhir + 1) . ' (Prediksi)';
            $values[] = null; // break line
        }
 
        return response()->json([
            'labels'   => $labels,
            'historis' => array_values($data['historis']),
            'prediksi' => $data['prediksi'],
            'differencing' => $data['differencing'],
        ]);
    }
}