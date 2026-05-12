<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'judul_kegiatan',
        'jenis_kegiatan',
        'tanggal',
        'lokasi',
        'status_publikasi',
        'deskripsi',
        'dokumentasi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
