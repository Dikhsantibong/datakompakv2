<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitBbm extends Model
{
    protected $table = 'laporan_kit_bbm';

    protected $fillable = [
        'laporan_kit_id',
        'storage_tank_1_cm',
        'storage_tank_1_liter',
        'storage_tank_2_cm', 
        'storage_tank_2_liter',
        'total_stok',
        'service_tank_1_liter',
        'service_tank_1_percentage',
        'service_tank_2_liter',
        'service_tank_2_percentage',
        'total_stok_tangki',
        'terima_bbm',
        'flowmeter_1_awal',
        'flowmeter_1_akhir',
        'flowmeter_1_pakai',
        'flowmeter_2_awal',
        'flowmeter_2_akhir', 
        'flowmeter_2_pakai',
        'total_pakai'
    ];
}