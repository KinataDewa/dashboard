<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Mahasiswa;
use App\Mail\MahasiswaBerisiko;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CekPeringatan extends Command
{
    protected $signature   = 'siakad:cek-peringatan {--dry-run : Tampilkan saja tanpa kirim email}';
    protected $description = 'Cek mahasiswa berisiko dan kirim notifikasi email';

    public function handle(): void
    {
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info('  SIAKAD — Cek Peringatan Akademik');
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        $dryRun = $this->option('dry-run');
        if ($dryRun) {
            $this->warn('  Mode: DRY RUN (email tidak akan dikirim)');
        }

        $mahasiswas = Mahasiswa::with([
            'nilais.mataKuliah',
            'absensis.mataKuliah',
            'kelas',
            'dosenPa',
            'user',
        ])->get();

        $berisiko = 0;
        $terkirim = 0;
        $gagal    = 0;
        $skip     = 0;

        foreach ($mahasiswas as $mhs) {
            $nilaiDE    = $mhs->nilais->whereIn('grade', ['D', 'E']);
            $totalAlpha = (int)$mhs->absensis->sum('jam_alpha');
            $isBerisiko = $nilaiDE->count() > 0 || $totalAlpha >= 14;

            if (!$isBerisiko) continue;

            $berisiko++;

            $this->newLine();
            $this->error("  ⚠ {$mhs->nama} ({$mhs->nim})");

            if ($nilaiDE->count() > 0) {
                $grades = $nilaiDE->pluck('grade')->join(', ');
                $this->line("    → Nilai D/E  : {$nilaiDE->count()} matkul ({$grades})");
            }
            if ($totalAlpha >= 14) {
                $label = $totalAlpha >= 18 ? '⛔ MELEWATI BATAS' : '⚠ waspada';
                $this->line("    → Alpha      : {$totalAlpha} jam {$label}");
            }

            // ── FIX: cek user & email tidak null ──
            if (!$mhs->user) {
                $this->warn("    → Skip: mahasiswa tidak punya akun user.");
                $skip++;
                continue;
            }

            $email = $mhs->user->email ?? null;
            if (!$email) {
                $this->warn("    → Skip: email tidak ditemukan.");
                $skip++;
                continue;
            }

            if ($dryRun) {
                $this->line("    → [DRY RUN] Akan kirim ke: {$email}");
                continue;
            }

            try {
                Mail::to($email)->send(new MahasiswaBerisiko($mhs));
                $terkirim++;
                $this->line("    → ✅ Email terkirim ke: {$email}");
                Log::info("SIAKAD: Email peringatan terkirim ke {$mhs->nama} ({$email})");
            } catch (\Exception $e) {
                $gagal++;
                $this->line("    → ❌ Gagal: " . $e->getMessage());
                Log::error("SIAKAD: Gagal kirim ke {$mhs->nama}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->info("  Selesai! Total berisiko : {$berisiko} mahasiswa");
        if ($skip > 0)    $this->warn("  Di-skip (no email)      : {$skip}");
        if (!$dryRun) {
            $this->info("  Email terkirim          : {$terkirim}");
            if ($gagal > 0) $this->warn("  Email gagal             : {$gagal}");
        }
        $this->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    }
}

