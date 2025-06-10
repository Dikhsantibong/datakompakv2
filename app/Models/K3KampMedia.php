<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class K3KampMedia extends Model
{
    public static $isSyncing = false;

    protected $fillable = [
        'item_id',
        'media_type',
        'file_path',
        'original_name',
        'file_size'
    ];

    protected $touches = ['item']; // Automatically update parent item's timestamp

    public function item(): BelongsTo
    {
        return $this->belongsTo(K3KampItem::class, 'item_id');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($media) {
            try {
                if (self::$isSyncing) return;
                $currentSession = session('unit', 'mysql');
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    $parentId = session('k3_kamp_item_id_map.' . $media->item_id);
                    if (!$parentId) {
                        Log::error('Parent K3KampItem mapping not found', [
                            'media_id' => $media->id,
                            'item_id' => $media->item_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    $data = [
                        'item_id' => $parentId,
                        'media_type' => $media->media_type,
                        'file_path' => $media->file_path,
                        'original_name' => $media->original_name,
                        'file_size' => $media->file_size,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    DB::connection('mysql')->table('k3_kamp_media')->insert($data);
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampMedia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($media) {
            try {
                if (self::$isSyncing) return;
                $currentSession = session('unit', 'mysql');
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    $parentId = session('k3_kamp_item_id_map.' . $media->item_id);
                    if (!$parentId) {
                        Log::error('Parent K3KampItem mapping not found', [
                            'media_id' => $media->id,
                            'item_id' => $media->item_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    $data = [
                        'item_id' => $parentId,
                        'media_type' => $media->media_type,
                        'file_path' => $media->file_path,
                        'original_name' => $media->original_name,
                        'file_size' => $media->file_size,
                        'updated_at' => now()
                    ];
                    DB::connection('mysql')->table('k3_kamp_media')->updateOrInsert([
                        'item_id' => $parentId,
                        'file_path' => $media->file_path
                    ], $data);
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampMedia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($media) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('k3_kamp_media')
                        ->where('item_id', $media->item_id)
                        ->where('id', $media->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampMedia sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 