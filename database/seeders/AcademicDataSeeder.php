<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicDataSeeder extends Seeder
{
    public function run(): void
    {
        // ========== 1. TAHUN AKADEMIK ==========
        $tahunAkademik = [
            ['tahun_awal' => '2022', 'tahun_akhir' => '2023', 'semester' => 'Ganjil', 'tanggal_mulai' => '2022-08-01', 'tanggal_selesai' => '2023-01-31', 'status_aktif' => false],
            ['tahun_awal' => '2022', 'tahun_akhir' => '2023', 'semester' => 'Genap', 'tanggal_mulai' => '2023-02-01', 'tanggal_selesai' => '2023-07-31', 'status_aktif' => false],
            ['tahun_awal' => '2023', 'tahun_akhir' => '2024', 'semester' => 'Ganjil', 'tanggal_mulai' => '2023-08-01', 'tanggal_selesai' => '2024-01-31', 'status_aktif' => false],
            ['tahun_awal' => '2023', 'tahun_akhir' => '2024', 'semester' => 'Genap', 'tanggal_mulai' => '2024-02-01', 'tanggal_selesai' => '2024-07-31', 'status_aktif' => false],
            ['tahun_awal' => '2024', 'tahun_akhir' => '2025', 'semester' => 'Ganjil', 'tanggal_mulai' => '2024-08-01', 'tanggal_selesai' => '2025-01-31', 'status_aktif' => true],
            ['tahun_awal' => '2024', 'tahun_akhir' => '2025', 'semester' => 'Genap', 'tanggal_mulai' => '2025-02-01', 'tanggal_selesai' => '2025-07-31', 'status_aktif' => false],
        ];

        foreach ($tahunAkademik as $ta) {
            DB::table('tahun_akademik')->updateOrInsert(
                ['tahun_awal' => $ta['tahun_awal'], 'semester' => $ta['semester']],
                array_merge($ta, ['created_at' => now(), 'updated_at' => now()])
            );
        }

        $this->command->info('✅ Tahun Akademik seeded');

        // ========== 2. AMBIL ID PRODI ==========
        $prodiTE = DB::table('prodi')->where('kode_prodi', 'TE')->first();
        $prodiTI = DB::table('prodi')->where('kode_prodi', 'TI')->first();
        $prodiTM = DB::table('prodi')->where('kode_prodi', 'TM')->first();

        if (!$prodiTE || !$prodiTI || !$prodiTM) {
            $this->command->error('⚠️  Prodi tidak ditemukan! Pastikan sudah ada data Fakultas dan Prodi.');
            return;
        }

        // Ambil satu dosen untuk dummy (nanti bisa diubah manual)
        $dosenDummy = DB::table('dosen')->first();
        if (!$dosenDummy) {
            $this->command->error('⚠️  Belum ada data dosen! Silakan buat dosen terlebih dahulu.');
            return;
        }

        // ========== 3. MATA KULIAH TEKNIK ELEKTRO ==========
        $matkulTE = [
            // Semester 1
            ['kode_mk' => 'MKU001', 'nama_mk' => 'Pendidikan Agama', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'MKU002', 'nama_mk' => 'Bahasa Indonesia', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'MKU003', 'nama_mk' => 'Pendidikan Kewarganegaraan', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'TTE101', 'nama_mk' => 'Kalkulus I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'TTE102', 'nama_mk' => 'Fisika I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'TTE103', 'nama_mk' => 'Pengantar Teknik Elektro', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'TTE104', 'nama_mk' => 'Dasar Pemrograman', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'TTE105', 'nama_mk' => 'Gambar Teknik', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 2
            ['kode_mk' => 'MKU004', 'nama_mk' => 'Pendidikan Pancasila', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'MKU005', 'nama_mk' => 'Bahasa Inggris', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB121', 'nama_mk' => 'Instrumentasi dan Pengukuran', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB122', 'nama_mk' => 'Kalkulus II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB123', 'nama_mk' => 'Kimia Dasar', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB124', 'nama_mk' => 'Aljabar Linier dan Matriks', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB125', 'nama_mk' => 'Fisika II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB126', 'nama_mk' => 'Rangkaian Listrik I (DC)', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 3
            ['kode_mk' => 'EB131', 'nama_mk' => 'Teknik Digital', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB131P', 'nama_mk' => 'Praktikum Teknik Digital', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB132', 'nama_mk' => 'Dasar Elektronika', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB132P', 'nama_mk' => 'Praktikum Rangkaian Listrik', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'MKU006', 'nama_mk' => 'Probabilitas dan Statistik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB133', 'nama_mk' => 'Medan Elektromagnetik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB134', 'nama_mk' => 'Rangkaian Listrik II (AC)', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB121P', 'nama_mk' => 'Praktikum Instrumentasi & Pengukuran', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB135', 'nama_mk' => 'Sinyal dan Sistem', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 4
            ['kode_mk' => 'EB141', 'nama_mk' => 'Sistem Komunikasi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB142', 'nama_mk' => 'Elektronika Analog', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB143', 'nama_mk' => 'Mikroprosesor dan Mikrokontroler', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB144', 'nama_mk' => 'Sistem Numerik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB145', 'nama_mk' => 'Elektronika Digital', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB146', 'nama_mk' => 'Teknik Tenaga Listrik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB141P', 'nama_mk' => 'Praktik Sistem Komunikasi', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB146P', 'nama_mk' => 'Praktik Teknik Tenaga Listrik', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB132P2', 'nama_mk' => 'Praktik Dasar Elektronika', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 5
            ['kode_mk' => 'MKU007', 'nama_mk' => 'Etika Profesi dan Kewirausahaan', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EK151', 'nama_mk' => 'Sistem Kontrol', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EL152', 'nama_mk' => 'Mesin Listrik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EK253', 'nama_mk' => 'Dasar Teknik Kendali', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EL254', 'nama_mk' => 'Energi Baru dan Terbarukan', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET155', 'nama_mk' => 'Pemrosesan Sinyal Digital', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EK356', 'nama_mk' => 'Mekatronika', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET145P', 'nama_mk' => 'Praktik Elektronika Digital', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET155P', 'nama_mk' => 'Praktik Pemrosesan Sinyal Digital', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 6
            ['kode_mk' => 'EL361', 'nama_mk' => 'Sistem Distribusi Tenaga Listrik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EL462', 'nama_mk' => 'Elektronika Daya', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET263', 'nama_mk' => 'Radar dan Navigasi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET364', 'nama_mk' => 'Komunikasi Data dan Jaringan', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EK465', 'nama_mk' => 'Embedded System and Robotics', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB167', 'nama_mk' => 'Manajemen Proyek Teknik', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EK151P', 'nama_mk' => 'Praktik Sistem Kontrol', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EL462P', 'nama_mk' => 'Antena dan Propagasi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'TTE645', 'nama_mk' => 'Praktik Elektronika Daya', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 7
            ['kode_mk' => 'MKU008', 'nama_mk' => 'Metodologi Penelitian', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET471', 'nama_mk' => 'Sistem dan Teknologi Nirkabel', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EB172', 'nama_mk' => 'Capstone Design', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],

            // Semester 8
            ['kode_mk' => 'MKU009', 'nama_mk' => 'Kuliah Kerja Praktik (KKP)', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'MKU010', 'nama_mk' => 'Seminar Tugas Akhir', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'MKU011', 'nama_mk' => 'Tugas Akhir', 'bobot' => 6, 'jenis' => 'wajib', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'ET573', 'nama_mk' => 'Teknik Optimasi', 'bobot' => 3, 'jenis' => 'pilihan', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EL574', 'nama_mk' => 'Kendali Mesin Listrik', 'bobot' => 3, 'jenis' => 'pilihan', 'id_prodi' => $prodiTE->id],
            ['kode_mk' => 'EK575', 'nama_mk' => 'Sistem Adaptif', 'bobot' => 3, 'jenis' => 'pilihan', 'id_prodi' => $prodiTE->id],
        ];

        // ========== 4. MATA KULIAH TEKNIK INFORMATIKA ==========
        $matkulTI = [
            // Semester 1
            ['kode_mk' => 'TTI101', 'nama_mk' => 'Algoritma Pemrograman', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI102', 'nama_mk' => 'Pengenalan Pemrograman', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI124', 'nama_mk' => 'Kalkulus I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI125', 'nama_mk' => 'Matematika Diskrit', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI143', 'nama_mk' => 'Fisika I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI136', 'nama_mk' => 'Pengantar Teknologi Informasi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],

            // Semester 2
            ['kode_mk' => 'TTI203', 'nama_mk' => 'Struktur Data', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI204', 'nama_mk' => 'Organisasi dan Arsitektur Komputer', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI205', 'nama_mk' => 'Basis Data', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI225', 'nama_mk' => 'Aljabar Linier', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI244', 'nama_mk' => 'Kalkulus II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI245', 'nama_mk' => 'Fisika II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],

            // Semester 3
            ['kode_mk' => 'TTI306', 'nama_mk' => 'Jaringan Komputer', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI307', 'nama_mk' => 'Sistem Operasi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI322', 'nama_mk' => 'Logika Matematika', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI308', 'nama_mk' => 'Kompleksitas Algoritma', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],

            // Semester 4
            ['kode_mk' => 'TTI409', 'nama_mk' => 'Rekayasa Perangkat Lunak', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI410', 'nama_mk' => 'Kecerdasan Buatan', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI411', 'nama_mk' => 'Pemrograman Berorientasi Objek', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI412', 'nama_mk' => 'Human Computer Interaction', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI413', 'nama_mk' => 'Pengolahan Citra Digital', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI437', 'nama_mk' => 'Embedded System', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],

            // Semester 5
            ['kode_mk' => 'TTI514', 'nama_mk' => 'Manajemen Proyek TI', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI515', 'nama_mk' => 'Analisis dan Desain Perangkat Lunak', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI516', 'nama_mk' => 'Komputasi Paralel dan Terdistribusi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI517', 'nama_mk' => 'Pemrograman Berorientasi Platform', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI518', 'nama_mk' => 'Internet of Things', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI538', 'nama_mk' => 'Data Science', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],

            // Semester 6
            ['kode_mk' => 'TTI619', 'nama_mk' => 'Keamanan Data dan Informasi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI620', 'nama_mk' => 'Pembelajaran Mesin', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI621', 'nama_mk' => 'Cloud Computing', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI622', 'nama_mk' => 'Big Data', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI639', 'nama_mk' => 'Pemrograman Web', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI634', 'nama_mk' => 'Etika Profesi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI5647', 'nama_mk' => 'KKN', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],

            // Semester 7
            ['kode_mk' => 'TTI723', 'nama_mk' => 'Proyek Perangkat Lunak', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI732', 'nama_mk' => 'Kerja Praktik / Magang', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI740', 'nama_mk' => 'AI Computing Platform', 'bobot' => 3, 'jenis' => 'pilihan', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI741', 'nama_mk' => 'Kriptografi', 'bobot' => 3, 'jenis' => 'pilihan', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI742', 'nama_mk' => 'Wireless Sensor Network', 'bobot' => 3, 'jenis' => 'pilihan', 'id_prodi' => $prodiTI->id],

            // Semester 8
            ['kode_mk' => 'TTI2833', 'nama_mk' => 'Tugas Akhir', 'bobot' => 6, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
            ['kode_mk' => 'TTI2835', 'nama_mk' => 'Hukum dan Kebijakan Teknologi Informasi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTI->id],
        ];

        // ========== 5. MATA KULIAH TEKNIK MESIN ==========
        $matkulTM = [
            // Semester 1
            ['kode_mk' => 'TMI101', 'nama_mk' => 'Matematika I', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI105', 'nama_mk' => 'Fisika I', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI107', 'nama_mk' => 'Kimia Dasar', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3138', 'nama_mk' => 'Gambar Mesin I', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 2
            ['kode_mk' => 'TMI202', 'nama_mk' => 'Matematika II', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI206', 'nama_mk' => 'Fisika II', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI208', 'nama_mk' => 'Statistika dan Probabilitas', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI209', 'nama_mk' => 'K3L', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI221', 'nama_mk' => 'Bahan Material Teknik I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI223', 'nama_mk' => 'Mekanika dan Kekuatan Bahan I', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI225', 'nama_mk' => 'Kinematika Dinamika', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3233', 'nama_mk' => 'Gambar Mesin II', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 3
            ['kode_mk' => 'TMI301', 'nama_mk' => 'Matematika III', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2312', 'nama_mk' => 'Bahan Material Teknik II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2314', 'nama_mk' => 'Mekanika dan Kekuatan Bahan II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2316', 'nama_mk' => 'Kinematika Dinamika II', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2320', 'nama_mk' => 'Termodinamika I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2322', 'nama_mk' => 'Mekanika Fluida I', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2324', 'nama_mk' => 'Perpindahan Kalor dan Massa I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3342', 'nama_mk' => 'Elemen Mesin I', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 4
            ['kode_mk' => 'TMI404', 'nama_mk' => 'Matematika IV', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TMI417', 'nama_mk' => 'Getaran Mekanik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2421', 'nama_mk' => 'Termodinamika II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2423', 'nama_mk' => 'Mekanika Fluida II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2425', 'nama_mk' => 'Perpindahan Kalor dan Massa II', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2429', 'nama_mk' => 'Pengukuran Teknik / Metrologi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3440', 'nama_mk' => 'Proses Manufaktur I', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3442', 'nama_mk' => 'Elemen Mesin II', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 5
            ['kode_mk' => 'TM2525', 'nama_mk' => 'Pengukuran Teknik / Metrologi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM2526', 'nama_mk' => 'Teknik Tenaga Listrik', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3536', 'nama_mk' => 'Metalurgi', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3541', 'nama_mk' => 'Proses Manufaktur II', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3544', 'nama_mk' => 'Mesin Konversi Energi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3545', 'nama_mk' => 'Sistem Kendali Kontrol', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3546', 'nama_mk' => 'Mekatronika', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 6
            ['kode_mk' => 'TM2601', 'nama_mk' => 'Hukum Tenaga Kerja dan Industri', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3633', 'nama_mk' => 'Sistem Hidrolik & Pneumatik', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3634', 'nama_mk' => 'Teknik Pemeliharaan Mesin', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3635', 'nama_mk' => 'Teknik Pengelasan', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3637', 'nama_mk' => 'Praktik Metalurgi', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3647', 'nama_mk' => 'Capstone Design', 'bobot' => 3, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 7
            ['kode_mk' => 'TM3722', 'nama_mk' => 'CAD/CAM', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3723', 'nama_mk' => 'Praktikum CNC', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3728', 'nama_mk' => 'Praktikum Proses Produksi', 'bobot' => 4, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3736', 'nama_mk' => 'Praktikum Fenomena Dasar Mesin', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3737', 'nama_mk' => 'Praktikum Prestasi Mesin', 'bobot' => 1, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3739', 'nama_mk' => 'Manajemen Proyek', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
            ['kode_mk' => 'TM3742', 'nama_mk' => 'Kerja Praktek', 'bobot' => 2, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],

            // Semester 8
            ['kode_mk' => 'TM3845', 'nama_mk' => 'Skripsi / Tugas Akhir', 'bobot' => 6, 'jenis' => 'wajib', 'id_prodi' => $prodiTM->id],
        ];

        // ========== 6. INSERT MATA KULIAH KE DATABASE ==========
        $allMatkul = array_merge($matkulTE, $matkulTI, $matkulTM);

        foreach ($allMatkul as $mk) {
            DB::table('matkul')->updateOrInsert(
                ['kode_mk' => $mk['kode_mk']],
                array_merge($mk, [
                    'id_dosen' => $dosenDummy->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ])
            );
        }

        $this->command->info('✅ Mata Kuliah Teknik Elektro: ' . count($matkulTE) . ' matkul');
        $this->command->info('✅ Mata Kuliah Teknik Informatika: ' . count($matkulTI) . ' matkul');
        $this->command->info('✅ Mata Kuliah Teknik Mesin: ' . count($matkulTM) . ' matkul');
        $this->command->info('✅ Total: ' . count($allMatkul) . ' mata kuliah berhasil di-seed');
    }
}
