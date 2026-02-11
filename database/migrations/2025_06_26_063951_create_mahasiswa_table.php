<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_prodi');
            $table->unsignedBigInteger('id_rombel');
            $table->string('nim')->unique();
            $table->string('nik',16)->nullable()->unique();
            $table->string('no_kk',16)->nullable()->unique();
            $table->string('tempat_lahir',20)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin',['Laki-laki','Perempuan'])->nullable();
            $table->enum('agama',['Islam', 'Katolik', 'Protestan', 'Budha', 'Hindu', 'Konghucu'])->nullable();
            $table->string('dusun',30)->nullable();
            $table->string('rt',3)->nullable();
            $table->string('rw',3)->nullable();
            $table->string('ds_kel',20)->nullable();
            $table->string('kec',20)->nullable();
            $table->string('kab',20)->nullable();
            $table->string('prov',20)->nullable();
            $table->string('kode_pos',5)->nullable();
            $table->string('hp',14)->nullable();
            $table->enum('marital_status',['Lajang', 'Menikah', 'Cerai Hidup', 'Cerai Mati'])->nullable();
            $table->enum('kewarganegaraan',['WNI', 'WNA'])->nullable();
            $table->enum('gol_darah',['A','B','AB','O'])->nullable();
            $table->enum('status', ['Aktif', 'Cuti', 'Drop Out', 'Lulus'])->nullable();
            $table->string('tahun_masuk',4)->nullable();
            $table->string('tahun_keluar',4)->nullable();
            $table->string('asal_sekolah',30)->nullable();
            $table->string('jurusan',20)->nullable();
            $table->string('alamat_sekolah',100)->nullable();
            $table->string('nisn',16)->nullable()->unique();
            $table->string('tahun_lulus',4)->nullable();
            $table->string('no_ijazah',30)->nullable()->unique();
            $table->string('nama_ayah',30)->nullable();
            $table->string('ttl_ayah',50)->nullable();
            $table->enum('pendidikan_ayah',['Tidak Sekolah', 'SD','SMP','SMA','Diploma/S1','S2','S3'])->nullable();
            $table->enum('status_ayah', ['Masih Hidup','Meninggal'])->nullable();
            $table->string('pekerjaan_ayah',30)->nullable();
            $table->string('penghasilan_ayah',50)->nullable();
            $table->string('nohp_ayah',30)->nullable();
            $table->string('alamat_lengkap',100)->nullable();
            $table->string('nama_ibu',30)->nullable();
            $table->string('ttl_ibu',50)->nullable();
            $table->enum('pendidikan_ibu',['Tidak Sekolah', 'SD','SMP','SMA','Diploma/S1','S2','S3'])->nullable();
            $table->enum('status_ibu', ['Masih Hidup','Meninggal'])->nullable();
            $table->string('pekerjaan_ibu',30)->nullable();
            $table->string('penghasilan_ibu',50)->nullable();
            $table->string('nohp_ibu',30)->nullable();
            $table->string('nama_wali',30)->nullable();
            $table->enum('pendidikan_wali',['Tidak Sekolah', 'SD','SMP','SMA','Diploma/S1','S2','S3'])->nullable();
            $table->string('pekerjaan_wali',30)->nullable();
            $table->string('penghasilan_wali',50)->nullable();
            $table->string('nohp_wali',30)->nullable();
            $table->timestamps();

            //relasi
            $table->foreign('id_users')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_prodi')->references('id')->on('prodi')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('id_rombel')->references('id')->on('rombel')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
