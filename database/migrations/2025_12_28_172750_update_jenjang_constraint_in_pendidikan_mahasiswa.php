<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE pendidikan_mahasiswa DROP CONSTRAINT IF EXISTS pendidikan_mahasiswa_jenjang_check");

        DB::statement(
            "ALTER TABLE pendidikan_mahasiswa ADD CONSTRAINT pendidikan_mahasiswa_jenjang_check 
            CHECK (jenjang::text IN (
                'SD', 'SMP', 'SMA', 'SMK', 'MA', 'MAK', 'Paket A', 'Paket B', 'Paket C', 
                'Universitas', 'Institut', 'Politeknik', 'Sekolah Tinggi', 'Akademi', 'Akademi Komunitas'
            ))"
        );
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pendidikan_mahasiswa DROP CONSTRAINT IF EXISTS pendidikan_mahasiswa_jenjang_check");
    }
};
