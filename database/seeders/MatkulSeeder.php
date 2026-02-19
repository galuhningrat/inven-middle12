<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    /**
     * ================================================================
     * MatkulSeeder — Seed Seluruh Mata Kuliah + Mapping ke Prodi
     * ================================================================
     * Meng-seed mata kuliah untuk:
     *   - Teknik Elektro  (kode prodi: TE)
     *   - Teknik Informatika (kode prodi: TI)
     *   - Teknik Mesin    (kode prodi: TM)
     *
     * Serta MKU (Mata Kuliah Umum) yang dipakai bersama.
     *
     * Jalankan:
     *   php artisan db:seed --class=MatkulSeeder
     * ================================================================
     */
    class MatkulSeeder extends Seeder
    {
        public function run(): void
        {
            $this->command->info('🚀 Memulai seeding mata kuliah...');

            // =========================================================
            // 1. Ambil ID Dosen Default (dipakai jika belum ada dosen)
            //    Sesuaikan dengan kondisi database Anda.
            // =========================================================
            $defaultDosenId = DB::table('dosen')->value('id');

            if (! $defaultDosenId) {
                $this->command->error('❌ Tidak ada data dosen. Jalankan DosenSeeder terlebih dahulu.');
                return;
            }

            // =========================================================
            // 2. Ambil ID Prodi
            // =========================================================
            $prodi = DB::table('prodi')
                ->whereIn('kode_prodi', ['TE', 'TI', 'TM'])
                ->pluck('id', 'kode_prodi');

            $teId = $prodi['TE'] ?? null;
            $tiId = $prodi['TI'] ?? null;
            $tmId = $prodi['TM'] ?? null;

            if (! $teId || ! $tiId || ! $tmId) {
                $this->command->warn('⚠️  Satu atau lebih prodi (TE/TI/TM) tidak ditemukan.');
                $this->command->warn('   Pastikan kode prodi di database sesuai (TE, TI, TM).');
            }

            $now = now();

            // =========================================================
            // 3. Definisi Mata Kuliah
            //    Format: [kode_mk, nama_mk, bobot (SKS), jenis]
            //    jenis: 'wajib' | 'pilihan' | 'umum'
            // =========================================================

            // --- MKU (berlaku lintas prodi) ---
            $mkuList = [
                ['MKU001', 'Pendidikan Agama',                   2, 'umum'],
                ['MKU002', 'Bahasa Indonesia',                   2, 'umum'],
                ['MKU003', 'Pendidikan Kewarganegaraan',         2, 'umum'],
                ['MKU004', 'Pendidikan Pancasila',               2, 'umum'],
                ['MKU005', 'Bahasa Inggris',                     2, 'umum'],
                ['MKU006', 'Probabilitas dan Statistik',         3, 'umum'],
                ['MKU007', 'Etika Profesi dan Kewirausahaan',    2, 'umum'],
                ['MKU008', 'Metodologi Penelitian',              2, 'umum'],
                ['MKU009', 'Kuliah Kerja Praktik (KKP)',         2, 'umum'],
                ['MKU010', 'Kewirausahaan',                      2, 'umum'],
                ['MKU011', 'Tugas Akhir',                        6, 'umum'],
            ];

            // --- Teknik Elektro ---
            $teMatkul = [
                // Semester 1
                ['TTE101', 'Kalkulus I',              3, 'wajib', 1],
                ['TTE102', 'Fisika I',                3, 'wajib', 1],
                ['TTE103', 'Pengantar Teknik Elektro',3, 'wajib', 1],
                ['TTE104', 'Dasar Pemrograman',       3, 'wajib', 1],
                ['TTE105', 'Gambar Teknik',           2, 'wajib', 1],
                // Semester 2
                ['EB121',  'Instrumentasi dan Pengukuran',         2, 'wajib', 2],
                ['EB122',  'Kalkulus II',                          3, 'wajib', 2],
                ['EB123',  'Kimia Dasar',                          2, 'wajib', 2],
                ['EB124',  'Aljabar Linier dan Matriks',           3, 'wajib', 2],
                ['EB125',  'Fisika II',                            3, 'wajib', 2],
                ['EB126',  'Rangkaian Listrik I (DC)',             3, 'wajib', 2],
                // Semester 3
                ['EB131',  'Teknik Digital',                       2, 'wajib', 3],
                ['EB131P', 'Praktikum Teknik Digital',             1, 'wajib', 3],
                ['EB132',  'Dasar Elektronika',                    3, 'wajib', 3],
                ['EB132P', 'Praktikum Rangkaian Listrik',          1, 'wajib', 3],
                ['EB133',  'Medan Elektromagnetik',                3, 'wajib', 3],
                ['EB134',  'Rangkaian Listrik II (AC)',            3, 'wajib', 3],
                ['EB121P', 'Praktikum Instrumentasi & Pengukuran', 1, 'wajib', 3],
                ['EB135',  'Sinyal dan Sistem',                    3, 'wajib', 3],
                // Semester 4
                ['EB141',  'Sistem Komunikasi',                    3, 'wajib', 4],
                ['EB142',  'Elektronika Analog',                   3, 'wajib', 4],
                ['EB143',  'Mikroprosesor dan Mikrokontroler',     3, 'wajib', 4],
                ['EB144',  'Sistem Numerik',                       3, 'wajib', 4],
                ['EB145',  'Elektronika Digital',                  3, 'wajib', 4],
                ['EB146',  'Teknik Tenaga Listrik',                3, 'wajib', 4],
                ['EB141P', 'Praktik Sistem Komunikasi',            1, 'wajib', 4],
                ['EB146P', 'Praktik Teknik Tenaga Listrik',        1, 'wajib', 4],
                ['EB132P2','Praktik Dasar Elektronika',            1, 'wajib', 4],
                // Semester 5
                ['EK151',  'Sistem Kontrol',                       3, 'wajib', 5],
                ['EL152',  'Mesin Listrik',                        3, 'wajib', 5],
                ['EK253',  'Dasar Teknik Kendali',                 2, 'wajib', 5],
                ['EL254',  'Energi Baru dan Terbarukan',           3, 'wajib', 5],
                ['ET155',  'Pemrosesan Sinyal Digital',            3, 'wajib', 5],
                ['EK356',  'Mekatronika',                          2, 'wajib', 5],
                ['ET145P', 'Praktik Elektronika Digital',          1, 'wajib', 5],
                ['ET155P', 'Praktik Pemrosesan Sinyal Digital',    1, 'wajib', 5],
                // Semester 6
                ['EL361',  'Sistem Distribusi Tenaga Listrik',     3, 'wajib', 6],
                ['EL462',  'Elektronika Daya',                     3, 'wajib', 6],
                ['ET263',  'Radar dan Navigasi',                   2, 'wajib', 6],
                ['ET364',  'Komunikasi Data dan Jaringan',         2, 'wajib', 6],
                ['EK465',  'Embedded System and Robotics',         3, 'wajib', 6],
                ['EB167',  'Manajemen Proyek Teknik',              2, 'wajib', 6],
                ['EK151P', 'Praktik Sistem Kontrol',               1, 'wajib', 6],
                ['EL462P', 'Antena dan Propagasi',                 2, 'wajib', 6],
                ['TTE645', 'Praktik Elektronika Daya',             1, 'wajib', 6],
                // Semester 7
                ['ET471',  'Sistem dan Teknologi Nirkabel',        2, 'wajib', 7],
                ['EB172',  'Capstone Design',                      4, 'wajib', 7],
                // Semester 8 (Pilihan)
                ['MKU010TE','Seminar Tugas Akhir',                2, 'wajib', 8],
                ['ET573',  'Teknik Optimasi',                      3, 'pilihan', 8],
                ['EL574',  'Kendali Mesin Listrik',               3, 'pilihan', 8],
                ['EK575',  'Sistem Adaptif',                       3, 'pilihan', 8],
            ];

            // --- Teknik Informatika ---
            $tiMatkul = [
                // Semester 1
                ['TTI101', 'Algoritma Pemrograman',                4, 'wajib', 1],
                ['TTI102', 'Pengenalan Pemrograman',               4, 'wajib', 1],
                ['TTI124', 'Kalkulus I',                           3, 'wajib', 1],
                ['TTI125', 'Matematika Diskrit',                   3, 'wajib', 1],
                ['TTI143', 'Fisika I',                             3, 'wajib', 1],
                ['TTI136', 'Pengantar Teknologi Informasi',        3, 'wajib', 1],
                // Semester 2
                ['TTI203', 'Struktur Data',                        4, 'wajib', 2],
                ['TTI204', 'Organisasi dan Arsitektur Komputer',   3, 'wajib', 2],
                ['TTI205', 'Basis Data',                           3, 'wajib', 2],
                ['TTI225', 'Aljabar Linier',                       3, 'wajib', 2],
                ['TTI244', 'Kalkulus II',                          3, 'wajib', 2],
                ['TTI245', 'Fisika II',                            3, 'wajib', 2],
                // Semester 3
                ['TTI306', 'Jaringan Komputer',                    4, 'wajib', 3],
                ['TTI307', 'Sistem Operasi',                       3, 'wajib', 3],
                ['TTI322', 'Logika Matematika',                    3, 'wajib', 3],
                ['TTI308', 'Kompleksitas Algoritma',               3, 'wajib', 3],
                // Semester 4
                ['TTI409', 'Rekayasa Perangkat Lunak',             3, 'wajib', 4],
                ['TTI410', 'Kecerdasan Buatan',                    3, 'wajib', 4],
                ['TTI411', 'Pemrograman Berorientasi Objek',       3, 'wajib', 4],
                ['TTI412', 'Human Computer Interaction',           3, 'wajib', 4],
                ['TTI413', 'Pengolahan Citra Digital',             3, 'wajib', 4],
                ['TTI437', 'Embedded System',                      3, 'wajib', 4],
                // Semester 5
                ['TTI514', 'Manajemen Proyek TI',                  3, 'wajib', 5],
                ['TTI515', 'Analisis dan Desain Perangkat Lunak',  3, 'wajib', 5],
                ['TTI516', 'Komputasi Paralel dan Terdistribusi',  3, 'wajib', 5],
                ['TTI517', 'Pemrograman Berorientasi Platform',    4, 'wajib', 5],
                ['TTI518', 'Internet of Things',                   3, 'wajib', 5],
                ['TTI538', 'Data Science',                         3, 'wajib', 5],
                // Semester 6
                ['TTI619', 'Keamanan Data dan Informasi',          3, 'wajib', 6],
                ['TTI620', 'Pembelajaran Mesin',                   3, 'wajib', 6],
                ['TTI621', 'Cloud Computing',                      3, 'wajib', 6],
                ['TTI622', 'Big Data',                             3, 'wajib', 6],
                ['TTI639', 'Pemrograman Web',                      3, 'wajib', 6],
                ['TTI634', 'Etika Profesi',                        2, 'wajib', 6],
                ['TTI5647','KKN',                                  2, 'wajib', 6],
                // Semester 7
                ['TTI723', 'Proyek Perangkat Lunak',               3, 'wajib', 7],
                ['TTI732', 'Kerja Praktik / Magang',               3, 'wajib', 7],
                ['TTI740', 'AI Computing Platform',                3, 'wajib', 7],
                ['TTI741', 'Kriptografi',                          3, 'wajib', 7],
                ['TTI742', 'Wireless Sensor Network',              3, 'wajib', 7],
                // Semester 8
                ['TTI2833','Tugas Akhir',                          6, 'wajib', 8],
                ['TTI2835','Hukum dan Kebijakan Teknologi Informasi', 2, 'wajib', 8],
            ];

            // --- Teknik Mesin ---
            $tmMatkul = [
                // Semester 1
                ['TMI101', 'Matematika I',             4, 'wajib', 1],
                ['TMI105', 'Fisika I',                 4, 'wajib', 1],
                ['TMI107', 'Kimia Dasar',              3, 'wajib', 1],
                ['TM3138', 'Gambar Mesin I',           2, 'wajib', 1],
                // Semester 2
                ['TMI202', 'Matematika II',            4, 'wajib', 2],
                ['TMI206', 'Fisika II',                4, 'wajib', 2],
                ['TMI208', 'Statistika dan Probabilitas', 3, 'wajib', 2],
                ['TMI209', 'K3L',                      2, 'wajib', 2],
                ['TMI221', 'Bahan Material Teknik I',  3, 'wajib', 2],
                ['TMI223', 'Mekanika dan Kekuatan Bahan I', 2, 'wajib', 2],
                ['TMI225', 'Kinematika Dinamika',      2, 'wajib', 2],
                ['TM3233', 'Gambar Mesin II',          2, 'wajib', 2],
                // Semester 3
                ['TMI301', 'Matematika III',           4, 'wajib', 3],
                ['TM2312', 'Bahan Material Teknik II', 3, 'wajib', 3],
                ['TM2314', 'Mekanika dan Kekuatan Bahan II', 3, 'wajib', 3],
                ['TM2316', 'Kinematika Dinamika II',   2, 'wajib', 3],
                ['TM2320', 'Termodinamika I',          3, 'wajib', 3],
                ['TM2322', 'Mekanika Fluida I',        2, 'wajib', 3],
                ['TM2324', 'Perpindahan Kalor dan Massa I', 3, 'wajib', 3],
                ['TM3342', 'Elemen Mesin I',           2, 'wajib', 3],
                // Semester 4
                ['TMI404', 'Matematika IV',            3, 'wajib', 4],
                ['TMI417', 'Getaran Mekanik',          3, 'wajib', 4],
                ['TM2421', 'Termodinamika II',         3, 'wajib', 4],
                ['TM2423', 'Mekanika Fluida II',       3, 'wajib', 4],
                ['TM2425', 'Perpindahan Kalor dan Massa II', 2, 'wajib', 4],
                ['TM2429', 'Pengukuran Teknik / Metrologi', 2, 'wajib', 4],
                ['TM3440', 'Proses Manufaktur I',      3, 'wajib', 4],
                ['TM3442', 'Elemen Mesin II',          2, 'wajib', 4],
                // Semester 5
                ['TM2525', 'Pengukuran Teknik / Metrologi', 2, 'wajib', 5],
                ['TM2526', 'Teknik Tenaga Listrik',    3, 'wajib', 5],
                ['TM3536', 'Metalurgi',                2, 'wajib', 5],
                ['TM3541', 'Proses Manufaktur II',     3, 'wajib', 5],
                ['TM3544', 'Mesin Konversi Energi',    3, 'wajib', 5],
                ['TM3545', 'Sistem Kendali Kontrol',   2, 'wajib', 5],
                ['TM3546', 'Mekatronika',              3, 'wajib', 5],
                // Semester 6
                ['TM2601', 'Hukum Tenaga Kerja dan Industri', 2, 'wajib', 6],
                ['TM3633', 'Sistem Hidrolik & Pneumatik', 2, 'wajib', 6],
                ['TM3634', 'Teknik Pemeliharaan Mesin', 2, 'wajib', 6],
                ['TM3635', 'Teknik Pengelasan',        2, 'wajib', 6],
                ['TM3637', 'Praktik Metalurgi',        3, 'wajib', 6],
                ['TM3647', 'Capstone Design',          3, 'wajib', 6],
                // Semester 7
                ['TM3722', 'CAD/CAM',                  2, 'wajib', 7],
                ['TM3723', 'Praktikum CNC',            2, 'wajib', 7],
                ['TM3728', 'Praktikum Proses Produksi', 4, 'wajib', 7],
                ['TM3736', 'Praktikum Fenomena Dasar Mesin', 1, 'wajib', 7],
                ['TM3737', 'Praktikum Prestasi Mesin', 1, 'wajib', 7],
                ['TM3739', 'Manajemen Proyek',         2, 'wajib', 7],
                ['TM3742', 'Kerja Praktek',            2, 'wajib', 7],
                // Semester 8
                ['TM3845', 'Skripsi / Tugas Akhir',   6, 'wajib', 8],
            ];

            // MKU mapping per prodi & semester (sesuai kurikulum)
            // Format: [kode_mku, prodi_key, semester]
            $mkuMapping = [
                // Teknik Elektro
                ['MKU001', 'TE', 1], ['MKU002', 'TE', 1], ['MKU003', 'TE', 1],
                ['MKU004', 'TE', 2], ['MKU005', 'TE', 2],
                ['MKU006', 'TE', 3],
                ['MKU007', 'TE', 5],
                ['MKU008', 'TE', 7],
                ['MKU009', 'TE', 8], ['MKU011', 'TE', 8],

                // Teknik Informatika
                ['MKU002', 'TI', 2],
                ['MKU004', 'TI', 3], ['MKU003', 'TI', 3], ['MKU001', 'TI', 3], ['MKU005', 'TI', 3],
                ['MKU006', 'TI', 4],
                ['MKU008', 'TI', 5],
                ['MKU011', 'TI', 8],

                // Teknik Mesin
                ['MKU001', 'TM', 1], ['MKU004', 'TM', 1], ['MKU002', 'TM', 1], ['MKU005', 'TM', 1],
                ['MKU010', 'TM', 6], ['MKU008', 'TM', 6],
            ];

            // =========================================================
            // 4. Insert / Update MKU
            // =========================================================
            $this->command->info('📚 Seeding MKU (Mata Kuliah Umum)...');
            foreach ($mkuList as [$kode, $nama, $bobot, $jenis]) {
                DB::table('matkul')->updateOrInsert(
                    ['kode_mk' => $kode],
                    [
                        'nama_mk'    => $nama,
                        'bobot'      => $bobot,
                        'jenis'      => $jenis,
                        'id_dosen'   => $defaultDosenId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            }
            $this->command->info('  ✅ ' . count($mkuList) . ' MKU selesai');

            // =========================================================
            // 5. Insert / Update MK Teknik Elektro + Pivot
            // =========================================================
            if ($teId) {
                $this->command->info('⚡ Seeding Teknik Elektro...');
                foreach ($teMatkul as [$kode, $nama, $bobot, $jenis, $smt]) {
                    $mkId = DB::table('matkul')->updateOrInsert(
                        ['kode_mk' => $kode],
                        ['nama_mk' => $nama, 'bobot' => $bobot, 'jenis' => $jenis,
                        'id_dosen' => $defaultDosenId, 'created_at' => $now, 'updated_at' => $now]
                    );
                    $mkRow = DB::table('matkul')->where('kode_mk', $kode)->first();
                    $this->upsertPivot($mkRow->id, $teId, $smt, $now);
                }
                $this->command->info('  ✅ ' . count($teMatkul) . ' MK Teknik Elektro selesai');
            }

            // =========================================================
            // 6. Insert / Update MK Teknik Informatika + Pivot
            // =========================================================
            if ($tiId) {
                $this->command->info('💻 Seeding Teknik Informatika...');
                foreach ($tiMatkul as [$kode, $nama, $bobot, $jenis, $smt]) {
                    DB::table('matkul')->updateOrInsert(
                        ['kode_mk' => $kode],
                        ['nama_mk' => $nama, 'bobot' => $bobot, 'jenis' => $jenis,
                        'id_dosen' => $defaultDosenId, 'created_at' => $now, 'updated_at' => $now]
                    );
                    $mkRow = DB::table('matkul')->where('kode_mk', $kode)->first();
                    $this->upsertPivot($mkRow->id, $tiId, $smt, $now);
                }
                $this->command->info('  ✅ ' . count($tiMatkul) . ' MK Teknik Informatika selesai');
            }

            // =========================================================
            // 7. Insert / Update MK Teknik Mesin + Pivot
            // =========================================================
            if ($tmId) {
                $this->command->info('⚙️  Seeding Teknik Mesin...');
                foreach ($tmMatkul as [$kode, $nama, $bobot, $jenis, $smt]) {
                    DB::table('matkul')->updateOrInsert(
                        ['kode_mk' => $kode],
                        ['nama_mk' => $nama, 'bobot' => $bobot, 'jenis' => $jenis,
                        'id_dosen' => $defaultDosenId, 'created_at' => $now, 'updated_at' => $now]
                    );
                    $mkRow = DB::table('matkul')->where('kode_mk', $kode)->first();
                    $this->upsertPivot($mkRow->id, $tmId, $smt, $now);
                }
                $this->command->info('  ✅ ' . count($tmMatkul) . ' MK Teknik Mesin selesai');
            }

            // =========================================================
            // 8. Mapping MKU ke Prodi & Semester
            // =========================================================
            $this->command->info('🔗 Mapping MKU ke prodi & semester...');
            $prodiMap = ['TE' => $teId, 'TI' => $tiId, 'TM' => $tmId];
            $mkuInserted = 0;

            foreach ($mkuMapping as [$kodeMku, $prodiKey, $smt]) {
                $pid = $prodiMap[$prodiKey] ?? null;
                if (! $pid) continue;

                $mkRow = DB::table('matkul')->where('kode_mk', $kodeMku)->first();
                if (! $mkRow) continue;

                $exists = DB::table('matkul_prodi_semester')
                    ->where('id_matkul', $mkRow->id)
                    ->where('id_prodi', $pid)
                    ->where('semester', $smt)
                    ->exists();

                if (! $exists) {
                    DB::table('matkul_prodi_semester')->insert([
                        'id_matkul'  => $mkRow->id,
                        'id_prodi'   => $pid,
                        'semester'   => $smt,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                    $mkuInserted++;
                }
            }
            $this->command->info("  ✅ {$mkuInserted} mapping MKU ditambahkan");

            $this->command->newLine();
            $this->command->info('🎉 MatkulSeeder selesai!');
            $this->command->info('   Jalankan: php artisan db:seed --class=MatkulSeeder');
        }

        /**
        * Insert ke matkul_prodi_semester jika belum ada.
        */
        private function upsertPivot(int $matkulId, int $prodiId, int $semester, $now): void
        {
            $exists = DB::table('matkul_prodi_semester')
                ->where('id_matkul', $matkulId)
                ->where('id_prodi', $prodiId)
                ->where('semester', $semester)
                ->exists();

            if (! $exists) {
                DB::table('matkul_prodi_semester')->insert([
                    'id_matkul'  => $matkulId,
                    'id_prodi'   => $prodiId,
                    'semester'   => $semester,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }
