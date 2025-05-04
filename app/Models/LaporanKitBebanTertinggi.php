<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitBebanTertinggi extends Model
{
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
}