<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keluhan extends Model
{
    use HasFactory;

    protected $fillable = [
        'laporan_id',
        'kode_tiket',
        'kategori',
        'isi_keluhan',
        'status',
        'catatan_satgas',
        'user_id',
        'is_read',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Label kategori keluhan dalam Bahasa Indonesia
     */
    public function getLabelKategoriAttribute(): string
    {
        return match($this->kategori) {
            'belum_dihubungi' => 'Belum dihubungi Satgas',
            'terlalu_lama'    => 'Penanganan terlalu lama',
            'kurang_jelas'    => 'Informasi kurang jelas',
            'lainnya'         => 'Lainnya',
            default           => $this->kategori,
        };
    }
}
