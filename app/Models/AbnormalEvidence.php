<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AbnormalEvidence extends Model
{
    protected $table = 'abnormal_evidences';
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'abnormal_report_id',
        'file_path',
        'description'
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

        static::created(function ($evidence) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $evidence->id,
                        'abnormal_report_id' => $evidence->abnormal_report_id,
                        'file_path' => $evidence->file_path,
                        'description' => $evidence->description,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('abnormal_evidences')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AbnormalEvidence sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($evidence) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'file_path' => $evidence->file_path,
                        'description' => $evidence->description,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('abnormal_evidences')
                        ->where('abnormal_report_id', $evidence->abnormal_report_id)
                        ->where('id', $evidence->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AbnormalEvidence sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($evidence) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('abnormal_evidences')
                        ->where('abnormal_report_id', $evidence->abnormal_report_id)
                        ->where('id', $evidence->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AbnormalEvidence sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 