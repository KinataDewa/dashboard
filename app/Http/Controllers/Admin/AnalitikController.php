<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalitikController extends Controller
{
    public function index()
    {
        $angkatanList = Mahasiswa::distinct('angkatan')
            ->orderBy('angkatan')
            ->pluck('angkatan');

        $trendData = [];
        foreach ($angkatanList as $angkatan) {
            $trendData[$angkatan] = $this->hitungTrenAngkatan($angkatan);
        }

        $ringkasan = [];
        foreach ($angkatanList as $angkatan) {
            $data = $trendData[$angkatan];
            if (empty($data['historis'])) continue;

            $nilai = array_values($data['historis']);
            $last  = end($nilai);

            $ringkasan[] = [
                'angkatan'       => $angkatan,
                'rata_saat_ini'  => $last,
                'prediksi'       => $data['prediksi'],
                'trend'          => $this->deteksiTren($nilai),
                'semester_count' => count($nilai),
            ];
        }

        return view('admin.analitik.index', compact(
            'angkatanList', 'trendData', 'ringkasan'
        ));
    }

    // ── Hitung tren dan prediksi ARIMA(0,1,1) per angkatan ──
    private function hitungTrenAngkatan(string|int $angkatan): array
    {
        // IPK per semester: Σ(bobot_grade × sks) / Σ(sks), lalu rata-rata per angkatan
        $rows = DB::table('nilais')
            ->join('mahasiswas', 'nilais.mahasiswa_id', '=', 'mahasiswas.id')
            ->join('mata_kuliahs', 'nilais.mata_kuliah_id', '=', 'mata_kuliahs.id')
            ->where('mahasiswas.angkatan', $angkatan)
            ->whereNotNull('nilais.grade')
            ->where('nilais.grade', '!=', '')
            ->where('mata_kuliahs.sks', '>', 0)
            ->select(
                'nilais.mahasiswa_id',
                'nilais.semester',
                'nilais.grade',
                'mata_kuliahs.sks'
            )
            ->get();

        if ($rows->isEmpty()) {
            return ['historis' => [], 'prediksi' => null, 'differencing' => [], 'evaluasi' => null, 'walkforward' => []];
        }

        // Hitung mutu per (mahasiswa, semester)
        $mutuMap = [];
        foreach ($rows as $row) {
            $key = $row->mahasiswa_id . '_' . $row->semester;
            $sks = (int) $row->sks;
            if (!isset($mutuMap[$key])) {
                $mutuMap[$key] = ['mutu' => 0.0, 'sks' => 0, 'semester' => (int) $row->semester];
            }
            $mutuMap[$key]['mutu'] += $this->gradeToPoint((string) $row->grade) * $sks;
            $mutuMap[$key]['sks']  += $sks;
        }

        // Rata-rata IPK per semester (seluruh mahasiswa angkatan)
        $semSum   = [];
        $semCount = [];
        foreach ($mutuMap as $entry) {
            if ($entry['sks'] <= 0) continue;
            $ipk = $entry['mutu'] / $entry['sks'];
            $sem = $entry['semester'];
            $semSum[$sem]   = ($semSum[$sem]   ?? 0.0) + $ipk;
            $semCount[$sem] = ($semCount[$sem] ?? 0)   + 1;
        }

        if (empty($semSum)) {
            return ['historis' => [], 'prediksi' => null, 'differencing' => [], 'evaluasi' => null, 'walkforward' => []];
        }

        ksort($semSum);
        $historis = [];
        foreach ($semSum as $sem => $sum) {
            $historis[(int)$sem] = round($sum / $semCount[$sem], 2);
        }

        $values  = array_values($historis);
        $semKeys = array_keys($historis);
        $n       = count($values);

        // Minimum 3 data points untuk prediksi
        if ($n < 3) {
            return [
                'historis'     => $historis,
                'prediksi'     => null,
                'differencing' => $this->hitungDifferencing($values),
                'evaluasi'     => null,
                'walkforward'  => [],
            ];
        }

        $diff     = $this->hitungDifferencing($values);
        $prediksi = $this->prediksiDariValues($values);

        // Walk-forward evaluation: sem ke-2 hingga terakhir
        $walkforward = [];
        for ($i = 2; $i <= $n; $i++) {
            $train = array_slice($values, 0, $i - 1);
            if (count($train) < 2) continue;
            $pred = $this->prediksiDariValues($train);
            if ($pred === null) continue;
            $actual   = $values[$i - 1];
            $selisih  = $actual - $pred;
            $pctError = $actual != 0 ? abs($selisih) / $actual * 100 : 0;
            $walkforward[] = [
                'semester'  => $semKeys[$i - 1],
                'aktual'    => round($actual, 2),
                'prediksi'  => round($pred, 2),
                'selisih'   => round($selisih, 2),
                'pct_error' => round($pctError, 2),
            ];
        }

        // MAE/MAPE: train n-1 semester, test semester terakhir
        $trainAll   = array_slice($values, 0, -1);
        $predLast   = $this->prediksiDariValues($trainAll);
        $actualLast = end($values);
        $evaluasi   = null;
        if ($predLast !== null) {
            $mae  = abs($actualLast - $predLast);
            $mape = $actualLast != 0 ? abs($actualLast - $predLast) / $actualLast * 100 : 0;
            $evaluasi = [
                'mae'           => round($mae, 2),
                'mape'          => round($mape, 2),
                'aktual_last'   => round($actualLast, 2),
                'prediksi_last' => round($predLast, 2),
                'sem_last'      => end($semKeys),
            ];
        }

        return [
            'historis'     => $historis,
            'differencing' => $diff,
            'prediksi'     => $prediksi,
            'evaluasi'     => $evaluasi,
            'walkforward'  => $walkforward,
        ];
    }

    // ── Prediksi dari array values langsung ──────────────
    private function prediksiDariValues(array $values): ?float
    {
        if (count($values) < 2) return null;
        $diff = $this->hitungDifferencing($values);
        return $this->hitungPrediksi($values, $diff);
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
        return round(max(0, min(4, $last + $meanDiff)), 2);
    }

    // ── Konversi grade ke bobot angka ────────────────────
    private function gradeToPoint(string $grade): float
    {
        return match (strtoupper(trim($grade))) {
            'A'  => 4.0,
            'B+' => 3.5,
            'B'  => 3.0,
            'C+' => 2.5,
            'C'  => 2.0,
            'D'  => 1.0,
            default => 0.0,
        };
    }

    // ── Deteksi tren berdasarkan mean diff ────────────────
    private function deteksiTren(array $values): string
    {
        if (count($values) < 2) return 'stabil';
        $diff     = $this->hitungDifferencing($values);
        $meanDiff = array_sum($diff) / count($diff);
        if (abs($meanDiff) < 0.02) return 'stabil';
        if ($meanDiff > 0)         return 'naik';
        return 'turun';
    }

    // ── API: data chart per angkatan ─────────────────────
    public function chartData(Request $request)
    {
        $angkatan = $request->get('angkatan');
        $data     = $this->hitungTrenAngkatan((int) $angkatan);

        $labels = [];
        foreach ($data['historis'] as $sem => $val) {
            $labels[] = 'Semester ' . $sem;
        }
        if ($data['prediksi'] !== null) {
            $semAkhir = max(array_keys($data['historis']));
            $labels[] = 'Sem ' . ($semAkhir + 1) . ' (Prediksi)';
        }

        return response()->json([
            'labels'       => $labels,
            'historis'     => array_values($data['historis']),
            'prediksi'     => $data['prediksi'],
            'differencing' => $data['differencing'],
            'evaluasi'     => $data['evaluasi'],
            'walkforward'  => $data['walkforward'],
        ]);
    }
}
