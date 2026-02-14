<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Matkul;
use App\Models\Prodi;
use App\Models\Dosen;

class MatkulSeeder extends Seeder
{
    /**
     * Seeder Mata Kuliah untuk 3 Prodi:
     * - Teknik Informatika (TI)
     * - Teknik Elektro (TE)
     * - Teknik Mesin (TM)
     */
    public function run()
    {
        // Ambil ID Prodi
        $prodiTI = Prodi::where('kode_prodi', 'TI')->first();
        $prodiTE = Prodi::where('kode_prodi', 'TE')->first();
        $prodiTM = Prodi::where('kode_prodi', 'TM')->first();

        if (!$prodiTI || !$prodiTE || !$prodiTM) {
            $this->command->error('❌ Prodi TI/TE/TM tidak ditemukan! Jalankan ProdiSeeder terlebih dahulu.');
            return;
        }

        // Ambil ID Dosen (sesuaikan dengan dosen yang ada)
        $dosenMap = $this->getDosenMap();

        $this->command->info('🚀 Seeding Mata Kuliah Umum...');
        $this->seedMatkulUmum();

        $this->command->info('🚀 Seeding Mata Kuliah Teknik Informatika...');
        $this->seedMatkulTI($prodiTI->id, $dosenMap);

        $this->command->info('🚀 Seeding Mata Kuliah Teknik Elektro...');
        $this->seedMatkulTE($prodiTE->id, $dosenMap);

        $this->command->info('🚀 Seeding Mata Kuliah Teknik Mesin...');
        $this->seedMatkulTM($prodiTM->id, $dosenMap);

        $this->command->info('✅ Seeder Mata Kuliah selesai!');
    }

    /**
     * Mapping nama dosen ke ID
     */
    private function getDosenMap()
    {
        $dosens = Dosen::with('user')->get();
        $map = [];

        foreach ($dosens as $d) {
            $nama = $d->user->nama ?? null;
            if ($nama) {
                $map[$nama] = $d->id;
            }
        }

        return $map;
    }

    /**
     * Mata Kuliah Umum (berlaku semua prodi)
     */
    private function seedMatkulUmum()
    {
        $matkulUmum = [
            ['MKU001', 'Pendidikan Agama', 2, null, 1],
            ['MKU002', 'Bahasa Indonesia', 2, 'Qa\'is Ghaziyudin', 1],
            ['MKU003', 'Pendidikan Kewarganegaraan', 2, null, 3],
            ['MKU004', 'Pendidikan Pancasila', 2, null, 1],
            ['MKU005', 'Bahasa Inggris', 2, null, 2],
            ['MKU006', 'Probabilitas dan Statistik', 3, 'Ani Cahyani', 3],
            ['MKU007', 'Etika Profesi dan Kewirausahaan', 2, null, 5],
            ['MKU008', 'Metodologi Penelitian', 2, null, 5],
            ['MKU009', 'Kuliah Kerja Praktik (KKP)', 2, null, 7],
            ['MKU010', 'Kewirausahaan', 2, null, 6],
            ['MKU011', 'Tugas Akhir', 6, null, 8],
        ];

        $dosenMap = $this->getDosenMap();

        foreach ($matkulUmum as $mk) {
            $idDosen = isset($mk[3]) && isset($dosenMap[$mk[3]])
                ? $dosenMap[$mk[3]]
                : Dosen::inRandomOrder()->first()->id;

            Matkul::create([
                'kode_mk'  => $mk[0],
                'nama_mk'  => $mk[1],
                'bobot'    => $mk[2],
                'jenis'    => 'umum',
                'id_dosen' => $idDosen,
                'semester' => $mk[4],
            ]);
        }
    }

    /**
     * Mata Kuliah Teknik Informatika
     */
    private function seedMatkulTI($prodiId, $dosenMap)
    {
        $matakuliah = [
            // SEMESTER 1
            ['TTI101', 'Algoritma Pemrograman', 4, 'wajib', 1, null],
            ['TTI102', 'Pengenalan Pemrograman', 4, 'wajib', 1, null],
            ['TTI124', 'Kalkulus I', 3, 'wajib', 1, 'Khaerul Rizal'],
            ['TTI125', 'Matematika Diskrit', 3, 'wajib', 1, null],
            ['TTI143', 'Fisika I', 3, 'wajib', 1, 'Saeful Rohman'],
            ['TTI136', 'Pengantar Teknologi Informasi', 3, 'wajib', 1, null],

            // SEMESTER 2
            ['TTI203', 'Struktur Data', 4, 'wajib', 2, 'Iqbal Najmul Ahyar'],
            ['TTI204', 'Organisasi dan Arsitektur Komputer', 3, 'wajib', 2, 'Andre Septian'],
            ['TTI205', 'Basis Data', 3, 'wajib', 2, 'Fathi Nurdien Ali Rahman'],
            ['TTI225', 'Aljabar Linier', 3, 'wajib', 2, 'Syahidah A\'yun'],
            ['TTI244', 'Kalkulus II', 3, 'wajib', 2, 'Khaerul Rizal'],
            ['TTI245', 'Fisika II', 3, 'wajib', 2, 'Saeful Rohman'],

            // SEMESTER 3
            ['TTI306', 'Jaringan Komputer', 4, 'wajib', 3, null],
            ['TTI307', 'Sistem Operasi', 3, 'wajib', 3, null],
            ['TTI322', 'Logika Matematika', 3, 'wajib', 3, null],
            ['TTI308', 'Kompleksitas Algoritma', 3, 'wajib', 3, null],

            // SEMESTER 4
            ['TTI409', 'Rekayasa Perangkat Lunak', 3, 'wajib', 4, 'Fathi Nurdien Ali Rahman'],
            ['TTI410', 'Kecerdasan Buatan', 3, 'wajib', 4, 'Iqbal Najmul Ahyar'],
            ['TTI411', 'Pemrograman Berorientasi Objek', 3, 'wajib', 4, 'Bima Azis Kusuma'],
            ['TTI412', 'Human Computer Interaction', 3, 'wajib', 4, 'Harry Darmawan'],
            ['TTI413', 'Pengolahan Citra Digital', 3, 'wajib', 4, 'Andre Septian'],
            ['TTI437', 'Embedded System', 3, 'wajib', 4, 'Siswo Teguh'],

            // SEMESTER 5
            ['TTI514', 'Manajemen Proyek TI', 3, 'wajib', 5, null],
            ['TTI515', 'Analisis dan Desain Perangkat Lunak', 3, 'wajib', 5, null],
            ['TTI516', 'Komputasi Paralel dan Terdistribusi', 3, 'wajib', 5, null],
            ['TTI517', 'Pemrograman Berorientasi Platform', 4, 'wajib', 5, null],
            ['TTI518', 'Internet of Things', 3, 'wajib', 5, null],
            ['TTI538', 'Data Science', 3, 'wajib', 5, null],

            // SEMESTER 6
            ['TTI619', 'Keamanan Data dan Informasi', 3, 'wajib', 6, null],
            ['TTI620', 'Pembelajaran Mesin', 3, 'wajib', 6, null],
            ['TTI621', 'Cloud Computing', 3, 'wajib', 6, null],
            ['TTI622', 'Big Data', 3, 'wajib', 6, null],
            ['TTI639', 'Pemrograman Web', 3, 'wajib', 6, null],
            ['TTI634', 'Etika Profesi', 2, 'wajib', 6, null],
            ['TTI5647', 'KKN', 2, 'wajib', 6, null],

            // SEMESTER 7
            ['TTI723', 'Proyek Perangkat Lunak', 3, 'wajib', 7, null],
            ['TTI732', 'Kerja Praktik / Magang', 3, 'wajib', 7, null],
            ['TTI740', 'AI Computing Platform', 3, 'pilihan', 7, null],
            ['TTI741', 'Kriptografi', 3, 'pilihan', 7, null],
            ['TTI742', 'Wireless Sensor Network', 3, 'pilihan', 7, null],

            // SEMESTER 8
            ['TTI2833', 'Tugas Akhir', 6, 'wajib', 8, null],
            ['TTI2835', 'Hukum dan Kebijakan Teknologi Informasi', 2, 'wajib', 8, null],
        ];

        foreach ($matakuliah as $mk) {
            $idDosen = isset($mk[5]) && isset($dosenMap[$mk[5]])
                ? $dosenMap[$mk[5]]
                : Dosen::inRandomOrder()->first()->id;

            $matkul = Matkul::create([
                'kode_mk'  => $mk[0],
                'nama_mk'  => $mk[1],
                'bobot'    => $mk[2],
                'jenis'    => $mk[3],
                'id_dosen' => $idDosen,
                'semester' => $mk[4],
            ]);

            // Attach ke prodi TI
            $matkul->prodis()->attach($prodiId);
        }
    }

    /**
     * Mata Kuliah Teknik Elektro
     */
    private function seedMatkulTE($prodiId, $dosenMap)
    {
        $matakuliah = [
            // SEMESTER 1
            ['TTE101', 'Kalkulus I', 3, 'wajib', 1, null],
            ['TTE102', 'Fisika I', 3, 'wajib', 1, null],
            ['TTE103', 'Pengantar Teknik Elektro', 3, 'wajib', 1, null],
            ['TTE104', 'Dasar Pemrograman', 3, 'wajib', 1, null],
            ['TTE105', 'Gambar Teknik', 2, 'wajib', 1, null],

            // SEMESTER 2
            ['EB121', 'Instrumentasi dan Pengukuran', 2, 'wajib', 2, null],
            ['EB122', 'Kalkulus II', 3, 'wajib', 2, null],
            ['EB123', 'Kimia Dasar', 2, 'wajib', 2, null],
            ['EB124', 'Aljabar Linier dan Matriks', 3, 'wajib', 2, null],
            ['EB125', 'Fisika II', 3, 'wajib', 2, null],
            ['EB126', 'Rangkaian Listrik I (DC)', 3, 'wajib', 2, null],

            // SEMESTER 3
            ['EB131', 'Teknik Digital', 2, 'wajib', 3, null],
            ['EB131P', 'Praktikum Teknik Digital', 1, 'wajib', 3, null],
            ['EB132', 'Dasar Elektronika', 3, 'wajib', 3, null],
            ['EB132P', 'Praktikum Rangkaian Listrik', 1, 'wajib', 3, null],
            ['EB133', 'Medan Elektromagnetik', 3, 'wajib', 3, null],
            ['EB134', 'Rangkaian Listrik II (AC)', 3, 'wajib', 3, null],
            ['EB121P', 'Praktikum Instrumentasi & Pengukuran', 1, 'wajib', 3, null],
            ['EB135', 'Sinyal dan Sistem', 3, 'wajib', 3, null],

            // SEMESTER 4
            ['EB141', 'Sistem Komunikasi', 3, 'wajib', 4, null],
            ['EB142', 'Elektronika Analog', 3, 'wajib', 4, null],
            ['EB143', 'Mikroprosesor dan Mikrokontroler', 3, 'wajib', 4, null],
            ['EB144', 'Sistem Numerik', 3, 'wajib', 4, null],
            ['EB145', 'Elektronika Digital', 3, 'wajib', 4, null],
            ['EB146', 'Teknik Tenaga Listrik', 3, 'wajib', 4, null],
            ['EB141P', 'Praktik Sistem Komunikasi', 1, 'wajib', 4, null],
            ['EB146P', 'Praktik Teknik Tenaga Listrik', 1, 'wajib', 4, null],
            ['EB132P', 'Praktik Dasar Elektronika', 1, 'wajib', 4, null],

            // SEMESTER 5
            ['EK151', 'Sistem Kontrol', 3, 'wajib', 5, null],
            ['EL152', 'Mesin Listrik', 3, 'wajib', 5, null],
            ['EK253', 'Dasar Teknik Kendali', 2, 'wajib', 5, null],
            ['EL254', 'Energi Baru dan Terbarukan', 3, 'wajib', 5, null],
            ['ET155', 'Pemrosesan Sinyal Digital', 3, 'wajib', 5, null],
            ['EK356', 'Mekatronika', 2, 'wajib', 5, null],
            ['ET145P', 'Praktik Elektronika Digital', 1, 'wajib', 5, null],
            ['ET155P', 'Praktik Pemrosesan Sinyal Digital', 1, 'wajib', 5, null],

            // SEMESTER 6
            ['EL361', 'Sistem Distribusi Tenaga Listrik', 3, 'wajib', 6, null],
            ['EL462', 'Elektronika Daya', 3, 'wajib', 6, null],
            ['ET263', 'Radar dan Navigasi', 2, 'wajib', 6, null],
            ['ET364', 'Komunikasi Data dan Jaringan', 2, 'wajib', 6, null],
            ['EK465', 'Embedded System and Robotics', 3, 'wajib', 6, null],
            ['EB167', 'Manajemen Proyek Teknik', 2, 'wajib', 6, null],
            ['EK151P', 'Praktik Sistem Kontrol', 1, 'wajib', 6, null],
            ['EL462P', 'Antena dan Propagasi', 2, 'wajib', 6, null],
            ['TTE645', 'Praktik Elektronika Daya', 1, 'wajib', 6, null],

            // SEMESTER 7
            ['ET471', 'Sistem dan Teknologi Nirkabel', 2, 'wajib', 7, null],
            ['EB172', 'Capstone Design', 4, 'wajib', 7, null],

            // SEMESTER 8 (Pilihan)
            ['ET573', 'Teknik Optimasi', 3, 'pilihan', 8, null],
            ['EL574', 'Kendali Mesin Listrik', 3, 'pilihan', 8, null],
            ['EK575', 'Sistem Adaptif', 3, 'pilihan', 8, null],
        ];

        foreach ($matakuliah as $mk) {
            $idDosen = Dosen::inRandomOrder()->first()->id;

            $matkul = Matkul::create([
                'kode_mk'  => $mk[0],
                'nama_mk'  => $mk[1],
                'bobot'    => $mk[2],
                'jenis'    => $mk[3],
                'id_dosen' => $idDosen,
                'semester' => $mk[4],
            ]);

            $matkul->prodis()->attach($prodiId);
        }
    }

    /**
     * Mata Kuliah Teknik Mesin
     */
    private function seedMatkulTM($prodiId, $dosenMap)
    {
        $matakuliah = [
            // SEMESTER 1
            ['TMI101', 'Matematika I', 4, 'wajib', 1, null],
            ['TMI105', 'Fisika I', 4, 'wajib', 1, null],
            ['TMI107', 'Kimia Dasar', 3, 'wajib', 1, null],
            ['TM3138', 'Gambar Mesin I', 2, 'wajib', 1, null],

            // SEMESTER 2
            ['TMI202', 'Matematika II', 4, 'wajib', 2, null],
            ['TMI206', 'Fisika II', 4, 'wajib', 2, null],
            ['TMI208', 'Statistika dan Probabilitas', 3, 'wajib', 2, null],
            ['TMI209', 'K3L', 2, 'wajib', 2, null],
            ['TMI221', 'Bahan Material Teknik I', 3, 'wajib', 2, null],
            ['TMI223', 'Mekanika dan Kekuatan Bahan I', 2, 'wajib', 2, null],
            ['TMI225', 'Kinematika Dinamika', 2, 'wajib', 2, null],
            ['TM3233', 'Gambar Mesin II', 2, 'wajib', 2, null],

            // SEMESTER 3
            ['TMI301', 'Matematika III', 4, 'wajib', 3, null],
            ['TM2312', 'Bahan Material Teknik II', 3, 'wajib', 3, null],
            ['TM2314', 'Mekanika dan Kekuatan Bahan II', 3, 'wajib', 3, null],
            ['TM2316', 'Kinematika Dinamika II', 2, 'wajib', 3, null],
            ['TM2320', 'Termodinamika I', 3, 'wajib', 3, null],
            ['TM2322', 'Mekanika Fluida I', 2, 'wajib', 3, null],
            ['TM2324', 'Perpindahan Kalor dan Massa I', 3, 'wajib', 3, null],
            ['TM3342', 'Elemen Mesin I', 2, 'wajib', 3, null],

            // SEMESTER 4
            ['TMI404', 'Matematika IV', 3, 'wajib', 4, null],
            ['TMI417', 'Getaran Mekanik', 3, 'wajib', 4, null],
            ['TM2421', 'Termodinamika II', 3, 'wajib', 4, null],
            ['TM2423', 'Mekanika Fluida II', 3, 'wajib', 4, null],
            ['TM2425', 'Perpindahan Kalor dan Massa II', 2, 'wajib', 4, null],
            ['TM2429', 'Pengukuran Teknik / Metrologi', 2, 'wajib', 4, null],
            ['TM3440', 'Proses Manufaktur I', 3, 'wajib', 4, null],
            ['TM3442', 'Elemen Mesin II', 2, 'wajib', 4, null],

            // SEMESTER 5
            ['TM2525', 'Pengukuran Teknik / Metrologi', 2, 'wajib', 5, null],
            ['TM2526', 'Teknik Tenaga Listrik', 3, 'wajib', 5, null],
            ['TM3536', 'Metalurgi', 2, 'wajib', 5, null],
            ['TM3541', 'Proses Manufaktur II', 3, 'wajib', 5, null],
            ['TM3544', 'Mesin Konversi Energi', 3, 'wajib', 5, null],
            ['TM3545', 'Sistem Kendali Kontrol', 2, 'wajib', 5, null],
            ['TM3546', 'Mekatronika', 3, 'wajib', 5, null],

            // SEMESTER 6
            ['TM2601', 'Hukum Tenaga Kerja dan Industri', 2, 'wajib', 6, null],
            ['TM3633', 'Sistem Hidrolik & Pneumatik', 2, 'wajib', 6, null],
            ['TM3634', 'Teknik Pemeliharaan Mesin', 2, 'wajib', 6, null],
            ['TM3635', 'Teknik Pengelasan', 2, 'wajib', 6, null],
            ['TM3637', 'Praktik Metalurgi', 3, 'wajib', 6, null],
            ['TM3647', 'Capstone Design', 3, 'wajib', 6, null],

            // SEMESTER 7
            ['TM3722', 'CAD/CAM', 2, 'wajib', 7, null],
            ['TM3723', 'Praktikum CNC', 2, 'wajib', 7, null],
            ['TM3728', 'Praktikum Proses Produksi', 4, 'wajib', 7, null],
            ['TM3736', 'Praktikum Fenomena Dasar Mesin', 1, 'wajib', 7, null],
            ['TM3737', 'Praktikum Prestasi Mesin', 1, 'wajib', 7, null],
            ['TM3739', 'Manajemen Proyek', 2, 'wajib', 7, null],
            ['TM3742', 'Kerja Praktek', 2, 'wajib', 7, null],

            // SEMESTER 8
            ['TM3845', 'Skripsi / Tugas Akhir', 6, 'wajib', 8, null],
        ];

        foreach ($matakuliah as $mk) {
            $idDosen = Dosen::inRandomOrder()->first()->id;

            $matkul = Matkul::create([
                'kode_mk'  => $mk[0],
                'nama_mk'  => $mk[1],
                'bobot'    => $mk[2],
                'jenis'    => $mk[3],
                'id_dosen' => $idDosen,
                'semester' => $mk[4],
            ]);

            $matkul->prodis()->attach($prodiId);
        }
    }
}
