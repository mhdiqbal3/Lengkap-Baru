<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatLaporan extends Model
{
    use HasFactory;
    
    protected $fillable = ['laporan_id', 'status', 'catatan'];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}
