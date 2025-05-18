<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitGangguan extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_gangguan';

    protected $fillable = [
        'laporan_kit_id',
        'machine_id',
        'mekanik',
        'elektrik',
        'keterangan'
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class, 'machine_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($gangguan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $gangguan->id,
                        'laporan_kit_id' => $gangguan->laporan_kit_id,
                        'machine_id' => $gangguan->machine_id,
                        'mekanik' => $gangguan->mekanik,
                        'elektrik' => $gangguan->elektrik,
                        'keterangan' => $gangguan->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_gangguan')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitGangguan sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($gangguan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'machine_id' => $gangguan->machine_id,
                        'mekanik' => $gangguan->mekanik,
                        'elektrik' => $gangguan->elektrik,
                        'keterangan' => $gangguan->keterangan,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_gangguan')
                        ->where('laporan_kit_id', $gangguan->laporan_kit_id)
                        ->where('id', $gangguan->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitGangguan sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($gangguan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_gangguan')
                        ->where('laporan_kit_id', $gangguan->laporan_kit_id)
                        ->where('id', $gangguan->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitGangguan sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}