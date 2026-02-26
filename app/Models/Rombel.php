<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';

    protected $fillable = [
        'kode_rombel',
        'nama_rombel',
        'tahun_masuk',   // FK → tahun_akademik.id (bukan id_tahun_akademik!)
        'id_prodi',
        'id_dosen',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    /**
     * ✅ Relasi ke TahunAkademik.
     * Kolom FK di tabel rombel: `tahun_masuk` → merujuk ke `tahun_akademik.id`
     */
    public function tahunMasuk()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_masuk', 'id');
    }

    /**
     * Prodi tempat Rombel ini berada.
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    /**
     * Dosen Pembimbing Akademik (DPA) Rombel ini.
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    /**
     * Mahasiswa yang terdaftar di Rombel ini.
     */
    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_rombel');
    }
}
