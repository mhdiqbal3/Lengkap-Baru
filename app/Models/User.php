<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'no_hp',
        'password',
        'password_plain',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ==========================================
    // RELASI ANTAR TABEL
    // ==========================================

    // 1 User bisa punya banyak Laporan
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    // 1 User (Admin) bisa buat banyak Arsip
    public function arsips()
    {
        return $this->hasMany(Arsip::class);
    }

    // 1 User punya banyak Notifikasi
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
