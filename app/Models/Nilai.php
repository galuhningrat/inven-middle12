<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $guarded = [];

    // Accessor untuk warna badge Status
    public function getStatusBadgeAttribute()
    {
        return [
            'draft' => 'badge-light-warning',
            'published' => 'badge-light-success',
            'final' => 'badge-light-primary',
        ][$this->status] ?? 'badge-light-secondary';
    }

    // Accessor untuk warna badge Grade
    public function getGradeBadgeAttribute()
    {
        if (in_array($this->nilai_huruf, ['A', 'A-'])) return 'badge-success';
        if (in_array($this->nilai_huruf, ['B+', 'B', 'B-'])) return 'badge-primary';
        if (in_array($this->nilai_huruf, ['C+', 'C'])) return 'badge-warning';
        return 'badge-danger';
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen');
    }
}
