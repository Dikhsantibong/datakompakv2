<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class FollowUpAction extends Model
{
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'abnormal_report_id',
        'flm_tindakan',
        'mo_non_rutin',
        'usul_mo_rutin',
        'lainnya'
    ];

    protected $casts = [
        'flm_tindakan' => 'boolean',
        'mo_non_rutin' => 'boolean',
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

        static::created(function ($action) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('abnormal_report_id_map.' . $action->abnormal_report_id);

                    if (!$parentId) {
                        Log::error('Parent AbnormalReport mapping not found', [
                            'follow_up_action_id' => $action->id,
                            'abnormal_report_id' => $action->abnormal_report_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'abnormal_report_id' => $parentId,
                        'flm_tindakan' => $action->flm_tindakan,
                        'mo_non_rutin' => $action->mo_non_rutin,
                        'usul_mo_rutin' => $action->usul_mo_rutin,
                        'lainnya' => $action->lainnya,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to create new record with auto-increment ID
                    DB::connection('mysql')->table('follow_up_actions')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in FollowUpAction sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'action_id' => $action->id ?? null,
                    'abnormal_report_id' => $action->abnormal_report_id ?? null
                ]);
            }
        });

        // Remove direct sync on update since it's handled by AbnormalReportUpdated event
        static::updated(function ($action) {
            // No direct sync needed - handled by AbnormalReportUpdated event
        });

        static::deleting(function ($action) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('follow_up_actions')
                        ->where('abnormal_report_id', $action->abnormal_report_id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in FollowUpAction sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'action_id' => $action->id ?? null,
                    'abnormal_report_id' => $action->abnormal_report_id ?? null
                ]);
            }
        });
    }
} 