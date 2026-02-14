<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $table = 'matkul';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'bobot',
        'jenis',
        'id_dosen',
        'semester',
        // id_prodi DIHAPUS — sekarang pakai pivot matkul_prodi
    ];

    protected $casts = [
        'semester' => 'integer',
        'bobot'    => 'integer',
    ];

    // ============================================================
    // RELASI: Many-to-Many ke Prodi (via pivot matkul_prodi)
    // ============================================================
    public function prodis()
    {
        return $this->belongsToMany(
            Prodi::class,
            'matkul_prodi',  // pivot table
            'id_matkul',     // FK di pivot → matkul
            'id_prodi'       // FK di pivot → prodi
        )->withTimestamps();
    }

    // ============================================================
    // RELASI: Dosen pengampu
    // ============================================================
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    // ============================================================
    // HELPER: Cek apakah MK ini termasuk MK Umum
    // ============================================================
    public function isUmum(): bool
    {
        return $this->jenis === 'umum';
    }

    // ============================================================
    // SCOPE: MK yang tampil di prodi tertentu
    // (MK spesifik prodi tersebut + semua MK Umum)
    // ============================================================
    public function scopeForProdi($query, int $prodiId)
    {
        return $query->where(function ($q) use ($prodiId) {
            // MK spesifik prodi ini
            $q->whereHas('prodis', fn($p) => $p->where('prodi.id', $prodiId))
              // ATAU MK Umum (berlaku di semua prodi)
              ->orWhere('jenis', 'umum');
        });
    }
}
