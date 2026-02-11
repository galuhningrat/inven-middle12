<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'nama_role' => 'Admin',
                'deskripsi_role' => 'Administrator sistem',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'nama_role' => 'Dosen',
                'deskripsi_role' => 'Akun Dosen',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'nama_role' => 'Karyawan',
                'deskripsi_role' => 'Akun Karyawan',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'nama_role' => 'Mahasiswa',
                'deskripsi_role' => 'Akun Mahasiswa',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
