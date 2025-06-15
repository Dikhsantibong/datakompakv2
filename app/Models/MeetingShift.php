<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\MeetingShiftUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShift extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'tanggal',
        'current_shift',
        'created_by'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    protected $primaryKey = 'id';

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function machineStatuses()
    {
        return $this->hasMany(MeetingShiftMachineStatus::class);
    }

    public function auxiliaryEquipments()
    {
        return $this->hasMany(MeetingShiftAuxiliaryEquipment::class);
    }

    public function resources()
    {
        return $this->hasMany(MeetingShiftResource::class);
    }

    public function k3ls()
    {
        return $this->hasMany(MeetingShiftK3l::class);
    }

    public function notes()
    {
        return $this->hasMany(MeetingShiftNote::class);
    }

    public function systemNote()
    {
        return $this->hasOne(MeetingShiftNote::class)->where('type', 'sistem');
    }

    public function generalNote()
    {
        return $this->hasOne(MeetingShiftNote::class)->where('type', 'umum');
    }

    public function resume()
    {
        return $this->hasOne(MeetingShiftResume::class);
    }

    public function attendances()
    {
        return $this->hasMany(MeetingShiftAttendance::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($meetingShift) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;

                    // Get the latest ID from mysql database
                    $latestId = DB::connection('mysql')->table('meeting_shifts')->max('id');
                    $newId = $latestId ? $latestId + 1 : 1;

                    // Create main record with new ID
                    $data = [
                        'id' => $newId,
                        'tanggal' => $meetingShift->tanggal,
                        'current_shift' => $meetingShift->current_shift,
                        'created_by' => $meetingShift->created_by,
                        'sync_unit_origin' => $meetingShift->sync_unit_origin,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert with new ID
                    DB::connection('mysql')->table('meeting_shifts')->insert($data);

                    // Store the mapping for child records
                    session(['meeting_shift_id_map.' . $meetingShift->id => $newId]);

                    Log::info('Created meeting shift record with new ID', [
                        'original_id' => $meetingShift->id,
                        'new_id' => $newId
                    ]);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShift sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($meetingShift) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;

                    // Check if record exists
                    $exists = DB::connection('mysql')->table('meeting_shifts')
                        ->where('id', $meetingShift->id)
                        ->exists();

                    if (!$exists) {
                        // If record doesn't exist, treat it as a create with new ID
                        $latestId = DB::connection('mysql')->table('meeting_shifts')->max('id');
                        $newId = $latestId ? $latestId + 1 : 1;

                        // Create main record with new ID
                        $data = [
                            'id' => $newId,
                            'tanggal' => $meetingShift->tanggal,
                            'current_shift' => $meetingShift->current_shift,
                            'created_by' => $meetingShift->created_by,
                            'sync_unit_origin' => $meetingShift->sync_unit_origin,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];

                        // Insert with new ID
                        DB::connection('mysql')->table('meeting_shifts')->insert($data);

                        // Store the mapping for child records
                        session(['meeting_shift_id_map.' . $meetingShift->id => $newId]);

                        Log::info('Created new record during update (record not found)', [
                            'original_id' => $meetingShift->id,
                            'new_id' => $newId
                        ]);
                    } else {
                        // Update existing record
                        DB::connection('mysql')->table('meeting_shifts')
                            ->where('id', $meetingShift->id)
                            ->update([
                                'tanggal' => $meetingShift->tanggal,
                                'current_shift' => $meetingShift->current_shift,
                                'updated_at' => now()
                            ]);

                        // Store the mapping for child records
                        session(['meeting_shift_id_map.' . $meetingShift->id => $meetingShift->id]);
                    }

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShift sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($meetingShift) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    event(new MeetingShiftUpdated($meetingShift, 'delete'));
                }
            } catch (\Exception $e) {
                Log::error('Error in MeetingShift sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}