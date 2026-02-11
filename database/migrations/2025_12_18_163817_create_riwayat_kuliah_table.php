<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ✅ DROP EXISTING TABLE
        Schema::dropIfExists('riwayat_kuliah');

        // ✅ CREATE COMPREHENSIVE RIWAYAT KULIAH
        Schema::create('riwayat_kuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');

            // KATEGORI: Formal (Transfer) atau Non-Formal (Organisasi/Magang)
            $table->enum('kategori', ['Formal', 'Non-Formal']);

            // FORMAL: Pendidikan Formal (Transfer/Pindahan)
            $table->string('kampus_asal', 150)->nullable(); // Untuk Formal
            $table->string('prodi_asal', 100)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_keluar')->nullable();
            $table->decimal('ipk_asal', 3, 2)->nullable();
            $table->string('gelar_diperoleh', 50)->nullable(); // Contoh: Sarjana Teknik
            $table->enum('jenis', ['Transfer', 'Pindahan', 'Lanjutan'])->nullable();
            $table->text('alasan_pindah')->nullable();
            $table->string('file_transkrip')->nullable();

            // NON-FORMAL: Organisasi/Magang/Kegiatan
            $table->string('nama_kegiatan', 150)->nullable(); // Untuk Non-Formal
            $table->enum('jenis_kegiatan', ['Organisasi', 'Magang', 'Ekstrakurikuler', 'Sertifikasi', 'Kompetisi'])->nullable();
            $table->string('penyelenggara', 150)->nullable();
            $table->string('posisi_jabatan', 100)->nullable(); // Ketua, Anggota, Intern, dll.
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->text('deskripsi_kegiatan')->nullable();
            $table->string('file_sertifikat')->nullable();

            $table->timestamps();

            $table->foreign('id_mahasiswa')
                ->references('id')->on('mahasiswa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kuliah');
    }
};
