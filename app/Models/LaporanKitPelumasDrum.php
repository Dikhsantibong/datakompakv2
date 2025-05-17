<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LaporanKitSyncable;

class LaporanKitPelumasDrum extends Model
{
    use LaporanKitSyncable;

    protected $table = 'laporan_kit_pelumas_drums';

    protected $fillable = [
        'laporan_kit_pelumas_id',
        'area_number',
        'jumlah'
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