<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitKwhPsPanel extends Model
{
    use LaporanKitSyncable;

    protected $table = 'laporan_kit_kwh_ps_panels';
    
    protected $fillable = [
        'laporan_kit_kwh_id',
        'panel_number',
        'awal',
        'akhir'
    ];

    protected $casts = [
        'awal' => 'decimal:2',
        'akhir' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function kwh()
    {
        return $this->belongsTo(LaporanKitKwh::class, 'laporan_kit_kwh_id');
    }

    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class)->through('kwh');
    }
} 