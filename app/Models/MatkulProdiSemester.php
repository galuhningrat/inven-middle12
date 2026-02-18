<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel pivot matkul_prodi_semester.
 *
 * Satu record = "MK X tampil di Prodi Y pada Semester Z".
 *
 * Fleksibilitas:
 *   MK Pancasila → TI  : Semester 1
 *   MK Pancasila → SI  : Semester 4
 *   MK Pancasila → EL  : Semester 2
 *
 * @property int    $id
 * @property int    $id_matkul
 * @property int    $id_prodi
 * @property int    $semester
 * @property string|null $angkatan
 */
class MatkulProdiSemester extends Model
{
    protected $table = 'matkul_prodi_semester';

    protected $fillable = [
        'id_matkul',
        'id_prodi',
        'semester',
        'angkatan',
    ];

    protected $casts = [
        'semester' => 'integer',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    /** Mata kuliah yang dipetakan */
    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'id_matkul');
    }

    /** Prodi tempat MK ini muncul */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    // ============================================================
    // SCOPE HELPERS
    // ============================================================

    /** Filter mapping untuk prodi tertentu */
    public function scopeForProdi($query, int $prodiId)
    {
        return $query->where('id_prodi', $prodiId);
    }

    /** Filter mapping untuk semester tertentu */
    public function scopeForSemester($query, int $semester)
    {
        return $query->where('semester', $semester);
    }

    /** Filter mapping untuk angkatan tertentu (atau null = berlaku semua) */
    public function scopeForAngkatan($query, ?string $angkatan)
    {
        if ($angkatan) {
            return $query->where(function ($q) use ($angkatan) {
                $q->where('angkatan', $angkatan)->orWhereNull('angkatan');
            });
        }
        return $query;
    }
}
