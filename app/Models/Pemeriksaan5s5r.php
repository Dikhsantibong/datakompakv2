<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        'eviden',
        'group_id',
        'batch_id',
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

    public function batch()
    {
        return $this->belongsTo(FiveS5rBatch::class, 'batch_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($pemeriksaan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('five_s5r_batch_id_map.' . $pemeriksaan->batch_id);

                    if (!$parentId) {
                        Log::error('Parent FiveS5rBatch mapping not found', [
                            'pemeriksaan_id' => $pemeriksaan->id,
                            'batch_id' => $pemeriksaan->batch_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'batch_id' => $parentId,
                        'kategori' => $pemeriksaan->kategori,
                        'detail' => $pemeriksaan->detail,
                        'kondisi_awal' => $pemeriksaan->kondisi_awal,
                        'pic' => $pemeriksaan->pic,
                        'area_kerja' => $pemeriksaan->area_kerja,
                        'area_produksi' => $pemeriksaan->area_produksi,
                        'membersihkan' => $pemeriksaan->membersihkan,
                        'merapikan' => $pemeriksaan->merapikan,
                        'membuang_sampah' => $pemeriksaan->membuang_sampah,
                        'mengecat' => $pemeriksaan->mengecat,
                        'lainnya' => $pemeriksaan->lainnya,
                        'kondisi_akhir' => $pemeriksaan->kondisi_akhir,
                        'eviden' => $pemeriksaan->eviden,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('tabel_pemeriksaan_5s5r')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in Pemeriksaan5s5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $pemeriksaan->batch_id
                ]);
            }
        });

        static::updated(function ($pemeriksaan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('five_s5r_batch_id_map.' . $pemeriksaan->batch_id);

                    if (!$parentId) {
                        Log::error('Parent FiveS5rBatch mapping not found', [
                            'pemeriksaan_id' => $pemeriksaan->id,
                            'batch_id' => $pemeriksaan->batch_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'batch_id' => $parentId,
                        'kategori' => $pemeriksaan->kategori,
                        'detail' => $pemeriksaan->detail,
                        'kondisi_awal' => $pemeriksaan->kondisi_awal,
                        'pic' => $pemeriksaan->pic,
                        'area_kerja' => $pemeriksaan->area_kerja,
                        'area_produksi' => $pemeriksaan->area_produksi,
                        'membersihkan' => $pemeriksaan->membersihkan,
                        'merapikan' => $pemeriksaan->merapikan,
                        'membuang_sampah' => $pemeriksaan->membuang_sampah,
                        'mengecat' => $pemeriksaan->mengecat,
                        'lainnya' => $pemeriksaan->lainnya,
                        'kondisi_akhir' => $pemeriksaan->kondisi_akhir,
                        'eviden' => $pemeriksaan->eviden,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('tabel_pemeriksaan_5s5r')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in Pemeriksaan5s5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $pemeriksaan->batch_id
                ]);
            }
        });

        static::deleting(function ($pemeriksaan) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('tabel_pemeriksaan_5s5r')
                        ->where('id', $pemeriksaan->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in Pemeriksaan5s5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 