<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blackstart extends Model
{
    use HasFactory;

    protected $table = 'blackstarts';

    protected $fillable = [
        'tanggal',
        'unit_id',
        'pembangkit_status',
        'black_start_status',
        'sop_status',
        'load_set_status',
        'line_energize_status',
        'status_jaringan',
        'pic',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with PowerPlant
    public function powerPlant()
    {
        return $this->belongsTo(PowerPlant::class, 'unit_id');
    }

    // Relationship with PeralatanBlackstart
    public function peralatanBlackstarts()
    {
        return $this->hasMany(PeralatanBlackstart::class);
    }
} 