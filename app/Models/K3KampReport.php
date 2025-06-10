<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class K3KampReport extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $fillable = [
        'date',
        'created_by',
        'sync_unit_origin'
    ];

    protected $dates = [
        'date'
    ];

    protected $with = ['items.media']; // Eager load items and media by default

    public function items(): HasMany
    {
        return $this->hasMany(K3KampItem::class, 'report_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($report) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                $unitMapping = [
                    'mysql_poasia' => 'PLTD POASIA',
                    'mysql_kolaka' => 'PLTD KOLAKA',
                    'mysql_bau_bau' => 'PLTD BAU BAU',
                    'mysql_wua_wua' => 'PLTD WUA WUA',
                    'mysql_winning' => 'PLTD WINNING',
                    'mysql_erkee' => 'PLTD ERKEE',
                    'mysql_ladumpi' => 'PLTD LADUMPI',
                    'mysql_langara' => 'PLTD LANGARA',
                    'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                    'mysql_pasarwajo' => 'PLTD PASARWAJO',
                    'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                    'mysql_raha' => 'PLTD RAHA',
                    'mysql_wajo' => 'PLTD WAJO',
                    'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
                    'mysql_rongi' => 'PLTD RONGI',
                    'mysql_sabilambo' => 'PLTD SABILAMBO',
                    'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
                    'mysql_pltmg_kendari' => 'PLTD KENDARI',
                    'mysql_baruta' => 'PLTD BARUTA',
                    'mysql_moramo' => 'PLTD MORAMO',
                    'mysql' => 'UP Kendari'
                ];
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    $data = [
                        'date' => $report->date,
                        'created_by' => $report->created_by,
                        'sync_unit_origin' => $unitMapping[$currentSession] ?? 'UP Kendari',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                    $reportId = DB::connection('mysql')->table('k3_kamp_reports')->insertGetId($data);
                    session(['k3_kamp_report_id_map.' . $report->id => $reportId]);
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampReport sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($report) {
            try {
                if (self::$isSyncing) return;
                $currentSession = session('unit', 'mysql');
                $unitMapping = [
                    'mysql_poasia' => 'PLTD POASIA',
                    'mysql_kolaka' => 'PLTD KOLAKA',
                    'mysql_bau_bau' => 'PLTD BAU BAU',
                    'mysql_wua_wua' => 'PLTD WUA WUA',
                    'mysql_winning' => 'PLTD WINNING',
                    'mysql_erkee' => 'PLTD ERKEE',
                    'mysql_ladumpi' => 'PLTD LADUMPI',
                    'mysql_langara' => 'PLTD LANGARA',
                    'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                    'mysql_pasarwajo' => 'PLTD PASARWAJO',
                    'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                    'mysql_raha' => 'PLTD RAHA',
                    'mysql_wajo' => 'PLTD WAJO',
                    'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
                    'mysql_rongi' => 'PLTD RONGI',
                    'mysql_sabilambo' => 'PLTD SABILAMBO',
                    'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
                    'mysql_pltmg_kendari' => 'PLTD KENDARI',
                    'mysql_baruta' => 'PLTD BARUTA',
                    'mysql_moramo' => 'PLTD MORAMO',
                    'mysql' => 'UP Kendari'
                ];
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    $data = [
                        'date' => $report->date,
                        'created_by' => $report->created_by,
                        'sync_unit_origin' => $unitMapping[$currentSession] ?? 'UP Kendari',
                        'updated_at' => now()
                    ];
                    $exists = DB::connection('mysql')->table('k3_kamp_reports')->where('id', $report->id)->exists();
                    if (!$exists) {
                        $data['created_at'] = now();
                        $reportId = DB::connection('mysql')->table('k3_kamp_reports')->insertGetId($data);
                        session(['k3_kamp_report_id_map.' . $report->id => $reportId]);
                    } else {
                        DB::connection('mysql')->table('k3_kamp_reports')->where('id', $report->id)->update($data);
                        session(['k3_kamp_report_id_map.' . $report->id => $report->id]);
                    }
                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampReport sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($report) {
            try {
                if (self::$isSyncing) return;

                $currentSession = session('unit', 'mysql');
                
                // Only sync if not in mysql session
                if ($currentSession !== 'mysql') {
                    self::$isSyncing = true;
                    
                    // Delete from mysql database
                    DB::connection('mysql')->table('k3_kamp_reports')
                        ->where('id', $report->id)
                        ->delete();

                    self::$isSyncing = false;
                }
            } catch (\Exception $e) {
                self::$isSyncing = false;
                Log::error('Error in K3KampReport sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }
} 