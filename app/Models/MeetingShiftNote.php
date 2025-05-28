<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftNote extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'meeting_shift_id',
        'type',
        'content'
    ];

    protected $casts = [
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

        static::created(function ($note) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $note->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'note_id' => $note->id,
                            'meeting_shift_id' => $note->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'type' => $note->type,
                        'content' => $note->content,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('meeting_shift_notes')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftNote sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $note->meeting_shift_id
                ]);
            }
        });

        static::updated(function ($note) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $note->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'note_id' => $note->id,
                            'meeting_shift_id' => $note->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'type' => $note->type,
                        'content' => $note->content,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('meeting_shift_notes')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftNote sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $note->meeting_shift_id
                ]);
            }
        });

        static::deleting(function ($note) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('meeting_shift_notes')
                        ->where('id', $note->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftNote sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 