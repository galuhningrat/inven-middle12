<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    use HasFactory;
    protected $table = 'fakultas';

    protected $fillable = [
        'kode_fakultas',
        'nama_fakultas',
        'id_dekan',
    ];

    public function dekan()
    {
        return $this->belongsTo(User::class, 'id_dekan', 'id');
    }

    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'id_fakultas');
    }
}
