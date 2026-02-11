<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'id_users',
        'nip',
        'nik',
        'no_kk',
        'nidn',
        'nuptk',
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
        'no_hp',
        'marital_status',
        'status',
        'kewarganegaraan',
        'gol_darah',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Default attributes (untuk kolom nullable)
     */
    protected $attributes = [
        'kewarganegaraan' => 'WNI',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    // Relasi ke Jadwal
    public function jadwal()
    {
        return $this->hasMany(Jadwal::class, 'id_dosen', 'id');
    }

    // Relasi ke Pendidikan
    public function pendidikan()
    {
        return $this->hasMany(PendidikanDosen::class, 'id_dosen')->orderBy('tahun_lulus', 'desc');
    }

    // Relasi ke Dokumen
    public function dokumen()
    {
        return $this->hasMany(DokumenDosen::class, 'id_dosen')->orderBy('created_at', 'desc');
    }

    /**
     * Accessor untuk nama lengkap
     */
    public function getNamaLengkapAttribute()
    {
        return $this->user->nama ?? '-';
    }

    /**
     * Accessor untuk alamat lengkap
     */
    public function getAlamatLengkapAttribute()
    {
        if (!$this->dusun)
            return '-';

        return "{$this->dusun} RT/RW {$this->rt}/{$this->rw}, Desa/Kel {$this->ds_kel}, Kec. {$this->kec}, Kab. {$this->kab}, Prov. {$this->prov} - {$this->kode_pos}";
    }
}