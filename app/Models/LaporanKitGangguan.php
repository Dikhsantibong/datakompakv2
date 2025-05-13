<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKitGangguan extends Model
{
    protected $table = 'laporan_kit_gangguan';

    protected $fillable = [
        'laporan_kit_id',
        'machine_id',
        'mekanik',
        'elektrik'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
}