<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\DailySummaryUpdated;

class DailySummary extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'uuid',
        'power_plant_id',
        'machine_name',
        'unit_source',
        'date',
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
        'b40_fuel',          // B40 (Liter)
        'mfo_fuel',          // MFO (Liter)
        'total_fuel',        // Total BBM (Liter)
        'water_usage',       // Air (M³)
        'meditran_oil',      // Meditran SX 15W/40 CH-4 (LITER) / Kolaka: MEDITRAN SMX 15W/40
        'salyx_420',         // Salyx 420 (LITER)
        'salyx_430',         // Salyx 430 (LITER)
        'travolube_a',       // TravoLube A (LITER)
        'turbolube_46',      // Turbolube 46 (LITER)
        'turbolube_68',      // Turbolube 68 (LITER)
        'shell_argina_s3',   // Shell Argina S3 (LITER)
        'total_oil',         // TOTAL (LITER)
        // Pelumas khusus Kolaka (tidak ada di field utama)
        'diala_b',           // Kolaka: DIALA B
        'turboil_68',        // Kolaka: TurbOil 68
        'meditran_s40',      // Kolaka: MEDITRAN S40
        'turbo_lube_xt68',   // Kolaka: Turbo Lube XT68
        'turbo_oil_46',      // Kolaka: Turbo Oil 46 (jika berbeda dengan turbolube_46)
        'trafo_lube_a',      // Kolaka: Trafo Lube A
        'sfc_scc',           // SFC/SCC (LITER/KWH)
        'nphr',              // TARA KALOR/NPHR (KCAL/KWH)
        'slc',               // SLC (CC/KWH)
        'notes',             // Keterangan
        'jsi',               // JSI
        'meditran_smx_15w40',    // Kolaka: MEDITRAN SMX 15W/40
        'salyx_420',             // Kolaka: SALYX 420
        'diala_b',               // Kolaka: DIALA B
        'turbo_oil_46',          // Kolaka: Turbo Oil 46
        'turboil_68',            // Kolaka: TurbOil 68
        'meditran_s40',          // Kolaka: MEDITRAN S40
        'turbo_lube_xt68',       // Kolaka: Turbo Lube XT68
        'trafo_lube_a',          // Kolaka: Trafo Lube A
        'meditran_sx_15w40',     // Kolaka: MEDITRAN SX  15W/40 (dua spasi, berbeda dengan SMX)
    ];

    // Sesuaikan casting dengan tipe data di database
    protected $casts = [
        'date' => 'date',
        'installed_power' => 'decimal:3',
        'dmn_power' => 'decimal:3',
        'capable_power' => 'decimal:3',
        'peak_load_day' => 'decimal:3',
        'peak_load_night' => 'decimal:3',
        'kit_ratio' => 'decimal:2',
        'gross_production' => 'decimal:3',
        'net_production' => 'decimal:3',
        'aux_power' => 'decimal:3',
        'transformer_losses' => 'decimal:3',
        'usage_percentage' => 'decimal:2',
        'period_hours' => 'decimal:2',
        'operating_hours' => 'decimal:2',
        'standby_hours' => 'decimal:2',
        'planned_outage' => 'decimal:2',
        'maintenance_outage' => 'decimal:2',
        'forced_outage' => 'decimal:2',
        'trip_machine' => 'decimal:2',
        'trip_electrical' => 'decimal:2',
        'efdh' => 'decimal:2',
        'epdh' => 'decimal:2',
        'eudh' => 'decimal:2',
        'esdh' => 'decimal:2',
        'eaf' => 'decimal:2',
        'sof' => 'decimal:2',
        'efor' => 'decimal:2',
        'sdof' => 'decimal:2',
        'ncf' => 'decimal:2',
        'nof' => 'decimal:2',
        'jsi' => 'decimal:2',
        'hsd_fuel' => 'decimal:3',
        'b35_fuel' => 'decimal:3',
        'b40_fuel' => 'decimal:3',
        'mfo_fuel' => 'decimal:3',
        'total_fuel' => 'decimal:3',
        'water_usage' => 'decimal:3',
        'meditran_oil' => 'decimal:3',
        'salyx_420' => 'decimal:3',
        'salyx_430' => 'decimal:3',
        'travolube_a' => 'decimal:3',
        'turbolube_46' => 'decimal:3',
        'turbolube_68' => 'decimal:3',
        'shell_argina_s3' => 'decimal:2',
        'total_oil' => 'decimal:3',
        // Pelumas khusus Kolaka
        'diala_b' => 'decimal:3',
        'turboil_68' => 'decimal:3',
        'meditran_s40' => 'decimal:3',
        'turbo_lube_xt68' => 'decimal:3',
        'turbo_oil_46' => 'decimal:3',
        'trafo_lube_a' => 'decimal:3',
        'sfc_scc' => 'decimal:3',
        'nphr' => 'decimal:3',
        'slc' => 'decimal:3',
        'uuid' => 'string',
        'unit_source' => 'string',
        'meditran_smx_15w40' => 'decimal:3',
        'diala_b' => 'decimal:3',
        'turbo_oil_46' => 'decimal:3',
        'turboil_68' => 'decimal:3',
        'meditran_s40' => 'decimal:3',
        'turbo_lube_xt68' => 'decimal:3',
        'trafo_lube_a' => 'decimal:3',
        'meditran_sx_15w40' => 'decimal:3',
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    public function powerPlant()
    {
        return $this->belongsTo(PowerPlant::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
            $model->unit_source = session('unit', 'mysql');
        });

        static::saved(function ($dailySummary) {
            if (self::$isSyncing) return;

            $currentSession = session('unit', 'mysql');
            $powerPlant = $dailySummary->powerPlant;

            if ($powerPlant) {
                if ($currentSession !== 'mysql') {
                    Log::info('Triggering sync from local to UP Kendari', [
                        'current_session' => $currentSession,
                        'uuid' => $dailySummary->uuid,
                        'power_plant' => $powerPlant->id
                    ]);
                    event(new DailySummaryUpdated($dailySummary, 'update'));
                } elseif ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    Log::info('Triggering sync from UP Kendari to local', [
                        'target_unit' => $powerPlant->unit_source,
                        'uuid' => $dailySummary->uuid,
                        'power_plant' => $powerPlant->id
                    ]);
                    event(new DailySummaryUpdated($dailySummary, 'update'));
                }
            }
        });

        static::created(function ($dailySummary) {
            if (self::$isSyncing) return;

            $currentSession = session('unit', 'mysql');
            $powerPlant = $dailySummary->powerPlant;

            if ($powerPlant) {
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    event(new DailySummaryUpdated($dailySummary, 'create'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    event(new DailySummaryUpdated($dailySummary, 'create'));
                }
            }
        });

        static::deleted(function ($dailySummary) {
            if (self::$isSyncing) return;

            $currentSession = session('unit', 'mysql');
            $powerPlant = $dailySummary->powerPlant;

            if ($powerPlant) {
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    event(new DailySummaryUpdated($dailySummary, 'delete'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    event(new DailySummaryUpdated($dailySummary, 'delete'));
                }
            }
        });
    }

    protected static function logSyncProcess($stage, $data)
    {
        $sessionId = uniqid('daily_summary_sync_');
        $currentSession = session('unit', 'mysql');

        $logData = array_merge([
            'sync_id' => $sessionId,
            'timestamp' => now()->toDateTimeString(),
            'stage' => $stage,
            'current_session' => $currentSession,
        ], $data);

        Log::channel('sync')->info("Daily Summary Sync Process: {$stage}", $logData);

        return $sessionId;
    }
}