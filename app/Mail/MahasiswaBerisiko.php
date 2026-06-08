<?php
namespace App\Mail;

use App\Models\Mahasiswa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MahasiswaBerisiko extends Mailable
{
    use Queueable, SerializesModels;

    public Mahasiswa $mahasiswa;
    public array $nilaiDE;
    public array $absensiAlpha;
    public float $ipk;
    public int   $totalAlpha;
    public array $kategoriRisiko;
    public string $labelKategori;

    public function __construct(Mahasiswa $mahasiswa)
    {
        $this->mahasiswa = $mahasiswa;

        // Gunakan semester terakhir agar email hanya mencantumkan kondisi terkini
        $semNilai = $mahasiswa->nilais->max('semester') ?? 0;
        $semAlpha = $mahasiswa->absensis->max('semester') ?? 0;

        // Nilai D/E semester terakhir
        $this->nilaiDE = $semNilai > 0
            ? $mahasiswa->nilais
                ->where('semester', $semNilai)
                ->whereIn('grade', ['D', 'E'])
                ->map(fn($n) => [
                    'nama'  => $n->mataKuliah->nama ?? '-',
                    'grade' => $n->grade,
                    'nilai' => round((float) $n->nilai_akhir, 1),
                ])->values()->toArray()
            : [];

        // SEMUA matkul dengan alpha > 0, diurutkan terbesar dulu
        $this->absensiAlpha = $semAlpha > 0
            ? $mahasiswa->absensis
                ->where('semester', $semAlpha)
                ->where('jam_alpha', '>', 0)
                ->sortByDesc('jam_alpha')
                ->map(fn($a) => [
                    'nama'      => $a->mataKuliah->nama ?? '-',
                    'jam_alpha' => (int) $a->jam_alpha,
                ])->values()->toArray()
            : [];

        // IPK sudah support grade B+ dan C+ via hitungIpDariKoleksi()
        $this->ipk = round((float) ($mahasiswa->ipk ?? 0), 2);

        $this->totalAlpha = $semAlpha > 0
            ? (int) $mahasiswa->absensis->where('semester', $semAlpha)->sum('jam_alpha')
            : 0;

        // Kategori risiko sesuai Pedoman Akademik D4 TI Polinema 2022/2023
        $this->kategoriRisiko = $mahasiswa->getKategoriRisiko();
        $this->labelKategori  = $this->buildLabelKategori();
    }

    private function buildLabelKategori(): string
    {
        $labels = [];
        foreach ($this->kategoriRisiko as $k) {
            $labels[] = match($k) {
                'ps'         => 'Putus Studi',
                'sp3'        => 'SP III',
                'sp2'        => 'SP II',
                'sp1'        => 'SP I',
                'nilai_e'    => 'Nilai E',
                'nilai_d'    => 'Nilai D > 3 MK',
                'ips_rendah' => 'IPS < 2.00',
                default      => $k,
            };
        }
        return implode(', ', $labels) ?: 'Berisiko';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Peringatan Akademik [' . $this->labelKategori . '] — ' . $this->mahasiswa->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mahasiswa-berisiko',
        );
    }
}
