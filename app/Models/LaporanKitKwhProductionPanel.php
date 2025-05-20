<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitKwhProductionPanel extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_kwh_production_panels';
    
    protected $fillable = [
        'laporan_kit_kwh_id',
        'panel_number',
        'awal',
        'akhir'
    ];

    protected $casts = [
        'awal' => 'decimal:2',
        'akhir' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function kwh()
    {
        return $this->belongsTo(LaporanKitKwh::class, 'laporan_kit_kwh_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($productionPanel) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $productionPanel->id,
                        'laporan_kit_kwh_id' => $productionPanel->laporan_kit_kwh_id,
                        'panel_number' => $productionPanel->panel_number,
                        'awal' => $productionPanel->awal,
                        'akhir' => $productionPanel->akhir,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_production_panels')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwhProductionPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($productionPanel) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'panel_number' => $productionPanel->panel_number,
                        'awal' => $productionPanel->awal,
                        'akhir' => $productionPanel->akhir,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_production_panels')
                        ->where('laporan_kit_kwh_id', $productionPanel->laporan_kit_kwh_id)
                        ->where('id', $productionPanel->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwhProductionPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($productionPanel) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_production_panels')
                        ->where('laporan_kit_kwh_id', $productionPanel->laporan_kit_kwh_id)
                        ->where('id', $productionPanel->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwhProductionPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 