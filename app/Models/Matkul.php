<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matkul extends Model
{
    use HasFactory;

    protected $table = 'matkul';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_mk',
        'nama_mk',
        'bobot',
        'jenis',
        'id_prodi',
        'id_dosen',
    ];

    // Relasi ke prodi
    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id');
    }

    // Relasi ke dosen
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    // Helper untuk update (optional)
    public function updateData($data)
    {
        return $this->update($data);
    }

    // Helper untuk delete (optional)
    public function deleteData()
    {
        return $this->delete();
    }
}
