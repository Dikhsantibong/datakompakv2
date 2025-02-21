<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'power_plant_id',
        'machine_name',
        'installed_power',
        'dmn_power',
        'capable_power',
        'peak_load_day',
        'peak_load_night',
        'kit_ratio',
        'gross_production',
        'net_production',
        'aux_power',
        'transformer_losses',
        'usage_percentage',
        'period_hours',
        'operating_hours',
        'standby_hours',
        'planned_outage',
        'maintenance_outage',
        'forced_outage',
        'trip_machine',
        'trip_electrical',
        'efdh',
        'epdh',
        'eudh',
        'esdh',
        'eaf',
        'sof',
        'efor',
        'sdof',
        'ncf',
        'nof',
    ];

    public function powerPlant()
    {
        return $this->belongsTo(PowerPlant::class);
    }
}
