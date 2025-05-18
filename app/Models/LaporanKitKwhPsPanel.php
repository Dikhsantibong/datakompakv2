<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitKwhPsPanel extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_kwh_ps_panels';
    
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

        static::created(function ($psPanel) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $psPanel->id,
                        'laporan_kit_kwh_id' => $psPanel->laporan_kit_kwh_id,
                        'panel_number' => $psPanel->panel_number,
                        'awal' => $psPanel->awal,
                        'akhir' => $psPanel->akhir,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_ps_panels')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwhPsPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($psPanel) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'panel_number' => $psPanel->panel_number,
                        'awal' => $psPanel->awal,
                        'akhir' => $psPanel->akhir,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_ps_panels')
                        ->where('laporan_kit_kwh_id', $psPanel->laporan_kit_kwh_id)
                        ->where('id', $psPanel->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwhPsPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($psPanel) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_ps_panels')
                        ->where('laporan_kit_kwh_id', $psPanel->laporan_kit_kwh_id)
                        ->where('id', $psPanel->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwhPsPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 