<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nomor_surat
 * @property string $judul
 * @property string $kategori
 * @property \Illuminate\Support\Carbon $tanggal_surat
 * @property string|null $file_path
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereNomorSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereTanggalSurat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Arsip whereUpdatedAt($value)
 */
	class Arsip extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_dosen
 * @property string $nama
 * @property string|null $berkas
 * @property int|null $size
 * @property string|null $ekstensi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereBerkas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereEkstensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenDosen whereUpdatedAt($value)
 */
	class DokumenDosen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property string $nama_dokumen
 * @property string $jenis_dokumen
 * @property string $file_path
 * @property int|null $ukuran_file
 * @property string|null $ekstensi
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $file_icon
 * @property-read mixed $file_url
 * @property-read mixed $formatted_file_size
 * @property-read mixed $is_image
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereEkstensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereJenisDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereNamaDokumen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereUkuranFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DokumenMahasiswa whereUpdatedAt($value)
 */
	class DokumenMahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_users
 * @property string $nip
 * @property string|null $nik
 * @property string|null $no_kk
 * @property string|null $nidn
 * @property string|null $nuptk
 * @property string|null $npwp
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $agama
 * @property string|null $dusun
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $ds_kel
 * @property string|null $kec
 * @property string|null $kab
 * @property string|null $prov
 * @property string|null $kode_pos
 * @property string|null $no_hp
 * @property string|null $marital_status
 * @property string|null $status
 * @property string $kewarganegaraan
 * @property string|null $gol_darah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumenDosen> $dokumen
 * @property-read int|null $dokumen_count
 * @property-read mixed $alamat_lengkap
 * @property-read mixed $nama_lengkap
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Jadwal> $jadwal
 * @property-read int|null $jadwal_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PendidikanDosen> $pendidikan
 * @property-read int|null $pendidikan_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereDsKel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereDusun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereIdUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereKab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereKec($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereKewarganegaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNidn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNoKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereNuptk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereProv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dosen whereUpdatedAt($value)
 */
	class Dosen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_fakultas
 * @property string $nama_fakultas
 * @property int $id_dekan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $dekan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Prodi> $prodi
 * @property-read int|null $prodi_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas whereIdDekan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas whereKodeFakultas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas whereNamaFakultas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fakultas whereUpdatedAt($value)
 */
	class Fakultas extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_barang
 * @property string $nama_barang
 * @property string $kategori
 * @property int $jumlah
 * @property string $kondisi
 * @property \Illuminate\Support\Carbon $tanggal_perolehan
 * @property numeric|null $harga_perolehan
 * @property string $lokasi
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereHargaPerolehan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereJumlah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereKodeBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereKondisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereLokasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereNamaBarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereTanggalPerolehan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Inventaris whereUpdatedAt($value)
 */
	class Inventaris extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_matkul
 * @property int $id_prodi
 * @property int $id_dosen
 * @property int $id_rombel
 * @property int $id_ruangan
 * @property int $tahun_akademik
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @property-read \App\Models\Matkul $matkul
 * @property-read \App\Models\Prodi $prodi
 * @property-read \App\Models\Rombel $rombel
 * @property-read \App\Models\Ruangan $ruangan
 * @property-read \App\Models\TahunAkademik $tahunAkademik
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereIdMatkul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereIdRombel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereIdRuangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereTahunAkademik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jadwal whereUpdatedAt($value)
 */
	class Jadwal extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_users
 * @property string $nip
 * @property string $nik
 * @property string $no_kk
 * @property string|null $npwp
 * @property string $tempat_lahir
 * @property string $tanggal_lahir
 * @property string $jenis_kelamin
 * @property string $agama
 * @property string $dusun
 * @property string $rt
 * @property string $rw
 * @property string $ds_kel
 * @property string $kec
 * @property string $kab
 * @property string $prov
 * @property string $kode_pos
 * @property string $hp
 * @property string $marital_status
 * @property string $status
 * @property string $pend-terakhir
 * @property string|null $gol_darah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereDsKel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereDusun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereIdUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereKab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereKec($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNoKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan wherePendTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereProv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Karyawan whereUpdatedAt($value)
 */
	class Karyawan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property string $semester
 * @property int $id_rombel
 * @property int $id_jadwal
 * @property string $status
 * @property int $status_kunci
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $nilai_huruf
 * @property numeric|null $nilai_angka
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\Matkul|null $matkul
 * @property-read \App\Models\Rombel $rombel
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereIdJadwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereIdRombel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereNilaiAngka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereNilaiHuruf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereStatusKunci($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Krs whereUpdatedAt($value)
 */
	class Krs extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id_history
 * @property int $id_users
 * @property string|null $id_sessions
 * @property string $login_time
 * @property string|null $logout_time
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereIdHistory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereIdSessions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereIdUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereLogoutTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginHistory whereUserAgent($value)
 */
	class LoginHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $payload
 * @property int $last_activity
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereLastActivity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession wherePayload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LoginSession whereUserId($value)
 */
	class LoginSession extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_users
 * @property int $id_prodi
 * @property int $id_rombel
 * @property string $nim
 * @property string|null $nik
 * @property string|null $no_kk
 * @property string|null $tempat_lahir
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir
 * @property string|null $jenis_kelamin
 * @property string|null $agama
 * @property string|null $dusun
 * @property string|null $rt
 * @property string|null $rw
 * @property string|null $ds_kel
 * @property string|null $kec
 * @property string|null $kab
 * @property string|null $prov
 * @property string|null $kode_pos
 * @property string|null $hp
 * @property string|null $marital_status
 * @property string|null $kewarganegaraan
 * @property string|null $gol_darah
 * @property string|null $status
 * @property string|null $tahun_masuk
 * @property string|null $tahun_keluar
 * @property string|null $asal_sekolah
 * @property string|null $jurusan
 * @property string|null $alamat_sekolah
 * @property string|null $nisn
 * @property string|null $tahun_lulus
 * @property string|null $no_ijazah
 * @property string|null $nama_ayah
 * @property string|null $ttl_ayah
 * @property string|null $pendidikan_ayah
 * @property string|null $status_ayah
 * @property string|null $pekerjaan_ayah
 * @property string|null $penghasilan_ayah
 * @property string|null $nohp_ayah
 * @property string|null $alamat_lengkap
 * @property string|null $nama_ibu
 * @property string|null $ttl_ibu
 * @property string|null $pendidikan_ibu
 * @property string|null $status_ibu
 * @property string|null $pekerjaan_ibu
 * @property string|null $penghasilan_ibu
 * @property string|null $nohp_ibu
 * @property string|null $nama_wali
 * @property string|null $pendidikan_wali
 * @property string|null $pekerjaan_wali
 * @property string|null $penghasilan_wali
 * @property string|null $nohp_wali
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $kelas
 * @property string|null $pekerjaan
 * @property string|null $bagian_pekerjaan
 * @property numeric|null $ips_1
 * @property numeric|null $ips_2
 * @property numeric|null $nilai_test_pmb
 * @property string|null $tempat_lahir_ayah
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir_ayah
 * @property string|null $hp_ayah
 * @property string|null $alamat_ayah
 * @property string|null $tempat_lahir_ibu
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir_ibu
 * @property string|null $hp_ibu
 * @property string|null $alamat_ibu
 * @property bool $hardfile_surat_pernyataan
 * @property bool $hardfile_pas_foto
 * @property bool $hardfile_ktp_mhs
 * @property bool $hardfile_kk
 * @property bool $hardfile_akte
 * @property bool $hardfile_ktp_ayah
 * @property bool $hardfile_ktp_ibu
 * @property bool $hardfile_skl
 * @property bool $hardfile_transkrip
 * @property bool $hardfile_ijazah
 * @property string|null $softfile_surat_pernyataan
 * @property string|null $softfile_pas_foto
 * @property string|null $softfile_ktp_mhs
 * @property string|null $softfile_kk
 * @property string|null $softfile_akte
 * @property string|null $softfile_ktp_ayah
 * @property string|null $softfile_ktp_ibu
 * @property string|null $softfile_skl
 * @property string|null $softfile_transkrip
 * @property string|null $softfile_ijazah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DokumenMahasiswa> $dokumen
 * @property-read int|null $dokumen_count
 * @property-read mixed $ipk
 * @property-read mixed $kelengkapan_hardfile
 * @property-read mixed $kelengkapan_softfile
 * @property-read mixed $semester_terakhir
 * @property-read mixed $total_sks
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Krs> $krs
 * @property-read int|null $krs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PembayaranMahasiswa> $pembayaran
 * @property-read int|null $pembayaran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PendidikanMahasiswa> $pendidikan
 * @property-read int|null $pendidikan_count
 * @property-read \App\Models\Prodi $prodi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RiwayatKuliah> $riwayatKuliah
 * @property-read int|null $riwayat_kuliah_count
 * @property-read \App\Models\Rombel $rombel
 * @property-read \App\Models\TahunAkademik|null $tahunAkademik
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAgama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamatAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamatIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamatLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAlamatSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereAsalSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereBagianPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDsKel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereDusun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereGolDarah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileAkte($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileKtpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileKtpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileKtpMhs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfilePasFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileSkl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileSuratPernyataan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHardfileTranskrip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereHpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereIdRombel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereIdUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereIps1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereIps2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKec($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKewarganegaraan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereKodePos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereMaritalStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNamaWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNilaiTestPmb($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNim($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNoIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNoKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNohpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNohpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereNohpWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePekerjaanAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePekerjaanIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePekerjaanWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePendidikanAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePendidikanIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePendidikanWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePenghasilanAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePenghasilanIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa wherePenghasilanWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereProv($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereRt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereRw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileAkte($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileKtpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileKtpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileKtpMhs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfilePasFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileSkl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileSuratPernyataan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereSoftfileTranskrip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereStatusAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereStatusIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTahunKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTanggalLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTanggalLahirAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTanggalLahirIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTempatLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTempatLahirAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTempatLahirIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTtlAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereTtlIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mahasiswa whereUpdatedAt($value)
 */
	class Mahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_mk
 * @property string $nama_mk
 * @property string $bobot
 * @property string $jenis
 * @property int $id_prodi
 * @property int $id_dosen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @property-read \App\Models\Prodi $prodi
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereBobot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereKodeMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereNamaMk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Matkul whereUpdatedAt($value)
 */
	class Matkul extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property int $id_jadwal
 * @property int $id_dosen
 * @property numeric|null $nilai_tugas
 * @property numeric|null $nilai_uts
 * @property numeric|null $nilai_uas
 * @property numeric|null $nilai_praktikum
 * @property numeric|null $nilai_kehadiran
 * @property numeric $bobot_tugas
 * @property numeric $bobot_uts
 * @property numeric $bobot_uas
 * @property numeric $bobot_praktikum
 * @property numeric $bobot_kehadiran
 * @property numeric|null $nilai_akhir
 * @property string|null $nilai_huruf
 * @property numeric|null $nilai_angka
 * @property string $status
 * @property string|null $catatan_dosen
 * @property int|null $jumlah_kehadiran
 * @property int|null $jumlah_pertemuan
 * @property string|null $published_at
 * @property int|null $published_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @property-read mixed $grade_badge
 * @property-read mixed $status_badge
 * @property-read \App\Models\Jadwal $jadwal
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereBobotKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereBobotPraktikum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereBobotTugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereBobotUas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereBobotUts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereCatatanDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereIdJadwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereJumlahKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereJumlahPertemuan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiAngka($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiHuruf($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiKehadiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiPraktikum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiTugas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiUas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereNilaiUts($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai wherePublishedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai wherePublishedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Nilai whereUpdatedAt($value)
 */
	class Nilai extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property string|null $nama_ayah
 * @property string|null $tempat_lahir_ayah
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir_ayah
 * @property string|null $pendidikan_ayah
 * @property string|null $pekerjaan_ayah
 * @property string|null $penghasilan_ayah
 * @property string|null $hp_ayah
 * @property string|null $alamat_ayah
 * @property string|null $nama_ibu
 * @property string|null $tempat_lahir_ibu
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir_ibu
 * @property string|null $pendidikan_ibu
 * @property string|null $pekerjaan_ibu
 * @property string|null $penghasilan_ibu
 * @property string|null $hp_ibu
 * @property string|null $alamat_ibu
 * @property string|null $nama_wali
 * @property string|null $tempat_lahir_wali
 * @property \Illuminate\Support\Carbon|null $tanggal_lahir_wali
 * @property string|null $pendidikan_wali
 * @property string|null $pekerjaan_wali
 * @property string|null $penghasilan_wali
 * @property string|null $hp_wali
 * @property string|null $alamat_wali
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $ttl_ayah
 * @property-read mixed $ttl_ibu
 * @property-read mixed $ttl_wali
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereAlamatAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereAlamatIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereAlamatWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereHpAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereHpIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereHpWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereNamaWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePekerjaanAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePekerjaanIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePekerjaanWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePendidikanAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePendidikanIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePendidikanWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePenghasilanAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePenghasilanIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa wherePenghasilanWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereTanggalLahirAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereTanggalLahirIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereTanggalLahirWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereTempatLahirAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereTempatLahirIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereTempatLahirWali($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrangtuaMahasiswa whereUpdatedAt($value)
 */
	class OrangtuaMahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property int $id_tahun_akademik
 * @property string $jenis_pembayaran
 * @property string|null $semester
 * @property numeric $jumlah_tagihan
 * @property numeric $jumlah_dibayar
 * @property numeric $sisa_tagihan
 * @property \Illuminate\Support\Carbon $tanggal_jatuh_tempo
 * @property \Illuminate\Support\Carbon|null $tanggal_bayar
 * @property string $status
 * @property string|null $bukti_bayar
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $is_terlambat
 * @property-read mixed $persentase_pembayaran
 * @property-read mixed $status_badge
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @property-read \App\Models\TahunAkademik $tahunAkademik
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereBuktiBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereIdTahunAkademik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereJenisPembayaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereJumlahDibayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereJumlahTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereSisaTagihan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereTanggalBayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereTanggalJatuhTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PembayaranMahasiswa whereUpdatedAt($value)
 */
	class PembayaranMahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_dosen
 * @property string $jenjang
 * @property string $nama_pt
 * @property string|null $jurusan
 * @property string|null $gelar
 * @property string $tahun_lulus
 * @property string $ijazah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereGelar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereJenjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereNamaPt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanDosen whereUpdatedAt($value)
 */
	class PendidikanDosen extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property string $jenjang
 * @property string $nama_sekolah
 * @property string|null $jurusan
 * @property string|null $alamat_sekolah
 * @property string|null $nisn
 * @property int $tahun_lulus
 * @property string|null $no_ijazah
 * @property string|null $file_ijazah
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $file_ijazah_url
 * @property-read mixed $jenjang_badge
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereAlamatSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereFileIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereJenjang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereNamaSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereNisn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereNoIjazah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereTahunLulus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendidikanMahasiswa whereUpdatedAt($value)
 */
	class PendidikanMahasiswa extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_prodi
 * @property int $id_fakultas
 * @property string $nama_prodi
 * @property int $id_kaprodi
 * @property string $status_akre
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Fakultas $fakultas
 * @property-read \App\Models\User $kaprodi
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswa
 * @property-read int|null $mahasiswa_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereIdFakultas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereIdKaprodi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereKodeProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereNamaProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereStatusAkre($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Prodi whereUpdatedAt($value)
 */
	class Prodi extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $id_mahasiswa
 * @property string $kategori
 * @property string|null $kampus_asal
 * @property string|null $prodi_asal
 * @property int|null $tahun_masuk
 * @property int|null $tahun_keluar
 * @property numeric|null $ipk_asal
 * @property string|null $gelar_diperoleh
 * @property string|null $jenis
 * @property string|null $alasan_pindah
 * @property string|null $file_transkrip
 * @property string|null $nama_kegiatan
 * @property string|null $jenis_kegiatan
 * @property string|null $penyelenggara
 * @property string|null $posisi_jabatan
 * @property string|null $tanggal_mulai
 * @property string|null $tanggal_selesai
 * @property string|null $deskripsi_kegiatan
 * @property string|null $file_sertifikat
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $alasan
 * @property-read mixed $file_transkrip_url
 * @property-read mixed $jenis_label
 * @property-read mixed $periode
 * @property-read \App\Models\Mahasiswa $mahasiswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereAlasan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereAlasanPindah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereDeskripsiKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereFileSertifikat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereFileTranskrip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereGelarDiperoleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereIdMahasiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereIpkAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereJenis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereJenisKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereKampusAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereKategori($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereNamaKegiatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah wherePenyelenggara($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah wherePosisiJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereProdiAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereTahunKeluar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RiwayatKuliah whereUpdatedAt($value)
 */
	class RiwayatKuliah extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $nama_role
 * @property string $deskripsi_role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereDeskripsiRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereNamaRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Role whereUpdatedAt($value)
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_rombel
 * @property string $nama_rombel
 * @property int $tahun_masuk
 * @property int $id_prodi
 * @property int $id_dosen
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Dosen $dosen
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswa
 * @property-read int|null $mahasiswa_count
 * @property-read \App\Models\Prodi $prodi
 * @property-read \App\Models\TahunAkademik $tahunMasuk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereIdDosen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereIdProdi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereKodeRombel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereNamaRombel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereTahunMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Rombel whereUpdatedAt($value)
 */
	class Rombel extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $kode_ruang
 * @property string $nama_ruang
 * @property int $kapasitas
 * @property string|null $keterangan
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereKapasitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereKodeRuang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereNamaRuang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Ruangan whereUpdatedAt($value)
 */
	class Ruangan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $tahun_awal
 * @property string $tahun_akhir
 * @property string $semester
 * @property string $tanggal_mulai
 * @property string $tanggal_selesai
 * @property bool $status_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Mahasiswa> $mahasiswa
 * @property-read int|null $mahasiswa_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Rombel> $rombel
 * @property-read int|null $rombel_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereTahunAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereTahunAwal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereTanggalMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereTanggalSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TahunAkademik whereUpdatedAt($value)
 */
	class TahunAkademik extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $nama
 * @property int $id_role
 * @property string|null $last_login
 * @property string $status
 * @property string|null $img
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status_aktif
 * @property-read \App\Models\Dosen|null $dosen
 * @property-read \App\Models\Karyawan|null $karyawan
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoginHistory> $loginHistories
 * @property-read int|null $login_histories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoginSession> $loginSession
 * @property-read int|null $login_session_count
 * @property-read \App\Models\Mahasiswa|null $mahasiswa
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Role $role
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIdRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastLogin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStatusAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

