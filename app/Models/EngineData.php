<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EngineData extends Model
{
    protected $fillable = [
        'machine_id',
        'date',
        'time',
        'kw',
        'kvar',
        'cos_phi',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
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