<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Events\RencanaDayaMampuUpdated;

class RencanaDayaMampu extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'rencana_daya_mampu';

    protected $fillable = [
        'uuid',
        'machine_id',
        'tanggal',
        'rencana',
        'realisasi',
        'daily_data',
        'daya_pjbtl_silm',
        'dmp_existing',
        'unit_source'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'daily_data' => 'json',
        'daya_pjbtl_silm' => 'decimal:2',
        'dmp_existing' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->uuid) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::saved(function ($rencanaDayaMampu) {
            if (self::$isSyncing) {
                return;
            }

            $currentSession = session('unit', 'mysql');
            $powerPlant = $rencanaDayaMampu->machine->powerPlant;

            if ($powerPlant) {
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    // Sync dari UP Kendari ke unit lokal
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'update'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    // Sync dari unit lokal ke UP Kendari
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'update'));
                }
            }
        });

        static::created(function ($rencanaDayaMampu) {
            if (self::$isSyncing) {
                return;
            }

            $currentSession = session('unit', 'mysql');
            $powerPlant = $rencanaDayaMampu->machine->powerPlant;

            if ($powerPlant) {
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'create'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'create'));
                }
            }
        });

        static::deleted(function ($rencanaDayaMampu) {
            if (self::$isSyncing) {
                return;
            }

            $currentSession = session('unit', 'mysql');
            $powerPlant = $rencanaDayaMampu->machine->powerPlant;

            if ($powerPlant) {
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'delete'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'delete'));
                }
            }
        });
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit');
    }

    // Helper methods untuk data JSON
    public function getDailyValue($date, $type = 'rencana')
    {
        $data = $this->daily_data ?? [];
        return $data[$date][$type] ?? null;
    }

    public function setDailyValue($date, $type, $value)
    {
        $data = $this->daily_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = [];
        }
        $data[$date][$type] = $value;
        $this->daily_data = $data;
        
        // Update tanggal jika belum diset
        if (!$this->tanggal) {
            $this->tanggal = $date;
        }
    }

    // Method untuk mengupdate nilai summary
    public function updateSummary()
    {
        $data = $this->daily_data ?? [];
        if (empty($data)) {
            return;
        }

        // Kumpulkan nilai-nilai yang bukan null atau empty string
        $rencanaValues = array_filter(
            array_column(array_column($data, 'rencana'), null),
            function($value) {
                return $value !== null && $value !== '';
            }
        );
        $realisasiValues = array_filter(
            array_column(array_column($data, 'realisasi'), null),
            function($value) {
                return $value !== null && $value !== '';
            }
        );

        // Set nilai summary sebagai text (concatenated values)
        $this->rencana = !empty($rencanaValues) ? implode(', ', $rencanaValues) : null;
        $this->realisasi = !empty($realisasiValues) ? implode(', ', $realisasiValues) : null;
    }

    // Method untuk mendapatkan data harian dalam format yang mudah dibaca
    public function getDailyData($month = null)
    {
        $data = $this->daily_data ?? [];
        if ($month) {
            return array_filter($data, function($key) use ($month) {
                return str_starts_with($key, $month);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $data;
    }
} 