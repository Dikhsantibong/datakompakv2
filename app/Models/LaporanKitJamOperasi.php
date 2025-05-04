<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitJamOperasi extends Model
{
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
}
