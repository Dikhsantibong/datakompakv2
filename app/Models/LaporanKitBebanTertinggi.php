<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitBebanTertinggi extends Model
{
    use LaporanKitSyncable;

    protected $table = 'laporan_kit_beban_tertinggi';

    protected $fillable = [
        'laporan_kit_id',
        'machine_id', 
        'siang',
        'malam'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
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