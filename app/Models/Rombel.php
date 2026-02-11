<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';

    protected $fillable = [
        'kode_rombel',
        'nama_rombel',
        'tahun_masuk',
        'id_prodi',
        'id_dosen',
    ];

    // Relasi ke Tahun Akademik
    public function tahunMasuk()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_masuk');
    }

    // Relasi ke Prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_rombel');
    }
}
