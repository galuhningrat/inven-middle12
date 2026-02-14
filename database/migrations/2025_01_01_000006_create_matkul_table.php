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
        Schema::create('matkul', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mk', 15)->unique();
            $table->string('nama_mk', 100);
            $table->string('bobot', length: 1);
            $table->enum('jenis', ['wajib', 'pilihan', 'umum']);
            $table->unsignedBigInteger('id_prodi')->nullable();
            $table->unsignedBigInteger('id_dosen');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_prodi')->references('id')->on('prodi')->onDelete('cascade');
            $table->foreign('id_dosen')->references('id')->on('dosen')->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matkul');
    }
};
