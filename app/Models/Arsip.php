<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $table = 'arsip';

    protected $fillable = [
        'nomor_surat',
        'judul',
        'kategori', // Surat Masuk/Keluar/SK
        'tanggal_surat',
        'file_path', // Lokasi file digital
        'keterangan',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];
}
