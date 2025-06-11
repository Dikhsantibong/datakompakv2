<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\FiveS5rBatchUpdated;
use Illuminate\Support\Facades\Log;

class FiveS5rBatch extends Model
{
    public static $isSyncing = false;

    protected $table = 'five_s5r_batches';
    protected $fillable = [
        'created_by',
        'sync_unit_origin',
    ];

    public function pemeriksaan()
    {
        return $this->hasMany(Pemeriksaan5s5r::class, 'batch_id');
    }

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja5r::class, 'batch_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($batch) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    event(new FiveS5rBatchUpdated($batch, 'create', $currentSession));
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in FiveS5rBatch sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($batch) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    event(new FiveS5rBatchUpdated($batch, 'update', $currentSession));
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in FiveS5rBatch sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($batch) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    event(new FiveS5rBatchUpdated($batch, 'delete', $currentSession));
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in FiveS5rBatch sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}