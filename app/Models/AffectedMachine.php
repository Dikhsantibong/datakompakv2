<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AffectedMachine extends Model
{
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'abnormal_report_id',
        'kondisi_rusak',
        'kondisi_abnormal',
        'nama_mesin',
        'keterangan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function abnormalReport(): BelongsTo
    {
        return $this->belongsTo(AbnormalReport::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($machine) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('abnormal_report_id_map.' . $machine->abnormal_report_id);

                    if (!$parentId) {
                        Log::error('Parent AbnormalReport mapping not found', [
                            'affected_machine_id' => $machine->id,
                            'abnormal_report_id' => $machine->abnormal_report_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'abnormal_report_id' => $parentId,
                        'kondisi_rusak' => $machine->kondisi_rusak,
                        'kondisi_abnormal' => $machine->kondisi_abnormal,
                        'nama_mesin' => $machine->nama_mesin,
                        'keterangan' => $machine->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert for auto-increment
                    DB::connection('mysql')->table('affected_machines')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AffectedMachine sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($machine) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'kondisi_rusak' => $machine->kondisi_rusak,
                        'kondisi_abnormal' => $machine->kondisi_abnormal,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('affected_machines')
                        ->where('abnormal_report_id', $machine->abnormal_report_id)
                        ->where('id', $machine->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AffectedMachine sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($machine) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                        
                    // Delete from mysql database
                    DB::connection('mysql')->table('affected_machines')
                        ->where('abnormal_report_id', $machine->abnormal_report_id)
                        ->where('id', $machine->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AffectedMachine sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 