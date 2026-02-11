<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrangtuaMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'orangtua_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
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
        'nama_wali',
        'tempat_lahir_wali',
        'tanggal_lahir_wali',
        'pendidikan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'hp_wali',
        'alamat_wali',
    ];

    protected $casts = [
        'tanggal_lahir_ayah' => 'date',
        'tanggal_lahir_ibu' => 'date',
        'tanggal_lahir_wali' => 'date',
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    /**
     * Get TTL Ayah formatted
     */
    public function getTtlAyahAttribute()
    {
        if (!$this->tempat_lahir_ayah || !$this->tanggal_lahir_ayah) {
            return '-';
        }

        return $this->tempat_lahir_ayah . ', ' . $this->tanggal_lahir_ayah->format('d M Y');
    }

    /**
     * Get TTL Ibu formatted
     */
    public function getTtlIbuAttribute()
    {
        if (!$this->tempat_lahir_ibu || !$this->tanggal_lahir_ibu) {
            return '-';
        }

        return $this->tempat_lahir_ibu . ', ' . $this->tanggal_lahir_ibu->format('d M Y');
    }

    /**
     * Get TTL Wali formatted
     */
    public function getTtlWaliAttribute()
    {
        if (!$this->tempat_lahir_wali || !$this->tanggal_lahir_wali) {
            return '-';
        }

        return $this->tempat_lahir_wali . ', ' . $this->tanggal_lahir_wali->format('d M Y');
    }
}
