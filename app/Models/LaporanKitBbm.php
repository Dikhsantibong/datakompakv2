<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitBbm extends Model
{
    protected $table = 'laporan_kit_bbm';

    protected $fillable = [
        'laporan_kit_id',
        'total_stok',
        'service_total_stok',
        'total_stok_tangki',
        'terima_bbm',
        'total_pakai'
    ];

    public function storageTanks()
    {
        return $this->hasMany(LaporanKitBbmStorageTank::class);
    }

    public function serviceTanks()
    {
        return $this->hasMany(LaporanKitBbmServiceTank::class);
    }

    public function flowmeters()
    {
        return $this->hasMany(LaporanKitBbmFlowmeter::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
}