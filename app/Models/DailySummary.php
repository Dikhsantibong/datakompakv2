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
        'installed_power',    // Terpasang
        'dmn_power',         // DMN
        'capable_power',     // Mampu
        'peak_load_day',     // Siang
        'peak_load_night',   // Malam
        'kit_ratio',         // Kit
        'gross_production',  // Bruto
        'net_production',    // Netto
        'aux_power',         // Aux (kWh)
        'transformer_losses', // Susut Trafo (kWh)
        'usage_percentage',  // Persentase (%)
        'period_hours',      // Jam
        'operating_hours',   // OPR
        'standby_hours',     // STANDBY
        'planned_outage',    // PO
        'maintenance_outage', // MO
        'forced_outage',     // FO
        'trip_machine',      // Mesin (kali)
        'trip_electrical',   // Listrik (kali)
        'efdh',              // EFDH
        'epdh',              // EPDH
        'eudh',              // EUDH
        'esdh',              // ESDH
        'eaf',               // EAF (%)
        'sof',               // SOF (%)
        'efor',              // EFOR (%)
        'sdof',              // SdOF (Kali)
        'ncf',               // NCF
        'nof',               // NOF
        'hsd_fuel',          // HSD (Liter)
        'b35_fuel',          // B35 (Liter)
        'mfo_fuel',          // MFO (Liter)
        'total_fuel',        // Total BBM (Liter)
        'water_usage',       // Air (M³)
        'meditran_oil',      // Meditran SX 15W/40 CH-4 (LITER)
        'salyx_420',         // Salyx 420 (LITER)
        'salyx_430',         // Salyx 430 (LITER)
        'travolube_a',       // TravoLube A (LITER)
        'turbolube_46',      // Turbolube 46 (LITER)
        'turbolube_68',      // Turbolube 68 (LITER)
        'total_oil',         // TOTAL (LITER)
        'sfc_scc',           // SFC/SCC (LITER/KWH)
        'nphr',              // TARA KALOR/NPHR (KCAL/KWH)
        'slc',               // SLC (CC/KWH)
        'notes',             // Keterangan
    ];

    public function powerPlant()
    {
        return $this->belongsTo(PowerPlant::class);
    }
}
