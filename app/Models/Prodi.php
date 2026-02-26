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
        'jenjang',
        'akreditasi',
    ];

    // ============================================================
    // RELASI
    // ============================================================

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

    /**
     * ✅ FIX: Relasi ke Rombel (sebelumnya TIDAK ADA di model ini).
     * Inilah penyebab utama Rombel tidak muncul di halaman Kurikulum.
     *
     * Rombel terikat ke Prodi via kolom `id_prodi` di tabel `rombel`.
     */
    public function rombel()
    {
        return $this->hasMany(Rombel::class, 'id_prodi');
    }

    /**
     * Semua mapping MK+Semester yang dimiliki prodi ini.
     * Gunakan ini untuk menampilkan kurikulum per prodi.
     */
    public function matkulMappings()
    {
        return $this->hasMany(MatkulProdiSemester::class, 'id_prodi');
    }

    // ============================================================
    // HELPER
    // ============================================================

    /**
     * Ambil semua matkul untuk prodi ini (dari pivot).
     * Opsional filter per semester.
     */
    public function getAllMatkul(?int $semester = null)
    {
        $query = Matkul::whereHas(
            'prodiMappings',
            fn($q) => $q->where('id_prodi', $this->id)
                ->when($semester, fn($q2) => $q2->where('semester', $semester))
        )->with(['dosen.user', 'prodiMappings' => fn($q) => $q->where('id_prodi', $this->id)]);

        return $query->get();
    }

    /**
     * Ambil daftar semester yang sudah punya MK di prodi ini.
     */
    public function getAvailableSemesters(): array
    {
        return $this->matkulMappings()
            ->pluck('semester')
            ->unique()
            ->sort()
            ->values()
            ->toArray();
    }
}
