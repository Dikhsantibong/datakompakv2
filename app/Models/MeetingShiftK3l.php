<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftK3l extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_shift_k3l';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_shift_id',
        'type',
        'uraian',
        'saran',
        'eviden_path'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => 'string'
    ];

    /**
     * Get the valid types
     *
     * @return array
     */
    public static function getValidTypes()
    {
        return ['unsafe_action', 'unsafe_condition'];
    }

    /**
     * Get the meeting shift that owns this K3L report.
     */
    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($k3l) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'meeting_shift_id' => $k3l->meeting_shift_id,
                        'type' => $k3l->type,
                        'uraian' => $k3l->uraian,
                        'saran' => $k3l->saran,
                        'eviden_path' => $k3l->eviden_path,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('meeting_shift_k3l')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftK3l sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($k3l) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'type' => $k3l->type,
                        'uraian' => $k3l->uraian,
                        'saran' => $k3l->saran,
                        'eviden_path' => $k3l->eviden_path,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('meeting_shift_k3l')
                        ->where('meeting_shift_id', $k3l->meeting_shift_id)
                        ->where('id', $k3l->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftK3l sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($k3l) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('meeting_shift_k3l')
                        ->where('meeting_shift_id', $k3l->meeting_shift_id)
                        ->where('id', $k3l->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftK3l sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 