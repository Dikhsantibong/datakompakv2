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
        'kompresor_diesel_kondisi',
        'kompresor_eviden',
        'tabung_udara_jumlah',
        'tabung_udara_kondisi',
        'tabung_eviden',
        'ups_kondisi',
        'lampu_emergency_jumlah',
        'lampu_emergency_kondisi',
        'lampu_eviden',
        'battery_catudaya_jumlah',
        'battery_catudaya_kondisi',
        'catudaya_eviden',
        'battery_blackstart_jumlah',
        'battery_blackstart_kondisi',
        'blackstart_eviden',
        'radio_jumlah',
        'radio_komunikasi_kondisi',
        'radio_eviden',
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

    // Status constants
    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';

    // Get available status options
    public static function getStatusOptions()
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_CLOSE => 'Close'
        ];
    }

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

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 