<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendidikan_dosen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_dosen');
           // pendidikan dosen
            $table->string('jenjang', 10);
            $table->string('nama_pt', 100);
            $table->string('jurusan', 100)->nullable();
            $table->string('gelar', 50)->nullable();
            $table->string('tahun_lulus', 4);
            $table->string('ijazah', 255);
            $table->timestamps();

            $table->foreign('id_dosen')->references('id')->on('dosen')->onUpdate('cascade')->onDelete('restrict');

        });
    }



    public function down(): void
    {
        Schema::dropIfExists('pendidikan_dosen');
    }
};
