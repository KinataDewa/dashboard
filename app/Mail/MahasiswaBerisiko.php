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
    public array $absensiKritis;
    public float $ipk;
    public int   $totalAlpha;

    public function __construct(Mahasiswa $mahasiswa)
    {
        $this->mahasiswa = $mahasiswa;

        $this->nilaiDE = $mahasiswa->nilais
            ->whereIn('grade', ['D', 'E'])
            ->map(fn($n) => [
                'nama'  => $n->mataKuliah->nama ?? '-',
                'grade' => $n->grade,
                'nilai' => round((float)$n->nilai_akhir, 1),
            ])->values()->toArray();

        $this->absensiKritis = $mahasiswa->absensis
            ->where('jam_alpha', '>=', 14)
            ->map(fn($a) => [
                'nama'      => $a->mataKuliah->nama ?? '-',
                'jam_alpha' => (int)$a->jam_alpha,
                'sisa'      => max(0, 18 - (int)$a->jam_alpha),
                'kritis'    => $a->jam_alpha >= 18,
            ])->values()->toArray();

        $this->ipk        = round((float)($mahasiswa->ipk ?? 0), 2);
        $this->totalAlpha = (int)$mahasiswa->absensis->sum('jam_alpha');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '⚠️ Peringatan Akademik — ' . $this->mahasiswa->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.mahasiswa-berisiko',
        );
    }
}
