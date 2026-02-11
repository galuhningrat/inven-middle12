<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionNilaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ========== SUPER ADMIN: FULL ACCESS ==========
        DB::table('permissions')->updateOrInsert(
            ['id_role' => 1, 'module' => 'nilai'],
            [
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // ========== ADMIN AKADEMIK: READ ONLY (untuk monitoring) ==========
        DB::table('permissions')->updateOrInsert(
            ['id_role' => 2, 'module' => 'nilai'],
            [
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // ========== DOSEN: FULL CRUD (hanya untuk mata kuliah yang diampunya) ==========
        DB::table('permissions')->updateOrInsert(
            ['id_role' => 7, 'module' => 'nilai'],
            [
                'can_create' => true,
                'can_read' => true,
                'can_update' => true,
                'can_delete' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // ========== KAPRODI: READ ONLY (untuk monitoring prodi) ==========
        DB::table('permissions')->updateOrInsert(
            ['id_role' => 6, 'module' => 'nilai'],
            [
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        // ========== MAHASISWA: READ ONLY (melihat nilai sendiri) ==========
        DB::table('permissions')->updateOrInsert(
            ['id_role' => 8, 'module' => 'nilai'],
            [
                'can_create' => false,
                'can_read' => true,
                'can_update' => false,
                'can_delete' => false,
                'created_at' => now(),
                'updated_at' => now()
            ]
        );

        $this->command->info('✅ Permission untuk modul Nilai berhasil di-seed!');
        $this->command->info('   - Super Admin: Full Access');
        $this->command->info('   - Admin Akademik: Read Only');
        $this->command->info('   - Dosen: Full CRUD (own classes only)');
        $this->command->info('   - Kaprodi: Read Only (monitoring)');
        $this->command->info('   - Mahasiswa: Read Only (own grades)');
    }
}
