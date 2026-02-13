<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom semester ke tabel matkul.
     * Semester digunakan untuk mengklasifikasikan mata kuliah
     * per program studi dan per semester.
     */
    public function up(): void
    {
        Schema::table('matkul', function (Blueprint $table) {
            $table->tinyInteger('semester')
                  ->unsigned()
                  ->nullable()
                  ->default(1)
                  ->after('id_dosen')
                  ->comment('Semester pengambilan mata kuliah (1 - 8)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matkul', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
