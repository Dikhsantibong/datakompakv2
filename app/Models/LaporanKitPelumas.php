<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitPelumas extends Model
{
    protected $table = 'laporan_kit_pelumas';

    protected $fillable = [
        'laporan_kit_id',
        'tank1_cm',
        'tank1_liter',
        'tank2_cm',
        'tank2_liter', 
        'tank_total_stok',
        'drum_area1',
        'drum_area2',
        'drum_total_stok',
        'total_stok_tangki',
        'terima_pelumas',
        'total_pakai',
        'jenis'
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
    
}