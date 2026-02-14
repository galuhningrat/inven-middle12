<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Jadwal;
use App\Models\Matkul;
use App\Models\Ruangan;
use App\Models\Kelas;

class JadwalSeeder extends Seeder
{
    /**
     * Seeder Jadwal Kuliah berdasarkan PDF yang diberikan:
     * - TI 25 A (Semester 2)
     * - TI 25 B (Semester 2)
     * - TI 24 A (Semester 4)
     * - TI 24 B (Semester 4)
     */
    public function run()
    {
        $this->command->info('🚀 Seeding Jadwal Kuliah TI 25 A...');
        $this->seedJadwalTI25A();

        $this->command->info('🚀 Seeding Jadwal Kuliah TI 25 B...');
        $this->seedJadwalTI25B();

        $this->command->info('🚀 Seeding Jadwal Kuliah TI 24 A...');
        $this->seedJadwalTI24A();

        $this->command->info('🚀 Seeding Jadwal Kuliah TI 24 B...');
        $this->seedJadwalTI24B();

        $this->command->info('✅ Seeder Jadwal Kuliah selesai!');
    }

    /**
     * Helper: Get Kelas ID
     */
    private function getKelasId($namaKelas)
    {
        $kelas = Kelas::where('nama_kelas', $namaKelas)->first();
        if (!$kelas) {
            $this->command->warn("⚠️  Kelas '{$namaKelas}' tidak ditemukan!");
        }
        return $kelas ? $kelas->id : null;
    }

    /**
     * Helper: Get Matkul ID by Kode
     */
    private function getMatkulId($kodeMk)
    {
        $matkul = Matkul::where('kode_mk', $kodeMk)->first();
        if (!$matkul) {
            $this->command->warn("⚠️  Mata Kuliah '{$kodeMk}' tidak ditemukan!");
        }
        return $matkul ? $matkul->id : null;
    }

    /**
     * Helper: Get Ruangan ID by Nama
     */
    private function getRuanganId($namaRuangan)
    {
        $ruangan = Ruangan::where('nama_ruangan', 'LIKE', "%{$namaRuangan}%")->first();
        if (!$ruangan) {
            $this->command->warn("⚠️  Ruangan '{$namaRuangan}' tidak ditemukan!");
        }
        return $ruangan ? $ruangan->id : null;
    }

    /**
     * JADWAL TI 25 A (Semester 2)
     */
    private function seedJadwalTI25A()
    {
        $kelasId = $this->getKelasId('TI 25 A');
        if (!$kelasId) return;

        $jadwal = [
            // SENIN
            ['TTI205', 1, '08:00', '10:30', 'Lab Komputer'],  // Basis Data (3 SKS)

            // RABU
            ['TTI204', 3, '09:40', '12:10', 'Lab Komputer'],  // Organisasi dan Arsitektur Komputer (3 SKS)
            ['TTI203', 3, '13:00', '16:20', 'Lab Komputer'],  // Struktur Data (4 SKS)

            // KAMIS
            ['TTI205', 4, '09:40', '10:30', 'Lab Komputer'],  // Basis Data (1 SKS)
            ['MKU002', 4, '10:30', '12:10', 'Kelas 2'],       // Bahasa Indonesia (2 SKS)

            // JUMAT
            ['TTI245', 5, '08:00', '10:30', 'Kelas 2'],       // Fisika 2 (3 SKS)

            // SABTU
            ['TTI244', 6, '08:00', '10:30', 'Kelas 1'],       // Kalkulus 2 (3 SKS)
            ['TTI225', 6, '13:00', '15:30', 'Kelas 2'],       // Aljabar Linier (3 SKS)
        ];

        foreach ($jadwal as $j) {
            $matkulId = $this->getMatkulId($j[0]);
            $ruanganId = $this->getRuanganId($j[4]);

            if ($matkulId && $ruanganId) {
                Jadwal::create([
                    'id_matkul'   => $matkulId,
                    'id_ruangan'  => $ruanganId,
                    'id_kelas'    => $kelasId,
                    'hari'        => $j[1],
                    'waktu_mulai' => $j[2],
                    'waktu_selesai' => $j[3],
                ]);
            }
        }
    }

    /**
     * JADWAL TI 25 B (Semester 2)
     */
    private function seedJadwalTI25B()
    {
        $kelasId = $this->getKelasId('TI 25 B');
        if (!$kelasId) return;

        $jadwal = [
            // SENIN
            ['TTI244', 1, '08:00', '10:30', 'Kelas 2'],       // Kalkulus 2 (3 SKS)
            ['MKU002', 1, '10:30', '12:10', 'Kelas 2'],       // Bahasa Indonesia (2 SKS)

            // SELASA
            ['TTI245', 2, '08:00', '10:30', 'Kelas 2'],       // Fisika 2 (3 SKS)

            // RABU
            ['TTI204', 3, '08:00', '10:30', 'Lab Komputer'],  // Organisasi dan Arsitektur Komputer (3 SKS)

            // KAMIS
            ['TTI205', 4, '08:00', '10:30', 'Lab Komputer'],  // Basis Data (3 SKS)

            // JUMAT
            ['TTI203', 5, '08:00', '11:20', 'Lab Komputer'],  // Struktur Data (4 SKS)

            // SABTU
            ['TTI225', 6, '08:00', '10:30', 'Kelas 1'],       // Aljabar Linier (3 SKS)
        ];

        foreach ($jadwal as $j) {
            $matkulId = $this->getMatkulId($j[0]);
            $ruanganId = $this->getRuanganId($j[4]);

            if ($matkulId && $ruanganId) {
                Jadwal::create([
                    'id_matkul'   => $matkulId,
                    'id_ruangan'  => $ruanganId,
                    'id_kelas'    => $kelasId,
                    'hari'        => $j[1],
                    'waktu_mulai' => $j[2],
                    'waktu_selesai' => $j[3],
                ]);
            }
        }
    }

    /**
     * JADWAL TI 24 A (Semester 4)
     */
    private function seedJadwalTI24A()
    {
        $kelasId = $this->getKelasId('TI 24 A');
        if (!$kelasId) return;

        $jadwal = [
            // SENIN
            ['TTI412', 1, '13:00', '15:30', 'Kelas 1'],       // Human-Computer Interaction (3 SKS)

            // SELASA
            ['TTI410', 2, '08:50', '12:10', 'Kelas 1'],       // Kecerdasan Buatan (4 SKS)
            ['TTI409', 2, '13:00', '15:30', 'Kelas 1'],       // Rekayasa Perangkat Lunak (3 SKS)

            // RABU
            ['MKU006', 3, '09:40', '12:10', 'Kelas 1'],       // Probabilitas Statistika (3 SKS)
            ['TTI411', 3, '13:00', '15:30', 'Kelas 1'],       // Pemrograman Berorientasi Objek (3 SKS)

            // KAMIS
            ['TTI413', 4, '13:00', '15:30', 'Kelas 1'],       // Pengolahan Citra Digital (3 SKS)

            // JUMAT
            ['TTI437', 5, '13:00', '15:30', 'Kelas 1'],       // Embedded System (3 SKS)
        ];

        foreach ($jadwal as $j) {
            $matkulId = $this->getMatkulId($j[0]);
            $ruanganId = $this->getRuanganId($j[4]);

            if ($matkulId && $ruanganId) {
                Jadwal::create([
                    'id_matkul'   => $matkulId,
                    'id_ruangan'  => $ruanganId,
                    'id_kelas'    => $kelasId,
                    'hari'        => $j[1],
                    'waktu_mulai' => $j[2],
                    'waktu_selesai' => $j[3],
                ]);
            }
        }
    }

    /**
     * JADWAL TI 24 B (Semester 4)
     */
    private function seedJadwalTI24B()
    {
        $kelasId = $this->getKelasId('TI 24 B');
        if (!$kelasId) return;

        $jadwal = [
            // SENIN
            ['TTI410', 1, '13:00', '16:20', 'Lab Komputer'],  // Kecerdasan Buatan (4 SKS)

            // SELASA
            ['TTI437', 2, '13:00', '15:30', 'Lab Komputer'],  // Embedded System (3 SKS)

            // RABU
            ['MKU006', 3, '09:40', '12:10', 'Kelas 1'],       // Probabilitas Statistika (3 SKS)
            ['TTI413', 3, '13:00', '15:30', 'Lab Komputer'],  // Pengolahan Citra Digital (3 SKS)

            // KAMIS
            ['TTI411', 4, '09:40', '12:10', 'Lab Komputer'],  // Pemrograman Berorientasi Objek (3 SKS)
            ['TTI409', 4, '13:00', '15:30', 'Lab Komputer'],  // Rekayasa Perangkat Lunak (3 SKS)

            // SABTU
            ['TTI412', 6, '13:00', '15:30', 'Lab Komputer'],  // Human-Computer Interaction (3 SKS)
        ];

        foreach ($jadwal as $j) {
            $matkulId = $this->getMatkulId($j[0]);
            $ruanganId = $this->getRuanganId($j[4]);

            if ($matkulId && $ruanganId) {
                Jadwal::create([
                    'id_matkul'   => $matkulId,
                    'id_ruangan'  => $ruanganId,
                    'id_kelas'    => $kelasId,
                    'hari'        => $j[1],
                    'waktu_mulai' => $j[2],
                    'waktu_selesai' => $j[3],
                ]);
            }
        }
    }
}
