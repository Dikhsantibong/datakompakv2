<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\FlmInspectionUpdated;
use Illuminate\Support\Facades\Log;

class FlmInspection extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $fillable = [
        'flm_id',
        'tanggal',
        'operator',
        'shift',
        'time',
        'mesin',
        'sistem',
        'masalah',
        'kondisi_awal',
        'tindakan_bersihkan',
        'tindakan_lumasi',
        'tindakan_kencangkan',
        'tindakan_perbaikan_koneksi',
        'tindakan_lainnya',
        'kondisi_akhir',
        'catatan',
        'eviden_sebelum',
        'eviden_sesudah',
        'status'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'time' => 'datetime',
        'tindakan_bersihkan' => 'boolean',
        'tindakan_lumasi' => 'boolean',
        'tindakan_kencangkan' => 'boolean',
        'tindakan_perbaikan_koneksi' => 'boolean',
        'tindakan_lainnya' => 'boolean',
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($flmInspection) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new FlmInspectionUpdated($flmInspection, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in FlmInspection sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($flmInspection) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new FlmInspectionUpdated($flmInspection, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in FlmInspection sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($flmInspection) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new FlmInspectionUpdated($flmInspection, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in FlmInspection sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 