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
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Validate required relationships
                    if (!$laporanKit->gangguan || !$laporanKit->bbm || !$laporanKit->kwh || !$laporanKit->pelumas) {
                        throw new \Exception('Required relationships are missing');
                    }
                    
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
                    
                    // Validate data before syncing
                    foreach ($laporanKit->gangguan as $gangguan) {
                        if (!$gangguan->machine_id || !isset($gangguan->mekanik) || !isset($gangguan->elektrik)) {
                            throw new \Exception('Invalid gangguan data: missing required fields');
                        }
                    }
                    
                    event(new LaporanKitUpdated($laporanKit, 'create'));

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'laporan_kit_id' => $laporanKit->id
                ]);
                throw $e;
            }
        });

        static::updated(function ($laporanKit) {
            try {
                if (self::$isSyncing) return;

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

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($laporanKit) {
            try {
                if (self::$isSyncing) return;

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

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in LaporanKit sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
}