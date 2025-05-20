<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitPelumasDrum extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_pelumas_drums';

    protected $fillable = [
        'laporan_kit_pelumas_id',
        'area_number',
        'jumlah'
    ];

    protected $casts = [
        'jumlah' => 'decimal:2'
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

        static::created(function ($drum) {
            try {
                if (self::$isSyncing || \App\Models\LaporanKit::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $drum->id,
                        'laporan_kit_pelumas_id' => $drum->laporan_kit_pelumas_id,
                        'area_number' => $drum->area_number,
                        'jumlah' => $drum->jumlah,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas_drums')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumasDrum sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($drum) {
            try {
                if (self::$isSyncing || \App\Models\LaporanKit::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'area_number' => $drum->area_number,
                        'jumlah' => $drum->jumlah,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas_drums')
                        ->where('laporan_kit_pelumas_id', $drum->laporan_kit_pelumas_id)
                        ->where('id', $drum->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumasDrum sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($drum) {
            try {
                if (self::$isSyncing || \App\Models\LaporanKit::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_pelumas_drums')
                        ->where('laporan_kit_pelumas_id', $drum->laporan_kit_pelumas_id)
                        ->where('id', $drum->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitPelumasDrum sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 