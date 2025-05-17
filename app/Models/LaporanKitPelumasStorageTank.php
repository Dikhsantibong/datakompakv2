<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitPelumasStorageTank extends Model
{
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

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 