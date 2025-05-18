<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitPelumas extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_pelumas';

    protected $fillable = [
        'laporan_kit_id',
        'tank_total_stok',
        'drum_total_stok',
        'total_stok_tangki',
        'terima_pelumas',
        'total_pakai',
        'jenis'
    ];

    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class, 'laporan_kit_id');
    }

    public function storageTanks()
    {
        return $this->hasMany(LaporanKitPelumasStorageTank::class, 'laporan_kit_pelumas_id');
    }

    public function drums()
    {
        return $this->hasMany(LaporanKitPelumasDrum::class, 'laporan_kit_pelumas_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($pelumas) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $pelumas->id,
                        'laporan_kit_id' => $pelumas->laporan_kit_id,
                        'tank_total_stok' => $pelumas->tank_total_stok,
                        'drum_total_stok' => $pelumas->drum_total_stok,
                        'total_stok_tangki' => $pelumas->total_stok_tangki,
                        'terima_pelumas' => $pelumas->terima_pelumas,
                        'total_pakai' => $pelumas->total_pakai,
                        'jenis' => $pelumas->jenis,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumas sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($pelumas) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'tank_total_stok' => $pelumas->tank_total_stok,
                        'drum_total_stok' => $pelumas->drum_total_stok,
                        'total_stok_tangki' => $pelumas->total_stok_tangki,
                        'terima_pelumas' => $pelumas->terima_pelumas,
                        'total_pakai' => $pelumas->total_pakai,
                        'jenis' => $pelumas->jenis,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas')
                        ->where('laporan_kit_id', $pelumas->laporan_kit_id)
                        ->where('id', $pelumas->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumas sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($pelumas) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas')
                        ->where('laporan_kit_id', $pelumas->laporan_kit_id)
                        ->where('id', $pelumas->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumas sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}