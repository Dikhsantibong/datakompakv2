<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\PatrolCheckUpdated;
use Illuminate\Support\Facades\Log;

class PatrolCheck extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'patrol_checks';

    protected $fillable = [
        'created_by',
        'shift',
        'time',
        'condition_systems',
        'abnormal_equipments',
        'condition_after',
        'notes',
        'status',
        'sync_unit_origin'
    ];

    protected $casts = [
        'time' => 'datetime',
        'condition_systems' => 'array',
        'abnormal_equipments' => 'array',
        'condition_after' => 'array',
        'sync_unit_origin' => 'string'
    ];

    public function creator()
    {
            return $this->belongsTo(User::class, 'created_by');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($patrolCheck) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new PatrolCheckUpdated($patrolCheck, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in PatrolCheck sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($patrolCheck) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new PatrolCheckUpdated($patrolCheck, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in PatrolCheck sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($patrolCheck) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new PatrolCheckUpdated($patrolCheck, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in PatrolCheck sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 