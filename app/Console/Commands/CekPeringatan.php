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
            'kompensasis',
            'kelas',
            'dosenPa',
            'user',
        ])->get();

        $berisiko = 0;
        $terkirim = 0;
        $gagal    = 0;
        $skip     = 0;

        foreach ($mahasiswas as $mhs) {
            if (!$mhs->isBerisiko()) continue;

            $berisiko++;

            $kategori   = $mhs->getKategoriRisiko();
            $semNilai   = $mhs->nilais->max('semester') ?? 0;
            $semAlpha   = $mhs->absensis->max('semester') ?? 0;
            $nilaiDE    = $semNilai > 0 ? $mhs->nilais->where('semester', $semNilai)->whereIn('grade', ['D', 'E']) : collect();
            $totalAlpha = $semAlpha > 0 ? (int) $mhs->absensis->where('semester', $semAlpha)->sum('jam_alpha') : 0;

            $this->newLine();
            $this->error("  ⚠ {$mhs->nama} ({$mhs->nim})");
            $this->line("    → Kategori   : " . implode(', ', $kategori));

            if ($nilaiDE->count() > 0) {
                $grades = $nilaiDE->pluck('grade')->join(', ');
                $this->line("    → Nilai D/E  : {$nilaiDE->count()} matkul ({$grades})");
            }
            if ($totalAlpha >= 18) {
                $this->line("    → Alpha      : {$totalAlpha} jam ⛔ MELEWATI BATAS SP I");
            }

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

                \App\Models\EmailLog::create([
                    'mahasiswa_id'    => $mhs->id,
                    'email_tujuan'    => $email,
                    'nama_mahasiswa'  => $mhs->nama ?? $mhs->user->name,
                    'kelas'           => $mhs->kelas->nama ?? '-',
                    'kategori_risiko' => $kategori,
                    'jumlah_nilai_de' => $nilaiDE->count(),
                    'total_alpha'     => $totalAlpha,
                    'status'          => 'berhasil',
                    'dikirim_oleh'    => null,
                ]);
            } catch (\Exception $e) {
                $gagal++;
                $this->line("    → ❌ Gagal: " . $e->getMessage());
                Log::error("SIAKAD: Gagal kirim ke {$mhs->nama}: " . $e->getMessage());

                \App\Models\EmailLog::create([
                    'mahasiswa_id'    => $mhs->id,
                    'email_tujuan'    => $email,
                    'nama_mahasiswa'  => $mhs->nama ?? $mhs->user->name,
                    'kelas'           => $mhs->kelas->nama ?? '-',
                    'kategori_risiko' => $kategori,
                    'jumlah_nilai_de' => $nilaiDE->count(),
                    'total_alpha'     => $totalAlpha,
                    'status'          => 'gagal',
                    'pesan_error'     => $e->getMessage(),
                    'dikirim_oleh'    => null,
                ]);
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

