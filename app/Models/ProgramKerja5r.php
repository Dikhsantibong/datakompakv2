<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\ProgramKerja5rUpdated;
use Illuminate\Support\Facades\Log;

class ProgramKerja5r extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'tabel_program_kerja_5r';

    protected $fillable = [
        'program_kerja',
        'goal',
        'kondisi_awal',
        'progress',
        'kondisi_akhir',
        'catatan',
        'eviden'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($programKerja) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new ProgramKerja5rUpdated($programKerja, 'create'));
                
            } catch (\Exception $e) {
                Log::error('Error in ProgramKerja5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($programKerja) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new ProgramKerja5rUpdated($programKerja, 'update'));
                
            } catch (\Exception $e) {
                Log::error('Error in ProgramKerja5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($programKerja) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Trigger sync event
                event(new ProgramKerja5rUpdated($programKerja, 'delete'));
                
            } catch (\Exception $e) {
                Log::error('Error in ProgramKerja5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 