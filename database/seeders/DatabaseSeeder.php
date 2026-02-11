<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'id' => 1,
                'nama_role' => 'Super Admin',
                'deskripsi_role' => 'IT Support/Developer dengan kendali teknis penuh. Mengelola user, role permissions, maintenance, backup database, dan konfigurasi global sistem.',
                'created_at' => now()
            ],
            [
                'id' => 2,
                'nama_role' => 'Admin Akademik',
                'deskripsi_role' => 'Biro Administrasi Akademik (BAAK). Mengelola data master akademik, plotting jadwal ruangan & waktu, manajemen status mahasiswa, dan pencetakan transkrip/ijazah.',
                'created_at' => now()
            ],
            [
                'id' => 3,
                'nama_role' => 'Bagian Keuangan',
                'deskripsi_role' => 'Biro Administrasi Keuangan (BAK). Mengelola komponen biaya, generate tagihan, validasi pembayaran, dan dispensasi cicilan.',
                'created_at' => now()
            ],
            [
                'id' => 4,
                'nama_role' => 'Rektor',
                'deskripsi_role' => 'Top Level Management dengan akses monitoring global (seluruh fakultas dan prodi). Dashboard eksekutif untuk pengambilan keputusan strategis.',
                'created_at' => now()
            ],
            [
                'id' => 5,
                'nama_role' => 'Dekan',
                'deskripsi_role' => 'Faculty Level Management. Memantau kinerja akademik seluruh prodi di fakultas, validasi yudisium tingkat fakultas, dan monitoring EDOM fakultas.',
                'created_at' => now()
            ],
            [
                'id' => 6,
                'nama_role' => 'Kaprodi',
                'deskripsi_role' => 'Program Study Level. Menyusun kurikulum prodi, plotting dosen pengampu, approval KRS manual, dan konsultasi akademik mahasiswa.',
                'created_at' => now()
            ],
            [
                'id' => 7,
                'nama_role' => 'Dosen',
                'deskripsi_role' => 'Tenaga pendidik. Mengisi jurnal & absensi, input nilai, validasi KRS mahasiswa bimbingan sebagai Dosen PA.',
                'created_at' => now()
            ],
            [
                'id' => 8,
                'nama_role' => 'Mahasiswa',
                'deskripsi_role' => 'Peserta didik. Melakukan pembayaran, pengisian KRS & revisi, melihat KHS, transkrip, dan tagihan melalui portal mahasiswa.',
                'created_at' => now()
            ],
            [
                'id' => 9,
                'nama_role' => 'Karyawan',
                'deskripsi_role' => 'Staff Umum. Mengelola data kepegawaian non-dosen, surat-menyurat, inventaris kampus, dan absensi karyawan non-akademik.',
                'created_at' => now()
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']], // Jika ID sudah ada
                $role                  // Maka update datanya
            );
        }

        $users = [
            [
                'email' => 'ali@superadmin.com',
                'password' => Hash::make('1234'),
                'nama' => 'Ali Super Admin',
                'id_role' => 1, // Super Admin (Root Access)
                'status' => 'Aktif',
                'img' => 'foto_users/default.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'galuh@superadmin.com',
                'password' => Hash::make('1234'),
                'nama' => 'Galuh Super Admin',
                'id_role' => 1, // Super Admin (Root Access)
                'status' => 'Aktif',
                'img' => 'foto_users/default.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'ali@admin.com',
                'password' => Hash::make('1234'),
                'nama' => 'Ali Administrator',
                'id_role' => 2, // Admin Akademik
                'status' => 'Aktif',
                'img' => 'foto_users/default.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'galuh@admin.com',
                'password' => Hash::make('1234'),
                'nama' => 'Galuh Administrator',
                'id_role' => 2, // Admin Akademik
                'status' => 'Aktif',
                'img' => 'foto_users/default.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $this->call([
            PermissionNilaiSeeder::class,
            PermissionSeeder::class,
            AcademicDataSeeder::class,
            AcademicSystemSeeder::class,
        ]);

        $this->command->info("\n✅ Database Seeding Completed!");
        $this->command->info("   - Roles: 9 roles created");
        $this->command->info("   - Users: 4 default users created");
        $this->command->info("   - Academic Data: Tahun Akademik & Matkul seeded");
        $this->command->info("\n📝 Default Credentials:");
        $this->command->info("   Email: ali@superadmin.com | Password: 1234");
        $this->command->info("   Email: galuh@superadmin.com | Password: 1234");
    }
}
