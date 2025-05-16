<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Events\RencanaDayaMampuUpdated;
use Illuminate\Support\Facades\Log;

class RencanaDayaMampu extends Model
{
    use HasFactory;

    public static $isSyncing = false;

    protected $table = 'rencana_daya_mampu';

    protected $fillable = [
        'uuid',
        'machine_id',
        'tanggal',
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

        static::created(function ($rencanaDayaMampu) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $rencanaDayaMampu->machine->powerPlant;
                
                if (!$powerPlant) {
                    Log::warning('Skipping sync - Power Plant not found for rencana daya mampu:', [
                        'machine_id' => $rencanaDayaMampu->machine_id
                    ]);
                    return;
                }

                $currentSession = session('unit', 'mysql');

                // Sinkronisasi hanya jika kondisi terpenuhi
                if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'create'));
                } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                    event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'create'));
                }
            } catch (\Exception $e) {
                Log::error('Error in RencanaDayaMampu sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::updated(function ($rencanaDayaMampu) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $rencanaDayaMampu->machine->powerPlant;
                if ($powerPlant) {
                    $currentSession = session('unit', 'mysql');

                    if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                        event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'update'));
                    } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                        event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'update'));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in RencanaDayaMampu sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });

        static::deleting(function ($rencanaDayaMampu) {
            try {
                if (self::$isSyncing) return;

                $powerPlant = $rencanaDayaMampu->machine->powerPlant;
                if ($powerPlant) {
                    $currentSession = session('unit', 'mysql');

                    if ($currentSession === 'mysql' && $powerPlant->unit_source !== 'mysql') {
                        event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'delete'));
                    } elseif ($currentSession !== 'mysql' && $currentSession === $powerPlant->unit_source) {
                        event(new RencanaDayaMampuUpdated($rencanaDayaMampu, 'delete'));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error in RencanaDayaMampu sync:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        });
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function getConnectionName()
    {
        return session('unit', 'mysql');
    }

    public function validateDailyData($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Daily data harus berupa array');
        }

        foreach ($data as $date => $dateData) {
            // Validate date format
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                throw new \InvalidArgumentException("Format tanggal tidak valid: $date");
            }

            // Initialize with empty template if not set
            if (!isset($dateData['rencana'])) {
                $dateData['rencana'] = [];
            }
            if (!isset($dateData['realisasi'])) {
                $dateData['realisasi'] = [];
            }

            // Validate rencana
            foreach ($dateData['rencana'] as $idx => $row) {
                // Skip empty rows
                if (empty($row['beban']) && empty($row['on']) && empty($row['off'])) {
                    continue;
                }

                // Validate required fields
                if (!isset($row['beban'])) $row['beban'] = '';
                if (!isset($row['durasi'])) $row['durasi'] = '';
                if (!isset($row['keterangan'])) $row['keterangan'] = '';
                if (!isset($row['on'])) $row['on'] = '';
                if (!isset($row['off'])) $row['off'] = '';

                // Validate numeric values
                if (!empty($row['beban']) && !is_numeric($row['beban'])) {
                    throw new \InvalidArgumentException("Beban harus berupa angka pada rencana index $idx");
                }
                if (!empty($row['durasi']) && !is_numeric($row['durasi'])) {
                    throw new \InvalidArgumentException("Durasi harus berupa angka pada rencana index $idx");
                }

                // Validate time format
                if (!empty($row['on']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row['on'])) {
                    throw new \InvalidArgumentException("Format waktu ON tidak valid pada rencana index $idx");
                }
                if (!empty($row['off']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row['off'])) {
                    throw new \InvalidArgumentException("Format waktu OFF tidak valid pada rencana index $idx");
                }
            }

            // Validate realisasi
            foreach ($dateData['realisasi'] as $idx => $row) {
                // Skip empty rows
                if (empty($row['beban']) && empty($row['keterangan'])) {
                    continue;
                }

                // Validate required fields
                if (!isset($row['beban'])) $row['beban'] = '';
                if (!isset($row['keterangan'])) $row['keterangan'] = '';

                // Validate numeric values
                if (!empty($row['beban']) && !is_numeric($row['beban'])) {
                    throw new \InvalidArgumentException("Beban harus berupa angka pada realisasi index $idx");
                }
            }
        }

        return true;
    }

    public function setDailyDataAttribute($value)
    {
        if ($value) {
            $this->validateDailyData($value);
        }
        $this->attributes['daily_data'] = json_encode($value);
    }

    public static function getEmptyDayTemplate()
    {
        return [
            'rencana' => [
                [
                    'beban' => '',
                    'durasi' => '',
                    'keterangan' => '',
                    'on' => '',
                    'off' => ''
                ]
            ],
            'realisasi' => [
                [
                    'beban' => '',
                    'keterangan' => ''
                ]
            ]
        ];
    }

    public function getDailyValue($date)
    {
        $data = $this->daily_data ?? [];
        return $data[$date] ?? self::getEmptyDayTemplate();
    }

    public function setDailyValue($date, $type, $value)
    {
        $data = $this->daily_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = self::getEmptyDayTemplate();
        }
        $data[$date][$type] = $value;
        $this->daily_data = $data;
    }

    public function addRencanaRow($date)
    {
        $data = $this->daily_data ?? [];
        if (!isset($data[$date])) {
            $data[$date] = self::getEmptyDayTemplate();
        }
        
        $data[$date]['rencana'][] = [
            'beban' => '',
            'durasi' => '',
            'keterangan' => '',
            'on' => '',
            'off' => ''
        ];
        
        $this->daily_data = $data;
    }

    public function deleteRencanaRow($date, $index)
    {
        $data = $this->daily_data ?? [];
        if (isset($data[$date]['rencana'][$index])) {
            unset($data[$date]['rencana'][$index]);
            $data[$date]['rencana'] = array_values($data[$date]['rencana']); // Reindex array
            $this->daily_data = $data;
        }
    }

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