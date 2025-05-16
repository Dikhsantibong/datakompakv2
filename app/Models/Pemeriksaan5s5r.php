<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\Pemeriksaan5s5rUpdated;
use Illuminate\Support\Facades\Log;

class Pemeriksaan5s5r extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'tabel_pemeriksaan_5s5r';

    protected $fillable = [
        'kategori',
        'detail',
        'kondisi_awal',
        'pic',
        'area_kerja',
        'area_produksi',
        'membersihkan',
        'merapikan',
        'membuang_sampah',
        'mengecat',
        'lainnya',
        'kondisi_akhir',
        'eviden'
    ];

    protected $casts = [
        'membersihkan' => 'boolean',
        'merapikan' => 'boolean',
        'membuang_sampah' => 'boolean',
        'mengecat' => 'boolean',
        'lainnya' => 'boolean',
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($pemeriksaan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new Pemeriksaan5s5rUpdated($pemeriksaan, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in Pemeriksaan5s5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($pemeriksaan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new Pemeriksaan5s5rUpdated($pemeriksaan, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in Pemeriksaan5s5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($pemeriksaan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new Pemeriksaan5s5rUpdated($pemeriksaan, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in Pemeriksaan5s5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 