<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftAttendance extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_shift_attendance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_shift_id',
        'nama',
        'shift',
        'status',
        'keterangan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'shift' => 'string',
        'status' => 'string'
    ];

    /**
     * Get valid shift values
     *
     * @return array
     */
    public static function getValidShifts()
    {
        return ['A', 'B', 'C', 'D'];
    }

    /**
     * Get valid status values
     *
     * @return array
     */
    public static function getValidStatuses()
    {
        return ['hadir', 'izin', 'sakit', 'cuti', 'alpha'];
    }

    /**
     * Get the meeting shift that owns this attendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
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

        static::created(function ($attendance) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'meeting_shift_id' => $attendance->meeting_shift_id,
                        'nama' => $attendance->nama,
                        'shift' => $attendance->shift,
                        'status' => $attendance->status,
                        'keterangan' => $attendance->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('meeting_shift_attendance')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAttendance sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($attendance) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'nama' => $attendance->nama,
                        'shift' => $attendance->shift,
                        'status' => $attendance->status,
                        'keterangan' => $attendance->keterangan,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('meeting_shift_attendance')
                        ->where('meeting_shift_id', $attendance->meeting_shift_id)
                        ->where('id', $attendance->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAttendance sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($attendance) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('meeting_shift_attendance')
                        ->where('meeting_shift_id', $attendance->meeting_shift_id)
                        ->where('id', $attendance->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAttendance sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 