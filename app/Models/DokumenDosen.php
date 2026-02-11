<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenDosen extends Model
{
    use HasFactory;

    protected $table = 'dokumen_dosen';

    protected $fillable = [
        'id_dosen',
        'nama',
        'berkas',
        'size',
        'ekstensi',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}