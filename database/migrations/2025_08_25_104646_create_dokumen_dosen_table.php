<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dosen');
            $table->string('nama', 150);
            $table->string('berkas', 255)->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('ekstensi', 20)->nullable();
            $table->timestamps();

            $table->foreign('id_dosen')->references('id')->on('dosen')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_dosen');
    }
};