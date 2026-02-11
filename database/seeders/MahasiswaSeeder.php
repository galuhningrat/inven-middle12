<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Hash;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Sample data berdasarkan file Excel yang diberikan
     */
    public function run(): void
    {
        $rombel = \App\Models\Rombel::firstOrCreate(
            ['id' => 1],
            [
                'nama_rombel' => 'TI24A', // Sesuaikan dengan kebutuhan
                'id_prodi' => $prodi->id,
                'id_tahun_akademik' => $tahunAkademik->id,
            ]
        );

        // Ambil data relasi
        $prodi = Prodi::where('nama_prodi', 'LIKE', '%Teknik Informatika%')->first();
        $tahunAkademik = TahunAkademik::where('tahun_awal', '2024')->first();

        if (!$prodi || !$tahunAkademik) {
            $this->command->error('Prodi atau Tahun Akademik tidak ditemukan. Pastikan data sudah di-seed terlebih dahulu.');
            return;
        }

        // Sample Data dari Excel
        $sampleData = [
            [
                // USER DATA
                'nama' => 'GALUH ROKHMANNUDIN',
                'email' => 'galuhningrat234@gmail.com',

                // MAHASISWA DATA
                'nim' => '24031011',
                'nik' => '3209242302040001',
                'no_kk' => '3209242702069592',
                'nisn' => '0391961', // Dari no ijazah di Excel
                'tempat_lahir' => 'Cirebon',
                'tanggal_lahir' => '2004-02-23',
                'jenis_kelamin' => 'Laki-laki',
                'agama' => 'Islam',
                'gol_darah' => 'AB',
                'marital_status' => 'Lajang',
                'kewarganegaraan' => 'WNI',
                'status' => 'Aktif',
                'kelas' => 'TI24A',

                // ALAMAT
                'dusun' => 'Jalan Ki Badang Samaran',
                'rt' => '002',
                'rw' => '002',
                'ds_kel' => 'Bulak',
                'kec' => 'Arjawinangun',
                'kab' => 'Kabupaten Cirebon',
                'prov' => 'Jawa Barat',
                'kode_pos' => '45162',
                'hp' => '083156969433',

                // AKADEMIK
                'asal_sekolah' => 'SMK NEGERI 1 JAMBLANG',
                'pekerjaan' => 'AsLab',
                'ips_1' => 2.50,
                'ips_2' => 2.76,
                'nilai_test_pmb' => null,

                // DATA AYAH
                'nama_ayah' => 'Sunara',
                'pendidikan_ayah' => 'SMP',
                'pekerjaan_ayah' => 'Wiraswasta',
                'penghasilan_ayah' => 'Rp.1.100.000 - Rp.2.000.000',
                'hp_ayah' => '082132481096',
                'alamat_ayah' => 'Jalan Ki Badang Samaran, RT 02 RW 02, No. 42, Bulak, Arjawinangun, Kabupaten Cirebon',

                // DATA IBU
                'nama_ibu' => 'Runasih',
                'pendidikan_ibu' => 'SD',
                'pekerjaan_ibu' => 'IRT',
                'penghasilan_ibu' => 'Rp.0 - Rp.1.000.000',
                'hp_ibu' => '088220793483',
                'alamat_ibu' => 'Jalan Ki Badang Samaran, RT 02 RW 02, No. 42, Bulak, Arjawinangun, Kabupaten Cirebon',

                // KELENGKAPAN (Semua SDH = true untuk sample)
                'hardfile_surat_pernyataan' => true,
                'hardfile_pas_foto' => true,
                'hardfile_ktp_mhs' => true,
                'hardfile_kk' => true,
                'hardfile_akte' => true,
                'hardfile_ktp_ayah' => true,
                'hardfile_ktp_ibu' => true,
                'hardfile_skl' => true,
                'hardfile_transkrip' => true,
                'hardfile_ijazah' => true,
            ],
            // Tambahkan data mahasiswa lain di sini jika ada
        ];

        foreach ($sampleData as $data) {
            // Cek apakah user sudah ada
            $user = User::where('email', $data['email'])->first();

            if (!$user) {
                // Create User
                $user = User::create([
                    'nama' => $data['nama'],
                    'email' => $data['email'],
                    'password' => Hash::make('password123'), // Default password
                    'id_role' => 4, // Role Mahasiswa
                    // 'status_aktif' => 'true',
                ]);

                $this->command->info("✓ User created: {$data['nama']} ({$data['email']})");
            } else {
                $this->command->warn("⚠ User already exists: {$data['email']}");
            }

            // Cek apakah mahasiswa sudah ada
            $mahasiswa = Mahasiswa::where('nim', $data['nim'])->first();

            if (!$mahasiswa) {
                // Create Mahasiswa
                Mahasiswa::create([
                    'id_users' => $user->id,
                    'id_prodi' => $prodi->id,
                    'id_rombel' => 1, // Default, adjust if needed
                    'nim' => $data['nim'],
                    'nik' => $data['nik'],
                    'no_kk' => $data['no_kk'],
                    'nisn' => $data['nisn'],
                    'tempat_lahir' => $data['tempat_lahir'],
                    'tanggal_lahir' => $data['tanggal_lahir'],
                    'jenis_kelamin' => $data['jenis_kelamin'],
                    'agama' => $data['agama'],
                    'gol_darah' => $data['gol_darah'],
                    'marital_status' => $data['marital_status'],
                    'kewarganegaraan' => $data['kewarganegaraan'],
                    'status' => $data['status'],
                    'kelas' => $data['kelas'],
                    'dusun' => $data['dusun'],
                    'rt' => $data['rt'],
                    'rw' => $data['rw'],
                    'ds_kel' => $data['ds_kel'],
                    'kec' => $data['kec'],
                    'kab' => $data['kab'],
                    'prov' => $data['prov'],
                    'kode_pos' => $data['kode_pos'],
                    'hp' => $data['hp'],
                    'asal_sekolah' => $data['asal_sekolah'],
                    'pekerjaan' => $data['pekerjaan'],
                    'ips_1' => $data['ips_1'],
                    'ips_2' => $data['ips_2'],
                    'nilai_test_pmb' => $data['nilai_test_pmb'],
                    'tahun_masuk' => $tahunAkademik->id,
                    'nama_ayah' => $data['nama_ayah'],
                    'pendidikan_ayah' => $data['pendidikan_ayah'],
                    'pekerjaan_ayah' => $data['pekerjaan_ayah'],
                    'penghasilan_ayah' => $data['penghasilan_ayah'],
                    'hp_ayah' => $data['hp_ayah'],
                    'alamat_ayah' => $data['alamat_ayah'],
                    'nama_ibu' => $data['nama_ibu'],
                    'pendidikan_ibu' => $data['pendidikan_ibu'],
                    'pekerjaan_ibu' => $data['pekerjaan_ibu'],
                    'penghasilan_ibu' => $data['penghasilan_ibu'],
                    'hp_ibu' => $data['hp_ibu'],
                    'alamat_ibu' => $data['alamat_ibu'],
                    'hardfile_surat_pernyataan' => $data['hardfile_surat_pernyataan'],
                    'hardfile_pas_foto' => $data['hardfile_pas_foto'],
                    'hardfile_ktp_mhs' => $data['hardfile_ktp_mhs'],
                    'hardfile_kk' => $data['hardfile_kk'],
                    'hardfile_akte' => $data['hardfile_akte'],
                    'hardfile_ktp_ayah' => $data['hardfile_ktp_ayah'],
                    'hardfile_ktp_ibu' => $data['hardfile_ktp_ibu'],
                    'hardfile_skl' => $data['hardfile_skl'],
                    'hardfile_transkrip' => $data['hardfile_transkrip'],
                    'hardfile_ijazah' => $data['hardfile_ijazah'],
                ]);

                $this->command->info("✓ Mahasiswa created: {$data['nim']} - {$data['nama']}");
                $this->command->line("  ├─ Hardfile: 100%");
                $this->command->line("  ├─ IPS 1: {$data['ips_1']}");
                $this->command->line("  └─ IPS 2: {$data['ips_2']}");
            } else {
                $this->command->warn("⚠ Mahasiswa already exists: {$data['nim']}");
            }
        }

        $this->command->info("\n✅ Seeding completed!");
        $this->command->info("Default password: password123");
        $this->command->info("Email: {$sampleData[0]['email']}");
    }
}
