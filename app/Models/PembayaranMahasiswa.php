<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_mahasiswa';

    protected $fillable = [
        'id_mahasiswa',
        'id_tahun_akademik',
        'jenis_pembayaran',
        'semester',
        'jumlah_tagihan',
        'jumlah_dibayar',
        'sisa_tagihan',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'status',
        'bukti_bayar',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_tagihan' => 'decimal:2',
        'jumlah_dibayar' => 'decimal:2',
        'sisa_tagihan' => 'decimal:2',
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
    ];

    /**
     * Relasi ke Mahasiswa
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa');
    }

    /**
     * Relasi ke Tahun Akademik
     */
    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_tahun_akademik');
    }

    /**
     * Auto calculate sisa tagihan before save
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $model->sisa_tagihan = $model->jumlah_tagihan - $model->jumlah_dibayar;

            // Auto set status
            if ($model->jumlah_dibayar >= $model->jumlah_tagihan) {
                $model->status = 'Lunas';
            } elseif ($model->jumlah_dibayar > 0 && $model->jumlah_dibayar < $model->jumlah_tagihan) {
                $model->status = 'Cicilan';
            } else {
                $model->status = 'Belum Bayar';
            }
        });
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'Lunas' => 'badge-success',
            'Cicilan' => 'badge-warning',
            'Belum Bayar' => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }

    /**
     * Check if terlambat
     */
    public function getIsTerlambatAttribute()
    {
        if ($this->status == 'Lunas') {
            return false;
        }

        return $this->tanggal_jatuh_tempo < now();
    }

    /**
     * Get persentase pembayaran
     */
    public function getPersentasePembayaranAttribute()
    {
        if ($this->jumlah_tagihan == 0) {
            return 0;
        }

        return round(($this->jumlah_dibayar / $this->jumlah_tagihan) * 100, 2);
    }
}
