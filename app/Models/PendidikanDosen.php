<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendidikanDosen extends Model
{
    use HasFactory;

    protected $table = 'pendidikan_dosen';

    protected $fillable = [
        'id_dosen',
        'jenjang',
        'gelar',
        'nama_pt',
        'jurusan',
        'tahun_lulus',
        'ijazah',
    ];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}