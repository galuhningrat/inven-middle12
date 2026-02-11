<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    use HasFactory;

    protected $table = 'login_history';
    protected $primaryKey = 'id_history';
    public $timestamps = true;

    protected $fillable = [
        'id_users',
        'id_sessions',
        'login_time',
        'logout_time',
        'user_agent',
        'location',
        'status',
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }
}
