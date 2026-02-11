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
        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_mahasiswa');
            $table->string('semester', 2);
            $table->unsignedBigInteger('id_rombel');
            $table->unsignedBigInteger('id_jadwal');
            $table->enum('status', ['draft', 'disetujui', 'ditolak'])->default('draft');
            $table->tinyInteger('status_kunci')->default(0); // 0 = Tidak terkunci, 1 = Terkunci
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswa');
            $table->foreign('id_rombel')->references('id')->on('rombel')->onDelete('restrict');
            $table->foreign('id_jadwal')->references('id')->on('jadwal')->onDelete('restrict');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs');
    }
};
