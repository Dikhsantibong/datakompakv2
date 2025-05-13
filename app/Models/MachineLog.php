<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineLog extends Model
{
    protected $fillable = [
        'machine_id',
        'date',
        'time',
        'kw',
        'kvar',
        'cos_phi',
        'status',
        'keterangan',
        'daya_terpasang',
        'silm_slo',
        'dmp_performance'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'kw' => 'decimal:2',
        'kvar' => 'decimal:2',
        'cos_phi' => 'decimal:2',
        'daya_terpasang' => 'decimal:2'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 