<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->string('jenjang', 10)->nullable()->after('nama_prodi');
            $table->string('akreditasi', 10)->nullable()->after('jenjang');
        });
    }

    public function down(): void
    {
        Schema::table('prodi', function (Blueprint $table) {
            $table->dropColumn(['jenjang', 'akreditasi']);
        });
    }
};
