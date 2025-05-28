<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftResume extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'meeting_shift_resume';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_shift_id',
        'content'
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the meeting shift that owns this resume.
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

        static::created(function ($resume) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $resume->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'resume_id' => $resume->id,
                            'meeting_shift_id' => $resume->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'content' => $resume->content,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('meeting_shift_resume')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftResume sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $resume->meeting_shift_id
                ]);
            }
        });

        static::updated(function ($resume) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $resume->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'resume_id' => $resume->id,
                            'meeting_shift_id' => $resume->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'content' => $resume->content,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('meeting_shift_resume')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftResume sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $resume->meeting_shift_id
                ]);
            }
        });

        static::deleting(function ($resume) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('meeting_shift_resume')
                        ->where('id', $resume->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftResume sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 