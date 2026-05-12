<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;

    // Tambahkan status_terlapor dan link_video, hapus is_anonim
    protected $fillable = [
        'user_id',
        'kode_tiket',
        'judul_lapor',
        'jenis_kasus',
        'nama_korban', // is_anonim dihapus
        'no_hp_korban',
        'status_korban',
        'status_terlapor', // Kolom baru
        'jenis_kelamin',
        'disabilitas',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'deskripsi',
        'link_video', // Kolom baru
        'bukti',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
