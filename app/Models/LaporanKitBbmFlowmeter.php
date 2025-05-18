<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitBbmFlowmeter extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_bbm_flowmeters';

    protected $fillable = [
        'laporan_kit_bbm_id',
        'flowmeter_number',
        'awal',
        'akhir',
        'pakai'
    ];

    protected $casts = [
        'awal' => 'decimal:2',
        'akhir' => 'decimal:2',
        'pakai' => 'decimal:2'
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

        static::created(function ($flowmeter) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $flowmeter->id,
                        'laporan_kit_bbm_id' => $flowmeter->laporan_kit_bbm_id,
                        'flowmeter_number' => $flowmeter->flowmeter_number,
                        'awal' => $flowmeter->awal,
                        'akhir' => $flowmeter->akhir,
                        'pakai' => $flowmeter->pakai,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm_flowmeters')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbmFlowmeter sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($flowmeter) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'flowmeter_number' => $flowmeter->flowmeter_number,
                        'awal' => $flowmeter->awal,
                        'akhir' => $flowmeter->akhir,
                        'pakai' => $flowmeter->pakai,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm_flowmeters')
                        ->where('laporan_kit_bbm_id', $flowmeter->laporan_kit_bbm_id)
                        ->where('id', $flowmeter->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbmFlowmeter sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($flowmeter) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_bbm_flowmeters')
                        ->where('laporan_kit_bbm_id', $flowmeter->laporan_kit_bbm_id)
                        ->where('id', $flowmeter->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBbmFlowmeter sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 