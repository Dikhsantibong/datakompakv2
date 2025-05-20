<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitBebanTertinggi extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_beban_tertinggi';

    protected $fillable = [
        'laporan_kit_id',
        'machine_id', 
        'siang',
        'malam'
    ];

    protected $casts = [
        'siang' => 'decimal:2',
        'malam' => 'decimal:2'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($bebanTertinggi) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $bebanTertinggi->id,
                        'laporan_kit_id' => $bebanTertinggi->laporan_kit_id,
                        'machine_id' => $bebanTertinggi->machine_id,
                        'siang' => $bebanTertinggi->siang,
                        'malam' => $bebanTertinggi->malam,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_beban_tertinggi')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBebanTertinggi sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($bebanTertinggi) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'machine_id' => $bebanTertinggi->machine_id,
                        'siang' => $bebanTertinggi->siang,
                        'malam' => $bebanTertinggi->malam,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_beban_tertinggi')
                        ->where('laporan_kit_id', $bebanTertinggi->laporan_kit_id)
                        ->where('id', $bebanTertinggi->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBebanTertinggi sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($bebanTertinggi) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_beban_tertinggi')
                        ->where('laporan_kit_id', $bebanTertinggi->laporan_kit_id)
                        ->where('id', $bebanTertinggi->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitBebanTertinggi sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}