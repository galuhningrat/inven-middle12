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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->string('nip')->unique();
            $table->string('nik',16)->unique();
            $table->string('no_kk',16)->unique();
            $table->string('npwp',16)->nullable();
            $table->string('tempat_lahir',20);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin',['Laki-laki','Perempuan']);
            $table->enum('agama',['Islam', 'Katolik', 'Protestan', 'Budha', 'Hindu', 'Konghucu'])->default('Islam');
            $table->string('dusun',30);
            $table->string('rt',3);
            $table->string('rw',3);
            $table->string('ds_kel',20);
            $table->string('kec',20);
            $table->string('kab',20);
            $table->string('prov',20);
            $table->string('kode_pos',5);
            $table->string('hp',14);
            $table->enum('marital_status',['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati']);
            $table->enum('status',['Karyawan Tetap', 'Karyawan Tidak Tetap']);
            $table->enum('pend-terakhir',['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3']);
            $table->enum('gol_darah',['A','B','AB','O'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
