<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matkul_prodi_semester', function (Blueprint $table) {
            if (!Schema::hasColumn('matkul_prodi_semester', 'angkatan')) {
                $table->string('angkatan', 4)->nullable()->after('semester');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matkul_prodi_semester', function (Blueprint $table) {
            $table->dropColumn('angkatan');
        });
    }
};
