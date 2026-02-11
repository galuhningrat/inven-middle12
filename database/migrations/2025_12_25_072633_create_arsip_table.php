<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel.
     */
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 100)->unique();
            $table->string('judul', 200);
            $table->enum('kategori', ['Surat Masuk', 'Surat Keluar', 'SK', 'Dokumen Penting']);
            $table->date('tanggal_surat');
            $table->string('file_path')->nullable(); // Menyimpan path: arsip/namafile.pdf
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};
