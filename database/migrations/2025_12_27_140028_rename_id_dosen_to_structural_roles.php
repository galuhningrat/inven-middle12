<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. PRODI: Drop FK lama dulu
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropForeign(['id_dosen']);
        });

        // 2. Ubah kolom id_dosen menjadi nullable sementara
        Schema::table('prodi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dosen')->nullable()->change();
        });

        // 3. Set id_dosen yang invalid menjadi NULL sementara
        DB::statement('UPDATE prodi SET id_dosen = NULL WHERE id_dosen NOT IN (SELECT id FROM users)');

        // 4. Rename kolom
        Schema::table('prodi', function (Blueprint $table) {
            $table->renameColumn('id_dosen', 'id_kaprodi');
        });

        // 5. Ubah kembali menjadi NOT NULL
        Schema::table('prodi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kaprodi')->nullable(false)->change();
        });

        // 6. Tambah FK baru ke users
        Schema::table('prodi', function (Blueprint $table) {
            $table->foreign('id_kaprodi')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });

        // 7. FAKULTAS: Drop FK lama (jika ada)
        try {
            Schema::table('fakultas', function (Blueprint $table) {
                $table->dropForeign(['id_dekan']);
            });
        } catch (\Exception $e) {
            // FK mungkin belum ada, skip
        }

        // 8. Ubah kolom id_dekan menjadi nullable sementara
        Schema::table('fakultas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dekan')->nullable()->change();
        });

        // 9. Set id_dekan yang invalid menjadi NULL sementara
        DB::statement('UPDATE fakultas SET id_dekan = NULL WHERE id_dekan NOT IN (SELECT id FROM users)');

        // 10. Ubah kembali menjadi NOT NULL
        Schema::table('fakultas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_dekan')->nullable(false)->change();
        });

        // 11. Tambah FK baru ke users untuk fakultas
        Schema::table('fakultas', function (Blueprint $table) {
            $table->foreign('id_dekan')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    public function down(): void
    {
        // Rollback Prodi
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropForeign(['id_kaprodi']);
        });

        Schema::table('prodi', function (Blueprint $table) {
            $table->renameColumn('id_kaprodi', 'id_dosen');
        });

        Schema::table('prodi', function (Blueprint $table) {
            $table->foreign('id_dosen')
                ->references('id')
                ->on('dosen')
                ->onUpdate('cascade');
        });

        // Rollback Fakultas
        Schema::table('fakultas', function (Blueprint $table) {
            $table->dropForeign(['id_dekan']);
        });
    }
};
