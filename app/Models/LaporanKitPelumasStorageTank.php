<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitPelumasStorageTank extends Model
{
    use LaporanKitSyncable;

    protected $table = 'laporan_kit_pelumas_storage_tanks';

    protected $fillable = [
        'laporan_kit_pelumas_id',
        'tank_number',
        'cm',
        'liter'
    ];

    public function pelumas()
    {
        return $this->belongsTo(LaporanKitPelumas::class, 'laporan_kit_pelumas_id');
    }

    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class)->through('pelumas');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 