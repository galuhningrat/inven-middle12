<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->string('nip')->unique();

            // ✅ UBAH SEMUA JADI NULLABLE
            $table->string('nik', 16)->nullable()->unique();
            $table->string('no_kk', 16)->nullable()->unique();
            $table->string('nidn', 16)->nullable();
            $table->string('nuptk', 16)->nullable();
            $table->string('npwp', 16)->nullable();
            $table->string('tempat_lahir', 20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('agama', ['Islam', 'Katolik', 'Protestan', 'Budha', 'Hindu', 'Konghucu'])->nullable();
            $table->string('dusun', 30)->nullable();
            $table->string('rt', 3)->nullable();
            $table->string('rw', 3)->nullable();
            $table->string('ds_kel', 20)->nullable();
            $table->string('kec', 20)->nullable();
            $table->string('kab', 20)->nullable();
            $table->string('prov', 20)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('no_hp', 14)->nullable();
            $table->enum('marital_status', ['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])->nullable();
            $table->enum('status', ['Dosen Tetap', 'Dosen Tidak Tetap'])->nullable();
            $table->enum('kewarganegaraan', ['WNI', 'WNA'])->default('WNI');
            $table->enum('gol_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};