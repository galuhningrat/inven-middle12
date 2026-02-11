<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Krs extends Model
{
    use HasFactory;

    protected $table = 'krs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_mahasiswa',
        'semester',
        'id_rombel',
        'id_jadwal',
        'status',
        'status_kunci',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel', 'id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id');
    }

    public function matkul()
    {
        return $this->hasOneThrough(
            Matkul::class,    // Model Tujuan (Mata Kuliah)
            Jadwal::class,    // Model Perantara (Jadwal)
            'id',             // Foreign key di tabel JADWAL (id jadwal itu sendiri)
            'id',             // Foreign key di tabel MATKUL (id matkul itu sendiri)
            'id_jadwal',      // Local key di tabel KRS (kolom yang Anda punya di gambar)
            'id_matkul'       // Local key di tabel JADWAL yang menghubungkan ke Matkul
        );
    }
}
