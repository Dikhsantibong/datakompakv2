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
        'ptw'
    ];

    protected $casts = [
        'flm' => 'boolean',
        'pm' => 'boolean',
        'cm' => 'boolean',
        'ptw' => 'boolean',
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
                    
                    $data = [
                        'id' => $admAction->id,
                        'abnormal_report_id' => $admAction->abnormal_report_id,
                        'flm' => $admAction->flm,
                        'pm' => $admAction->pm,
                        'cm' => $admAction->cm,
                        'ptw' => $admAction->ptw,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use updateOrInsert instead of insert
                    DB::connection('mysql')->table('adm_actions')
                        ->updateOrInsert(
                            ['id' => $admAction->id],
                            $data
                        );

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