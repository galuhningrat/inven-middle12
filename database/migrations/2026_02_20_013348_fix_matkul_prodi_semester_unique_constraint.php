<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Fix unique constraint pada tabel matkul_prodi_semester.
 *
 * Masalah:
 *   Constraint lama: UNIQUE(id_matkul, id_prodi)
 *   Ini mencegah MK yang sama muncul di 2 semester berbeda pada prodi yang sama,
 *   padahal arsitektur pivot justru dirancang untuk itu.
 *
 * Solusi:
 *   Ganti menjadi: UNIQUE(id_matkul, id_prodi, semester)
 *   Sehingga kombinasi MK + Prodi + Semester harus unik, bukan hanya MK + Prodi.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matkul_prodi_semester', function (Blueprint $table) {
            // Hapus constraint lama (hanya id_matkul + id_prodi)
            $table->dropUnique(['id_matkul', 'id_prodi']);

            // Tambah constraint baru yang benar (id_matkul + id_prodi + semester)
            $table->unique(['id_matkul', 'id_prodi', 'semester'], 'matkul_prodi_semester_unique');
        });
    }

    public function down(): void
    {
        Schema::table('matkul_prodi_semester', function (Blueprint $table) {
            // Kembalikan ke constraint lama
            $table->dropUnique('matkul_prodi_semester_unique');
            $table->unique(['id_matkul', 'id_prodi']);
        });
    }
};
