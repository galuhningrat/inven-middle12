<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangan';
    protected $primaryKey = 'id';

    // Set false jika tabel tidak ada kolom created_at, updated_at
    public $timestamps = false;

    protected $fillable = [
        'kode_ruang',
        'nama_ruang',
        'kapasitas',
        'keterangan',
    ];
}