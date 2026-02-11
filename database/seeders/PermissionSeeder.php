<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ========== SUPER ADMIN: FULL ACCESS TO ALL MODULES ==========
        $superAdminModules = [
            'kurikulum',
            'jadwal',
            'ruangan',
            'mahasiswa',
            'dosen',
            'inventaris',
            'arsip',
            'pengguna',
            'tagihan',
            'pembayaran',
            'pegawai_nondosen',
            'krs',
            'nilai',
            'laporan'
        ];

        foreach ($superAdminModules as $module) {
            DB::table('permissions')->updateOrInsert(
                ['id_role' => 1, 'module' => $module],
                [
                    'can_create' => true,
                    'can_read' => true,
                    'can_update' => true,
                    'can_delete' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // ========== ADMIN AKADEMIK (Role 2) ==========
        $adminAkademikPermissions = [
            ['id_role' => 2, 'module' => 'kurikulum', 'can_read' => true],
            ['id_role' => 2, 'module' => 'jadwal', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 2, 'module' => 'ruangan', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 2, 'module' => 'mahasiswa', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 2, 'module' => 'dosen', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 2, 'module' => 'status_mahasiswa', 'can_update' => true],
            ['id_role' => 2, 'module' => 'krs', 'can_read' => true, 'can_update' => true],
        ];

        // ========== KAPRODI (Role 6) ==========
        $kaprodiPermissions = [
            ['id_role' => 6, 'module' => 'kurikulum', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 6, 'module' => 'plotting_dosen', 'can_create' => true, 'can_read' => true, 'can_update' => true],
            ['id_role' => 6, 'module' => 'jadwal', 'can_read' => true],
            ['id_role' => 6, 'module' => 'ruangan', 'can_read' => true],
            ['id_role' => 6, 'module' => 'krs_mahasiswa', 'can_read' => true, 'can_update' => true],
            ['id_role' => 6, 'module' => 'status_mahasiswa', 'can_read' => true],
        ];

        // ========== KARYAWAN (Role 9) ==========
        $karyawanPermissions = [
            ['id_role' => 9, 'module' => 'pegawai_nondosen', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 9, 'module' => 'inventaris', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 9, 'module' => 'arsip', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 9, 'module' => 'mahasiswa', 'can_read' => true],
            ['id_role' => 9, 'module' => 'dosen', 'can_read' => true],
        ];

        // ========== BAGIAN KEUANGAN (Role 3) ==========
        $keuanganPermissions = [
            ['id_role' => 3, 'module' => 'tagihan', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 3, 'module' => 'pembayaran', 'can_create' => true, 'can_read' => true, 'can_update' => true, 'can_delete' => true],
            ['id_role' => 3, 'module' => 'mahasiswa', 'can_read' => true],
        ];

        // Merge & Insert
        $allPermissions = array_merge(
            $adminAkademikPermissions,
            $kaprodiPermissions,
            $karyawanPermissions,
            $keuanganPermissions
        );

        foreach ($allPermissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                [
                    'id_role' => $permission['id_role'],
                    'module' => $permission['module']
                ],
                array_merge($permission, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('✅ Permissions seeded successfully with Super Admin full access!');
    }
}
