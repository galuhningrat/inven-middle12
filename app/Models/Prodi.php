<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodi';

    protected $fillable = [
        'kode_prodi',
        'nama_prodi',
        'id_fakultas',
        'id_kaprodi',
        'status_akre',
    ];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'id_fakultas');
    }

    public function kaprodi()
    {
        return $this->belongsTo(User::class, 'id_kaprodi', 'id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'id_prodi');
    }

    // ============================================================
    // RELASI: Many-to-Many ke Matkul (via pivot matkul_prodi)
    // ============================================================
    public function matkulSpesifik()
    {
        return $this->belongsToMany(
            Matkul::class,
            'matkul_prodi',
            'id_prodi',
            'id_matkul'
        )->withTimestamps();
    }

    // ============================================================
    // HELPER: Ambil semua MK untuk prodi ini (spesifik + umum)
    // ============================================================
    public function getAllMatkul()
    {
        return Matkul::forProdi($this->id)->get();
    }
}
