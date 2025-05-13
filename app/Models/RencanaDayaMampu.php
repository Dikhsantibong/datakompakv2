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

    // Tambahkan validasi format data
    public function validateDailyData($data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Daily data harus berupa array');
        }

        foreach ($data as $date => $dateData) {
            if (!isset($dateData['rencana']) || !isset($dateData['realisasi'])) {
                throw new \InvalidArgumentException("Data untuk tanggal $date harus memiliki rencana dan realisasi");
            }

            // Validasi rencana
            foreach ($dateData['rencana'] as $idx => $row) {
                if (!isset($row['beban']) || !isset($row['durasi']) || !isset($row['keterangan']) || 
                    !isset($row['on']) || !isset($row['off'])) {
                    throw new \InvalidArgumentException("Data rencana index $idx tidak lengkap");
                }

                // Validasi format waktu
                if ($row['on'] && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row['on'])) {
                    throw new \InvalidArgumentException("Format waktu ON tidak valid pada index $idx");
                }
                if ($row['off'] && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row['off'])) {
                    throw new \InvalidArgumentException("Format waktu OFF tidak valid pada index $idx");
                }
            }

            // Validasi realisasi
            if (!isset($dateData['realisasi']['beban']) || !isset($dateData['realisasi']['keterangan'])) {
                throw new \InvalidArgumentException("Data realisasi untuk tanggal $date tidak lengkap");
            }
        }

        return true;
    }

    // Override setter untuk daily_data
    public function setDailyDataAttribute($value)
    {
        if ($value) {
            $this->validateDailyData($value);
        }
        $this->attributes['daily_data'] = json_encode($value);
    }

    // Helper method untuk mendapatkan template data kosong
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
                'beban' => '',
                'keterangan' => ''
            ]
        ];
    }

    // Update method getDailyValue untuk mendukung format baru
    public function getDailyValue($date, $type = null)
    {
        $data = $this->daily_data ?? [];
        $emptyTemplate = self::getEmptyDayTemplate();
        
        if ($type) {
            return $data[$date][$type] ?? $emptyTemplate[$type];
        }
        return $data[$date] ?? $emptyTemplate;
    }

    // Method untuk menambah row rencana
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

    // Method untuk menghapus row rencana
    public function deleteRencanaRow($date, $index)
    {
        $data = $this->daily_data ?? [];
        if (isset($data[$date]['rencana'][$index])) {
            unset($data[$date]['rencana'][$index]);
            $data[$date]['rencana'] = array_values($data[$date]['rencana']); // Reindex array
            $this->daily_data = $data;
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