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
        // 1. TABEL PENDIDIKAN MAHASISWA
        Schema::create('pendidikan_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->enum('jenjang', [
                'SD',
                'SMP',
                'SMA',
                'SMK',
                'MA',
                'MAK',
                'Paket A',
                'Paket B',
                'Paket C',
                'Universitas',
                'Institut',
                'Politeknik',
                'Sekolah Tinggi',
                'Akademi',
                'Akademi Komunitas'
            ]);
            $table->string('nama_sekolah', 150);
            $table->string('jurusan', 100)->nullable();
            $table->text('alamat_sekolah')->nullable();
            $table->string('nisn', 10)->nullable();
            $table->year('tahun_lulus');
            $table->string('no_ijazah', 50)->nullable();
            $table->string('file_ijazah')->nullable();
            $table->timestamps();

            $table->foreign('id_mahasiswa')
                ->references('id')->on('mahasiswa')
                ->onDelete('cascade');
        });

        // 2. TABEL ORANG TUA MAHASISWA
        Schema::create('orangtua_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa')->unique();

            // Data Ayah
            $table->string('nama_ayah', 100)->nullable();
            $table->string('tempat_lahir_ayah', 50)->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah', 50)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('penghasilan_ayah', 100)->nullable();
            $table->string('hp_ayah', 14)->nullable();
            $table->text('alamat_ayah')->nullable();

            // Data Ibu
            $table->string('nama_ibu', 100)->nullable();
            $table->string('tempat_lahir_ibu', 50)->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu', 50)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('penghasilan_ibu', 100)->nullable();
            $table->string('hp_ibu', 14)->nullable();
            $table->text('alamat_ibu')->nullable();

            // Data Wali
            $table->string('nama_wali', 100)->nullable();
            $table->string('tempat_lahir_wali', 50)->nullable();
            $table->date('tanggal_lahir_wali')->nullable();
            $table->string('pendidikan_wali', 50)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->string('penghasilan_wali', 100)->nullable();
            $table->string('hp_wali', 14)->nullable();
            $table->text('alamat_wali')->nullable();

            $table->timestamps();

            $table->foreign('id_mahasiswa')
                ->references('id')->on('mahasiswa')
                ->onDelete('cascade');
        });

        // 3. TABEL RIWAYAT KULIAH
        Schema::create('riwayat_kuliah', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('kampus_asal', 150);
            $table->string('prodi_asal', 100);
            $table->year('tahun_masuk');
            $table->year('tahun_keluar');
            $table->enum('jenis', ['Transfer', 'Pindahan', 'Lanjutan']);
            $table->text('alasan')->nullable();
            $table->string('file_transkrip')->nullable();
            $table->timestamps();

            $table->foreign('id_mahasiswa')
                ->references('id')->on('mahasiswa')
                ->onDelete('cascade');
        });

        // 4. TABEL PEMBAYARAN MAHASISWA
        Schema::create('pembayaran_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->unsignedBigInteger('id_tahun_akademik');
            $table->string('jenis_pembayaran', 50);
            $table->string('semester', 20)->nullable();
            $table->decimal('jumlah_tagihan', 15, 2)->default(0);
            $table->decimal('jumlah_dibayar', 15, 2)->default(0);
            $table->decimal('sisa_tagihan', 15, 2)->default(0);
            $table->date('tanggal_jatuh_tempo');
            $table->date('tanggal_bayar')->nullable();
            $table->enum('status', ['Belum Bayar', 'Cicilan', 'Lunas'])->default('Belum Bayar');
            $table->string('bukti_bayar')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_mahasiswa')
                ->references('id')->on('mahasiswa')
                ->onDelete('cascade');

            $table->foreign('id_tahun_akademik')
                ->references('id')->on('tahun_akademik')
                ->onDelete('restrict');
        });

        // 5. TABEL DOKUMEN MAHASISWA
        Schema::create('dokumen_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('nama_dokumen', 150);
            $table->enum('jenis_dokumen', [
                'Ijazah',
                'Transkrip',
                'KTP',
                'KK',
                'Akta Lahir',
                'Foto',
                'Sertifikat',
                'Surat Keterangan',
                'Lainnya'
            ]);
            $table->string('file_path');
            $table->bigInteger('ukuran_file')->nullable();
            $table->string('ekstensi', 10)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('id_mahasiswa')
                ->references('id')->on('mahasiswa')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_mahasiswa');
        Schema::dropIfExists('pembayaran_mahasiswa');
        Schema::dropIfExists('riwayat_kuliah');
        Schema::dropIfExists('orangtua_mahasiswa');
        Schema::dropIfExists('pendidikan_mahasiswa');
    }
};
