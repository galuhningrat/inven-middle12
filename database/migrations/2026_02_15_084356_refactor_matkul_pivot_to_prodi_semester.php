<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * ============================================================
 * REFACTOR: matkul_prodi + matkul.semester → matkul_prodi_semester
 *
 * MASALAH LAMA:
 *   - Kolom `semester` di tabel `matkul` bersifat global.
 *     MK "Pancasila" selalu Semester 1 di semua prodi.
 *   - MK Umum tidak bisa dipetakan ke semester tertentu per prodi.
 *
 * SOLUSI BARU:
 *   - Kolom `semester` dipindahkan ke tabel pivot `matkul_prodi_semester`.
 *   - Setiap MK (Wajib, Pilihan, Umum) WAJIB dipetakan ke kombinasi
 *     (prodi, semester) yang spesifik.
 *   - MK "Pancasila" bisa → TI: Semester 1, Elektro: Semester 2, SI: Semester 4.
 *
 * MIGRASI DATA OTOMATIS:
 *   - Data dari `matkul_prodi` + `matkul.semester` dipindah ke pivot baru.
 * ============================================================
 */
return new class extends Migration
{
    public function up(): void
    {
        // ============================================================
        // STEP 1: Buat tabel pivot baru dengan kolom semester
        // ============================================================
        Schema::create('matkul_prodi_semester', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_matkul');
            $table->unsignedBigInteger('id_prodi');
            $table->tinyInteger('semester')->unsigned()
                  ->comment('Semester pengambilan di prodi ini (1–14)');
            $table->string('angkatan', 4)->nullable()
                  ->comment('Opsional: spesifik angkatan tertentu, null = berlaku semua');
            $table->timestamps();

            $table->foreign('id_matkul')
                ->references('id')->on('matkul')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('id_prodi')
                ->references('id')->on('prodi')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // Satu MK tidak bisa muncul 2x di prodi & semester yang sama
            $table->unique(['id_matkul', 'id_prodi', 'semester'], 'uq_matkul_prodi_semester');
        });

        // ============================================================
        // STEP 2: Migrasi data lama (matkul_prodi + matkul.semester)
        //         ke tabel pivot baru
        // ============================================================
        if (Schema::hasTable('matkul_prodi')) {
            $oldRows = DB::table('matkul_prodi')
                ->join('matkul', 'matkul_prodi.id_matkul', '=', 'matkul.id')
                ->select(
                    'matkul_prodi.id_matkul',
                    'matkul_prodi.id_prodi',
                    DB::raw('COALESCE(matkul.semester, 1) AS semester')
                )
                ->get();

            foreach ($oldRows as $row) {
                DB::table('matkul_prodi_semester')->insertOrIgnore([
                    'id_matkul'  => $row->id_matkul,
                    'id_prodi'   => $row->id_prodi,
                    'semester'   => (int) $row->semester,
                    'angkatan'   => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ============================================================
        // STEP 3: Hapus tabel pivot lama
        // ============================================================
        Schema::dropIfExists('matkul_prodi');

        // ============================================================
        // STEP 4: Hapus kolom semester dari tabel matkul
        //         (semester kini ada di pivot, bukan di matkul)
        // ============================================================
        if (Schema::hasColumn('matkul', 'semester')) {
            Schema::table('matkul', function (Blueprint $table) {
                $table->dropColumn('semester');
            });
        }
    }

    public function down(): void
    {
        // ============================================================
        // ROLLBACK: Kembalikan ke struktur lama
        // ============================================================

        // 1. Tambah kembali kolom semester ke matkul
        if (!Schema::hasColumn('matkul', 'semester')) {
            Schema::table('matkul', function (Blueprint $table) {
                $table->tinyInteger('semester')->unsigned()->nullable()->default(1)
                      ->after('id_dosen');
            });
        }

        // 2. Buat ulang pivot lama
        Schema::create('matkul_prodi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_matkul');
            $table->unsignedBigInteger('id_prodi');
            $table->timestamps();

            $table->foreign('id_matkul')->references('id')->on('matkul')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('id_prodi')->references('id')->on('prodi')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['id_matkul', 'id_prodi']);
        });

        // 3. Kembalikan data dari pivot baru → pivot lama + semester di matkul
        $newRows = DB::table('matkul_prodi_semester')->get();
        foreach ($newRows as $row) {
            DB::table('matkul_prodi')->insertOrIgnore([
                'id_matkul'  => $row->id_matkul,
                'id_prodi'   => $row->id_prodi,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // Kembalikan semester ke matkul (nilai terakhir menang)
            DB::table('matkul')
                ->where('id', $row->id_matkul)
                ->update(['semester' => $row->semester]);
        }

        // 4. Hapus pivot baru
        Schema::dropIfExists('matkul_prodi_semester');
    }
};
