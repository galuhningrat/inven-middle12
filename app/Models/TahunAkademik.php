<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{
    protected $table = 'tahun_akademik';
    protected $fillable = [
        'tahun_awal',
        'tahun_akhir',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'status_aktif',
        'smt',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'tahun_masuk', 'id');
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class, 'id_tahun_akademik');
    }
}
