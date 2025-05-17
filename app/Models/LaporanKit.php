<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\LaporanKitUpdated;
use Illuminate\Support\Facades\Log;

class LaporanKit extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kits';

    protected $fillable = [
        'tanggal',
        'unit_source',
        'created_by',
        // tambahkan field lain jika ada
    ];

    public function jamOperasi()    { return $this->hasMany(LaporanKitJamOperasi::class); }
    public function gangguan()      { return $this->hasMany(LaporanKitGangguan::class); }
    public function bbm()           { return $this->hasMany(LaporanKitBbm::class); }
    public function kwh()           { return $this->hasMany(LaporanKitKwh::class); }
    public function pelumas()       { return $this->hasMany(LaporanKitPelumas::class); }
    public function bahanKimia()    { return $this->hasMany(LaporanKitBahanKimia::class); }
    public function bebanTertinggi(){ return $this->hasMany(LaporanKitBebanTertinggi::class); }
    public function creator()       { return $this->belongsTo(User::class, 'created_by'); }
    public function powerPlant()
    {
        return $this->belongsTo(\App\Models\PowerPlant::class, 'unit_source', 'unit_source');
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $model->powerPlant;
                
                if (!$powerPlant) {
                    Log::warning('Skipping sync - Power Plant not found for Laporan KIT:', [
                        'id' => $model->id
                    ]);
                    return;
                }

                event(new LaporanKitUpdated($model, 'create', 'LaporanKit'));
            } catch (\Exception $e) {
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($model) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $model->powerPlant;
                if ($powerPlant) {
                    event(new LaporanKitUpdated($model, 'update', 'LaporanKit'));
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($model) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $model->powerPlant;
                if ($powerPlant) {
                    event(new LaporanKitUpdated($model, 'delete', 'LaporanKit'));
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}