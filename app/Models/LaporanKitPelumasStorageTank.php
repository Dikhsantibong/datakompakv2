<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitPelumasStorageTank extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_pelumas_storage_tanks';

    protected $fillable = [
        'laporan_kit_pelumas_id',
        'tank_number',
        'cm',
        'liter'
    ];

    protected $casts = [
        'cm' => 'decimal:2',
        'liter' => 'decimal:2'
    ];

    public function pelumas()
    {
        return $this->belongsTo(LaporanKitPelumas::class, 'laporan_kit_pelumas_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($storageTank) {
            try {
                if (self::$isSyncing || \App\Models\LaporanKit::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $storageTank->id,
                        'laporan_kit_pelumas_id' => $storageTank->laporan_kit_pelumas_id,
                        'tank_number' => $storageTank->tank_number,
                        'cm' => $storageTank->cm,
                        'liter' => $storageTank->liter,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas_storage_tanks')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumasStorageTank sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($storageTank) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'tank_number' => $storageTank->tank_number,
                        'cm' => $storageTank->cm,
                        'liter' => $storageTank->liter,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas_storage_tanks')
                        ->where('laporan_kit_pelumas_id', $storageTank->laporan_kit_pelumas_id)
                        ->where('id', $storageTank->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumasStorageTank sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($storageTank) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas_storage_tanks')
                        ->where('laporan_kit_pelumas_id', $storageTank->laporan_kit_pelumas_id)
                        ->where('id', $storageTank->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumasStorageTank sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 