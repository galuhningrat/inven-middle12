<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            // Tambah kolom untuk nilai
            $table->enum('nilai_huruf', ['A', 'B', 'C', 'D', 'E'])->nullable()->after('status_kunci');
            $table->decimal('nilai_angka', 3, 2)->nullable()->after('nilai_huruf'); // 0.00 - 4.00

            if (!Schema::hasColumn('krs', 'semester')) {
                $table->integer('semester')->nullable()->after('nilai_angka');
            }
        });
    }

    public function down(): void
    {
        Schema::table('krs', function (Blueprint $table) {
            $table->dropColumn(['nilai_huruf', 'nilai_angka']);
        });
    }
};
