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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_matkul');
            $table->unsignedBigInteger('id_prodi');
            $table->unsignedBigInteger('id_dosen');
            $table->unsignedBigInteger('id_rombel');
            $table->unsignedBigInteger('id_ruangan');
            $table->unsignedBigInteger('tahun_akademik');
            $table->enum('hari', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_matkul')
                ->references('id')->on('matkul')
                ->onUpdate('cascade');

            $table->foreign('id_prodi')
                ->references('id')->on('prodi')
                ->onUpdate('cascade');

            $table->foreign('id_dosen')
                ->references('id')->on('dosen')
                ->onUpdate('cascade');

            $table->foreign('id_rombel')
                ->references('id')->on('rombel')
                ->onUpdate('cascade');

             $table->foreign('tahun_akademik')
             ->references('id')->on('tahun_akademik');

            $table->foreign('id_ruangan')
                ->references('id')->on('ruangan')
                ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
