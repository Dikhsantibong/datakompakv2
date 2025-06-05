<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\BahanBakarUpdated;
use Illuminate\Support\Facades\Log;

class BahanBakar extends Model
{
    public static $isSyncing = false;

    protected $table = 'bahan_bakar';
    
    protected $fillable = [
        'tanggal',
        'unit_id',
        'jenis_bbm',
        'saldo_awal',
        'penerimaan',
        'pemakaian',
        'saldo_akhir',
        'hop',
        'catatan_transaksi',
        'document'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'saldo_awal' => 'decimal:2',
        'penerimaan' => 'decimal:2',
        'pemakaian' => 'decimal:2',
        'saldo_akhir' => 'decimal:2',
        'hop' => 'decimal:2',
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

        static::created(function ($bahanBakar) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new BahanBakarUpdated($bahanBakar, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in BahanBakar sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($bahanBakar) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new BahanBakarUpdated($bahanBakar, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in BahanBakar sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($bahanBakar) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new BahanBakarUpdated($bahanBakar, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in BahanBakar sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 