<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_matkul',
        'id_prodi',
        'id_dosen',
        'id_rombel',
        'id_ruangan',
        'tahun_akademik',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    // Relasi ke mata kuliah
    public function matkul()
    {
        return $this->belongsTo(Matkul::class, 'id_matkul', 'id');
    }

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

    // Relasi ke rombel
    public function rombel()
    {
        return $this->belongsTo(Rombel::class, 'id_rombel', 'id');
    }

    // Relasi ke ruangan
    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id');
    }

    // Relasi ke tahun akademik
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'tahun_akademik', 'id');
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
