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
                    
                    $data = [
                        'id' => $equipment->id,
                        'meeting_shift_id' => $equipment->meeting_shift_id,
                        'name' => $equipment->name,
                        'status' => $equipment->status,
                        'keterangan' => $equipment->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use updateOrInsert instead of insert
                    DB::connection('mysql')->table('auxiliary_equipment_statuses')
                        ->updateOrInsert(
                            ['id' => $equipment->id],
                            $data
                        );

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

        static::updated(function ($equipment) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'name' => $equipment->name,
                        'status' => $equipment->status,
                        'keterangan' => $equipment->keterangan,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('auxiliary_equipment_statuses')
                        ->where('id', $equipment->id)
                        ->update($data);

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