<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'id_users',
        'id_prodi',
        'id_rombel',
        'nim',
        'nik',
        'no_kk',
        'nisn',
        'kelas',
        'pekerjaan',
        'bagian_pekerjaan',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'dusun',
        'rt',
        'rw',
        'ds_kel',
        'kec',
        'kab',
        'prov',
        'kode_pos',
        'hp',
        'marital_status',
        'kewarganegaraan',
        'gol_darah',
        'status',
        'tahun_masuk',
        'tahun_keluar',
        'asal_sekolah',
        'jurusan_sekolah',
        'alamat_sekolah',
        'tahun_lulus_sekolah',
        'no_ijazah_sekolah',
        'nilai_test_pmb',
        // Data Orang Tua
        'nama_ayah',
        'tempat_lahir_ayah',
        'tanggal_lahir_ayah',
        'pendidikan_ayah',
        'pekerjaan_ayah',
        'penghasilan_ayah',
        'hp_ayah',
        'alamat_ayah',
        'nama_ibu',
        'tempat_lahir_ibu',
        'tanggal_lahir_ibu',
        'pendidikan_ibu',
        'pekerjaan_ibu',
        'penghasilan_ibu',
        'hp_ibu',
        'alamat_ibu',
        // Softfile & Hardfile fields tetap sama...
        'softfile_surat_pernyataan',
        'softfile_pas_foto',
        'softfile_ktp_mhs',
        'softfile_kk',
        'softfile_akte',
        'softfile_ktp_ayah',
        'softfile_ktp_ibu',
        'softfile_skl',
        'softfile_transkrip',
        'softfile_ijazah',
        'hardfile_surat_pernyataan',
        'hardfile_pas_foto',
        'hardfile_ktp_mhs',
        'hardfile_kk',
        'hardfile_akte',
        'hardfile_ktp_ayah',
        'hardfile_ktp_ibu',
        'hardfile_skl',
        'hardfile_transkrip',
        'hardfile_ijazah',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'nilai_test_pmb' => 'decimal:2',
        'hardfile_surat_pernyataan' => 'boolean',
        'hardfile_pas_foto' => 'boolean',
        'hardfile_ktp_mhs' => 'boolean',
        'hardfile_kk' => 'boolean',
        'hardfile_akte' => 'boolean',
        'hardfile_ktp_ayah' => 'boolean',
        'hardfile_ktp_ibu' => 'boolean',
        'hardfile_skl' => 'boolean',
        'hardfile_transkrip' => 'boolean',
        'hardfile_ijazah' => 'boolean',
    ];

    // =========================================================
    // LOGIKA AKADEMIK DINAMIS (Mendukung Semester 1 - 14+)
    // =========================================================

    /**
     * Konversi Nilai Huruf ke Angka (Support A- s/d E)
     */
    private function konversiNilaiKeAngka($nilaiHuruf)
    {
        return match (strtoupper($nilaiHuruf)) {
            'A'  => 4.0,
            'A-' => 3.7,
            'B+' => 3.3,
            'B'  => 3.0,
            'B-' => 2.7,
            'C+' => 2.3,
            'C'  => 2.0,
            'D'  => 1.0,
            'E'  => 0.0,
            default => 0.0,
        };
    }

    /**
     * Hitung IPS untuk semester tertentu
     */
    public function hitungIPS($semester)
    {
        // Ambil KRS semester ini dengan eager loading matkul untuk SKS
        $krs = $this->krs()
            ->where('semester', $semester)
            ->with('matkul')
            ->get();

        if ($krs->isEmpty()) return 0.00;

        $totalBobotPoint = 0;
        $totalSksSemester = 0;

        foreach ($krs as $item) {
            $nilaiAngka = $this->konversiNilaiKeAngka($item->nilai_huruf);
            $sks = $item->matkul->bobot ?? 0; // Pakai 'bobot' dari tabel matkul

            $totalBobotPoint += ($nilaiAngka * $sks);
            $totalSksSemester += $sks;
        }

        return $totalSksSemester > 0 ? round($totalBobotPoint / $totalSksSemester, 2) : 0.00;
    }

    /**
     * Hitung IPK Kumulatif (Seluruh Semester)
     */
    public function hitungIPK()
    {
        $allKrs = $this->krs()->with('matkul')->get();

        if ($allKrs->isEmpty()) return 0.00;

        $totalBobotPoint = 0;
        $totalSksKumulatif = 0;

        foreach ($allKrs as $item) {
            $nilaiAngka = $this->konversiNilaiKeAngka($item->nilai_huruf);
            $sks = $item->matkul->bobot ?? 0;

            $totalBobotPoint += ($nilaiAngka * $sks);
            $totalSksKumulatif += $sks;
        }

        return $totalSksKumulatif > 0 ? round($totalBobotPoint / $totalSksKumulatif, 2) : 0.00;
    }

    /**
     * Ambil daftar IPS seluruh semester yang pernah diambil
     */
    public function getAllSemesterIPS()
    {
        // Cari semester tertinggi yang ada di data KRS
        $maxSemester = $this->krs()->max('semester') ?? 0;
        $ipsData = [];

        for ($i = 1; $i <= $maxSemester; $i++) {
            $ips = $this->hitungIPS($i);
            if ($ips > 0) { // Hanya tampilkan semester yang ada nilainya
                $ipsData[$i] = $ips;
            }
        }

        return $ipsData;
    }

    /**
     * Tentukan jatah SKS semester depan berdasarkan IPS terakhir
     */
    public function tentukanBebanSKS($semesterTerakhir = null)
    {
        if (!$semesterTerakhir) {
            $semesterTerakhir = $this->krs()->max('semester') ?? 0;
        }

        if ($semesterTerakhir == 0) return 24; // Default untuk mahasiswa baru

        $ips = $this->hitungIPS($semesterTerakhir);

        // Aturan SKS berdasarkan IPS
        if ($ips >= 3.00) return 24;
        if ($ips >= 2.50) return 22;
        if ($ips >= 2.00) return 20;
        return 16;
    }

    /**
     * Accessor untuk IPK (bisa dipanggil dengan $mahasiswa->ipk)
     */
    public function getIpkAttribute()
    {
        return $this->hitungIPK();
    }

    /**
     * Get Total SKS yang sudah diambil
     */
    public function getTotalSksAttribute()
    {
        return $this->krs()->with('matkul')->get()->sum(function ($krs) {
            return $krs->matkul->bobot ?? 0;
        });
    }

    /**
     * Get Semester Terakhir
     */
    public function getSemesterTerakhirAttribute()
    {
        return $this->krs()->max('semester') ?? 0;
    }

    // =========================================================
    // RELASI & HELPER
    // =========================================================

    public function krs()
    {
        return $this->hasMany(Krs::class, 'id_mahasiswa');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_masuk', 'id');
    }
    public function riwayatKuliah()
    {
        return $this->hasMany(RiwayatKuliah::class, 'id_mahasiswa');
    }

    public function pembayaran()
    {
        return $this->hasMany(PembayaranMahasiswa::class, 'id_mahasiswa');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenMahasiswa::class, 'id_mahasiswa');
    }

    public function pendidikan()
    {
        return $this->hasMany(PendidikanMahasiswa::class, 'id_mahasiswa');
    }

    // Accessor untuk alamat dan kelengkapan berkas tetap sama seperti sebelumnya...
    public function getAlamatLengkapAttribute()
    {
        return sprintf("%s RT/RW %s/%s, %s, Kec. %s, %s", $this->dusun, $this->rt, $this->rw, $this->ds_kel, $this->kec, $this->kab);
    }

    public function getKelengkapanSoftfileAttribute()
    {
        $total = 10; // Total jenis dokumen
        $terpenuhi = collect([
            $this->softfile_surat_pernyataan,
            $this->softfile_pas_foto,
            $this->softfile_ktp_mhs,
            $this->softfile_kk,
            $this->softfile_akte,
            $this->softfile_ktp_ayah,
            $this->softfile_ktp_ibu,
            $this->softfile_skl,
            $this->softfile_transkrip,
            $this->softfile_ijazah,
        ])->filter()->count();

        return round(($terpenuhi / $total) * 100);
    }

    public function getKelengkapanHardfileAttribute()
    {
        $total = 10;
        $terpenuhi = collect([
            $this->hardfile_surat_pernyataan,
            $this->hardfile_pas_foto,
            $this->hardfile_ktp_mhs,
            $this->hardfile_kk,
            $this->hardfile_akte,
            $this->hardfile_ktp_ayah,
            $this->hardfile_ktp_ibu,
            $this->hardfile_skl,
            $this->hardfile_transkrip,
            $this->hardfile_ijazah,
        ])->filter()->count();

        return round(($terpenuhi / $total) * 100);
    }
}
