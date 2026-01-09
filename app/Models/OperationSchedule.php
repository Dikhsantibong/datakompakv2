<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\OperationScheduleUpdated;
use Illuminate\Support\Facades\Log;

class OperationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'schedule_date',
        'start_time',
        'end_time',
        'location',
        'status',
        'participants',
        'created_by'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'participants' => 'array'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    // Sinkronisasi event
    public static $isSyncing = false;
    
    protected static function boot()
    {
        parent::boot();

        static::created(function ($schedule) {
            try {
                if (self::$isSyncing) return;
                $currentSession = session('unit', 'mysql');
                event(new OperationScheduleUpdated($schedule, 'create'));
            } catch (\Exception $e) {
                Log::error('Error in OperationSchedule sync (create):', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($schedule) {
            try {
                if (self::$isSyncing) return;
                $currentSession = session('unit', 'mysql');
                event(new OperationScheduleUpdated($schedule, 'update'));
            } catch (\Exception $e) {
                Log::error('Error in OperationSchedule sync (update):', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($schedule) {
            try {
                if (self::$isSyncing) return;
                $currentSession = session('unit', 'mysql');
                event(new OperationScheduleUpdated($schedule, 'delete'));
            } catch (\Exception $e) {
                Log::error('Error in OperationSchedule sync (delete):', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}