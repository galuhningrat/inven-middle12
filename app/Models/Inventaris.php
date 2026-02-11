<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;

    protected $table = 'inventaris';

    // Kolom yang boleh diisi oleh Karyawan
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'kondisi',
        'tanggal_perolehan',
        'harga_perolehan',
        'lokasi',
        'keterangan',
    ];

    // Casting agar tipe data output sesuai (opsional tapi bagus)
    protected $casts = [
        'tanggal_perolehan' => 'date',
        'harga_perolehan' => 'decimal:2',
    ];
}
