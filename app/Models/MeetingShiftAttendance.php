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
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'shift' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
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
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $attendance->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'attendance_id' => $attendance->id,
                            'meeting_shift_id' => $attendance->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'meeting_shift_id' => $parentId,
                        'nama' => $attendance->nama,
                        'shift' => $attendance->shift,
                        'status' => $attendance->status,
                        'keterangan' => $attendance->keterangan ?? '',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('meeting_shift_attendance')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAttendance sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $attendance->meeting_shift_id
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
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $attendance->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'attendance_id' => $attendance->id,
                            'meeting_shift_id' => $attendance->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'meeting_shift_id' => $parentId,
                        'nama' => $attendance->nama,
                        'shift' => $attendance->shift,
                        'status' => $attendance->status,
                        'keterangan' => $attendance->keterangan ?? '',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('meeting_shift_attendance')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAttendance sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $attendance->meeting_shift_id
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