<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class RiwayatKuliah extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kuliah';

    protected $fillable = [
        'id_mahasiswa',
        'kategori',
        'kampus_asal',
        'prodi_asal',
        'tahun_masuk',
        'tahun_keluar',
        'jenis',
        'alasan',
        'file_transkrip',
    ];

    protected $casts = [
        'tahun_masuk' => 'integer',
        'tahun_keluar' => 'integer',
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    /**
     * Get file transkrip URL
     */
    public function getFileTranskripUrlAttribute()
    {
        if (!$this->file_transkrip) {
            return null;
        }

        return asset('storage/' . $this->file_transkrip);
    }

    /**
     * Get formatted jenis label
     */
    public function getJenisLabelAttribute()
    {
        $labels = [
            'Transfer' => 'badge-primary',
            'Pindahan' => 'badge-warning',
            'Lanjutan' => 'badge-info',
        ];

        return $labels[$this->jenis] ?? 'badge-secondary';
    }

    /**
     * Get formatted periode
     */
    public function getPeriodeAttribute()
    {
        return $this->tahun_masuk . ' - ' . $this->tahun_keluar;
    }

    /**
     * Delete file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Menggunakan facade Storage yang sudah di-import di atas agar lebih aman
            if ($model->file_transkrip && Storage::disk('public')->exists($model->file_transkrip)) {
                Storage::disk('public')->delete($model->file_transkrip);
            }
        });
    }
}
