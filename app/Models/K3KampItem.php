<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class K3KampItem extends Model
{
    public static $isSyncing = false;

    protected $fillable = [
        'report_id',
        'item_type',
        'item_name',
        'status',
        'kondisi',
        'keterangan'
    ];

    protected $with = ['media']; // Eager load media by default

    public function report(): BelongsTo
    {
        return $this->belongsTo(K3KampReport::class, 'report_id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(K3KampMedia::class, 'item_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($item) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'id' => $item->id,
                        'report_id' => $item->report_id,
                        'item_type' => $item->item_type,
                        'item_name' => $item->item_name,
                        'status' => $item->status,
                        'kondisi' => $item->kondisi,
                        'keterangan' => $item->keterangan,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Sync to mysql database
                    DB::connection('mysql')->table('k3_kamp_items')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampItem sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($item) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    $data = [
                        'item_type' => $item->item_type,
                        'item_name' => $item->item_name,
                        'status' => $item->status,
                        'kondisi' => $item->kondisi,
                        'keterangan' => $item->keterangan,
                        'updated_at' => now()
                    ];

                    // Update in mysql database
                    DB::connection('mysql')->table('k3_kamp_items')
                        ->where('report_id', $item->report_id)
                        ->where('id', $item->id)
                        ->update($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampItem sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($item) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('k3_kamp_items')
                        ->where('report_id', $item->report_id)
                        ->where('id', $item->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampItem sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 