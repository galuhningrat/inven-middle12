<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ============================================================
 * CLEANUP: Hapus kolom id_prodi legacy dari tabel matkul
 * ============================================================
 * Setelah refactoring ke matkul_prodi_semester, kolom id_prodi
 * di tabel matkul sudah tidak relevan karena relasi prodi kini
 * ada di pivot matkul_prodi_semester.
 *
 * Tabel matkul seharusnya hanya berisi data MASTER mata kuliah
 * tanpa terikat ke prodi tertentu.
 * ============================================================
 */
return new class extends Migration
{
    public function up(): void
    {
        // Hapus kolom id_prodi hanya jika masih ada
        if (Schema::hasColumn('matkul', 'id_prodi')) {
            Schema::table('matkul', function (Blueprint $table) {
                // Drop FK dulu sebelum drop kolom
                try {
                    $table->dropForeign(['id_prodi']);
                } catch (\Exception $e) {
                    // FK mungkin sudah tidak ada, lanjutkan
                }
                $table->dropColumn('id_prodi');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasColumn('matkul', 'id_prodi')) {
            Schema::table('matkul', function (Blueprint $table) {
                $table->unsignedBigInteger('id_prodi')->nullable()->after('jenis');
                $table->foreign('id_prodi')
                    ->references('id')->on('prodi')
                    ->onDelete('cascade');
            });
        }
    }
};
