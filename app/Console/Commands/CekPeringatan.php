<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mahasiswa;

class CekPeringatan extends Command
{
    protected $signature   = 'siakad:cek-peringatan';
    protected $description = 'Cek mahasiswa dengan nilai D/E atau absensi ≥18 jam alpha';

    public function handle(): void
    {
        $this->info('🔍 Mengecek mahasiswa berisiko...');

        $mahasiswas = Mahasiswa::with([
            'nilais.mataKuliah',
            'absensis.mataKuliah',
            'user',
            'kelas',
        ])->where('status', 'aktif')->get();

        $totalBerisiko = 0;

        foreach ($mahasiswas as $mhs) {
            $peringatan = [];

            // Cek nilai D/E semester aktif
            $semesterAktif = $mhs->kelas->semester ?? 6;
            $nilaiDE = $mhs->nilais
                ->where('semester', $semesterAktif)
                ->whereIn('grade', ['D', 'E']);

            foreach ($nilaiDE as $n) {
                $peringatan[] = "Nilai {$n->grade} pada {$n->mataKuliah->nama}";
            }

            // Cek absensi >= 18 jam alpha
            $absensiKritis = $mhs->absensis
                ->where('semester', $semesterAktif)
                ->where('jam_alpha', '>=', 18);

            foreach ($absensiKritis as $a) {
                $peringatan[] = "Alpha {$a->jam_alpha} jam pada {$a->mataKuliah->nama}";
            }

            if (!empty($peringatan)) {
                $totalBerisiko++;
                $this->warn("⚠ {$mhs->nama} ({$mhs->nim}):");
                foreach ($peringatan as $p) {
                    $this->line("   → {$p}");
                }
            }
        }

        $this->newLine();
        $this->info("✅ Selesai! Ditemukan {$totalBerisiko} mahasiswa berisiko dari {$mahasiswas->count()} mahasiswa aktif.");
    }
}
