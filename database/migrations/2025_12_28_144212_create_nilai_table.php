<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel terkait
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_jadwal'); // Jadwal sudah contain: matkul, dosen, rombel, tahun akademik
            $table->unsignedBigInteger('id_dosen'); // Dosen pengampu

            // Komponen Nilai (sesuai standar akademik)
            $table->decimal('nilai_tugas', 5, 2)->nullable()->default(0); // Max 100
            $table->decimal('nilai_uts', 5, 2)->nullable()->default(0); // Max 100
            $table->decimal('nilai_uas', 5, 2)->nullable()->default(0); // Max 100
            $table->decimal('nilai_praktikum', 5, 2)->nullable()->default(0); // Max 100
            $table->decimal('nilai_kehadiran', 5, 2)->nullable()->default(0); // Max 100

            // Bobot Penilaian (%)
            $table->decimal('bobot_tugas', 5, 2)->default(20); // 20%
            $table->decimal('bobot_uts', 5, 2)->default(30); // 30%
            $table->decimal('bobot_uas', 5, 2)->default(35); // 35%
            $table->decimal('bobot_praktikum', 5, 2)->default(10); // 10%
            $table->decimal('bobot_kehadiran', 5, 2)->default(5); // 5%

            // Nilai Akhir (Otomatis dihitung)
            $table->decimal('nilai_akhir', 5, 2)->nullable()->default(0); // Hasil pembobotan
            $table->enum('nilai_huruf', ['A', 'A-', 'B+', 'B', 'B-', 'C+', 'C', 'D', 'E'])->nullable();
            $table->decimal('nilai_angka', 3, 2)->nullable(); // Untuk IPK (0.00 - 4.00)

            // Status & Metadata
            $table->enum('status', ['draft', 'published', 'final'])->default('draft');
            $table->text('catatan_dosen')->nullable(); // Catatan/komentar dosen
            $table->integer('jumlah_kehadiran')->nullable(); // Total kehadiran
            $table->integer('jumlah_pertemuan')->nullable(); // Total pertemuan

            // Audit Trail
            $table->timestamp('published_at')->nullable(); // Kapan nilai dipublish
            $table->unsignedBigInteger('published_by')->nullable(); // Siapa yang publish
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswa')->onDelete('cascade');
            $table->foreign('id_jadwal')->references('id')->on('jadwal')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id')->on('dosen')->onDelete('cascade');
            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null');

            // Indexes untuk performa
            $table->index(['id_mahasiswa', 'id_jadwal']);
            $table->index('id_dosen');
            $table->index('status');

            // Unique constraint: 1 mahasiswa hanya punya 1 nilai per jadwal
            $table->unique(['id_mahasiswa', 'id_jadwal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
