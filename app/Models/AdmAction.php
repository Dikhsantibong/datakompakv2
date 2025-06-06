<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AdmAction extends Model
{
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'abnormal_report_id',
        'flm',
        'pm',
        'cm',
        'ptw',
        'sr'
    ];

    protected $casts = [
        'flm' => 'boolean',
        'pm' => 'boolean',
        'cm' => 'boolean',
        'ptw' => 'boolean',
        'sr' => 'boolean',
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

        static::created(function ($admAction) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('abnormal_report_id_map.' . $admAction->abnormal_report_id);

                    if (!$parentId) {
                        Log::error('Parent AbnormalReport mapping not found', [
                            'adm_action_id' => $admAction->id,
                            'abnormal_report_id' => $admAction->abnormal_report_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'abnormal_report_id' => $parentId,
                        'flm' => $admAction->flm,
                        'pm' => $admAction->pm,
                        'cm' => $admAction->cm,
                        'ptw' => $admAction->ptw,
                        'sr' => $admAction->sr,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert for auto-increment
                    DB::connection('mysql')->table('adm_actions')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AdmAction sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($admAction) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'flm' => $admAction->flm,
                        'pm' => $admAction->pm,
                        'cm' => $admAction->cm,
                        'ptw' => $admAction->ptw,
                        'sr' => $admAction->sr,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('adm_actions')
                        ->where('abnormal_report_id', $admAction->abnormal_report_id)
                        ->where('id', $admAction->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AdmAction sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($admAction) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('adm_actions')
                        ->where('abnormal_report_id', $admAction->abnormal_report_id)
                        ->where('id', $admAction->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AdmAction sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 