<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\BahanKimiaUpdated;
use Illuminate\Support\Facades\Log;

class BahanKimia extends Model
{
    public static $isSyncing = false;

    protected $table = 'bahan_kimia';
    
    protected $fillable = [
        'tanggal',
        'unit_id',
        'jenis_bahan',
        'saldo_awal',
        'penerimaan',
        'pemakaian',
        'saldo_akhir',
        'is_opening_balance',
        'catatan_transaksi',
        'evidence'
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

        static::created(function ($bahanKimia) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new BahanKimiaUpdated($bahanKimia, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in BahanKimia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($bahanKimia) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new BahanKimiaUpdated($bahanKimia, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in BahanKimia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($bahanKimia) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new BahanKimiaUpdated($bahanKimia, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in BahanKimia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 