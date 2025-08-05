<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MeetingShiftResource extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'resource_statuses';

    protected $fillable = [
        'meeting_shift_id',
        'name',
        'category',
        'status',
        'keterangan'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the valid status values
     *
     * @return array
     */
    public static function getValidStatuses()
    {
        return ['0-20', '21-40', '41-61', '61-80', 'up-80'];
    }

    /**
     * Get the valid categories
     *
     * @return array
     */
    public static function getValidCategories()
    {
        return ['PELUMAS', 'BBM', 'AIR PENDINGIN', 'UDARA START', 'AKI'];
    }

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

        static::created(function ($resource) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $resource->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'resource_id' => $resource->id,
                            'meeting_shift_id' => $resource->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }

                    $data = [
                        'meeting_shift_id' => $parentId,
                        'name' => $resource->name,
                        'category' => $resource->category,
                        'status' => $resource->status,
                        'keterangan' => $resource->keterangan ?? '',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('resource_statuses')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftResource sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $resource->meeting_shift_id
                ]);
            }
        });

        static::updated(function ($resource) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('meeting_shift_id_map.' . $resource->meeting_shift_id);

                    if (!$parentId) {
                        Log::error('Parent MeetingShift mapping not found', [
                            'resource_id' => $resource->id,
                            'meeting_shift_id' => $resource->meeting_shift_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'meeting_shift_id' => $parentId,
                        'name' => $resource->name,
                        'category' => $resource->category,
                        'status' => $resource->status,
                        'keterangan' => $resource->keterangan ?? '',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('resource_statuses')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftResource sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $resource->meeting_shift_id
                ]);
            }
        });

        static::deleting(function ($resource) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('resource_statuses')
                        ->where('id', $resource->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in MeetingShiftResource sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 