<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class Recommendation extends Model
{
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'abnormal_report_id',
        'content'
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

        static::created(function ($recommendation) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $recommendation->id,
                        'abnormal_report_id' => $recommendation->abnormal_report_id,
                        'content' => $recommendation->content,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('recommendations')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in Recommendation sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($recommendation) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'content' => $recommendation->content,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('recommendations')
                        ->where('abnormal_report_id', $recommendation->abnormal_report_id)
                        ->where('id', $recommendation->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in Recommendation sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($recommendation) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('recommendations')
                        ->where('abnormal_report_id', $recommendation->abnormal_report_id)
                        ->where('id', $recommendation->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in Recommendation sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 