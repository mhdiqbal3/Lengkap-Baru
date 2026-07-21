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
        'nama_korban',
        'no_hp_korban',
        'status_korban',
        'status_korban_lainnya', // Keterangan jika pilih Lainnya
        'status_terlapor',
        'status_terlapor_lainnya',
        'jenis_kelamin',
        'disabilitas',
        'tanggal_kejadian',
        'lokasi_kejadian',
        'saksi',               // Data saksi (JSON)
        'deskripsi',
        'link_video',
        'bukti',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function arsips()
    {
        return $this->hasMany(Arsip::class, 'laporan_id');
    }

    public function riwayats()
    {
        return $this->hasMany(RiwayatLaporan::class, 'laporan_id')->orderBy('created_at', 'asc');
    }

}
