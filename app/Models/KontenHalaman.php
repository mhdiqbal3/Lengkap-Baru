<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontenHalaman extends Model
{
    use HasFactory;
    // Tambahkan baris ini agar Laravel tidak salah mengeja nama tabel
    protected $table = 'konten_halamans';
    protected $fillable = ['halaman', 'konten'];
}
