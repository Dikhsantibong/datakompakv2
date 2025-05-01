<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeralatanBlackstart extends Model
{
    use HasFactory;

    protected $table = 'peralatan_blackstarts';

    protected $fillable = [
        'blackstart_id',
        'unit_id',
        'kompresor_diesel_jumlah',
        'kompresor_diesel_satuan',
        'kompresor_diesel_kondisi',
        'tabung_udara_jumlah',
        'tabung_udara_satuan',
        'tabung_udara_kondisi',
        'ups_kondisi',
        'lampu_emergency_jumlah',
        'lampu_emergency_kondisi',
        'battery_catudaya_jumlah',
        'battery_catudaya_satuan',
        'battery_catudaya_kondisi',
        'battery_blackstart_jumlah',
        'battery_blackstart_satuan',
        'battery_blackstart_kondisi',
        'radio_komunikasi_kondisi',
        'radio_kompresor_kondisi',
        'panel_kondisi',
        'simulasi_blackstart',
        'start_kondisi_blackout',
        'waktu_mulai',
        'waktu_selesai',
        'waktu_deadline',
        'pic',
        'status'
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'waktu_deadline' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with Blackstart
    public function blackstart()
    {
        return $this->belongsTo(Blackstart::class);
    }

    // Relationship with PowerPlant
    public function powerPlant()
    {
        return $this->belongsTo(PowerPlant::class, 'unit_id');
    }
} 