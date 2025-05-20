<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitBbm extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_bbm';

    protected $fillable = [
        'laporan_kit_id',
        'total_stok',
        'service_total_stok',
        'total_stok_tangki',
        'terima_bbm',
        'total_pakai'
    ];

    public function storageTanks()
    {
        return $this->hasMany(LaporanKitBbmStorageTank::class);
    }

    public function serviceTanks()
    {
        return $this->hasMany(LaporanKitBbmServiceTank::class);
    }

    public function flowmeters()
    {
        return $this->hasMany(LaporanKitBbmFlowmeter::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($bbm) {
            try {
                if (self::$isSyncing || \App\Models\LaporanKit::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'laporan_kit_id' => $bbm->laporan_kit_id,
                        'total_stok' => $bbm->total_stok,
                        'service_total_stok' => $bbm->service_total_stok,
                        'total_stok_tangki' => $bbm->total_stok_tangki,
                        'terima_bbm' => $bbm->terima_bbm,
                        'total_pakai' => $bbm->total_pakai,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database without id
                    DB::connection('mysql')->table('laporan_kit_bbm')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbm sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($bbm) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'total_stok' => $bbm->total_stok,
                        'service_total_stok' => $bbm->service_total_stok,
                        'total_stok_tangki' => $bbm->total_stok_tangki,
                        'terima_bbm' => $bbm->terima_bbm,
                        'total_pakai' => $bbm->total_pakai,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm')
                        ->where('laporan_kit_id', $bbm->laporan_kit_id)
                        ->where('id', $bbm->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbm sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($bbm) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm')
                        ->where('laporan_kit_id', $bbm->laporan_kit_id)
                        ->where('id', $bbm->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbm sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}