<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitJamOperasi extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_jam_operasi';

    protected $fillable = [
        'laporan_kit_id',
        'machine_id', 
        'ops',
        'har',
        'ggn',
        'stby',
        'jam_hari'
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

        static::created(function ($jamOperasi) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $jamOperasi->id,
                        'laporan_kit_id' => $jamOperasi->laporan_kit_id,
                        'machine_id' => $jamOperasi->machine_id,
                        'ops' => $jamOperasi->ops,
                        'har' => $jamOperasi->har,
                        'ggn' => $jamOperasi->ggn,
                        'stby' => $jamOperasi->stby,
                        'jam_hari' => $jamOperasi->jam_hari,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_jam_operasi')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitJamOperasi sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($jamOperasi) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'machine_id' => $jamOperasi->machine_id,
                        'ops' => $jamOperasi->ops,
                        'har' => $jamOperasi->har,
                        'ggn' => $jamOperasi->ggn,
                        'stby' => $jamOperasi->stby,
                        'jam_hari' => $jamOperasi->jam_hari,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_jam_operasi')
                        ->where('laporan_kit_id', $jamOperasi->laporan_kit_id)
                        ->where('id', $jamOperasi->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitJamOperasi sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($jamOperasi) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_jam_operasi')
                        ->where('laporan_kit_id', $jamOperasi->laporan_kit_id)
                        ->where('id', $jamOperasi->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitJamOperasi sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}