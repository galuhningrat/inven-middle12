<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_users',
        'nip',
        'nik',
        'no_kk',
        'npwp',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'agama',
        'dusun',
        'rt',
        'rw',
        'ds_kel',
        'kec',
        'kab',
        'prov',
        'kode_pos',
        'no_tel',
        'hp',
        'marital_status',
        'status',
        'pend_terakhir',
        'gol_darah',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users', 'id');
    }
}