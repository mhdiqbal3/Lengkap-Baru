<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatLaporan extends Model
{
    use HasFactory;
    
    protected $fillable = ['laporan_id', 'status', 'catatan', 'tipe', 'keluhan_id'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
