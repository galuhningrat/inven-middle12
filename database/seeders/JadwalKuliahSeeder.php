<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * JadwalKuliahSeeder
 *
 * Menyeed jadwal kuliah berdasarkan jadwal resmi Semester Genap 2025/2026
 * untuk 4 kelas Teknik Informatika STTI:
 *   - TI 25 A (Semester 2) → rombel_id = 8
 *   - TI 25 B (Semester 2) → rombel_id = 9
 *   - TI 24 A (Semester 4) → rombel_id = 2
 *   - TI 24 B (Semester 4) → rombel_id = 5
 *
 * Asumsi tabel: jadwal_kuliah
 *   - id, id_rombel, id_matkul, id_dosen, id_ruangan,
 *     hari, jam_mulai, jam_selesai,
 *     semester, tahun_akademik,
 *     created_at, updated_at
 *
 * Dosen dicari dengan LIKE pada tabel users.nama (join ke dosen)
 * sehingga perbedaan penulisan gelar tidak menyebabkan error.
 *
 * Ruangan:
 *   "Kelas 1"     → id = 1 (KLS-001)
 *   "Kelas 2"     → id = 2 (KLS-002)
 *   "Lab Komputer" → id = 4 (LAB1)
 */
class JadwalKuliahSeeder extends Seeder
{
    const TABLE_JADWAL = 'jadwal';
    const TA           = '2025/2026';

    // ── Helper: cari id_dosen berdasarkan kata kunci nama (LIKE) ──────────
    private function findDosenId(string $keyword): ?int
    {
        $result = DB::table('dosen')
            ->join('users', 'dosen.id_users', '=', 'users.id')
            ->where('users.nama', 'LIKE', "%{$keyword}%")
            ->select('dosen.id')
            ->first();

        if (!$result) {
            $this->command->warn("  ⚠  Dosen LIKE '%{$keyword}%' tidak ditemukan, field id_dosen akan NULL.");
            return null;
        }

        return $result->id;
    }

    // ── Helper: cari id_matkul berdasarkan kata kunci nama_mk (LIKE) ─────
    private function findMatkulId(string $keyword): ?int
    {
        $result = DB::table('matkul')
            ->where('nama_mk', 'LIKE', "%{$keyword}%")
            ->select('id')
            ->first();

        if (!$result) {
            $this->command->warn("  ⚠  Matakuliah LIKE '%{$keyword}%' tidak ditemukan, jadwal item ini dilewati.");
            return null;
        }

        return $result->id;
    }

    // ── Helper: map nama ruangan ke id ────────────────────────────────────
    private function ruanganId(string $namaRuangan): ?int
    {
        static $map = null;

        if ($map === null) {
            $ruangans = DB::table('ruangan')->get();
            $map = [];
            foreach ($ruangans as $r) {
                $map[strtolower($r->nama_ruang)] = $r->id;
                if (isset($r->kode_ruang)) {
                    $map[strtolower($r->kode_ruang)] = $r->id;
                }
            }
        }

        // Coba exact match (case-insensitive)
        $key = strtolower(trim($namaRuangan));
        if (isset($map[$key])) {
            return $map[$key];
        }

        // Fallback: LIKE
        $result = DB::table('ruangan')
            ->where('nama_ruang', 'LIKE', "%{$namaRuangan}%")
            ->select('id')
            ->first();

        return $result?->id;
    }

    // ── Insert satu baris jadwal ──────────────────────────────────────────
    private function insertJadwal(
        int     $rombelId,
        int     $semester,
        ?int    $matkulId,
        ?int    $dosenId,
        ?int    $ruanganId,
        string  $hari,
        string  $jamMulai,
        string  $jamSelesai,
        Carbon  $now
    ): void {
        if (!$matkulId) return; // Skip jika matkul tidak ditemukan

        // Hindari duplikat
        $exists = DB::table(self::TABLE_JADWAL)
            ->where('id_rombel',      $rombelId)
            ->where('id_matkul',      $matkulId)
            ->where('hari',           $hari)
            ->where('jam_mulai',      $jamMulai)
            ->where('tahun_akademik', self::TA)
            ->exists();

        if ($exists) return;

        DB::table(self::TABLE_JADWAL)->insert([
            'id_rombel'      => $rombelId,
            'id_matkul'      => $matkulId,
            'id_dosen'       => $dosenId,
            'id_ruangan'     => $ruanganId,
            'hari'           => $hari,
            'jam_mulai'      => $jamMulai,
            'jam_selesai'    => $jamSelesai,
            'semester'       => $semester,
            'tahun_akademik' => self::TA,
            'created_at'     => $now,
            'updated_at'     => $now,
        ]);
    }

    public function run(): void
    {
        $now = Carbon::now();

        // ─────────────────────────────────────────────────────────────────
        // Pre-load dosen IDs (LIKE pencarian)
        // ─────────────────────────────────────────────────────────────────
        $dosen = [
            // Kunci pencarian ↓           Kata kunci LIKE
            'iqbal'     => $this->findDosenId('Iqbal'),
            'andre'     => $this->findDosenId('Andre'),
            'alirahman' => $this->findDosenId('Ali Rahman'),
            'syahidah'  => $this->findDosenId('Syahidah'),
            'qais'      => $this->findDosenId('Ghaziyudin'),
            'rizal'     => $this->findDosenId('Rizal'),
            'saeful'    => $this->findDosenId('Saeful'),
            'bima'      => $this->findDosenId('Bima'),
            'harry'     => $this->findDosenId('Harry'),
            'ani'       => $this->findDosenId('Ani Cahyani'),
            'siswo'     => $this->findDosenId('Siswo'),
        ];

        // ─────────────────────────────────────────────────────────────────
        // Pre-load matakuliah IDs
        // ─────────────────────────────────────────────────────────────────
        $mk = [
            // Semester 2 TI
            'struktur_data' => $this->findMatkulId('Struktur Data'),
            'oak'           => $this->findMatkulId('Organisasi dan Arsitektur Komputer'),
            'basis_data'    => $this->findMatkulId('Basis Data'),
            'aljabar'       => $this->findMatkulId('Aljabar Linier'),
            'bahasa_ind'    => $this->findMatkulId('Bahasa Indonesia'),
            'kalkulus2'     => $this->findMatkulId('Kalkulus II'),
            'fisika2'       => $this->findMatkulId('Fisika II'),
            // Semester 4 TI
            'rpl'           => $this->findMatkulId('Rekayasa Perangkat Lunak'),
            'kb'            => $this->findMatkulId('Kecerdasan Buatan'),
            'pbo'           => $this->findMatkulId('Pemrograman Berorientasi Objek'),
            'hci'           => $this->findMatkulId('Human Computer Interaction'),
            'citra'         => $this->findMatkulId('Pengolahan Citra Digital'),
            'prob_stat'     => $this->findMatkulId('Probabilitas'),  // akan cocok dgn "Probabilitas dan Statistik"
            'embedded'      => $this->findMatkulId('Embedded System'),
        ];

        // ─────────────────────────────────────────────────────────────────
        // Ruangan
        // ─────────────────────────────────────────────────────────────────
        $kls1 = $this->ruanganId('Ruang Kuliah 001') ?? 1;
        $kls2 = $this->ruanganId('Ruang Kuliah 002') ?? 2;
        $lab  = $this->ruanganId('Lab Komputer 1')   ?? 4;

        // ═════════════════════════════════════════════════════════════════
        // KELAS TI 25 A — Semester 2 — rombel_id = 8
        // ═════════════════════════════════════════════════════════════════
        $r = 8; $smt = 2;
        $this->command->info("\n[TI 25 A] Smt {$smt} — rombel {$r}");

        // SENIN
        $this->insertJadwal($r,$smt, $mk['basis_data'],  $dosen['alirahman'], $lab,  'Senin',  '08:00', '09:40', $now);
        $this->insertJadwal($r,$smt, $mk['oak'],          $dosen['andre'],     $lab,  'Senin',  '09:40', '12:10', $now);
        $this->insertJadwal($r,$smt, $mk['struktur_data'],$dosen['iqbal'],     $lab,  'Senin',  '13:00', '16:20', $now);
        // SELASA
        $this->insertJadwal($r,$smt, $mk['basis_data'],  $dosen['alirahman'], $lab,  'Selasa', '09:40', '10:30', $now);
        // RABU
        $this->insertJadwal($r,$smt, $mk['fisika2'],     $dosen['saeful'],    $kls2, 'Rabu',   '08:00', '10:30', $now);
        $this->insertJadwal($r,$smt, $mk['bahasa_ind'],  $dosen['qais'],      $kls2, 'Rabu',   '10:30', '12:10', $now);
        $this->insertJadwal($r,$smt, $mk['aljabar'],     $dosen['syahidah'],  $kls2, 'Rabu',   '13:00', '15:30', $now);
        // KAMIS
        $this->insertJadwal($r,$smt, $mk['kalkulus2'],   $dosen['rizal'],     $kls1, 'Kamis',  '08:00', '10:30', $now);

        // ═════════════════════════════════════════════════════════════════
        // KELAS TI 25 B — Semester 2 — rombel_id = 9
        // ═════════════════════════════════════════════════════════════════
        $r = 9; $smt = 2;
        $this->command->info("\n[TI 25 B] Smt {$smt} — rombel {$r}");

        // SENIN
        $this->insertJadwal($r,$smt, $mk['kalkulus2'],    $dosen['rizal'],     $kls2, 'Senin',  '08:00', '10:30', $now);
        // SELASA
        $this->insertJadwal($r,$smt, $mk['fisika2'],      $dosen['saeful'],    $kls2, 'Selasa', '08:00', '10:30', $now);
        $this->insertJadwal($r,$smt, $mk['bahasa_ind'],   $dosen['qais'],      $kls2, 'Selasa', '10:30', '12:10', $now);
        // RABU
        $this->insertJadwal($r,$smt, $mk['oak'],          $dosen['andre'],     $lab,  'Rabu',   '08:00', '10:30', $now);
        // KAMIS
        $this->insertJadwal($r,$smt, $mk['basis_data'],   $dosen['alirahman'], $lab,  'Kamis',  '08:00', '10:30', $now);
        // JUMAT
        $this->insertJadwal($r,$smt, $mk['struktur_data'],$dosen['iqbal'],     $lab,  'Jumat',  '08:00', '11:20', $now);
        // SABTU
        $this->insertJadwal($r,$smt, $mk['aljabar'],      $dosen['syahidah'],  $kls1, 'Sabtu',  '08:00', '10:30', $now);

        // ═════════════════════════════════════════════════════════════════
        // KELAS TI 24 A — Semester 4 — rombel_id = 2
        // ═════════════════════════════════════════════════════════════════
        $r = 2; $smt = 4;
        $this->command->info("\n[TI 24 A] Smt {$smt} — rombel {$r}");

        // SENIN
        $this->insertJadwal($r,$smt, $mk['hci'],      $dosen['harry'],     $kls1, 'Senin',  '13:00', '15:30', $now);
        // SELASA
        $this->insertJadwal($r,$smt, $mk['kb'],       $dosen['iqbal'],     $kls1, 'Selasa', '08:50', '11:20', $now);
        $this->insertJadwal($r,$smt, $mk['rpl'],      $dosen['alirahman'], $kls1, 'Selasa', '13:00', '15:30', $now);
        // RABU
        $this->insertJadwal($r,$smt, $mk['prob_stat'],$dosen['ani'],       $kls1, 'Rabu',   '09:40', '12:10', $now);
        $this->insertJadwal($r,$smt, $mk['pbo'],      $dosen['bima'],      $kls1, 'Rabu',   '13:00', '15:30', $now);
        // KAMIS
        $this->insertJadwal($r,$smt, $mk['citra'],    $dosen['andre'],     $kls1, 'Kamis',  '13:00', '15:30', $now);
        // JUMAT
        $this->insertJadwal($r,$smt, $mk['embedded'], $dosen['siswo'],     $kls1, 'Jumat',  '13:00', '15:30', $now);

        // ═════════════════════════════════════════════════════════════════
        // KELAS TI 24 B — Semester 4 — rombel_id = 5
        // ═════════════════════════════════════════════════════════════════
        $r = 5; $smt = 4;
        $this->command->info("\n[TI 24 B] Smt {$smt} — rombel {$r}");

        // SENIN
        $this->insertJadwal($r,$smt, $mk['kb'],       $dosen['iqbal'],     $lab,  'Senin',  '13:00', '16:20', $now);
        // SELASA
        $this->insertJadwal($r,$smt, $mk['embedded'], $dosen['siswo'],     $lab,  'Selasa', '13:00', '15:30', $now);
        // RABU
        $this->insertJadwal($r,$smt, $mk['prob_stat'],$dosen['ani'],       $kls1, 'Rabu',   '09:40', '12:10', $now);
        $this->insertJadwal($r,$smt, $mk['citra'],    $dosen['andre'],     $lab,  'Rabu',   '13:00', '15:30', $now);
        // KAMIS
        $this->insertJadwal($r,$smt, $mk['pbo'],      $dosen['bima'],      $lab,  'Kamis',  '09:40', '12:10', $now);
        $this->insertJadwal($r,$smt, $mk['rpl'],      $dosen['alirahman'], $lab,  'Kamis',  '13:00', '15:30', $now);
        // JUMAT
        $this->insertJadwal($r,$smt, $mk['hci'],      $dosen['harry'],     $lab,  'Jumat',  '13:00', '15:30', $now);

        $this->command->info("\n✅ JadwalKuliahSeeder selesai.");
    }
}
