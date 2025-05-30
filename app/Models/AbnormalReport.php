<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Events\AbnormalReportUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AbnormalReport extends Model
{
    public static $isSyncing = false;

    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'sync_unit_origin' => 'string',
    ];

    public function chronologies(): HasMany
    {
        return $this->hasMany(AbnormalChronology::class);
    }

    public function affectedMachines(): HasMany
    {
        return $this->hasMany(AffectedMachine::class);
    }

    public function followUpActions(): HasMany
    {
        return $this->hasMany(FollowUpAction::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function admActions(): HasMany
    {
        return $this->hasMany(AdmAction::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function evidences(): HasMany
    {
        return $this->hasMany(AbnormalEvidence::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($abnormalReport) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'created_by' => $abnormalReport->created_by,
                        'sync_unit_origin' => $abnormalReport->sync_unit_origin,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get new auto-increment ID
                    $newId = DB::connection('mysql')->table('abnormal_reports')->insertGetId($data);

                    // Store the new ID mapping in session for child records
                    session(['abnormal_report_id_map.' . $abnormalReport->id => $newId]);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                Log::error('Error in AbnormalReport sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($abnormalReport) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    event(new AbnormalReportUpdated($abnormalReport, 'update'));
                }
            } catch (\Exception $e) {
                Log::error('Error in AbnormalReport sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($abnormalReport) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    event(new AbnormalReportUpdated($abnormalReport, 'delete'));
                }
            } catch (\Exception $e) {
                Log::error('Error in AbnormalReport sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 