<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitBbmFlowmeter extends Model
{
    protected $table = 'laporan_kit_bbm_flowmeters';

    protected $fillable = [
        'laporan_kit_bbm_id',
        'flowmeter_number',
        'awal',
        'akhir',
        'pakai'
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