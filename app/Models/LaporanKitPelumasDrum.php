<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitPelumasDrum extends Model
{
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

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 