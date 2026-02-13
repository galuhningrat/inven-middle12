<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ============================================================
 * BUG FIX: File migrasi sebelumnya bernama "add_semester.php.php"
 * (double extension). Laravel hanya membaca file berekstensi .php,
 * sehingga migrasi ini TIDAK PERNAH dijalankan dan kolom 'semester'
 * tidak ada di tabel matkul.
 *
 * Akibatnya: setiap kali form Tambah Matakuliah disubmit,
 * Laravel/PostgreSQL melempar QueryException:
 *   "column semester does not exist"
 * yang menyebabkan insert selalu gagal.
 *
 * SOLUSI:
 *   1. Hapus file lama: database/migrations/2026_02_12_110109_add_semester.php.php
 *   2. Ganti dengan file ini (nama benar, tanpa double .php)
 *   3. Jalankan: php artisan migrate
 * ============================================================
 */
return new class extends Migration
{
    public function up(): void
    {
        // Guard: jangan error jika kolom sudah ada (misal pernah dibuat manual)
        if (!Schema::hasColumn('matkul', 'semester')) {
            Schema::table('matkul', function (Blueprint $table) {
                $table->tinyInteger('semester')
                      ->unsigned()
                      ->nullable()
                      ->default(1)
                      ->after('id_dosen')
                      ->comment('Semester pengambilan mata kuliah (1–8)');
            });
        }
    }

    public function down(): void
    {
        Schema::table('matkul', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
