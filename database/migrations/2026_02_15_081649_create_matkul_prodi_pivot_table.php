<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * ============================================================
     * REFACTORING: Centralized Course Master with Dynamic Semester
     * ============================================================
     * Perubahan:
     * 1. Hapus kolom `semester` dari tabel `matkul`
     * 2. Tambah kolom `semester` ke pivot `matkul_prodi`
     * 3. Migrasi data existing: pindahkan semester dari matkul ke pivot
     */
    public function up(): void
    {
        // ============================================================
        // STEP 1: Tambah kolom semester ke pivot matkul_prodi
        // ============================================================
        Schema::table('matkul_prodi', function (Blueprint $table) {
            $table->tinyInteger('semester')
                  ->unsigned()
                  ->default(1)
                  ->after('id_prodi')
                  ->comment('Semester pengambilan MK dalam konteks prodi ini');

            // Opsional: Tambah kolom angkatan jika perlu spesifik per angkatan
            // $table->string('angkatan', 4)->nullable()->after('semester');
        });

        // ============================================================
        // STEP 2: Migrasi data existing dari matkul.semester → pivot
        // ============================================================
        $existingPivots = DB::table('matkul_prodi')
            ->select('matkul_prodi.*', 'matkul.semester as old_semester')
            ->join('matkul', 'matkul_prodi.id_matkul', '=', 'matkul.id')
            ->get();

        foreach ($existingPivots as $pivot) {
            DB::table('matkul_prodi')
                ->where('id', $pivot->id)
                ->update([
                    'semester' => $pivot->old_semester ?? 1,
                    'updated_at' => now()
                ]);
        }

        // ============================================================
        // STEP 3: Hapus kolom semester dari tabel matkul
        // (Sekarang semester adalah konteks per prodi di pivot)
        // ============================================================
        Schema::table('matkul', function (Blueprint $table) {
            $table->dropColumn('semester');
        });

        // ============================================================
        // STEP 4: Update unique constraint di pivot
        // Sekarang kombinasi (matkul, prodi, semester) harus unik
        // ============================================================
        Schema::table('matkul_prodi', function (Blueprint $table) {
            // Drop constraint lama
            $table->dropUnique(['id_matkul', 'id_prodi']);

            // Tambah constraint baru dengan semester
            $table->unique(['id_matkul', 'id_prodi', 'semester'], 'matkul_prodi_semester_unique');
        });
    }

    /**
     * Rollback: Kembalikan struktur lama
     */
    public function down(): void
    {
        // Kembalikan kolom semester ke matkul
        Schema::table('matkul', function (Blueprint $table) {
            $table->tinyInteger('semester')
                  ->unsigned()
                  ->nullable()
                  ->default(1)
                  ->after('id_dosen');
        });

        // Migrasi data kembali ke matkul (ambil semester tertinggi dari pivot)
        $matkuls = DB::table('matkul')->get();
        foreach ($matkuls as $mk) {
            $maxSemester = DB::table('matkul_prodi')
                ->where('id_matkul', $mk->id)
                ->max('semester');

            if ($maxSemester) {
                DB::table('matkul')
                    ->where('id', $mk->id)
                    ->update(['semester' => $maxSemester]);
            }
        }

        // Drop unique constraint baru
        Schema::table('matkul_prodi', function (Blueprint $table) {
            $table->dropUnique('matkul_prodi_semester_unique');
        });

        // Kembalikan unique constraint lama
        Schema::table('matkul_prodi', function (Blueprint $table) {
            $table->unique(['id_matkul', 'id_prodi']);
        });

        // Drop kolom semester dari pivot
        Schema::table('matkul_prodi', function (Blueprint $table) {
            $table->dropColumn('semester');
        });
    }
};
