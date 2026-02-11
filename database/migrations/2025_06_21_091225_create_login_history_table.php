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
        Schema::create('login_history', function (Blueprint $table) {
            $table->id('id_history');
            $table->foreignId('id_users')->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('id_sessions')->nullable();
            $table->dateTime('login_time');
            $table->dateTime('logout_time')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['Sukses', 'Gagal'])->default('Sukses');
            $table->timestamps();

            // Relasi opsional ke sessions
            $table->foreign('id_sessions')->references('id')->on('sessions')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('login_history');
    }
};
