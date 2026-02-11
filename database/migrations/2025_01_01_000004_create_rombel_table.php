<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRombelTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('rombel', function (Blueprint $table) {
            $table->id();
            $table->string('kode_rombel', length: 6)->unique();
            $table->string('nama_rombel');
            $table->unsignedBigInteger('tahun_masuk');
            $table->unsignedBigInteger('id_prodi');
            $table->unsignedBigInteger('id_dosen');
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('tahun_masuk')->references('id')->on('tahun_akademik')->onDelete('restrict');
            $table->foreign('id_prodi')->references('id')->on('prodi')->onDelete('set null');
            $table->foreign('id_dosen')->references('id')->on('dosen')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('rombel');
    }
}
