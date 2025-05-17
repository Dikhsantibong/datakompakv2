<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitBbmServiceTank extends Model
{
    protected $table = 'laporan_kit_bbm_service_tanks';

    protected $fillable = [
        'laporan_kit_bbm_id',
        'tank_number',
        'liter',
        'percentage'
    ];

    public function bbm()
    {
        return $this->belongsTo(LaporanKitBbm::class, 'laporan_kit_bbm_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 