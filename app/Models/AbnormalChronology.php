<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AbnormalChronology extends Model
{
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $casts = [
        'waktu' => 'datetime',
        'turun_beban' => 'boolean',
        'off_cbg' => 'boolean',
        'stop' => 'boolean',
        'tl_ophar' => 'boolean',
        'tl_op' => 'boolean',
        'tl_har' => 'boolean',
        'mul' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        'abnormal_report_id',
        'waktu',
        'uraian_kejadian',
        'visual',
        'parameter',
        'turun_beban',
        'off_cbg',
        'stop',
        'tl_ophar',
        'tl_op',
        'tl_har',
        'mul'
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

        static::created(function ($chronology) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $chronology->id,
                        'abnormal_report_id' => $chronology->abnormal_report_id,
                        'waktu' => $chronology->waktu,
                        'uraian_kejadian' => $chronology->uraian_kejadian,
                        'visual' => $chronology->visual,
                        'parameter' => $chronology->parameter,
                        'turun_beban' => $chronology->turun_beban,
                        'off_cbg' => $chronology->off_cbg,
                        'stop' => $chronology->stop,
                        'tl_ophar' => $chronology->tl_ophar,
                        'tl_op' => $chronology->tl_op,
                        'tl_har' => $chronology->tl_har,
                        'mul' => $chronology->mul,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('abnormal_chronologies')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AbnormalChronology sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($chronology) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'waktu' => $chronology->waktu,
                        'uraian_kejadian' => $chronology->uraian_kejadian,
                        'visual' => $chronology->visual,
                        'parameter' => $chronology->parameter,
                        'turun_beban' => $chronology->turun_beban,
                        'off_cbg' => $chronology->off_cbg,
                        'stop' => $chronology->stop,
                        'tl_ophar' => $chronology->tl_ophar,
                        'tl_op' => $chronology->tl_op,
                        'tl_har' => $chronology->tl_har,
                        'mul' => $chronology->mul,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('abnormal_chronologies')
                        ->where('abnormal_report_id', $chronology->abnormal_report_id)
                        ->where('id', $chronology->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AbnormalChronology sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($chronology) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('abnormal_chronologies')
                        ->where('abnormal_report_id', $chronology->abnormal_report_id)
                        ->where('id', $chronology->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in AbnormalChronology sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 