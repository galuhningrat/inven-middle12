<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DokumenMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'dokumen_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'nama_dokumen',
        'jenis_dokumen',
        'file_path',
        'ukuran_file',
        'ekstensi',
        'keterangan',
    ];

    protected $casts = [
        'ukuran_file' => 'integer',
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
    public function getFileUrlAttribute()
    {
        if (!$this->file_path) {
            return null;
        }

        return asset('storage/' . $this->file_path);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->ukuran_file) {
            return '-';
        }

        $size = $this->ukuran_file;

        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } else {
            return round($size / 1048576, 2) . ' MB';
        }
    }

    /**
     * Get file icon based on extension
     */
    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => 'bi-file-earmark-pdf text-danger',
            'doc' => 'bi-file-earmark-word text-primary',
            'docx' => 'bi-file-earmark-word text-primary',
            'xls' => 'bi-file-earmark-excel text-success',
            'xlsx' => 'bi-file-earmark-excel text-success',
            'jpg' => 'bi-file-earmark-image text-info',
            'jpeg' => 'bi-file-earmark-image text-info',
            'png' => 'bi-file-earmark-image text-info',
            'gif' => 'bi-file-earmark-image text-info',
        ];

        return $icons[strtolower($this->ekstensi)] ?? 'bi-file-earmark';
    }

    /**
     * Check if file is image
     */
    public function getIsImageAttribute()
    {
        return in_array(strtolower($this->ekstensi), ['jpg', 'jpeg', 'png', 'gif']);
    }

    /**
     * Check if file exists
     */
    public function fileExists()
    {
        return Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Delete file when model is deleted
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            if ($model->file_path && Storage::disk('public')->exists($model->file_path)) {
                Storage::disk('public')->delete($model->file_path);
            }
        });
    }
}
