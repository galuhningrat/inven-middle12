<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎓 Seeding Mahasiswa...');

        // Data: [id_mahasiswa, id_users, nim, nama]
        $data = [
            ['id' => 1, 'id_users' => 24, 'nim' => '24031010', 'nama' => 'Fakhru Reza'],
            ['id' => 2, 'id_users' => 35, 'nim' => '24031026', 'nama' => 'M. Alfian Nur Rachman'],
            ['id' => 3, 'id_users' => 33, 'nim' => '24031016', 'nama' => 'Jahid Mujadid'],
            ['id' => 4, 'id_users' => 34, 'nim' => '24031017', 'nama' => 'Latif Fajri Mulya'],
            ['id' => 5, 'id_users' => 36, 'nim' => '24031025', 'nama' => 'Muhammad Akira Dwi N'],
            ['id' => 6, 'id_users' => 37, 'nim' => '24031022', 'nama' => 'Muhammad Faris Nuriman'],
            ['id' => 7, 'id_users' => 38, 'nim' => '24031037', 'nama' => 'Refo Hayiwas Sabrianshah'],
            ['id' => 8, 'id_users' => 39, 'nim' => '24031040', 'nama' => 'Rio Rizal Muttaqien'],
            ['id' => 9, 'id_users' => 40, 'nim' => '24031011', 'nama' => 'Mahasiswa 24031011'], // nama belum ada
        ];

        foreach ($data as $d) {
            // Update nama di tabel users
            DB::table('users')->updateOrInsert(
                ['id' => $d['id_users']],
                [
                    'email'        => $d['nim'] . '@mahasiswa.stti.ac.id',
                    'password'     => Hash::make('1234'),
                    'nama'         => $d['nama'],
                    'id_role'      => 8,
                    'status'       => 'Aktif',
                    'status_aktif' => true,
                    'img'          => 'foto_users/default.png',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]
            );

            // Update data mahasiswa
            DB::table('mahasiswa')->updateOrInsert(
                ['id' => $d['id']],
                [
                    'id_users'    => $d['id_users'],
                    'id_prodi'    => 2,
                    'id_rombel'   => 2,
                    'nim'         => $d['nim'],
                    'tahun_masuk' => 5,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }

        $this->command->info('  ✓ ' . count($data) . ' mahasiswa seeded');
        $this->command->info('    - Prodi : Teknik Informatika');
        $this->command->info('    - Rombel: TI24A (Angkatan 2024)');
        $this->command->info('    - NIM 24031011 belum ada nama, update manual jika perlu');
        $this->command->info('    - Password default: 1234');
    }
}
