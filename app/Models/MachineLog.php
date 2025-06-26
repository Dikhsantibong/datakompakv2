<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Events\MachineLogUpdated;
use Illuminate\Support\Facades\DB;

class MachineLog extends Model
{
    public static $isSyncing = false;

    protected $fillable = [
        'machine_id',
        'date',
        'time',
        'kw',
        'kvar',
        'cos_phi',
        'status',
        'keterangan',
        'daya_terpasang',
        'silm_slo',
        'dmp_performance'
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
        'kw' => 'decimal:2',
        'kvar' => 'decimal:2',
        'cos_phi' => 'decimal:2',
        'daya_terpasang' => 'decimal:2'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    // Add new method to check existing data
    public static function checkExistingData($machineId, $date, $time)
    {
        return static::where('machine_id', $machineId)
                    ->where('date', $date)
                    ->where('time', $time)
                    ->first();
    }

    // Add new method to update existing data
    public static function updateExistingData($machineId, $date, $time, $data)
    {
        return static::where('machine_id', $machineId)
                    ->where('date', $date)
                    ->where('time', $time)
                    ->update($data);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($machineLog) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $machineLog->machine->powerPlant;

                if (!$powerPlant) {
                    Log::warning('Skipping sync - Power Plant not found for machine log:', [
                        'machine_id' => $machineLog->machine_id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');

                // Sinkronisasi hanya jika kondisi terpenuhi
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    event(new MachineLogUpdated($machineLog, 'create'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    event(new MachineLogUpdated($machineLog, 'create'));
                }
            } catch (\Exception $e) {
                Log::error('Error in MachineLog sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($machineLog) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $machineLog->machine->powerPlant;
                if ($powerPlant) {
                    $currentSession = session('unit', 'mysql');

                    if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                        event(new MachineLogUpdated($machineLog, 'update'));
                    } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                        event(new MachineLogUpdated($machineLog, 'update'));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in MachineLog sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($machineLog) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $machineLog->machine->powerPlant;
                if ($powerPlant) {
                    $currentSession = session('unit', 'mysql');

                    if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                        event(new MachineLogUpdated($machineLog, 'delete'));
                    } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                        event(new MachineLogUpdated($machineLog, 'delete'));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in MachineLog sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}
