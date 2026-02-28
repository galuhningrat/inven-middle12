<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel pivot matkul_prodi_semester.
 *
 * Satu record = "MK X tampil di Prodi Y pada Semester Z".
 *
 * Contoh fleksibilitas:
 *   MK Pancasila (MKU) → TI  : Semester 1
 *   MK Pancasila (MKU) → SI  : Semester 4
 *   MK Pancasila (MKU) → EL  : Semester 2
 *
 * Inilah kunci mengapa semester diletakkan di pivot, bukan di matkul.
 *
 * @property int         $id
 * @property int         $id_matkul
 * @property int         $id_prodi
 * @property int|null     $id_rombel
 * @property int         $semester
 * @property string|null $angkatan
 */
class MatkulProdiSemester extends Model
{
    protected $table = 'matkul_prodi_semester';

    protected $fillable = [
        'id_matkul',
        'id_prodi',
        'id_rombel',
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

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
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

    /**
     * Filter mapping untuk angkatan tertentu.
     * null = berlaku untuk semua angkatan.
     */
    public function scopeForAngkatan($query, ?string $angkatan)
    {
        if ($angkatan) {
            return $query->where(function ($q) use ($angkatan) {
                $q->where('angkatan', $angkatan)->orWhereNull('angkatan');
            });
        }
        return $query;
    }

    /**
     * Filter MK berdasarkan jenis (wajib/pilihan/umum) via relasi.
     * Berguna untuk memisahkan MKU dari MK Prodi di kurikulum.
     */
    public function scopeOfJenis($query, string $jenis)
    {
        return $query->whereHas('matkul', fn($q) => $q->where('jenis', $jenis));
    }
}
