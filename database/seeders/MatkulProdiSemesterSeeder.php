<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * ============================================================
 * Seeder: Mapping MKU (Mata Kuliah Umum) ke Prodi & Semester
 * ============================================================
 * Seeder ini menambahkan entri ke tabel matkul_prodi_semester
 * untuk semua MK bertipe 'umum' yang belum memiliki mapping.
 *
 * Sesuaikan array $mkuSemesterMapping dengan kebutuhan kampus Anda.
 * Format: 'kode_mk' => [prodi_id => semester, ...]
 * ============================================================
 */
class MatkulProdiSemesterSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     *
     * CARA PENGGUNAAN:
     *   php artisan db:seed --class=MatkulProdiSemesterSeeder
     *
     * Atau tambahkan di DatabaseSeeder.php:
     *   $this->call(MatkulProdiSemesterSeeder::class);
     */
    public function run(): void
    {
        // ============================================================
        // LANGKAH 1: Ambil semua prodi yang ada
        // ============================================================
        $allProdi = DB::table('prodi')->get(['id', 'kode_prodi', 'nama_prodi']);

        if ($allProdi->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada data prodi. Seeder dibatalkan.');
            return;
        }

        // ============================================================
        // LANGKAH 2: Ambil semua MK yang belum punya mapping di pivot
        // ============================================================
        $matkulTanpaMapping = DB::table('matkul')
            ->whereNotIn('id', function ($query) {
                $query->select('id_matkul')->from('matkul_prodi_semester');
            })
            ->get(['id', 'kode_mk', 'nama_mk', 'jenis']);

        if ($matkulTanpaMapping->isEmpty()) {
            $this->command->info('✅ Semua mata kuliah sudah memiliki mapping. Tidak ada yang perlu ditambahkan.');
            return;
        }

        $this->command->info("📚 Ditemukan {$matkulTanpaMapping->count()} mata kuliah tanpa mapping:");

        // ============================================================
        // LANGKAH 3: Definisikan mapping MKU ke Prodi & Semester
        //
        // Sesuaikan di sini! Format:
        //   'KODE_MK' => [
        //       'semua'           => N,   // Berlaku untuk SEMUA prodi di semester N
        //       'kode_prodi_xxx'  => N,   // Override untuk prodi tertentu
        //   ]
        //
        // Jika menggunakan 'semua', MK akan dipetakan ke semua prodi
        // yang ada di database pada semester yang ditentukan.
        // ============================================================
        $mkuSemesterConfig = [
            // ---- MKU Standard (berlaku di semua prodi) ----
            'MKU001' => ['semua' => 1],  // Pendidikan Agama         → Semester 1
            'MKU002' => ['semua' => 1],  // Bahasa Indonesia         → Semester 1
            'MKU003' => ['semua' => 1],  // PKN                      → Semester 1
            'MKU004' => ['semua' => 2],  // Pendidikan Pancasila     → Semester 2
            'MKU005' => ['semua' => 2],  // Bahasa Inggris           → Semester 2
            'MKU006' => ['semua' => 3],  // Probabilitas & Statistik → Semester 3
            'MKU007' => ['semua' => 6],  // Etika Profesi            → Semester 6
            'MKU008' => ['semua' => 6],  // Metodologi Penelitian    → Semester 6
            'MKU009' => ['semua' => 7],  // KKP                      → Semester 7
            'MKU010' => ['semua' => 5],  // Kewirausahaan            → Semester 5
        ];

        $inserted = 0;
        $skipped  = 0;
        $now      = now();

        // ============================================================
        // LANGKAH 4: Proses setiap MK tanpa mapping
        // ============================================================
        foreach ($matkulTanpaMapping as $matkul) {
            $this->command->line("  → [{$matkul->jenis}] {$matkul->kode_mk}: {$matkul->nama_mk}");

            // Cek apakah ada konfigurasi untuk MK ini
            if (!isset($mkuSemesterConfig[$matkul->kode_mk])) {
                // Tidak ada konfigurasi → skip, biarkan admin mapping manual
                $this->command->warn("    ⚠️  Tidak ada konfigurasi mapping untuk {$matkul->kode_mk}. Skip.");
                $skipped++;
                continue;
            }

            $config = $mkuSemesterConfig[$matkul->kode_mk];

            // Cek apakah mapping berlaku untuk semua prodi
            if (isset($config['semua'])) {
                $semester = $config['semua'];

                // Petakan ke semua prodi
                foreach ($allProdi as $prodi) {
                    // Cek duplikat
                    $exists = DB::table('matkul_prodi_semester')
                        ->where('id_matkul', $matkul->id)
                        ->where('id_prodi', $prodi->id)
                        ->where('semester', $semester)
                        ->exists();

                    if (!$exists) {
                        DB::table('matkul_prodi_semester')->insert([
                            'id_matkul'  => $matkul->id,
                            'id_prodi'   => $prodi->id,
                            'semester'   => $semester,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $inserted++;
                        $this->command->line("    ✅ Mapped ke [{$prodi->kode_prodi}] Semester {$semester}");
                    }
                }
            } else {
                // Mapping per prodi spesifik
                foreach ($config as $prodiKode => $semester) {
                    $prodi = $allProdi->firstWhere('kode_prodi', $prodiKode);
                    if (!$prodi) {
                        $this->command->warn("    ⚠️  Prodi '{$prodiKode}' tidak ditemukan. Skip.");
                        continue;
                    }

                    $exists = DB::table('matkul_prodi_semester')
                        ->where('id_matkul', $matkul->id)
                        ->where('id_prodi', $prodi->id)
                        ->where('semester', $semester)
                        ->exists();

                    if (!$exists) {
                        DB::table('matkul_prodi_semester')->insert([
                            'id_matkul'  => $matkul->id,
                            'id_prodi'   => $prodi->id,
                            'semester'   => $semester,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                        $inserted++;
                        $this->command->line("    ✅ Mapped ke [{$prodi->kode_prodi}] Semester {$semester}");
                    }
                }
            }
        }

        $this->command->newLine();
        $this->command->info("🎉 Seeder selesai! {$inserted} mapping ditambahkan, {$skipped} MK dilewati.");
    }
}
