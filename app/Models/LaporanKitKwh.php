<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LaporanKitKwh extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kit_kwh';

    protected $fillable = [
        'laporan_kit_id',
        'prod_panel1_awal',
        'prod_panel1_akhir',
        'prod_panel2_awal',
        'prod_panel2_akhir',
        'prod_total',
        'ps_panel1_awal',
        'ps_panel1_akhir',
        'ps_panel2_awal',
        'ps_panel2_akhir',
        'ps_total'
    ];

    protected $casts = [
        'prod_total' => 'decimal:2',
        'ps_total' => 'decimal:2'
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    public function laporanKit()
    {
        return $this->belongsTo(LaporanKit::class);
    }

    public function productionPanels()
    {
        return $this->hasMany(LaporanKitKwhProductionPanel::class, 'laporan_kit_kwh_id');
    }

    public function psPanels()
    {
        return $this->hasMany(LaporanKitKwhPsPanel::class, 'laporan_kit_kwh_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($kwh) {
            try {
                if (self::$isSyncing || \App\Models\LaporanKit::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $kwh->id,
                        'laporan_kit_id' => $kwh->laporan_kit_id,
                        'prod_panel1_awal' => $kwh->prod_panel1_awal,
                        'prod_panel1_akhir' => $kwh->prod_panel1_akhir,
                        'prod_panel2_awal' => $kwh->prod_panel2_awal,
                        'prod_panel2_akhir' => $kwh->prod_panel2_akhir,
                        'prod_total' => $kwh->prod_total,
                        'ps_panel1_awal' => $kwh->ps_panel1_awal,
                        'ps_panel1_akhir' => $kwh->ps_panel1_akhir,
                        'ps_panel2_awal' => $kwh->ps_panel2_awal,
                        'ps_panel2_akhir' => $kwh->ps_panel2_akhir,
                        'ps_total' => $kwh->ps_total,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwh sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($kwh) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'prod_panel1_awal' => $kwh->prod_panel1_awal,
                        'prod_panel1_akhir' => $kwh->prod_panel1_akhir,
                        'prod_panel2_awal' => $kwh->prod_panel2_awal,
                        'prod_panel2_akhir' => $kwh->prod_panel2_akhir,
                        'prod_total' => $kwh->prod_total,
                        'ps_panel1_awal' => $kwh->ps_panel1_awal,
                        'ps_panel1_akhir' => $kwh->ps_panel1_akhir,
                        'ps_panel2_awal' => $kwh->ps_panel2_awal,
                        'ps_panel2_akhir' => $kwh->ps_panel2_akhir,
                        'ps_total' => $kwh->ps_total,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh')
                        ->where('laporan_kit_id', $kwh->laporan_kit_id)
                        ->where('id', $kwh->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwh sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($kwh) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('laporan_kit_kwh')
                        ->where('laporan_kit_id', $kwh->laporan_kit_id)
                        ->where('id', $kwh->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKitKwh sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}