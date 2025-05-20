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

        static::created(function ($panel) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKitKwhPsPanel created event - already syncing', [
                        'id' => $panel->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'laporan_kit_kwh_id' => $panel->laporan_kit_kwh_id,
                        'panel_number' => $panel->panel_number,
                        'awal' => $panel->awal,
                        'akhir' => $panel->akhir,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database using updateOrInsert
                    DB::connection('mysql')->table('laporan_kit_kwh_ps_panels')
                        ->updateOrInsert(
                            [
                                'laporan_kit_kwh_id' => $panel->laporan_kit_kwh_id,
                                'panel_number' => $panel->panel_number
                            ],
                            $data
                        );
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKitKwhPsPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'id' => $panel->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });

        static::updated(function ($panel) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKitKwhPsPanel updated event - already syncing', [
                        'id' => $panel->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'laporan_kit_kwh_id' => $panel->laporan_kit_kwh_id,
                        'panel_number' => $panel->panel_number,
                        'awal' => $panel->awal,
                        'akhir' => $panel->akhir,
                        'updated_at' => now()
                    ];

                    // Sync to mysql database using updateOrInsert
                    DB::connection('mysql')->table('laporan_kit_kwh_ps_panels')
                        ->updateOrInsert(
                            [
                                'laporan_kit_kwh_id' => $panel->laporan_kit_kwh_id,
                                'panel_number' => $panel->panel_number
                            ],
                            $data
                        );
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKitKwhPsPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'id' => $panel->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });

        static::deleted(function ($panel) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKitKwhPsPanel deleted event - already syncing', [
                        'id' => $panel->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh_ps_panels')
                        ->where('laporan_kit_kwh_id', $panel->laporan_kit_kwh_id)
                        ->where('panel_number', $panel->panel_number)
                        ->delete();
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKitKwhPsPanel sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'id' => $panel->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });
    }
} 