<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitBahanKimia extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_bahan_kimia';

    protected $fillable = [
        'laporan_kit_id',
        'jenis',
        'stok_awal',
        'terima', 
        'total_pakai'
    ];
    
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($bahanKimia) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKitBahanKimia created event - already syncing', [
                        'id' => $bahanKimia->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'laporan_kit_id' => $bahanKimia->laporan_kit_id,
                        'jenis' => $bahanKimia->jenis,
                        'stok_awal' => $bahanKimia->stok_awal,
                        'terima' => $bahanKimia->terima,
                        'total_pakai' => $bahanKimia->total_pakai,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database using updateOrInsert
                    DB::connection('mysql')->table('laporan_kit_bahan_kimia')
                        ->updateOrInsert(
                            [
                                'laporan_kit_id' => $bahanKimia->laporan_kit_id,
                                'jenis' => $bahanKimia->jenis
                            ],
                            $data
                        );
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKitBahanKimia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'id' => $bahanKimia->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });

        static::updated(function ($bahanKimia) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKitBahanKimia updated event - already syncing', [
                        'id' => $bahanKimia->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'laporan_kit_id' => $bahanKimia->laporan_kit_id,
                        'jenis' => $bahanKimia->jenis,
                        'stok_awal' => $bahanKimia->stok_awal,
                        'terima' => $bahanKimia->terima,
                        'total_pakai' => $bahanKimia->total_pakai,
                        'updated_at' => now()
                    ];

                    // Sync to mysql database using updateOrInsert
                    DB::connection('mysql')->table('laporan_kit_bahan_kimia')
                        ->updateOrInsert(
                            [
                                'laporan_kit_id' => $bahanKimia->laporan_kit_id,
                                'jenis' => $bahanKimia->jenis
                            ],
                            $data
                        );
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKitBahanKimia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'id' => $bahanKimia->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });

        static::deleted(function ($bahanKimia) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKitBahanKimia deleted event - already syncing', [
                        'id' => $bahanKimia->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_bahan_kimia')
                        ->where('laporan_kit_id', $bahanKimia->laporan_kit_id)
                        ->where('jenis', $bahanKimia->jenis)
                        ->delete();
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKitBahanKimia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'id' => $bahanKimia->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });
    }
}