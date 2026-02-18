<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel matkul (Master Data Mata Kuliah).
 *
 * PERUBAHAN dari versi lama:
 *   - Kolom `semester` DIHAPUS dari tabel ini.
 *     Semester kini disimpan di pivot `matkul_prodi_semester`
 *     sehingga MK yang sama bisa berada di semester berbeda
 *     pada tiap prodi.
 *   - Relasi `prodis()` (many-to-many via matkul_prodi) DIGANTI
 *     dengan `prodiMappings()` (hasMany via matkul_prodi_semester).
 */
class Matkul extends Model
{
    use HasFactory;

    protected $table    = 'matkul';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'bobot',
        'jenis',
        'id_dosen',
        // semester TIDAK ADA DI SINI — ada di pivot matkul_prodi_semester
    ];

    protected $casts = [
        'bobot' => 'integer',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    /**
     * Semua mapping prodi+semester untuk MK ini.
     *
     * Satu record mapping = "MK ini ada di Prodi X pada Semester Y".
     */
    public function prodiMappings()
    {
        return $this->hasMany(MatkulProdiSemester::class, 'id_matkul');
    }

    /** Dosen pengampu MK ini. */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    // ============================================================
    // HELPER
    // ============================================================

    /** Cek apakah MK ini bertipe Umum. */
    public function isUmum(): bool
    {
        return $this->jenis === 'umum';
    }

    /**
     * Ambil daftar semester MK ini di prodi tertentu.
     * Contoh return: [1, 3] → tampil di semester 1 dan 3 pada prodi itu.
     */
    public function semestersForProdi(int $prodiId): array
    {
        return $this->prodiMappings
            ->where('id_prodi', $prodiId)
            ->pluck('semester')
            ->sort()
            ->values()
            ->toArray();
    }

    // ============================================================
    // SCOPE
    // ============================================================

    /**
     * Scope: MK yang dipetakan ke prodi tertentu
     * (terlepas dari jenisnya: wajib / pilihan / umum).
     *
     * CATATAN: Tidak ada lagi logika khusus `jenis='umum'`.
     * Semua MK — termasuk MK Umum — harus dipetakan secara eksplisit.
     */
    public function scopeForProdi($query, int $prodiId)
    {
        return $query->whereHas(
            'prodiMappings',
            fn($q) => $q->where('id_prodi', $prodiId)
        );
    }

    /**
     * Scope: MK yang dipetakan ke prodi + semester tertentu.
     */
    public function scopeForProdiSemester($query, int $prodiId, int $semester)
    {
        return $query->whereHas(
            'prodiMappings',
            fn($q) => $q->where('id_prodi', $prodiId)->where('semester', $semester)
        );
    }
}
