<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PendidikanMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'pendidikan_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'jenjang',
        'nama_sekolah',
        'jurusan',
        'alamat_sekolah',
        'nisn',
        'tahun_lulus',
        'no_ijazah',
        'file_ijazah',
    ];

    protected $casts = [
        'tahun_lulus' => 'integer',
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    /**
     * Get file URL
     */
    public function getFileIjazahUrlAttribute()
    {
        if (!$this->file_ijazah) {
            return null;
        }

        return asset('storage/' . $this->file_ijazah);
    }

    /**
     * Get jenjang badge
     */
    public function getJenjangBadgeAttribute()
    {
        $badges = [
            'Universitas'       => 'badge-dark',
            'Institut'          => 'badge-dark',
            'Politeknik'        => 'badge-dark',
            'Sekolah Tinggi'    => 'badge-dark',
            'Akademi'           => 'badge-dark',
            'Akademi Komunitas' => 'badge-dark',

            'SMA'             => 'badge-primary',
            'SMK'             => 'badge-success',
            'MA'              => 'badge-info',
            'MAK'             => 'badge-warning',
            'Paket C'         => 'badge-light-primary',
            'SMAK'            => 'badge-primary',
            'SMTK'            => 'badge-primary',
            'Utama Widyalaya' => 'badge-primary',
            'PDF Ulya'        => 'badge-info',

            'SD'      => 'badge-danger',
            'SMP'     => 'badge-light-danger',
            'Paket A' => 'badge-light-danger',
            'Paket B' => 'badge-light-danger',
        ];

        return $badges[$this->jenjang] ?? 'badge-secondary';
    }

    /**
     * Delete file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if ($model->file_ijazah && Storage::disk('public')->exists($model->file_ijazah)) {
                Storage::disk('public')->delete($model->file_ijazah);
            }
        });
    }
}
