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

    protected $casts = [
        'prod_total' => 'decimal:2',
        'ps_total' => 'decimal:2'
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class);
    }

    public function productionPanels()
    {
        return $this->hasMany(LaporanKitKwhProductionPanel::class, 'laporan_kit_kwh_id');
    }

    public function psPanels()
    {
        return $this->hasMany(LaporanKitKwhPsPanel::class, 'laporan_kit_kwh_id');
    }
}