<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // STEP 1: Buat tabel pivot matkul_prodi
        // ============================================================
        Schema::create('matkul_prodi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_matkul');
            $table->unsignedBigInteger('id_prodi');
            $table->timestamps();

            $table->foreign('id_matkul')
                ->references('id')->on('matkul')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_prodi')
                ->references('id')->on('prodi')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Satu MK tidak bisa duplikat di prodi yang sama
            $table->unique(['id_matkul', 'id_prodi']);
        });

        // ============================================================
        // STEP 2: Migrasi data lama dari kolom id_prodi → pivot table
        // (hanya MK yang id_prodi-nya tidak null / bukan MK Umum)
        // ============================================================
        $matkulDenganProdi = DB::table('matkul')
            ->whereNotNull('id_prodi')
            ->select('id', 'id_prodi')
            ->get();

        foreach ($matkulDenganProdi as $mk) {
            DB::table('matkul_prodi')->insertOrIgnore([
                'id_matkul'  => $mk->id,
                'id_prodi'   => $mk->id_prodi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // STEP 3: Hapus kolom id_prodi dari tabel matkul
        // ============================================================
        Schema::table('matkul', function (Blueprint $table) {
            // Drop foreign key dulu sebelum drop kolom
            $table->dropForeign(['id_prodi']);
            $table->dropColumn('id_prodi');
        });
    }

    public function down(): void
    {
        // Kembalikan kolom id_prodi ke tabel matkul
        Schema::table('matkul', function (Blueprint $table) {
            $table->unsignedBigInteger('id_prodi')->nullable()->after('jenis');
            $table->foreign('id_prodi')
                ->references('id')->on('prodi')
                ->onUpdate('cascade');
        });

        // Kembalikan data dari pivot ke kolom id_prodi
        $pivot = DB::table('matkul_prodi')->get();
        foreach ($pivot as $p) {
            DB::table('matkul')
                ->where('id', $p->id_matkul)
                ->update(['id_prodi' => $p->id_prodi]);
        }

        Schema::dropIfExists('matkul_prodi');
    }
};
