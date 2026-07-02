<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanDpa extends Model
{
    protected $table = 'catatan_dpa';

    protected $fillable = ['mahasiswa_id', 'dosen_id', 'semester', 'catatan'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
