<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Tambah kolom id_rombel ke tabel matkul_prodi_semester
 *
 * ALASAN:
 * Sebelumnya 1 mapping MK berlaku untuk SEMUA Rombel di suatu Prodi+Semester.
 * Dengan kolom id_rombel, mapping bisa dispesifikasikan per Rombel/Angkatan:
 *   - id_rombel = NULL → berlaku untuk SEMUA Rombel (backward compatible)
 *   - id_rombel = X    → berlaku HANYA untuk Rombel X
 *
 * CARA RUN:
 *   php artisan migrate
 *
 * CARA ROLLBACK:
 *   php artisan migrate:rollback --step=1
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matkul_prodi_semester', function (Blueprint $table) {
            // Tambah kolom id_rombel setelah kolom id_prodi
            // nullable → NULL berarti "berlaku untuk semua rombel"
            $table->unsignedBigInteger('id_rombel')
                ->nullable()
                ->after('id_prodi');

            // Foreign key ke tabel rombel
            // onDelete('set null') → jika Rombel dihapus, mapping tetap ada tapi
            //   kembali menjadi "berlaku untuk semua rombel" (id_rombel = NULL)
            //   LEBIH AMAN daripada cascade yang menghapus data kurikulum.
            $table->foreign('id_rombel')
                ->references('id')
                ->on('rombel')
                ->onDelete('set null');
        });

        // ── Update unique constraint (jika ada) ────────────────────────────
        //
        // Jika tabel sebelumnya punya unique key di (id_matkul, id_prodi, semester),
        // drop dulu lalu buat ulang dengan id_rombel disertakan.
        //
        // Uncomment blok ini jika ada unique constraint yang perlu diupdate:
        //
        // Schema::table('matkul_prodi_semester', function (Blueprint $table) {
        //     // Sesuaikan nama constraint dengan yang ada di database Anda
        //     // Cek dengan: \d matkul_prodi_semester  (PostgreSQL)
        //     $table->dropUnique(['id_matkul', 'id_prodi', 'semester']);
        //
        //     $table->unique(['id_matkul', 'id_prodi', 'semester', 'id_rombel'],
        //                    'matkul_prodi_sem_rombel_unique');
        // });
    }

    public function down(): void
    {
        Schema::table('matkul_prodi_semester', function (Blueprint $table) {
            $table->dropForeign(['id_rombel']);
            $table->dropColumn('id_rombel');
        });

        // Jika Anda uncomment blok unique di atas, uncomment ini juga:
        // Schema::table('matkul_prodi_semester', function (Blueprint $table) {
        //     $table->dropUnique('matkul_prodi_sem_rombel_unique');
        //     $table->unique(['id_matkul', 'id_prodi', 'semester']);
        // });
    }
};
