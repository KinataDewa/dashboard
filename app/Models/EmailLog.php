<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'email_tujuan',
        'nama_mahasiswa',
        'kelas',
        'kategori_risiko',
        'jumlah_nilai_de',
        'total_alpha',
        'status',
        'pesan_error',
        'dikirim_oleh',
    ];

    protected $casts = [
        'kategori_risiko' => 'array',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function pengirim()
    {
        return $this->belongsTo(User::class, 'dikirim_oleh');
    }
}
