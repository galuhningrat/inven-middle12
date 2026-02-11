<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Data Akademik & Status
            $table->string('kelas', 20)->nullable()->after('id_rombel');
            $table->string('pekerjaan', 100)->nullable()->after('status');
            $table->string('bagian_pekerjaan', 100)->nullable()->after('pekerjaan');
            $table->decimal('ips_1', 3, 2)->nullable()->after('bagian_pekerjaan');
            $table->decimal('ips_2', 3, 2)->nullable()->after('ips_1');
            $table->decimal('nilai_test_pmb', 5, 2)->nullable()->after('ips_2');

            // Data Orang Tua - Ayah (Update)
            $table->string('tempat_lahir_ayah', 50)->nullable()->after('nama_ayah');
            $table->date('tanggal_lahir_ayah')->nullable()->after('tempat_lahir_ayah');
            $table->string('pendidikan_ayah', 50)->nullable()->change();
            $table->string('penghasilan_ayah', 100)->nullable()->change();
            $table->string('hp_ayah', 14)->nullable()->after('penghasilan_ayah');
            $table->text('alamat_ayah')->nullable()->after('hp_ayah');

            // Data Orang Tua - Ibu (Update)
            $table->string('tempat_lahir_ibu', 50)->nullable()->after('nama_ibu');
            $table->date('tanggal_lahir_ibu')->nullable()->after('tempat_lahir_ibu');
            $table->string('pendidikan_ibu', 50)->nullable()->change();
            $table->string('penghasilan_ibu', 100)->nullable()->change();
            $table->string('hp_ibu', 14)->nullable()->after('penghasilan_ibu');
            $table->text('alamat_ibu')->nullable()->after('hp_ibu');

            // Kelengkapan Berkas Hard File (Boolean)
            $table->boolean('hardfile_surat_pernyataan')->default(false);
            $table->boolean('hardfile_pas_foto')->default(false);
            $table->boolean('hardfile_ktp_mhs')->default(false);
            $table->boolean('hardfile_kk')->default(false);
            $table->boolean('hardfile_akte')->default(false);
            $table->boolean('hardfile_ktp_ayah')->default(false);
            $table->boolean('hardfile_ktp_ibu')->default(false);
            $table->boolean('hardfile_skl')->default(false);
            $table->boolean('hardfile_transkrip')->default(false);
            $table->boolean('hardfile_ijazah')->default(false);

            // Kelengkapan Berkas Soft File (Path Upload)
            $table->string('softfile_surat_pernyataan')->nullable();
            $table->string('softfile_pas_foto')->nullable();
            $table->string('softfile_ktp_mhs')->nullable();
            $table->string('softfile_kk')->nullable();
            $table->string('softfile_akte')->nullable();
            $table->string('softfile_ktp_ayah')->nullable();
            $table->string('softfile_ktp_ibu')->nullable();
            $table->string('softfile_skl')->nullable();
            $table->string('softfile_transkrip')->nullable();
            $table->string('softfile_ijazah')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            $table->dropColumn([
                'kelas',
                'pekerjaan',
                'bagian_pekerjaan',
                'ips_1',
                'ips_2',
                'nilai_test_pmb',
                'tempat_lahir_ayah',
                'tanggal_lahir_ayah',
                'hp_ayah',
                'alamat_ayah',
                'tempat_lahir_ibu',
                'tanggal_lahir_ibu',
                'hp_ibu',
                'alamat_ibu',
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
                'softfile_surat_pernyataan',
                'softfile_pas_foto',
                'softfile_ktp_mhs',
                'softfile_kk',
                'softfile_akte',
                'softfile_ktp_ayah',
                'softfile_ktp_ibu',
                'softfile_skl',
                'softfile_transkrip',
                'softfile_ijazah'
            ]);
        });
    }
};
