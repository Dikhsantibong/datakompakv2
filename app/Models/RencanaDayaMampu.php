<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaDayaMampu extends Model
{
    use HasFactory;

    protected $table = 'rencana_daya_mampu';

    protected $fillable = [
        'machine_id',
        'tanggal',
        'rencana',
        'realisasi',
        'daya_pjbtl_silm',
        'dmp_existing',
        'unit_source'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'rencana' => 'float',
        'realisasi' => 'float',
        'daya_pjbtl_silm' => 'float',
        'dmp_existing' => 'float'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit');
    }
} 