<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model untuk tabel matkul (Master Data Mata Kuliah).
 *
 * Struktur kolom saat ini:
 *   - id, kode_mk, nama_mk, bobot, jenis, id_dosen
 *   - created_at, updated_at
 *
 * NOTE: Kolom `semester` dan `id_prodi` sudah DIHAPUS dari tabel ini.
 * Relasi ke prodi dan info semester kini ada di pivot matkul_prodi_semester.
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
        // TIDAK ADA: semester, id_prodi — keduanya sudah dipindah ke pivot
    ];

    protected $casts = [
        'bobot' => 'integer',
    ];

    // ============================================================
    // RELASI
    // ============================================================

    /**
     * Semua mapping prodi+semester untuk MK ini.
     * Satu record = "MK ini ada di Prodi X pada Semester Y".
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

    /**
     * Cek apakah MK sudah dipetakan ke prodi+semester tertentu.
     */
    public function isMappedTo(int $prodiId, int $semester): bool
    {
        return $this->prodiMappings
            ->where('id_prodi', $prodiId)
            ->where('semester', $semester)
            ->isNotEmpty();
    }

    // ============================================================
    // SCOPE
    // ============================================================

    /**
     * Scope: MK yang dipetakan ke prodi tertentu.
     * Berlaku untuk semua jenis MK (wajib, pilihan, umum).
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
     * Ini adalah scope utama untuk menampilkan kurikulum per semester.
     */
    public function scopeForProdiSemester($query, int $prodiId, int $semester)
    {
        return $query->whereHas(
            'prodiMappings',
            fn($q) => $q->where('id_prodi', $prodiId)->where('semester', $semester)
        );
    }

    /**
     * Scope: Hanya MK yang belum memiliki mapping sama sekali.
     * Berguna untuk menemukan MK "orphan" yang perlu di-mapping.
     */
    public function scopeWithoutMapping($query)
    {
        return $query->whereDoesntHave('prodiMappings');
    }
}
