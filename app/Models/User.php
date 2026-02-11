<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'password',
        'nama',
        'id_role',
        'status',
        'img',
        'last_login', // tambahkan jika kolom ini ada di tabel
    ];

    public function dosen()
    {
        return $this->hasOne(Dosen::class, 'id_users', 'id');
    }

    public function mahasiswa()
    {
        return $this->hasOne(Mahasiswa::class, 'id_users', 'id');
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'id_users', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role', 'id');
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class, 'id_users', 'id');
    }

    public function loginSession()
    {
        return $this->hasMany(LoginSession::class, 'id_users', 'user_id');
    }

    /**
     * Accessor untuk ambil login terakhir dari relasi loginHistories.
     * Akan mengembalikan kolom last_login jika tersedia, jika tidak akan query ke login_history.
     */
    public function lastLogin(): Attribute
    {
        return Attribute::get(function () {
            // Jika kolom last_login ada, pakai itu
            if (!empty($this->last_login)) {
                return $this->last_login;
            }

            // Kalau tidak ada kolom last_login, ambil dari login_history
            $last = $this->loginHistories()
                ->orderByDesc('login_time')
                ->first();

            return $last ? $last->login_time : null;
        });
    }

    /**
     * Update kolom last_login setiap kali user login.
     * Bisa dipanggil dari AuthenticatedSessionController setelah login sukses.
     */
    public function updateLastLogin()
    {
        $this->update([
            'last_login' => now(),
        ]);
    }
}
