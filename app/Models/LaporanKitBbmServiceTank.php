<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitBbmServiceTank extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_bbm_service_tanks';

    protected $fillable = [
        'laporan_kit_bbm_id',
        'tank_number',
        'liter',
        'percentage'
    ];

    protected $casts = [
        'liter' => 'decimal:2',
        'percentage' => 'decimal:2'
    ];

    public function bbm()
    {
        return $this->belongsTo(LaporanKitBbm::class, 'laporan_kit_bbm_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($serviceTank) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $serviceTank->id,
                        'laporan_kit_bbm_id' => $serviceTank->laporan_kit_bbm_id,
                        'tank_number' => $serviceTank->tank_number,
                        'liter' => $serviceTank->liter,
                        'percentage' => $serviceTank->percentage,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm_service_tanks')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbmServiceTank sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($serviceTank) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'tank_number' => $serviceTank->tank_number,
                        'liter' => $serviceTank->liter,
                        'percentage' => $serviceTank->percentage,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm_service_tanks')
                        ->where('laporan_kit_bbm_id', $serviceTank->laporan_kit_bbm_id)
                        ->where('id', $serviceTank->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbmServiceTank sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($serviceTank) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm_service_tanks')
                        ->where('laporan_kit_bbm_id', $serviceTank->laporan_kit_bbm_id)
                        ->where('id', $serviceTank->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbmServiceTank sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 