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
        'keterangan'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'kw' => 'decimal:2',
        'kvar' => 'decimal:2',
        'cos_phi' => 'decimal:2'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
} 