<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = ['nama', 'angkatan', 'semester', 'tahun_akademik', 'prodi', 'dosen_pa_id'];

    public function dosenPa()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pa_id');
    }

    public function mahasiswas()
    {
        return $this->hasMany(Mahasiswa::class);
    }

    public function kelasMahasiswas()
    {
        return $this->hasMany(KelasMahasiswa::class);
    }

    public function mahasiswasViaPivot()
    {
        return $this->hasManyThrough(
            Mahasiswa::class,
            KelasMahasiswa::class,
            'kelas_id',
            'id',
            'id',
            'mahasiswa_id'
        );
    }

    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }
}
