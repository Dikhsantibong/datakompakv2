<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\LaporanKitUpdated;
use Illuminate\Support\Facades\Log;

class LaporanKit extends Model
{
    public static $isSyncing = false;

    protected $table = 'laporan_kits';

    protected $fillable = [
        'tanggal',
        'unit_source',
        'created_by',
        // tambahkan field lain jika ada
    ];

    protected $with = [
        'jamOperasi',
        'gangguan',
        'bbm',
        'bbm.storageTanks',
        'bbm.serviceTanks',
        'bbm.flowmeters',
        'kwh',
        'kwh.productionPanels',
        'kwh.psPanels',
        'pelumas',
        'pelumas.storageTanks',
        'pelumas.drums',
        'bahanKimia',
        'bebanTertinggi'
    ];

    public function jamOperasi()    { return $this->hasMany(LaporanKitJamOperasi::class); }
    public function gangguan()      { return $this->hasMany(LaporanKitGangguan::class); }
    public function bbm()           { return $this->hasMany(LaporanKitBbm::class); }
    public function kwh()           { return $this->hasMany(LaporanKitKwh::class); }
    public function pelumas()       { return $this->hasMany(LaporanKitPelumas::class); }
    public function bahanKimia()    { return $this->hasMany(LaporanKitBahanKimia::class); }
    public function bebanTertinggi(){ return $this->hasMany(LaporanKitBebanTertinggi::class); }
    public function creator()       { return $this->belongsTo(User::class, 'created_by'); }
    public function powerPlant()
    {
        return $this->belongsTo(\App\Models\PowerPlant::class, 'unit_source', 'unit_source');
    }
    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($laporanKit) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKit created event - already syncing', [
                        'id' => $laporanKit->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Load all relationships before dispatching event
                    $laporanKit->load([
                        'jamOperasi',
                        'gangguan',
                        'bbm',
                        'bbm.storageTanks',
                        'bbm.serviceTanks',
                        'bbm.flowmeters',
                        'kwh',
                        'kwh.productionPanels',
                        'kwh.psPanels',
                        'pelumas',
                        'pelumas.storageTanks',
                        'pelumas.drums',
                        'bahanKimia',
                        'bebanTertinggi'
                    ]);
                    
                    event(new LaporanKitUpdated($laporanKit, 'create'));
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'laporan_kit_id' => $laporanKit->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });

        static::updated(function ($laporanKit) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKit updated event - already syncing', [
                        'id' => $laporanKit->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Load all relationships before dispatching event
                    $laporanKit->load([
                        'jamOperasi',
                        'gangguan',
                        'bbm',
                        'bbm.storageTanks',
                        'bbm.serviceTanks',
                        'bbm.flowmeters',
                        'kwh',
                        'kwh.productionPanels',
                        'kwh.psPanels',
                        'pelumas',
                        'pelumas.storageTanks',
                        'pelumas.drums',
                        'bahanKimia',
                        'bebanTertinggi'
                    ]);
                    
                    event(new LaporanKitUpdated($laporanKit, 'update'));
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'laporan_kit_id' => $laporanKit->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });

        static::deleting(function ($laporanKit) {
            try {
                if (self::$isSyncing) {
                    Log::info('Skipping sync for LaporanKit deleting event - already syncing', [
                        'id' => $laporanKit->id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Load all relationships before dispatching event
                    $laporanKit->load([
                        'jamOperasi',
                        'gangguan',
                        'bbm',
                        'bbm.storageTanks',
                        'bbm.serviceTanks',
                        'bbm.flowmeters',
                        'kwh',
                        'kwh.productionPanels',
                        'kwh.psPanels',
                        'pelumas',
                        'pelumas.storageTanks',
                        'pelumas.drums',
                        'bahanKimia',
                        'bebanTertinggi'
                    ]);
                    
                    event(new LaporanKitUpdated($laporanKit, 'delete'));
                }
            } catch (\Exception $e) {
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'laporan_kit_id' => $laporanKit->id
                ]);
            } finally {
                self::$isSyncing = false;
            }
        });
    }
}