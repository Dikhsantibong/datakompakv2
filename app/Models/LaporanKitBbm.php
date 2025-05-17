<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitBbm extends Model
{
    use LaporanKitSyncable;

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

    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
}