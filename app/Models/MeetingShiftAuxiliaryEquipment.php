<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftAuxiliaryEquipment extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'auxiliary_equipment_statuses';

    protected $guarded = ['id'];

    protected $fillable = [
        'meeting_shift_id',
        'name',
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

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($equipment) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $equipment->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'equipment_id' => $equipment->id,
                            'meeting_shift_id' => $equipment->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'name' => $equipment->name,
                        'status' => is_string($equipment->status) ? $equipment->status : json_encode($equipment->status),
                        'keterangan' => $equipment->keterangan ?? '',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('auxiliary_equipment_statuses')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAuxiliaryEquipment sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $equipment->meeting_shift_id
                ]);
            }
        });

        static::updated(function ($equipment) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $equipment->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'equipment_id' => $equipment->id,
                            'meeting_shift_id' => $equipment->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'name' => $equipment->name,
                        'status' => is_string($equipment->status) ? $equipment->status : json_encode($equipment->status),
                        'keterangan' => $equipment->keterangan ?? '',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('auxiliary_equipment_statuses')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAuxiliaryEquipment sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $equipment->meeting_shift_id
                ]);
            }
        });

        static::deleting(function ($equipment) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('auxiliary_equipment_statuses')
                        ->where('id', $equipment->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftAuxiliaryEquipment sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 