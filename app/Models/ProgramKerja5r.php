<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        'eviden',
        'group_id',
        'batch_id',
    ];

    protected $casts = [
        'tanggal' => 'date'
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

        static::created(function ($program) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('five_s5r_batch_id_map.' . $program->batch_id);

                    if (!$parentId) {
                        Log::error('Parent FiveS5rBatch mapping not found', [
                            'program_id' => $program->id,
                            'batch_id' => $program->batch_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'batch_id' => $parentId,
                        'program_kerja' => $program->program_kerja,
                        'goal' => $program->goal,
                        'kondisi_awal' => $program->kondisi_awal,
                        'progress' => $program->progress,
                        'kondisi_akhir' => $program->kondisi_akhir,
                        'catatan' => $program->catatan,
                        'eviden' => $program->eviden,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Use insert to get a new ID
                    DB::connection('mysql')->table('tabel_program_kerja_5r')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in ProgramKerja5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $program->batch_id
                ]);
            }
        });

        static::updated(function ($program) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Get mapped parent ID from session
                    $parentId = session('five_s5r_batch_id_map.' . $program->batch_id);

                    if (!$parentId) {
                        Log::error('Parent FiveS5rBatch mapping not found', [
                            'program_id' => $program->id,
                            'batch_id' => $program->batch_id
                        ]);
                        self::$isSyncing = false;
                        return;
                    }
                    
                    $data = [
                        'batch_id' => $parentId,
                        'program_kerja' => $program->program_kerja,
                        'goal' => $program->goal,
                        'kondisi_awal' => $program->kondisi_awal,
                        'progress' => $program->progress,
                        'kondisi_akhir' => $program->kondisi_akhir,
                        'catatan' => $program->catatan,
                        'eviden' => $program->eviden,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Insert new record instead of update
                    DB::connection('mysql')->table('tabel_program_kerja_5r')->insert($data);

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in ProgramKerja5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $data ?? null,
                    'parent_id' => $parentId ?? null,
                    'original_id' => $program->batch_id
                ]);
            }
        });

        static::deleting(function ($program) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('tabel_program_kerja_5r')
                        ->where('id', $program->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in ProgramKerja5r sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 