<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitKwh extends Model
{
    protected $table = 'laporan_kit_kwh';

    protected $fillable = [
        'laporan_kit_id',
        'prod_panel1_awal',
        'prod_panel1_akhir',
        'prod_panel2_awal',
        'prod_panel2_akhir',
        'prod_total',
        'ps_panel1_awal',
        'ps_panel1_akhir',
        'ps_panel2_awal',
        'ps_panel2_akhir',
        'ps_total'
    ];
}
