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
        'diagram_evidence',
        'sop_status',
        'sop_evidence',
        'load_set_status',
        'line_energize_status',
        'status_jaringan',
        'pic'
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

    // Helper method to get full evidence path
    public function getDiagramEvidencePathAttribute()
    {
        return $this->diagram_evidence ? asset('storage/' . $this->diagram_evidence) : null;
    }

    public function getSopEvidencePathAttribute()
    {
        return $this->sop_evidence ? asset('storage/' . $this->sop_evidence) : null;
    }
} 