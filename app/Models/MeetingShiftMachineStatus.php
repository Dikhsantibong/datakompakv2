<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftMachineStatus extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'machine_statuses';

    protected $guarded = ['id'];

    protected $fillable = [
        'meeting_shift_id',
        'machine_id',
        'status',
        'keterangan'
    ];

    protected $casts = [
        'status' => 'json',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function meetingShift()
    {
        return $this->belongsTo(MeetingShift::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($machineStatus) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $machineStatus->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'machine_status_id' => $machineStatus->id,
                            'meeting_shift_id' => $machineStatus->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'machine_id' => $machineStatus->machine_id,
                        'status' => is_string($machineStatus->status) ? $machineStatus->status : json_encode($machineStatus->status),
                        'keterangan' => $machineStatus->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('machine_statuses')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftMachineStatus sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $machineStatus->meeting_shift_id
                ]);
            }
        });

        static::updated(function ($machineStatus) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $machineStatus->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'machine_status_id' => $machineStatus->id,
                            'meeting_shift_id' => $machineStatus->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'machine_id' => $machineStatus->machine_id,
                        'status' => is_string($machineStatus->status) ? $machineStatus->status : json_encode($machineStatus->status),
                        'keterangan' => $machineStatus->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('machine_statuses')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftMachineStatus sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $machineStatus->meeting_shift_id
                ]);
            }
        });

        static::deleting(function ($machineStatus) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('machine_statuses')
                        ->where('id', $machineStatus->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftMachineStatus sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 