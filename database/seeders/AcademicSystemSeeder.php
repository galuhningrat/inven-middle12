<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AcademicSystemSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🚀 Starting Academic System Seeder...');

        // ========== 1. TAHUN AKADEMIK ==========
        $this->seedTahunAkademik();

        // ========== 2. FAKULTAS & PRODI ==========
        $this->seedFakultasProdi();

        // ========== 3. DOSEN DUMMY ==========
        $dosenIds = $this->seedDosenDummy();

        // ========== 4. MATA KULIAH ==========
        $this->seedMataKuliah($dosenIds);

        // ========== 5. RUANGAN ==========
        $this->seedRuangan();

        // ========== 6. ROMBEL ==========
        $this->seedRombel();

        $this->command->info('✅ Academic System Seeder Completed!');
    }

    // ========== TAHUN AKADEMIK ==========
    private function seedTahunAkademik()
    {
        $tahunAkademik = [
            ['id' => 1, 'tahun_awal' => '2024', 'tahun_akhir' => '2025', 'semester' => 'Ganjil', 'tanggal_mulai' => '2024-08-01', 'tanggal_selesai' => '2025-01-31', 'status_aktif' => false],
            ['id' => 2, 'tahun_awal' => '2024', 'tahun_akhir' => '2025', 'semester' => 'Genap', 'tanggal_mulai' => '2025-02-01', 'tanggal_selesai' => '2025-07-31', 'status_aktif' => false],
            ['id' => 3, 'tahun_awal' => '2025', 'tahun_akhir' => '2026', 'semester' => 'Ganjil', 'tanggal_mulai' => '2025-08-01', 'tanggal_selesai' => '2026-01-31', 'status_aktif' => true],
        ];

        foreach ($tahunAkademik as $ta) {
            DB::table('tahun_akademik')->updateOrInsert(
                ['id' => $ta['id']],
                array_merge($ta, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('✅ Tahun Akademik seeded (3 records)');
    }

    // ========== FAKULTAS & PRODI ==========
    private function seedFakultasProdi()
    {
        // Buat user dummy untuk Dekan dan Kaprodi
        $dekanTeknik = DB::table('users')->insertGetId([
            'email' => 'dekan.teknik@stti.ac.id',
            'password' => Hash::make('1234'),
            'nama' => 'Dr. Dekan Teknik',
            'id_role' => 5, // Role Dekan
            'status' => 'Aktif',
            'img' => 'foto_users/default.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Fakultas Teknik
        $fakultasTeknik = DB::table('fakultas')->insertGetId([
            'kode_fakultas' => 'T',
            'nama_fakultas' => 'Teknik',
            'id_dekan' => $dekanTeknik,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Buat user untuk Kaprodi
        $kaprodiTE = $this->createKaprodiUser('kaprodi.te@stti.ac.id', 'Kaprodi Teknik Elektro');
        $kaprodiTI = $this->createKaprodiUser('kaprodi.ti@stti.ac.id', 'Kaprodi Teknik Informatika');
        $kaprodiTM = $this->createKaprodiUser('kaprodi.tm@stti.ac.id', 'Kaprodi Teknik Mesin');
        $kaprodiKB = $this->createKaprodiUser('kaprodi.kb@stti.ac.id', 'Kaprodi Kebidanan');

        // Buat dosen dummy untuk setiap prodi
        $dosenTE = $this->createDosenForProdi($kaprodiTE);
        $dosenTI = $this->createDosenForProdi($kaprodiTI);
        $dosenTM = $this->createDosenForProdi($kaprodiTM);
        $dosenKB = $this->createDosenForProdi($kaprodiKB);

        // Program Studi
        $prodi = [
            ['kode_prodi' => 'TE', 'nama_prodi' => 'Teknik Elektro', 'id_fakultas' => $fakultasTeknik, 'id_kaprodi' => $dosenTE, 'status_akre' => 'Unggul'],
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'id_fakultas' => $fakultasTeknik, 'id_kaprodi' => $dosenTI, 'status_akre' => 'Unggul'],
            ['kode_prodi' => 'TM', 'nama_prodi' => 'Teknik Mesin', 'id_fakultas' => $fakultasTeknik, 'id_kaprodi' => $dosenTM, 'status_akre' => 'Unggul'],
            ['kode_prodi' => 'KB', 'nama_prodi' => 'Kebidanan', 'id_fakultas' => $fakultasTeknik, 'id_kaprodi' => $dosenKB, 'status_akre' => 'Unggul'],
        ];

        foreach ($prodi as $p) {
            DB::table('prodi')->insertOrIgnore(array_merge($p, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('✅ Fakultas & Prodi seeded (1 fakultas, 4 prodi)');
    }

    private function createKaprodiUser($email, $nama)
    {
        return DB::table('users')->insertGetId([
            'email' => $email,
            'password' => Hash::make('1234'),
            'nama' => $nama,
            'id_role' => 6, // Role Kaprodi
            'status' => 'Aktif',
            'img' => 'foto_users/default.png',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    private function createDosenForProdi($userId)
    {
        return DB::table('dosen')->insertGetId([
            'id_users' => $userId,
            'nip' => '199001' . rand(100000, 999999),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    // ========== DOSEN DUMMY ==========
    private function seedDosenDummy()
    {
        $dosenIds = [];

        for ($i = 1; $i <= 15; $i++) {
            $userId = DB::table('users')->insertGetId([
                'email' => "dosen{$i}@stti.ac.id",
                'password' => Hash::make('1234'),
                'nama' => "Dosen Pengampu {$i}",
                'id_role' => 7, // Role Dosen
                'status' => 'Aktif',
                'img' => 'foto_users/default.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            $dosenIds[] = DB::table('dosen')->insertGetId([
                'id_users' => $userId,
                'nip' => '1990' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $this->command->info('✅ Dosen Dummy seeded (15 dosen)');
        return $dosenIds;
    }

    // ========== MATA KULIAH ==========
    private function seedMataKuliah($dosenIds)
    {
        $prodi = DB::table('prodi')->get()->keyBy('kode_prodi');

        // MK Umum (untuk semua prodi)
        $mkUmum = [
            ['kode_mk' => 'MKU001', 'nama_mk' => 'Pendidikan Agama', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU002', 'nama_mk' => 'Bahasa Indonesia', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU003', 'nama_mk' => 'Pendidikan Kewarganegaraan', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU004', 'nama_mk' => 'Pendidikan Pancasila', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU005', 'nama_mk' => 'Bahasa Inggris', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU006', 'nama_mk' => 'Probabilitas dan Statistik', 'bobot' => '3', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU007', 'nama_mk' => 'Etika Profesi dan Kewirausahaan', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU008', 'nama_mk' => 'Metodologi Penelitian', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU009', 'nama_mk' => 'Kuliah Kerja Praktik (KKP)', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU010', 'nama_mk' => 'Seminar Tugas Akhir', 'bobot' => '2', 'jenis' => 'umum'],
            ['kode_mk' => 'MKU011', 'nama_mk' => 'Tugas Akhir', 'bobot' => '6', 'jenis' => 'umum'],
        ];

        // Mata Kuliah Teknik Elektro
        $mkTE = [
            // Semester 1
            ['kode_mk' => 'TTE101', 'nama_mk' => 'Kalkulus I', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'TTE102', 'nama_mk' => 'Fisika I', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'TTE103', 'nama_mk' => 'Pengantar Teknik Elektro', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'TTE104', 'nama_mk' => 'Dasar Pemrograman', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'TTE105', 'nama_mk' => 'Gambar Teknik', 'bobot' => '2', 'prodi' => 'TE'],
            // Semester 2
            ['kode_mk' => 'EB121', 'nama_mk' => 'Instrumentasi dan Pengukuran', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EB122', 'nama_mk' => 'Kalkulus II', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB123', 'nama_mk' => 'Kimia Dasar', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EB124', 'nama_mk' => 'Aljabar Linier dan Matriks', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB125', 'nama_mk' => 'Fisika II', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB126', 'nama_mk' => 'Rangkaian Listrik I (DC)', 'bobot' => '3', 'prodi' => 'TE'],
            // Semester 3
            ['kode_mk' => 'EB131', 'nama_mk' => 'Teknik Digital', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EB131P', 'nama_mk' => 'Praktikum Teknik Digital', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'EB132', 'nama_mk' => 'Dasar Elektronika', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB132P', 'nama_mk' => 'Praktikum Rangkaian Listrik', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'EB133', 'nama_mk' => 'Medan Elektromagnetik', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB134', 'nama_mk' => 'Rangkaian Listrik II (AC)', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB121P', 'nama_mk' => 'Praktikum Instrumentasi & Pengukuran', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'EB135', 'nama_mk' => 'Sinyal dan Sistem', 'bobot' => '3', 'prodi' => 'TE'],
            // Semester 4
            ['kode_mk' => 'EB141', 'nama_mk' => 'Sistem Komunikasi', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB142', 'nama_mk' => 'Elektronika Analog', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB143', 'nama_mk' => 'Mikroprosesor dan Mikrokontroler', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB144', 'nama_mk' => 'Sistem Numerik', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB145', 'nama_mk' => 'Elektronika Digital', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB146', 'nama_mk' => 'Teknik Tenaga Listrik', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB141P', 'nama_mk' => 'Praktik Sistem Komunikasi', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'EB146P', 'nama_mk' => 'Praktik Teknik Tenaga Listrik', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'EB132P2', 'nama_mk' => 'Praktik Dasar Elektronika', 'bobot' => '1', 'prodi' => 'TE'],
            // Semester 5
            ['kode_mk' => 'EK151', 'nama_mk' => 'Sistem Kontrol', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EL152', 'nama_mk' => 'Mesin Listrik', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EK253', 'nama_mk' => 'Dasar Teknik Kendali', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EL254', 'nama_mk' => 'Energi Baru dan Terbarukan', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'ET155', 'nama_mk' => 'Pemrosesan Sinyal Digital', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EK356', 'nama_mk' => 'Mekatronika', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'ET145P', 'nama_mk' => 'Praktik Elektronika Digital', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'ET155P', 'nama_mk' => 'Praktik Pemrosesan Sinyal Digital', 'bobot' => '1', 'prodi' => 'TE'],
            // Semester 6
            ['kode_mk' => 'EL361', 'nama_mk' => 'Sistem Distribusi Tenaga Listrik', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EL462', 'nama_mk' => 'Elektronika Daya', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'ET263', 'nama_mk' => 'Radar dan Navigasi', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'ET364', 'nama_mk' => 'Komunikasi Data dan Jaringan', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EK465', 'nama_mk' => 'Embedded System and Robotics', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EB167', 'nama_mk' => 'Manajemen Proyek Teknik', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EK151P', 'nama_mk' => 'Praktik Sistem Kontrol', 'bobot' => '1', 'prodi' => 'TE'],
            ['kode_mk' => 'EL462P', 'nama_mk' => 'Antena dan Propagasi', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'TTE645', 'nama_mk' => 'Praktik Elektronika Daya', 'bobot' => '1', 'prodi' => 'TE'],
            // Semester 7
            ['kode_mk' => 'ET471', 'nama_mk' => 'Sistem dan Teknologi Nirkabel', 'bobot' => '2', 'prodi' => 'TE'],
            ['kode_mk' => 'EB172', 'nama_mk' => 'Capstone Design', 'bobot' => '4', 'prodi' => 'TE'],
            // Semester 8
            ['kode_mk' => 'ET573', 'nama_mk' => 'Teknik Optimasi', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EL574', 'nama_mk' => 'Kendali Mesin Listrik', 'bobot' => '3', 'prodi' => 'TE'],
            ['kode_mk' => 'EK575', 'nama_mk' => 'Sistem Adaptif', 'bobot' => '3', 'prodi' => 'TE'],
        ];

        // Mata Kuliah Teknik Informatika
        $mkTI = [
            // Semester 1
            ['kode_mk' => 'TTI101', 'nama_mk' => 'Algoritma Pemrograman', 'bobot' => '4', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI102', 'nama_mk' => 'Pengenalan Pemrograman', 'bobot' => '4', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI124', 'nama_mk' => 'Kalkulus I', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI125', 'nama_mk' => 'Matematika Diskrit', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI143', 'nama_mk' => 'Fisika I', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI136', 'nama_mk' => 'Pengantar Teknologi Informasi', 'bobot' => '3', 'prodi' => 'TI'],
            // Semester 2
            ['kode_mk' => 'TTI203', 'nama_mk' => 'Struktur Data', 'bobot' => '4', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI204', 'nama_mk' => 'Organisasi dan Arsitektur Komputer', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI205', 'nama_mk' => 'Basis Data', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI225', 'nama_mk' => 'Aljabar Linier', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI244', 'nama_mk' => 'Kalkulus II', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI245', 'nama_mk' => 'Fisika II', 'bobot' => '3', 'prodi' => 'TI'],
            // Semester 3
            ['kode_mk' => 'TTI306', 'nama_mk' => 'Jaringan Komputer', 'bobot' => '4', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI307', 'nama_mk' => 'Sistem Operasi', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI322', 'nama_mk' => 'Logika Matematika', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI308', 'nama_mk' => 'Kompleksitas Algoritma', 'bobot' => '3', 'prodi' => 'TI'],

            // Semester 4
            ['kode_mk' => 'TTI409', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI410', 'nama_mk' => 'Kecerdasan Buatan', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI411', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI412', 'nama_mk' => 'Human Computer Interaction', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI413', 'nama_mk' => 'Pengolahan Citra Digital', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI437', 'nama_mk' => 'Embedded System', 'bobot' => '3', 'prodi' => 'TI'],
            // Semester 5
            ['kode_mk' => 'TTI514', 'nama_mk' => 'Manajemen Proyek TI', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI515', 'nama_mk' => 'Analisis dan Desain Perangkat Lunak', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI516', 'nama_mk' => 'Komputasi Paralel dan Terdistribusi', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI517', 'nama_mk' => 'Pemrograman Berorientasi Platform', 'bobot' => '4', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI518', 'nama_mk' => 'Internet of Things', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI538', 'nama_mk' => 'Data Science', 'bobot' => '3', 'prodi' => 'TI'],
            // Semester 6
            ['kode_mk' => 'TTI619', 'nama_mk' => 'Keamanan Data dan Informasi', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI620', 'nama_mk' => 'Pembelajaran Mesin', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI621', 'nama_mk' => 'Cloud Computing', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI622', 'nama_mk' => 'Big Data', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI639', 'nama_mk' => 'Pemrograman Web', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI634', 'nama_mk' => 'Etika Profesi', 'bobot' => '2', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI5647', 'nama_mk' => 'KKN', 'bobot' => '2', 'prodi' => 'TI'],
            // Semester 7
            ['kode_mk' => 'TTI723', 'nama_mk' => 'Proyek Perangkat Lunak', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI732', 'nama_mk' => 'Kerja Praktik / Magang', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI740', 'nama_mk' => 'AI Computing Platform', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI741', 'nama_mk' => 'Kriptografi', 'bobot' => '3', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI742', 'nama_mk' => 'Wireless Sensor Network', 'bobot' => '3', 'prodi' => 'TI'],
            // Semester 8
            ['kode_mk' => 'TTI2833', 'nama_mk' => 'Tugas Akhir', 'bobot' => '6', 'prodi' => 'TI'],
            ['kode_mk' => 'TTI2835', 'nama_mk' => 'Hukum dan Kebijakan Teknologi Informasi', 'bobot' => '2', 'prodi' => 'TI'],
        ];

        // Mata Kuliah Teknik Mesin
        $mkTM = [
            // Semester 1
            ['kode_mk' => 'TMI101', 'nama_mk' => 'Matematika I', 'bobot' => '4', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI105', 'nama_mk' => 'Fisika I', 'bobot' => '4', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI107', 'nama_mk' => 'Kimia Dasar', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3138', 'nama_mk' => 'Gambar Mesin I', 'bobot' => '2', 'prodi' => 'TM'],
            // Semester 2
            ['kode_mk' => 'TMI202', 'nama_mk' => 'Matematika II', 'bobot' => '4', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI206', 'nama_mk' => 'Fisika II', 'bobot' => '4', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI208', 'nama_mk' => 'Statistika dan Probabilitas', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI209', 'nama_mk' => 'K3L', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI221', 'nama_mk' => 'Bahan Material Teknik I', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI223', 'nama_mk' => 'Mekanika dan Kekuatan Bahan I', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI225', 'nama_mk' => 'Kinematika Dinamika', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3233', 'nama_mk' => 'Gambar Mesin II', 'bobot' => '2', 'prodi' => 'TM'],
            // Semester 3
            ['kode_mk' => 'TMI301', 'nama_mk' => 'Matematika III', 'bobot' => '4', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2312', 'nama_mk' => 'Bahan Material Teknik II', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2314', 'nama_mk' => 'Mekanika dan Kekuatan Bahan II', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2316', 'nama_mk' => 'Kinematika Dinamika II', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2320', 'nama_mk' => 'Termodinamika I', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2322', 'nama_mk' => 'Mekanika Fluida I', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2324', 'nama_mk' => 'Perpindahan Kalor dan Massa I', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3342', 'nama_mk' => 'Elemen Mesin I', 'bobot' => '2', 'prodi' => 'TM'],
            // Semester 4
            ['kode_mk' => 'TMI404', 'nama_mk' => 'Matematika IV', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TMI417', 'nama_mk' => 'Getaran Mekanik', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2421', 'nama_mk' => 'Termodinamika II', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2423', 'nama_mk' => 'Mekanika Fluida II', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2425', 'nama_mk' => 'Perpindahan Kalor dan Massa II', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2429', 'nama_mk' => 'Pengukuran Teknik / Metrologi', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3440', 'nama_mk' => 'Proses Manufaktur I', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3442', 'nama_mk' => 'Elemen Mesin II', 'bobot' => '2', 'prodi' => 'TM'],
            // Semester 5
            ['kode_mk' => 'TM2525', 'nama_mk' => 'Pengukuran Teknik / Metrologi', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM2526', 'nama_mk' => 'Teknik Tenaga Listrik', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3536', 'nama_mk' => 'Metalurgi', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3541', 'nama_mk' => 'Proses Manufaktur II', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3544', 'nama_mk' => 'Mesin Konversi Energi', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3545', 'nama_mk' => 'Sistem Kendali Kontrol', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3546', 'nama_mk' => 'Mekatronika', 'bobot' => '3', 'prodi' => 'TM'],
            // Semester 6
            ['kode_mk' => 'TM2601', 'nama_mk' => 'Hukum Tenaga Kerja dan Industri', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3633', 'nama_mk' => 'Sistem Hidrolik & Pneumatik', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3634', 'nama_mk' => 'Teknik Pemeliharaan Mesin', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3635', 'nama_mk' => 'Teknik Pengelasan', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3637', 'nama_mk' => 'Praktik Metalurgi', 'bobot' => '3', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3647', 'nama_mk' => 'Capstone Design', 'bobot' => '3', 'prodi' => 'TM'],
            // Semester 7
            ['kode_mk' => 'TM3722', 'nama_mk' => 'CAD/CAM', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3723', 'nama_mk' => 'Praktikum CNC', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3728', 'nama_mk' => 'Praktikum Proses Produksi', 'bobot' => '4', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3736', 'nama_mk' => 'Praktikum Fenomena Dasar Mesin', 'bobot' => '1', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3737', 'nama_mk' => 'Praktikum Prestasi Mesin', 'bobot' => '1', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3739', 'nama_mk' => 'Manajemen Proyek', 'bobot' => '2', 'prodi' => 'TM'],
            ['kode_mk' => 'TM3742', 'nama_mk' => 'Kerja Praktek', 'bobot' => '2', 'prodi' => 'TM'],
            // Semester 8
            ['kode_mk' => 'TM3845', 'nama_mk' => 'Skripsi / Tugas Akhir', 'bobot' => '6', 'prodi' => 'TM'],
        ];

        // Insert MK Umum (assign ke prodi dummy untuk relasi)
        foreach ($mkUmum as $mk) {
            DB::table('matkul')->insertOrIgnore([
                'kode_mk' => $mk['kode_mk'],
                'nama_mk' => $mk['nama_mk'],
                'bobot' => $mk['bobot'],
                'jenis' => $mk['jenis'],
                'id_prodi' => $prodi['TE']->id, // Assign ke prodi pertama
                'id_dosen' => $dosenIds[0],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Insert MK per Prodi
        $allMK = array_merge($mkTE, $mkTI, $mkTM);
        foreach ($allMK as $index => $mk) {
            $prodiCode = $mk['prodi'];
            $prodiId = $prodi[$prodiCode]->id;
            $dosenId = $dosenIds[$index % count($dosenIds)]; // Round-robin assignment

            DB::table('matkul')->insertOrIgnore([
                'kode_mk' => $mk['kode_mk'],
                'nama_mk' => $mk['nama_mk'],
                'bobot' => $mk['bobot'],
                'jenis' => 'wajib',
                'id_prodi' => $prodiId,
                'id_dosen' => $dosenId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        $totalMK = count($mkUmum) + count($allMK);
        $this->command->info("✅ Mata Kuliah seeded ({$totalMK} matkul)");
        $this->command->info("   - MK Umum: " . count($mkUmum));
        $this->command->info("   - Teknik Elektro: " . count($mkTE));
        $this->command->info("   - Teknik Informatika: " . count($mkTI));
        $this->command->info("   - Teknik Mesin: " . count($mkTM));
    }

    // ========== RUANGAN ==========
    private function seedRuangan()
    {
        $ruangan = [
            ['kode_ruang' => 'R101', 'nama_ruang' => 'Ruang Kuliah 101', 'kapasitas' => 20],
            ['kode_ruang' => 'R102', 'nama_ruang' => 'Ruang Kuliah 102', 'kapasitas' => 20],
            ['kode_ruang' => 'R201', 'nama_ruang' => 'Ruang Kuliah 201', 'kapasitas' => 25],
            ['kode_ruang' => 'LAB1', 'nama_ruang' => 'Lab Komputer 1', 'kapasitas' => 25],
            ['kode_ruang' => 'LAB2', 'nama_ruang' => 'Lab Elektronika', 'kapasitas' => 25],
        ];

        foreach ($ruangan as $r) {
            DB::table('ruangan')->insertOrIgnore(array_merge($r, [
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('✅ Ruangan seeded (5 ruangan)');
    }

    // ========== ROMBEL ==========
    private function seedRombel()
    {
        $prodi = DB::table('prodi')->get()->keyBy('kode_prodi');
        $tahun2024 = DB::table('tahun_akademik')->where('tahun_awal', '2024')->first();
        $tahun2025 = DB::table('tahun_akademik')->where('tahun_awal', '2025')->first();

        $rombel = [
            // Angkatan 2024
            ['kode_rombel' => 'TE24', 'nama_rombel' => 'Teknik Elektro 2024', 'tahun_masuk' => $tahun2024->id, 'id_prodi' => $prodi['TE']->id, 'id_dosen' => $prodi['TE']->id_kaprodi],
            ['kode_rombel' => 'TI24', 'nama_rombel' => 'Teknik Informatika 2024', 'tahun_masuk' => $tahun2024->id, 'id_prodi' => $prodi['TI']->id, 'id_dosen' => $prodi['TI']->id_kaprodi],
            ['kode_rombel' => 'TM24', 'nama_rombel' => 'Teknik Mesin 2024', 'tahun_masuk' => $tahun2024->id, 'id_prodi' => $prodi['TM']->id, 'id_dosen' => $prodi['TM']->id_kaprodi],

            // Angkatan 2025
            ['kode_rombel' => 'TE25', 'nama_rombel' => 'Teknik Elektro 2025', 'tahun_masuk' => $tahun2025->id, 'id_prodi' => $prodi['TE']->id, 'id_dosen' => $prodi['TE']->id_kaprodi],
            ['kode_rombel' => 'TI25', 'nama_rombel' => 'Teknik Informatika 2025', 'tahun_masuk' => $tahun2025->id, 'id_prodi' => $prodi['TI']->id, 'id_dosen' => $prodi['TI']->id_kaprodi],
            ['kode_rombel' => 'TM25', 'nama_rombel' => 'Teknik Mesin 2025', 'tahun_masuk' => $tahun2025->id, 'id_prodi' => $prodi['TM']->id, 'id_dosen' => $prodi['TM']->id_kaprodi],

            // Kebidanan (kosong, tidak ada mahasiswa)
            ['kode_rombel' => 'KB24', 'nama_rombel' => 'Kebidanan 2024', 'tahun_masuk' => $tahun2024->id, 'id_prodi' => $prodi['KB']->id, 'id_dosen' => $prodi['KB']->id_kaprodi],
        ];
        foreach ($rombel as $r) {
            DB::table('rombel')->insertOrIgnore(array_merge($r, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }

        $this->command->info('✅ Rombel seeded (7 rombel: 6 aktif + 1 kosong)');
        $this->command->info('   - Angkatan 2024: TE24, TI24, TM24');
        $this->command->info('   - Angkatan 2025: TE25, TI25, TM25');
        $this->command->info('   - Kebidanan (kosong): KB24');
    }
}
