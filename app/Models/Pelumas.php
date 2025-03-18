<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelumas extends Model
{
    protected $table = 'pelumas';
    
    protected $fillable = [
        'tanggal',
        'unit_id',
        'jenis_pelumas',
        'saldo_awal',
        'penerimaan',
        'pemakaian',
        'saldo_akhir',
        'is_opening_balance'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'saldo_awal' => 'decimal:2',
        'penerimaan' => 'decimal:2',
        'pemakaian' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(PowerPlant::class, 'unit_id');
    }
} 