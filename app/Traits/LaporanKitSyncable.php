<?php

namespace App\Traits;

use App\Events\LaporanKitUpdated;
use Illuminate\Support\Facades\Log;

trait LaporanKitSyncable
{
    public static $isSyncing = false;

    protected static function bootLaporanKitSyncable()
    {
        static::created(function ($model) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $model->laporanKit->powerPlant ?? null;
                
                if (!$powerPlant) {
                    Log::warning('Skipping sync - Power Plant not found for model:', [
                        'model' => get_class($model),
                        'id' => $model->id
                    ]);
                    return;
                }

                event(new LaporanKitUpdated($model, 'create', class_basename(get_class($model))));
            } catch (\Exception $e) {
                Log::error('Error in model sync:', [
                    'model' => get_class($model),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($model) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $model->laporanKit->powerPlant ?? null;
                if ($powerPlant) {
                    event(new LaporanKitUpdated($model, 'update', class_basename(get_class($model))));
                }
            } catch (\Exception $e) {
                Log::error('Error in model sync:', [
                    'model' => get_class($model),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($model) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $model->laporanKit->powerPlant ?? null;
                if ($powerPlant) {
                    event(new LaporanKitUpdated($model, 'delete', class_basename(get_class($model))));
                }
            } catch (\Exception $e) {
                Log::error('Error in model sync:', [
                    'model' => get_class($model),
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }
} 