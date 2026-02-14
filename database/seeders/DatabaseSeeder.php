<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Konteks: Tanggal 14 Februari 2026, Tahun Ajaran 2025/2026 Genap
     * Kampus berdiri 2024, memiliki 2 angkatan (2024 & 2025)
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting Database Seeding...');
        $this->command->info('📅 Context: 14 Februari 2026 - TA 2025/2026 Genap');
        $this->command->newLine();

        // Urutan seeding yang benar (mengikuti foreign key dependencies)
        $this->seedRoles();
        $this->seedUsers();
        $this->seedFakultas();
        $this->seedProdi();
        $this->seedDosen();
        $this->seedTahunAkademik();
        $this->seedRuangan();
        $this->seedMataKuliah();
        $this->seedRombel();
        $this->seedJadwalKuliah();

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('📊 Default password for all users: 1234');

        // Call permission seeders
        $this->call([
            PermissionNilaiSeeder::class,
            PermissionSeeder::class,
        ]);
    }

    /**
     * Seed roles table
     */
    private function seedRoles(): void
    {
        $this->command->info('📋 Seeding Roles...');

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
            DB::table('roles')->updateOrInsert(['id' => $role['id']], $role);
        }

        $this->command->info('  ✓ ' . count($roles) . ' roles seeded');
    }

    /**
     * Seed users table (Management, Dosen, Staff)
     */
    private function seedUsers(): void
    {
        $this->command->info('👥 Seeding Users...');

        $users = [
            // Super Admin
            ['id' => 23, 'email' => 'galuh@superadmin.com', 'nama' => 'Galuh Ningrat', 'id_role' => 1],

            // Rektor
            ['id' => 25, 'email' => 'msugiharto@rektor.com', 'nama' => 'Muhammad Sugiarto, S.E., M.M.', 'id_role' => 4],

            // Dekan
            ['id' => 1, 'email' => 'dekan.teknik@stti.ac.id', 'nama' => 'Dr. Dekan Teknik', 'id_role' => 5],

            // Kaprodi
            ['id' => 2, 'email' => 'kaprodi.te@stti.ac.id', 'nama' => 'Suharno, S.T., M.T.', 'id_role' => 6],
            ['id' => 3, 'email' => 'kaprodi.ti@stti.ac.id', 'nama' => 'Bima Azis Kusuma, S.T., M.T.', 'id_role' => 6],
            ['id' => 4, 'email' => 'kaprodi.tm@stti.ac.id', 'nama' => 'Elya Heryana, S.ST., M.T.', 'id_role' => 6],
            ['id' => 5, 'email' => 'kaprodi.kb@stti.ac.id', 'nama' => 'Kaprodi Kebidanan', 'id_role' => 6],

            // Dosen
            ['id' => 6, 'email' => 'zaky.dosen@stti.ac.id', 'nama' => 'Zaky Mubarok, S.Farm.', 'id_role' => 7],
            ['id' => 7, 'email' => 'andre.dosen@stti.ac.id', 'nama' => 'Andre Septian, S.Kom., M.Kom.', 'id_role' => 7],
            ['id' => 8, 'email' => 'ali.dosen@stti.ac.id', 'nama' => 'Fathi Nurdien Ali Rahman, S.Pd., M.Eng.', 'id_role' => 7],
            ['id' => 9, 'email' => 'yogi.dosen@stti.ac.id', 'nama' => 'Yogi Adi Nugraha, S.T., M.T.', 'id_role' => 7],
            ['id' => 10, 'email' => 'harry.dosen@stti.ac.id', 'nama' => 'Harry Darmawan, S.Kom.', 'id_role' => 7],
            ['id' => 11, 'email' => 'qais.dosen@stti.ac.id', 'nama' => 'Qa\'is Ghaziyudin, S.Pd., M.Hum.', 'id_role' => 7],
            ['id' => 12, 'email' => 'sandra.dosen@stti.ac.id', 'nama' => 'Sandra, S.Kom., M.T.', 'id_role' => 7],
            ['id' => 14, 'email' => 'afif.dosen@stti.ac.id', 'nama' => 'Afif Yusuf, S.T., M.T.', 'id_role' => 7],
            ['id' => 15, 'email' => 'adep.dosen@stti.ac.id', 'nama' => 'Ade Paturohman, S.T., M.T.', 'id_role' => 7],
            ['id' => 16, 'email' => 'fadil.dosen@stti.ac.id', 'nama' => 'Muhammad Fadhil Suwarman, S.T., M.T.', 'id_role' => 7],
            ['id' => 17, 'email' => 'ryan.dosen@stti.ac.id', 'nama' => 'Ryan Wibowo Dwijaksono, S.T.', 'id_role' => 7],
            ['id' => 18, 'email' => 'siswo.dosen@stti.ac.id', 'nama' => 'Siswo Teguh Adinugraha, S.Kom.', 'id_role' => 7],
            ['id' => 19, 'email' => 'iqbal.dosen@stti.ac.id', 'nama' => 'Iqbal Najmul Achyar, S.T.', 'id_role' => 7],
            ['id' => 20, 'email' => 'rizal.dosen@stti.ac.id', 'nama' => 'Khaorul Rizal, S.Pd.', 'id_role' => 7],
            ['id' => 26, 'email' => 'gulbudin.dosen@stti.ac.id', 'nama' => 'Gulbudin Hekmatiar, S.T., M.T.', 'id_role' => 7],
            ['id' => 29, 'email' => 'ani.dosen@stti.ac.id', 'nama' => 'Ani Cahyani, S.Pd., M.Pd.', 'id_role' => 7],
            ['id' => 30, 'email' => 'saeful.dosen@stti.ac.id', 'nama' => 'Saeful Rohman, S.Si., M.Pd.', 'id_role' => 7],
            ['id' => 31, 'email' => 'ishaq.dosen@stti.ac.id', 'nama' => 'Ishaq, S.T.', 'id_role' => 7],
            ['id' => 32, 'email' => 'abdul.dosen@stti.ac.id', 'nama' => 'Abdul Hasanudin, S.T.', 'id_role' => 7],
            ['id' => 41, 'email' => 'ader.dosen@stti.ac.id', 'nama' => 'Ade Riadi, S.E.', 'id_role' => 7],
            ['id' => 42, 'email' => 'suharno.dosen@stti.ac.id', 'nama' => 'Suharno, S.T., M.T.', 'id_role' => 7],
            ['id' => 43, 'email' => 'elya.dosen@stti.ac.id', 'nama' => 'Elya Heryana, S.ST., M.T.', 'id_role' => 7],
            ['id' => 44, 'email' => 'bima.dosen@stti.ac.id', 'nama' => 'Bima Azis Kusuma, S.T., M.T.', 'id_role' => 7],
            ['id' => 45, 'email' => 'dewin.dosen@stti.ac.id', 'nama' => 'Dewin Amsari Nasution, S.T., M.T.', 'id_role' => 7],
            ['id' => 46, 'email' => 'syahidah.dosen@stti.ac.id', 'nama' => 'Syahidah Ayun, S.Si., M.Kom.', 'id_role' => 7],

            // Staff
            ['id' => 13, 'email' => 'ade.admin@stti.ac.id', 'nama' => 'Ade Riadi, S.E.', 'id_role' => 2],
            ['id' => 27, 'email' => 'nazilah.keuangan@stti.ac.id', 'nama' => 'Rodlotun Nazilah, A.Md.Keb.', 'id_role' => 3],
            ['id' => 28, 'email' => 'utami.admin@stti.ac.id', 'nama' => 'Utami Alifah Sukur, A.Md.Kes.', 'id_role' => 2],
        ];

        foreach ($users as $userData) {
            DB::table('users')->updateOrInsert(
                ['id' => $userData['id']],
                [
                    'email' => $userData['email'],
                    'password' => Hash::make('1234'),
                    'nama' => $userData['nama'],
                    'id_role' => $userData['id_role'],
                    'status' => 'Aktif',
                    'img' => 'foto_users/default.png',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'status_aktif' => true
                ]
            );
        }

        $this->command->info('  ✓ ' . count($users) . ' users seeded');
    }

    /**
     * Seed fakultas table
     */
    private function seedFakultas(): void
    {
        $this->command->info('🏛️  Seeding Fakultas...');

        $fakultas = [
            [
                'id' => 1,
                'kode_fakultas' => 'T',
                'nama_fakultas' => 'Teknik',
                'id_dekan' => 1, // Dr. Dekan Teknik
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($fakultas as $fak) {
            DB::table('fakultas')->updateOrInsert(['id' => $fak['id']], $fak);
        }

        $this->command->info('  ✓ ' . count($fakultas) . ' fakultas seeded');
    }

    /**
     * Seed prodi table
     */
    private function seedProdi(): void
    {
        $this->command->info('🎓 Seeding Program Studi...');

        $prodi = [
            [
                'id' => 1,
                'kode_prodi' => 'TE',
                'id_fakultas' => 1,
                'nama_prodi' => 'Teknik Elektro',
                'id_kaprodi' => 2, // Suharno, S.T., M.T.
                'status_akre' => 'Unggul',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'kode_prodi' => 'TI',
                'id_fakultas' => 1,
                'nama_prodi' => 'Teknik Informatika',
                'id_kaprodi' => 3, // Bima Azis Kusuma, S.T., M.T.
                'status_akre' => 'Unggul',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'kode_prodi' => 'TM',
                'id_fakultas' => 1,
                'nama_prodi' => 'Teknik Mesin',
                'id_kaprodi' => 4, // Elya Heryana, S.ST., M.T.
                'status_akre' => 'Unggul',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'kode_prodi' => 'KB',
                'id_fakultas' => 1,
                'nama_prodi' => 'Kebidanan',
                'id_kaprodi' => 5, // Kaprodi Kebidanan
                'status_akre' => 'Unggul',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($prodi as $p) {
            DB::table('prodi')->updateOrInsert(['id' => $p['id']], $p);
        }

        $this->command->info('  ✓ ' . count($prodi) . ' prodi seeded');
    }

    /**
     * Seed dosen table
     */
    private function seedDosen(): void
    {
        $this->command->info('👨‍🏫 Seeding Dosen...');

        $dosen = [
            ['id' => 5, 'id_users' => 6, 'nip' => '1990000001'],
            ['id' => 6, 'id_users' => 7, 'nip' => '1990000002'],
            ['id' => 7, 'id_users' => 8, 'nip' => '1990000003'],
            ['id' => 8, 'id_users' => 9, 'nip' => '1990000004'],
            ['id' => 9, 'id_users' => 10, 'nip' => '1990000005'],
            ['id' => 10, 'id_users' => 11, 'nip' => '1990000006'],
            ['id' => 11, 'id_users' => 12, 'nip' => '1990000007'],
            ['id' => 13, 'id_users' => 14, 'nip' => '1990000009'],
            ['id' => 14, 'id_users' => 15, 'nip' => '1990000010'],
            ['id' => 15, 'id_users' => 16, 'nip' => '1990000011'],
            ['id' => 16, 'id_users' => 17, 'nip' => '1990000012'],
            ['id' => 17, 'id_users' => 18, 'nip' => '1990000013'],
            ['id' => 18, 'id_users' => 19, 'nip' => '1990000014'],
            ['id' => 19, 'id_users' => 20, 'nip' => '1990000015'],
            ['id' => 20, 'id_users' => 31, 'nip' => '198005122005031001'],
            ['id' => 21, 'id_users' => 26, 'nip' => '198512202010012015'],
            ['id' => 22, 'id_users' => 29, 'nip' => '197808152003121004'],
            ['id' => 23, 'id_users' => 30, 'nip' => '199202252019031022'],
            ['id' => 24, 'id_users' => 32, 'nip' => '198211052009041003'],
            ['id' => 25, 'id_users' => 41, 'nip' => '199003142018121012'],
            ['id' => 26, 'id_users' => 42, 'nip' => '198709212014031007'],
            ['id' => 27, 'id_users' => 43, 'nip' => '199306122020102005'],
            ['id' => 28, 'id_users' => 44, 'nip' => '199501302022032018'],
            ['id' => 29, 'id_users' => 45, 'nip' => '198709212014031001'],
            ['id' => 30, 'id_users' => 46, 'nip' => '199108152020122009'],
        ];

        foreach ($dosen as $d) {
            DB::table('dosen')->updateOrInsert(
                ['id' => $d['id']],
                [
                    'id_users' => $d['id_users'],
                    'nip' => $d['nip'],
                    'status' => 'Dosen Tetap',
                    'kewarganegaraan' => 'WNI',
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        $this->command->info('  ✓ ' . count($dosen) . ' dosen seeded');
    }

    /**
     * Seed tahun_akademik table
     */
    private function seedTahunAkademik(): void
    {
        $this->command->info('📅 Seeding Tahun Akademik...');

        $tahunAkademik = [
            [
                'id' => 1,
                'tahun_awal' => '2024',
                'tahun_akhir' => '2025',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2025-01-31',
                'status_aktif' => false,
            ],
            [
                'id' => 2,
                'tahun_awal' => '2024',
                'tahun_akhir' => '2025',
                'semester' => 'Genap',
                'tanggal_mulai' => '2025-02-01',
                'tanggal_selesai' => '2025-06-30',
                'status_aktif' => false,
            ],
            [
                'id' => 3,
                'tahun_awal' => '2025',
                'tahun_akhir' => '2026',
                'semester' => 'Ganjil',
                'tanggal_mulai' => '2025-09-01',
                'tanggal_selesai' => '2026-01-31',
                'status_aktif' => false,
            ],
            [
                'id' => 4,
                'tahun_awal' => '2025',
                'tahun_akhir' => '2026',
                'semester' => 'Genap',
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-06-30',
                'status_aktif' => true, // Aktif saat ini (14 Feb 2026)
            ],
        ];

        foreach ($tahunAkademik as $ta) {
            DB::table('tahun_akademik')->updateOrInsert(
                ['id' => $ta['id']],
                array_merge($ta, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('  ✓ ' . count($tahunAkademik) . ' tahun akademik seeded');
        $this->command->info('  ℹ️  Aktif: 2025/2026 Genap');
    }

    /**
     * Seed ruangan table
     */
    private function seedRuangan(): void
    {
        $this->command->info('🏫 Seeding Ruangan...');

        $ruangan = [
            ['id' => 1, 'kode_ruang' => 'KLS-001', 'nama_ruang' => 'Ruang Kuliah 001', 'kapasitas' => 20],
            ['id' => 2, 'kode_ruang' => 'KLS-002', 'nama_ruang' => 'Ruang Kuliah 002', 'kapasitas' => 20],
            ['id' => 3, 'kode_ruang' => 'KLS-003', 'nama_ruang' => 'Ruang Kuliah 003', 'kapasitas' => 25],
            ['id' => 4, 'kode_ruang' => 'LAB1', 'nama_ruang' => 'Lab Komputer 1', 'kapasitas' => 25],
            ['id' => 5, 'kode_ruang' => 'LAB2', 'nama_ruang' => 'Lab Elektronika', 'kapasitas' => 25],
        ];

        foreach ($ruangan as $r) {
            DB::table('ruangan')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'kode_ruang' => $r['kode_ruang'],
                    'nama_ruang' => $r['nama_ruang'],
                    'kapasitas' => $r['kapasitas'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        $this->command->info('  ✓ ' . count($ruangan) . ' ruangan seeded');
    }

    /**
     * Seed mata_kuliah table
     * Format: [F][PP][S][NN]
     * F=Fakultas (T/K/M), PP=Prodi, S=Semester, NN=Urut
     */
    private function seedMataKuliah(): void
    {
        $this->command->info('📚 Seeding Mata Kuliah...');

        // Panggil helper methods untuk setiap kurikulum
        $mkUmum = $this->getMKUmum();
        $mkTE = $this->getMKTeknikElektro();
        $mkTI = $this->getMKTeknikInformatika();
        $mkTM = $this->getMKTeknikMesin();

        $allMK = array_merge($mkUmum, $mkTE, $mkTI, $mkTM);

        foreach ($allMK as $mk) {
            DB::table('matkul')->updateOrInsert(
                ['kode_mk' => $mk['kode_mk']],
                array_merge($mk, [
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('  ✓ ' . count($allMK) . ' mata kuliah seeded');
        $this->command->info('    - MK Umum: ' . count($mkUmum));
        $this->command->info('    - Teknik Elektro: ' . count($mkTE));
        $this->command->info('    - Teknik Informatika: ' . count($mkTI));
        $this->command->info('    - Teknik Mesin: ' . count($mkTM));
    }

    /**
     * Get MK Umum (MKDU) - berlaku untuk semua prodi
     */
    private function getMKUmum(): array
    {
        return [
            ['kode_mk' => 'MKU001', 'nama_mk' => 'Pendidikan Agama', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 10],
            ['kode_mk' => 'MKU002', 'nama_mk' => 'Bahasa Indonesia', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 10],
            ['kode_mk' => 'MKU003', 'nama_mk' => 'Pendidikan Kewarganegaraan', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 10],
            ['kode_mk' => 'MKU004', 'nama_mk' => 'Pendidikan Pancasila', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 10],
            ['kode_mk' => 'MKU005', 'nama_mk' => 'Bahasa Inggris', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 10],
            ['kode_mk' => 'MKU006', 'nama_mk' => 'Probabilitas dan Statistik', 'bobot' => 3, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 22],
            ['kode_mk' => 'MKU007', 'nama_mk' => 'Etika Profesi dan Kewirausahaan', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 25],
            ['kode_mk' => 'MKU008', 'nama_mk' => 'Metodologi Penelitian', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 28],
            ['kode_mk' => 'MKU009', 'nama_mk' => 'Kuliah Kerja Praktik (KKP)', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 28],
            ['kode_mk' => 'MKU010', 'nama_mk' => 'Kewirausahaan', 'bobot' => 2, 'semester' => 0, 'jenis' => 'umum', 'id_prodi' => null, 'id_dosen' => 25],
        ];
    }

    /**
     * Get MK Teknik Elektro
     * Format: TTE[S][NN]
     */
    private function getMKTeknikElektro(): array
    {
        return [
            // Semester 1
            ['kode_mk' => 'TTE101', 'nama_mk' => 'Kalkulus I', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 23],
            ['kode_mk' => 'TTE102', 'nama_mk' => 'Fisika I', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 23],
            ['kode_mk' => 'TTE103', 'nama_mk' => 'Pengantar Teknik Elektro', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE104', 'nama_mk' => 'Dasar Pemrograman', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 6],
            ['kode_mk' => 'TTE105', 'nama_mk' => 'Gambar Teknik', 'bobot' => 2, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 20],

            // Semester 2
            ['kode_mk' => 'TTE121', 'nama_mk' => 'Instrumentasi dan Pengukuran', 'bobot' => 2, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE122', 'nama_mk' => 'Kalkulus II', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 23],
            ['kode_mk' => 'TTE123', 'nama_mk' => 'Kimia Dasar', 'bobot' => 2, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 5],
            ['kode_mk' => 'TTE124', 'nama_mk' => 'Aljabar Linier dan Matriks', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 30],
            ['kode_mk' => 'TTE125', 'nama_mk' => 'Fisika II', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 23],
            ['kode_mk' => 'TTE126', 'nama_mk' => 'Rangkaian Listrik I (DC)', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],

            // Semester 3
            ['kode_mk' => 'TTE131', 'nama_mk' => 'Teknik Digital', 'bobot' => 2, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE132', 'nama_mk' => 'Dasar Elektronika', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE133', 'nama_mk' => 'Medan Elektromagnetik', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE134', 'nama_mk' => 'Rangkaian Listrik II (AC)', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE135', 'nama_mk' => 'Sinyal dan Sistem', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],

            // Semester 4
            ['kode_mk' => 'TTE141', 'nama_mk' => 'Sistem Komunikasi', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE142', 'nama_mk' => 'Elektronika Analog', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE143', 'nama_mk' => 'Mikroprosesor dan Mikrokontroler', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE144', 'nama_mk' => 'Sistem Numerik', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE145', 'nama_mk' => 'Elektronika Digital', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
            ['kode_mk' => 'TTE146', 'nama_mk' => 'Teknik Tenaga Listrik', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 1, 'id_dosen' => 26],
        ];
    }

    /**
     * Get MK Teknik Informatika
     * Format: TTI[S][NN]
     */
    private function getMKTeknikInformatika(): array
    {
        return [
            // Semester 1
            ['kode_mk' => 'TTI101', 'nama_mk' => 'Algoritma Pemrograman', 'bobot' => 4, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI102', 'nama_mk' => 'Pengenalan Pemrograman', 'bobot' => 4, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 17],
            ['kode_mk' => 'TTI124', 'nama_mk' => 'Kalkulus I', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 23],
            ['kode_mk' => 'TTI125', 'nama_mk' => 'Matematika Diskrit', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 30],
            ['kode_mk' => 'TTI143', 'nama_mk' => 'Fisika I', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 23],
            ['kode_mk' => 'TTI136', 'nama_mk' => 'Pengantar Teknologi Informasi', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],

            // Semester 2
            ['kode_mk' => 'TTI203', 'nama_mk' => 'Struktur Data', 'bobot' => 4, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 18],
            ['kode_mk' => 'TTI204', 'nama_mk' => 'Organisasi dan Arsitektur Komputer', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 6],
            ['kode_mk' => 'TTI205', 'nama_mk' => 'Basis Data', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 7],
            ['kode_mk' => 'TTI225', 'nama_mk' => 'Aljabar Linier', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 30],
            ['kode_mk' => 'TTI244', 'nama_mk' => 'Kalkulus II', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 19],
            ['kode_mk' => 'TTI245', 'nama_mk' => 'Fisika II', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 23],

            // Semester 3
            ['kode_mk' => 'TTI306', 'nama_mk' => 'Jaringan Komputer', 'bobot' => 4, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI307', 'nama_mk' => 'Sistem Operasi', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 17],
            ['kode_mk' => 'TTI322', 'nama_mk' => 'Logika Matematika', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 30],
            ['kode_mk' => 'TTI308', 'nama_mk' => 'Kompleksitas Algoritma', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],

            // Semester 4
            ['kode_mk' => 'TTI409', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 7],
            ['kode_mk' => 'TTI410', 'nama_mk' => 'Kecerdasan Buatan', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 18],
            ['kode_mk' => 'TTI411', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI412', 'nama_mk' => 'Human Computer Interaction', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 9],
            ['kode_mk' => 'TTI413', 'nama_mk' => 'Pengolahan Citra Digital', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 6],
            ['kode_mk' => 'TTI437', 'nama_mk' => 'Embedded System', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 17],

            // Semester 5
            ['kode_mk' => 'TTI514', 'nama_mk' => 'Manajemen Proyek TI', 'bobot' => 3, 'semester' => 5, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI515', 'nama_mk' => 'Analisis dan Desain Perangkat Lunak', 'bobot' => 3, 'semester' => 5, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 7],
            ['kode_mk' => 'TTI516', 'nama_mk' => 'Komputasi Paralel dan Terdistribusi', 'bobot' => 3, 'semester' => 5, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 6],
            ['kode_mk' => 'TTI517', 'nama_mk' => 'Pemrograman Berorientasi Platform', 'bobot' => 4, 'semester' => 5, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 17],
            ['kode_mk' => 'TTI518', 'nama_mk' => 'Internet of Things', 'bobot' => 3, 'semester' => 5, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI538', 'nama_mk' => 'Data Science', 'bobot' => 3, 'semester' => 5, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],

            // Semester 6
            ['kode_mk' => 'TTI619', 'nama_mk' => 'Keamanan Data dan Informasi', 'bobot' => 3, 'semester' => 6, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI620', 'nama_mk' => 'Pembelajaran Mesin', 'bobot' => 3, 'semester' => 6, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI621', 'nama_mk' => 'Cloud Computing', 'bobot' => 3, 'semester' => 6, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 6],
            ['kode_mk' => 'TTI622', 'nama_mk' => 'Big Data', 'bobot' => 3, 'semester' => 6, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI639', 'nama_mk' => 'Pemrograman Web', 'bobot' => 3, 'semester' => 6, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 17],
            ['kode_mk' => 'TTI634', 'nama_mk' => 'Etika Profesi', 'bobot' => 2, 'semester' => 6, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],

            // Semester 7
            ['kode_mk' => 'TTI723', 'nama_mk' => 'Proyek Perangkat Lunak', 'bobot' => 3, 'semester' => 7, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 7],
            ['kode_mk' => 'TTI740', 'nama_mk' => 'AI Computing Platform', 'bobot' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI741', 'nama_mk' => 'Kriptografi', 'bobot' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI742', 'nama_mk' => 'Wireless Sensor Network', 'bobot' => 3, 'semester' => 7, 'jenis' => 'pilihan', 'id_prodi' => 2, 'id_dosen' => 28],

            // Semester 8
            ['kode_mk' => 'TTI833', 'nama_mk' => 'Tugas Akhir', 'bobot' => 6, 'semester' => 8, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
            ['kode_mk' => 'TTI835', 'nama_mk' => 'Hukum dan Kebijakan Teknologi Informasi', 'bobot' => 2, 'semester' => 8, 'jenis' => 'wajib', 'id_prodi' => 2, 'id_dosen' => 28],
        ];
    }

    /**
     * Get MK Teknik Mesin
     * Format: TTM[S][NN] atau TMI[S][NN]
     */
    private function getMKTeknikMesin(): array
    {
        return [
            // Semester 1
            ['kode_mk' => 'TMI101', 'nama_mk' => 'Matematika I', 'bobot' => 4, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 23],
            ['kode_mk' => 'TMI105', 'nama_mk' => 'Fisika I', 'bobot' => 4, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 23],
            ['kode_mk' => 'TMI107', 'nama_mk' => 'Kimia Dasar', 'bobot' => 3, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 5],
            ['kode_mk' => 'TTM138', 'nama_mk' => 'Gambar Mesin I', 'bobot' => 2, 'semester' => 1, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],

            // Semester 2
            ['kode_mk' => 'TMI202', 'nama_mk' => 'Matematika II', 'bobot' => 4, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 23],
            ['kode_mk' => 'TMI206', 'nama_mk' => 'Fisika II', 'bobot' => 4, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 23],
            ['kode_mk' => 'TMI208', 'nama_mk' => 'Statistika dan Probabilitas', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 22],
            ['kode_mk' => 'TMI209', 'nama_mk' => 'K3L', 'bobot' => 2, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TMI221', 'nama_mk' => 'Bahan Material Teknik I', 'bobot' => 3, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TMI223', 'nama_mk' => 'Mekanika dan Kekuatan Bahan I', 'bobot' => 2, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TMI225', 'nama_mk' => 'Kinematika Dinamika', 'bobot' => 2, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM233', 'nama_mk' => 'Gambar Mesin II', 'bobot' => 2, 'semester' => 2, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],

            // Semester 3
            ['kode_mk' => 'TMI301', 'nama_mk' => 'Matematika III', 'bobot' => 4, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 23],
            ['kode_mk' => 'TTM312', 'nama_mk' => 'Bahan Material Teknik II', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM314', 'nama_mk' => 'Mekanika dan Kekuatan Bahan II', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM316', 'nama_mk' => 'Kinematika Dinamika II', 'bobot' => 2, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM320', 'nama_mk' => 'Termodinamika I', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM322', 'nama_mk' => 'Mekanika Fluida I', 'bobot' => 2, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM324', 'nama_mk' => 'Perpindahan Kalor dan Massa I', 'bobot' => 3, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM342', 'nama_mk' => 'Elemen Mesin I', 'bobot' => 2, 'semester' => 3, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],

            // Semester 4
            ['kode_mk' => 'TMI404', 'nama_mk' => 'Matematika IV', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 23],
            ['kode_mk' => 'TMI417', 'nama_mk' => 'Getaran Mekanik', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM421', 'nama_mk' => 'Termodinamika II', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM423', 'nama_mk' => 'Mekanika Fluida II', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM425', 'nama_mk' => 'Perpindahan Kalor dan Massa II', 'bobot' => 2, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM429', 'nama_mk' => 'Pengukuran Teknik / Metrologi', 'bobot' => 2, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM440', 'nama_mk' => 'Proses Manufaktur I', 'bobot' => 3, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
            ['kode_mk' => 'TTM442', 'nama_mk' => 'Elemen Mesin II', 'bobot' => 2, 'semester' => 4, 'jenis' => 'wajib', 'id_prodi' => 3, 'id_dosen' => 27],
        ];
    }

    /**
     * Seed rombel table
     * Konteks: Angkatan 2024 (semester 4) & 2025 (semester 2)
     */
    private function seedRombel(): void
    {
        $this->command->info('👨‍🎓 Seeding Rombel...');

        $rombel = [
            // Angkatan 2024 - Sekarang Semester 4
            ['id' => 1, 'kode_rombel' => 'TE24', 'nama_rombel' => 'Teknik Elektro 2024', 'tahun_masuk' => 1, 'id_prodi' => 1, 'id_dosen' => 26],
            ['id' => 2, 'kode_rombel' => 'TI24A', 'nama_rombel' => 'Teknik Informatika 2024 (Laki-Laki)', 'tahun_masuk' => 1, 'id_prodi' => 2, 'id_dosen' => 16],
            ['id' => 5, 'kode_rombel' => 'TI24B', 'nama_rombel' => 'Teknik Informatika 2024 (Perempuan)', 'tahun_masuk' => 1, 'id_prodi' => 2, 'id_dosen' => 16],
            ['id' => 3, 'kode_rombel' => 'TM24', 'nama_rombel' => 'Teknik Mesin 2024', 'tahun_masuk' => 1, 'id_prodi' => 3, 'id_dosen' => 27],

            // Angkatan 2025 - Sekarang Semester 2
            ['id' => 4, 'kode_rombel' => 'TE25', 'nama_rombel' => 'Teknik Elektro 2025', 'tahun_masuk' => 3, 'id_prodi' => 1, 'id_dosen' => 26],
            ['id' => 8, 'kode_rombel' => 'TI25A', 'nama_rombel' => 'Teknik Informatika 2025 (Laki-Laki)', 'tahun_masuk' => 3, 'id_prodi' => 2, 'id_dosen' => 17],
            ['id' => 9, 'kode_rombel' => 'TI25B', 'nama_rombel' => 'Teknik Informatika 2025 (Perempuan)', 'tahun_masuk' => 3, 'id_prodi' => 2, 'id_dosen' => 17],
            ['id' => 6, 'kode_rombel' => 'TM25', 'nama_rombel' => 'Teknik Mesin 2025', 'tahun_masuk' => 3, 'id_prodi' => 3, 'id_dosen' => 27],

            // Kebidanan - Belum ada mahasiswa
            ['id' => 7, 'kode_rombel' => 'KB?', 'nama_rombel' => 'Kebidanan 202?', 'tahun_masuk' => 1, 'id_prodi' => 4, 'id_dosen' => 5],
        ];

        foreach ($rombel as $r) {
            DB::table('rombel')->updateOrInsert(
                ['id' => $r['id']],
                [
                    'kode_rombel' => $r['kode_rombel'],
                    'nama_rombel' => $r['nama_rombel'],
                    'tahun_masuk' => $r['tahun_masuk'],
                    'id_prodi' => $r['id_prodi'],
                    'id_dosen' => $r['id_dosen'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        $this->command->info('  ✓ ' . count($rombel) . ' rombel seeded');
        $this->command->info('    - Angkatan 2024: 4 rombel (Semester 4)');
        $this->command->info('    - Angkatan 2025: 4 rombel (Semester 2)');
        $this->command->info('    - Kebidanan: 1 rombel (kosong)');
    }

    /**
     * Seed jadwal_kuliah table
     * Konteks: TA 2025/2026 Genap
     * - TI24A & TI24B: Semester 4
     * - TI25A & TI25B: Semester 2
     */
    private function seedJadwalKuliah(): void
    {
        $this->command->info('📅 Seeding Jadwal Kuliah...');

        $jadwal = [];

        // TI24A - Semester 4
        $jadwalTI24A = [
            ['hari' => 'Senin', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI409', 'id_dosen' => 7, 'id_ruangan' => 1],
            ['hari' => 'Senin', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI410', 'id_dosen' => 18, 'id_ruangan' => 4],
            ['hari' => 'Selasa', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI411', 'id_dosen' => 28, 'id_ruangan' => 4],
            ['hari' => 'Selasa', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI412', 'id_dosen' => 9, 'id_ruangan' => 1],
            ['hari' => 'Rabu', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI413', 'id_dosen' => 6, 'id_ruangan' => 4],
            ['hari' => 'Rabu', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'MKU006', 'id_dosen' => 22, 'id_ruangan' => 1],
            ['hari' => 'Kamis', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI437', 'id_dosen' => 17, 'id_ruangan' => 5],
        ];

        foreach ($jadwalTI24A as $j) {
            $jadwal[] = array_merge($j, [
                'id_rombel' => 2, // TI24A
                'tahun_akademik' => 4, // 2025/2026 Genap
            ]);
        }

        // TI24B - Semester 4 (jadwal berbeda untuk menghindari bentrok dosen)
        $jadwalTI24B = [
            ['hari' => 'Senin', 'waktu_mulai' => '10:40', 'waktu_selesai' => '13:10', 'kode_mk' => 'TTI409', 'id_dosen' => 7, 'id_ruangan' => 2],
            ['hari' => 'Selasa', 'waktu_mulai' => '10:40', 'waktu_selesai' => '13:10', 'kode_mk' => 'TTI410', 'id_dosen' => 18, 'id_ruangan' => 2],
            ['hari' => 'Rabu', 'waktu_mulai' => '10:40', 'waktu_selesai' => '13:10', 'kode_mk' => 'TTI411', 'id_dosen' => 28, 'id_ruangan' => 2],
            ['hari' => 'Kamis', 'waktu_mulai' => '10:40', 'waktu_selesai' => '13:10', 'kode_mk' => 'TTI412', 'id_dosen' => 9, 'id_ruangan' => 2],
            ['hari' => 'Jumat', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI413', 'id_dosen' => 6, 'id_ruangan' => 2],
            ['hari' => 'Jumat', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'MKU006', 'id_dosen' => 22, 'id_ruangan' => 2],
            ['hari' => 'Kamis', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI437', 'id_dosen' => 17, 'id_ruangan' => 5],
        ];

        foreach ($jadwalTI24B as $j) {
            $jadwal[] = array_merge($j, [
                'id_rombel' => 5, // TI24B
                'tahun_akademik' => 4, // 2025/2026 Genap
            ]);
        }

        // TI25A - Semester 2
        $jadwalTI25A = [
            ['hari' => 'Senin', 'waktu_mulai' => '08:00', 'waktu_selesai' => '11:30', 'kode_mk' => 'TTI203', 'id_dosen' => 18, 'id_ruangan' => 3],
            ['hari' => 'Senin', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI204', 'id_dosen' => 6, 'id_ruangan' => 3],
            ['hari' => 'Selasa', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI205', 'id_dosen' => 7, 'id_ruangan' => 3],
            ['hari' => 'Selasa', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI225', 'id_dosen' => 30, 'id_ruangan' => 3],
            ['hari' => 'Rabu', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'MKU002', 'id_dosen' => 10, 'id_ruangan' => 3],
            ['hari' => 'Rabu', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI244', 'id_dosen' => 19, 'id_ruangan' => 3],
            ['hari' => 'Kamis', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI245', 'id_dosen' => 23, 'id_ruangan' => 3],
        ];

        foreach ($jadwalTI25A as $j) {
            $jadwal[] = array_merge($j, [
                'id_rombel' => 8, // TI25A
                'tahun_akademik' => 4, // 2025/2026 Genap
            ]);
        }

        // TI25B - Semester 2 (jadwal sore/berbeda)
        $jadwalTI25B = [
            ['hari' => 'Senin', 'waktu_mulai' => '13:00', 'waktu_selesai' => '16:30', 'kode_mk' => 'TTI203', 'id_dosen' => 18, 'id_ruangan' => 4],
            ['hari' => 'Selasa', 'waktu_mulai' => '10:40', 'waktu_selesai' => '13:10', 'kode_mk' => 'TTI204', 'id_dosen' => 6, 'id_ruangan' => 3],
            ['hari' => 'Selasa', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI205', 'id_dosen' => 7, 'id_ruangan' => 4],
            ['hari' => 'Rabu', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI225', 'id_dosen' => 30, 'id_ruangan' => 4],
            ['hari' => 'Kamis', 'waktu_mulai' => '08:00', 'waktu_selesai' => '09:40', 'kode_mk' => 'MKU002', 'id_dosen' => 10, 'id_ruangan' => 4],
            ['hari' => 'Kamis', 'waktu_mulai' => '13:00', 'waktu_selesai' => '15:30', 'kode_mk' => 'TTI244', 'id_dosen' => 19, 'id_ruangan' => 4],
            ['hari' => 'Jumat', 'waktu_mulai' => '08:00', 'waktu_selesai' => '10:30', 'kode_mk' => 'TTI245', 'id_dosen' => 23, 'id_ruangan' => 4],
        ];

        foreach ($jadwalTI25B as $j) {
            $jadwal[] = array_merge($j, [
                'id_rombel' => 9, // TI25B
                'tahun_akademik' => 4, // 2025/2026 Genap
            ]);
        }

        // Insert jadwal
        foreach ($jadwal as $j) {
            $matkul = DB::table('matkul')->where('kode_mk', $j['kode_mk'])->first();
            $rombel = DB::table('rombel')->where('id', $j['id_rombel'])->first();

            if ($matkul && $rombel) {
                DB::table('jadwal')->insert([
                    'id_rombel'      => $j['id_rombel'],
                    'id_matkul'      => $matkul->id,
                    'id_prodi'       => $rombel->id_prodi,  // ← ambil dari rombel
                    'id_dosen'       => $j['id_dosen'],
                    'id_ruangan'     => $j['id_ruangan'],
                    'tahun_akademik' => $j['tahun_akademik'],
                    'hari'           => $j['hari'],
                    'jam_mulai'      => $j['waktu_mulai'],   // ← nama kolom benar
                    'jam_selesai'    => $j['waktu_selesai'], // ← nama kolom benar
                    'created_at'     => now(),
                    'updated_at'     => now()
                ]);
            }
        }

        $this->command->info('  ✓ ' . count($jadwal) . ' jadwal kuliah seeded');
        $this->command->info('    - TI24A (Sem 4): 7 jadwal');
        $this->command->info('    - TI24B (Sem 4): 7 jadwal');
        $this->command->info('    - TI25A (Sem 2): 7 jadwal');
        $this->command->info('    - TI25B (Sem 2): 7 jadwal');
    }
}
