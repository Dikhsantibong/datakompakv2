<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitJamOperasi extends Model
{
    use LaporanKitSyncable;

    protected $table = 'laporan_kit_jam_operasi';

    protected $fillable = [
        'laporan_kit_id',
        'machine_id', 
        'ops',
        'har',
        'ggn',
        'stby',
        'jam_hari'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
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