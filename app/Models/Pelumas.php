<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\PelumasUpdated;
use Illuminate\Support\Facades\Log;

class Pelumas extends Model
{
    public static $isSyncing = false;

    protected $table = 'pelumas';
    
    protected $fillable = [
        'tanggal',
        'unit_id',
        'jenis_pelumas',
        'saldo_awal',
        'penerimaan',
        'pemakaian',
        'saldo_akhir',
        'is_opening_balance',
        'catatan_transaksi',
        'document'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'saldo_awal' => 'decimal:2',
        'penerimaan' => 'decimal:2',
        'pemakaian' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
    ];

    public function unit()
    {
        return $this->belongsTo(PowerPlant::class, 'unit_id');
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
                
                // Trigger sync event
                event(new PelumasUpdated($pelumas, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in Pelumas sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($pelumas) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new PelumasUpdated($pelumas, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in Pelumas sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($pelumas) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new PelumasUpdated($pelumas, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in Pelumas sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 