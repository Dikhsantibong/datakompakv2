<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\DailySummary;
use App\Models\MachineStatusLog;
use App\Models\EngineData;
use App\Models\PowerPlant;
use App\Models\MachineLog;
use App\Models\MeetingShift;
use Illuminate\Support\Collection;
use App\Models\BahanBakar;
use App\Models\Pelumas;
use App\Models\LaporanKit;
use App\Exports\MonitoringDatakompakExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FlmInspection;
use App\Models\FiveS5rBatch;
use App\Models\BahanKimia;
use App\Models\PatrolCheck;
use App\Models\Machine;

class MonitoringDatakompakController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = $request->get('date', now()->format('Y-m-d'));
        $activeTab = $request->get('tab', 'data-engine');
        $startDate = $request->get('start_date', now()->subDays(13)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Get monitoring summary
        $monitoringSummary = $this->getMonitoringSummary(
            \Carbon\Carbon::parse($startDate),
            \Carbon\Carbon::parse($endDate)
        );

        // Get all power plants
        $powerPlants = PowerPlant::with(['machines'])->get();

        // Calculate stats and get categorized units based on active tab
        if ($activeTab === 'data-engine') {
            $statsData = $this->calculateStats($powerPlants, $date);
        } else {
            $statsData = $this->calculateStats($powerPlants, Carbon::createFromFormat('Y-m', $month)->format('Y-m-d'));
        }
        $stats = $statsData['stats'];
        $categorizedUnits = $statsData['units'];

        // Get data based on active tab
        switch ($activeTab) {
            case 'data-engine':
                $data = $this->getDataEngineData($date, $powerPlants);
                break;
            case 'daily-summary':
                $data = $this->getDailySummaryData(Carbon::createFromFormat('Y-m', $month)->format('Y-m-d'), $powerPlants);
                break;
            case 'meeting-shift':
                $data = $this->getMeetingShiftData(Carbon::createFromFormat('Y-m', $month)->format('Y-m-d'), $powerPlants);
                break;
            case 'bahan-bakar':
                $data = $this->getBahanBakarData($month);
                break;
            case 'pelumas':
                $data = $this->getPelumasData($month);
                break;
            case 'laporan-kit':
                $data = $this->getLaporanKitData($month);
                break;
            case 'flm':
                $data = $this->getFlmData($month);
                break;
            case '5s5r':
                $data = $this->get5s5rData($month);
                break;
            case 'bahan-kimia':
                $data = $this->getBahanKimiaData($month);
                break;
            case 'patrol-check':
                $data = $this->getPatrolCheckData($month);
                break;
            default:
                $data = $this->getDataEngineData($date, $powerPlants);
        }

        if ($request->ajax()) {
            if ($request->get('get_summary')) {
                return response()->json([
                    'summary' => $monitoringSummary,
                    'date_range' => [
                        'start' => $startDate,
                        'end' => $endDate
                    ]
                ]);
            }
            return view('admin.monitoring-datakompak._table', compact('data', 'activeTab', 'date', 'month'));
        }

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('admin.monitoring-datakompak', compact(
            'data',
            'activeTab',
            'month',
            'date',
            'stats',
            'recentActivities',
            'categorizedUnits',
            'monitoringSummary',
            'startDate',
            'endDate'
        ));
    }

    private function calculateStats($powerPlants, $date)
    {
        $stats = [
            'total_units' => 0,
            'completed' => 0,
            'pending' => 0,
            'overdue' => 0
        ];

        $completedUnits = new Collection();
        $pendingUnits = new Collection();
        $overdueUnits = new Collection();

        foreach ($powerPlants as $plant) {
            $stats['total_units']++;

            $plantData = [
                'id' => $plant->id,
                'name' => $plant->name,
                'machines' => $plant->machines,
                'completed_inputs' => 0,
                'last_update' => null
            ];

            // Check DailySummary
            $hasDaily = DailySummary::where('power_plant_id', $plant->id)
                ->whereDate('date', $date)
                ->exists();

            // Check MachineStatusLog
            $hasStatus = MachineStatusLog::whereIn('machine_id', $plant->machines->pluck('id'))
                ->whereDate('tanggal', $date)
                ->exists();

            // Check EngineData
            $hasEngine = EngineData::whereIn('machine_id', $plant->machines->pluck('id'))
                ->whereDate('date', $date)
                ->exists();

            $plantData['completed_inputs'] = ($hasDaily ? 1 : 0) + ($hasStatus ? 1 : 0) + ($hasEngine ? 1 : 0);

            if ($hasDaily && $hasStatus && $hasEngine) {
                $stats['completed']++;
                $completedUnits->push($plantData);
            } else {
                // Get latest update time
                $latestUpdates = [];

                $dailySummaryUpdate = DailySummary::where('power_plant_id', $plant->id)
                    ->latest('updated_at')
                    ->value('updated_at');
                if ($dailySummaryUpdate) {
                    $latestUpdates[] = $dailySummaryUpdate;
                }

                $machineStatusUpdate = MachineStatusLog::whereIn('machine_id', $plant->machines->pluck('id'))
                    ->latest('updated_at')
                    ->value('updated_at');
                if ($machineStatusUpdate) {
                    $latestUpdates[] = $machineStatusUpdate;
                }

                $engineDataUpdate = EngineData::whereIn('machine_id', $plant->machines->pluck('id'))
                    ->latest('updated_at')
                    ->value('updated_at');
                if ($engineDataUpdate) {
                    $latestUpdates[] = $engineDataUpdate;
                }

                if (!empty($latestUpdates)) {
                    $latestUpdate = max($latestUpdates);
                    $plantData['last_update'] = $latestUpdate;

                    if (Carbon::parse($latestUpdate)->diffInHours(now()) > 6) {
                    $stats['overdue']++;
                        $overdueUnits->push($plantData);
                    } else {
                        $stats['pending']++;
                        $pendingUnits->push($plantData);
                    }
                } else {
                    $stats['pending']++;
                    $pendingUnits->push($plantData);
                }
            }
        }

        return [
            'stats' => $stats,
            'units' => [
                'completed' => $completedUnits,
                'pending' => $pendingUnits,
                'overdue' => $overdueUnits
            ]
        ];
    }

    private function getDataEngineData($date, $powerPlants)
    {
        $hours = [];
        $selectedDate = Carbon::parse($date);

        for ($i = 0; $i < 24; $i++) {
            $hours[] = $selectedDate->copy()->startOfDay()->addHours($i)->format('Y-m-d H:i:s');
        }

        foreach ($powerPlants as $powerPlant) {
            $hourlyStatus = [];
            $hourlyLog = [];
            foreach ($hours as $hour) {
                $log = MachineLog::whereIn('machine_id', $powerPlant->machines->pluck('id'))
                    ->whereDate('date', Carbon::parse($hour)->format('Y-m-d'))
                    ->whereTime('time', Carbon::parse($hour)->format('H:i:s'))
                    ->first();
                $hasData = !is_null($log);
                $hourlyStatus[$hour] = $hasData;
                $hourlyLog[$hour] = $log;
            }
            $powerPlant->hourlyStatus = $hourlyStatus;
            $powerPlant->hourlyLog = $hourlyLog;
        }

        return [
            'type' => 'data-engine',
            'hours' => $hours,
            'date' => $selectedDate->format('Y-m-d'),
            'powerPlants' => $powerPlants
        ];
    }

    private function getDailySummaryData($date, $powerPlants)
    {
        $startDate = Carbon::parse($date)->startOfMonth();
        $endDate = Carbon::parse($date)->endOfMonth();
        $dates = [];

        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        foreach ($powerPlants as $powerPlant) {
            $dailyStatus = [];
            foreach ($dates as $date) {
                $hasData = DailySummary::where('power_plant_id', $powerPlant->id)
                    ->whereDate('date', $date)
                    ->exists();
                $dailyStatus[$date] = $hasData;
            }
            $powerPlant->dailyStatus = $dailyStatus;
        }

        return [
            'type' => 'daily-summary',
            'dates' => $dates,
            'powerPlants' => $powerPlants
        ];
    }

    private function getMeetingShiftData($date, $powerPlants)
    {
        $shifts = ['A', 'B', 'C', 'D'];
        $dates = [];

        // Start from the 1st of the month
        $startDate = Carbon::parse($date)->startOfMonth();
        $endDate = Carbon::parse($date)->endOfMonth();

        // Generate dates for the entire month
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        foreach ($powerPlants as $powerPlant) {
            $shiftStatus = [];

            foreach ($dates as $date) {
                foreach ($shifts as $shift) {
                    $key = $date . '_' . $shift;

                    // Check if data exists for this unit's sync_unit_origin using unit_source
                    $hasData = MeetingShift::whereDate('tanggal', $date)
                        ->where('current_shift', $shift)
                        ->where('sync_unit_origin', $powerPlant->unit_source)
                        ->exists();

                    $shiftStatus[$key] = $hasData;
                }
            }
            $powerPlant->shiftStatus = $shiftStatus;
        }

        return [
            'type' => 'meeting-shift',
            'shifts' => $shifts,
            'dates' => $dates,
            'powerPlants' => $powerPlants
        ];
    }

    private function getPlantType($plantName)
    {
        if (str_contains($plantName, 'PLTD')) return 'PLTD';
        if (str_contains($plantName, 'PLTM')) return 'PLTM';
        if (str_contains($plantName, 'PLTU')) return 'PLTU';
        if (str_contains($plantName, 'PLTMG')) return 'PLTMG';
        return 'Other';
    }

    private function getSystemStats()
    {
        $today = Carbon::today();

        return [
            'uptime' => '99.8%',
            'avg_response' => '1.2s',
            'total_inputs_today' => DailySummary::whereDate('date', $today)->count() +
                                  MachineStatusLog::whereDate('tanggal', $today)->count() +
                                  EngineData::whereDate('date', $today)->count(),
            'active_users' => DB::table('sessions')->whereDate('last_activity', '>=', now()->subHours(1))->count()
        ];
    }

    private function getRecentActivities()
    {
        $activities = collect();

        // Get recent DailySummary updates
        $dailySummaries = DailySummary::with('powerPlant')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($summary) {
                return [
                    'type' => 'Daily Summary',
                    'unit' => $summary->powerPlant->name,
                    'time' => $summary->created_at,
                    'action' => 'Updated daily summary data'
                ];
            });

        // Get recent MachineStatusLog updates
        $statusLogs = MachineStatusLog::with('machine.powerPlant')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'type' => 'Machine Status',
                    'unit' => $log->machine->powerPlant->name,
                    'time' => $log->created_at,
                    'action' => "Updated status to {$log->status}"
                ];
            });

        // Get recent EngineData updates
        $engineData = EngineData::with('machine.powerPlant')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($data) {
                return [
                    'type' => 'Engine Data',
                    'unit' => $data->machine->powerPlant->name,
                    'time' => $data->created_at,
                    'action' => 'Updated engine performance data'
                ];
            });

        return $activities->concat($dailySummaries)
                         ->concat($statusLogs)
                         ->concat($engineData)
                         ->sortByDesc('time')
                         ->take(10)
                         ->values();
    }

    private function getBahanBakarData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get all power plants
        $powerPlants = PowerPlant::all();

        // Get all dates in the month
        $dates = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Get all bahan bakar data for the month
        $bahanBakarData = BahanBakar::with('unit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(['unit_id', function($item) {
                return $item->tanggal->format('Y-m-d');
            }]);

        // Prepare data for each power plant
        foreach ($powerPlants as $powerPlant) {
            $powerPlant->dailyData = collect();
            foreach ($dates as $date) {
                $dayData = $bahanBakarData->get($powerPlant->id, collect())
                    ->get($date, collect())
                    ->first();

                $powerPlant->dailyData->put($date, [
                    'status' => !is_null($dayData),
                    'data' => $dayData
                ]);
            }
        }

        return [
            'type' => 'bahan-bakar',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'powerPlants' => $powerPlants
        ];
    }

    private function getPelumasData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get all power plants
        $powerPlants = PowerPlant::all();

        // Get all dates in the month
        $dates = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Get all pelumas data for the month
        $pelumasData = Pelumas::with('unit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(['unit_id', function($item) {
                return $item->tanggal->format('Y-m-d');
            }]);

        // Prepare data for each power plant
        foreach ($powerPlants as $powerPlant) {
            $powerPlant->dailyData = collect();
            foreach ($dates as $date) {
                $dayData = $pelumasData->get($powerPlant->id, collect())
                    ->get($date, collect())
                    ->first();

                $powerPlant->dailyData->put($date, [
                    'status' => !is_null($dayData),
                    'data' => $dayData
                ]);
            }
        }

        return [
            'type' => 'pelumas',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'powerPlants' => $powerPlants
        ];
    }

    private function getLaporanKitData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get all power plants
        $powerPlants = PowerPlant::all();

        // Get all dates in the month
        $dates = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Get all laporan kit data for the month
        $laporanKitData = LaporanKit::with(['jamOperasi', 'gangguan', 'bbm', 'kwh', 'pelumas', 'bahanKimia', 'bebanTertinggi'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(['unit_source', function($item) {
                return \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
            }]);

        // Prepare data for each power plant
        foreach ($powerPlants as $powerPlant) {
            $powerPlant->dailyData = collect();
            foreach ($dates as $date) {
                $dayData = $laporanKitData->get($powerPlant->unit_source, collect())
                    ->get($date, collect())
                    ->first();

                $powerPlant->dailyData->put($date, [
                    'status' => !is_null($dayData),
                    'data' => $dayData
                ]);
            }
        }

        return [
            'type' => 'laporan-kit',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'powerPlants' => $powerPlants
        ];
    }

    private function getFlmData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        $dates = [];

        // Start from the 1st of the month
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get all power plants
        $powerPlants = PowerPlant::all();

        foreach ($powerPlants as $powerPlant) {
            $dailyStatus = [];
            // Get the short name version for matching
            $shortName = trim(explode('(', $powerPlant->name)[0]);

            foreach ($dates as $date) {
                // Check if data exists matching the power plant name
                $hasData = FlmInspection::whereDate('tanggal', $date)
                    ->where('sync_unit_origin', $shortName)
                    ->exists();

                $dailyStatus[$date] = $hasData;
            }
            $powerPlant->dailyStatus = $dailyStatus;
        }

        return [
            'type' => 'flm',
            'month' => $month,
            'dates' => array_map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }, $dates),
            'powerPlants' => $powerPlants
        ];
    }

    private function get5s5rData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        $dates = [];

        // Start from the 1st of the month
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get all power plants
        $powerPlants = PowerPlant::all();

        foreach ($powerPlants as $powerPlant) {
            $dailyStatus = [];
            // Get the short name version for matching
            $shortName = trim(explode('(', $powerPlant->name)[0]);

            foreach ($dates as $date) {
                // Check if data exists matching the power plant name
                $hasData = FiveS5rBatch::whereDate('created_at', $date)
                    ->where('sync_unit_origin', $shortName)
                    ->exists();

                $dailyStatus[$date] = $hasData;
            }
            $powerPlant->dailyStatus = $dailyStatus;
        }

        return [
            'type' => '5s5r',
            'month' => $month,
            'dates' => array_map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }, $dates),
            'powerPlants' => $powerPlants
        ];
    }

    private function getBahanKimiaData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get all power plants
        $powerPlants = PowerPlant::all();

        // Get all dates in the month
        $dates = collect();
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates->push($currentDate->format('Y-m-d'));
            $currentDate->addDay();
        }

        // Get all bahan kimia data for the month
        $bahanKimiaData = BahanKimia::with('unit')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->get()
            ->groupBy(['unit_id', function($item) {
                return $item->tanggal->format('Y-m-d');
            }]);

        // Prepare data for each power plant
        foreach ($powerPlants as $powerPlant) {
            $powerPlant->dailyData = collect();
            foreach ($dates as $date) {
                $dayData = $bahanKimiaData->get($powerPlant->id, collect())
                    ->get($date, collect())
                    ->first();

                $powerPlant->dailyData->put($date, [
                    'status' => !is_null($dayData),
                    'data' => $dayData
                ]);
            }
        }

        return [
            'type' => 'bahan-kimia',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'powerPlants' => $powerPlants
        ];
    }

    private function getPatrolCheckData($month)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        $dates = [];

        // Start from the 1st of the month
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Get all power plants
        $powerPlants = PowerPlant::all();

        foreach ($powerPlants as $powerPlant) {
            $dailyStatus = [];
            // Get the short name version for matching
            $shortName = trim(explode('(', $powerPlant->name)[0]);

            foreach ($dates as $date) {
                // Check if data exists matching the power plant name
                $hasData = PatrolCheck::whereDate('created_at', $date)
                    ->where('sync_unit_origin', $shortName)
                    ->exists();

                $dailyStatus[$date] = $hasData;
            }
            $powerPlant->dailyStatus = $dailyStatus;
        }

        return [
            'type' => 'patrol-check',
            'month' => $month,
            'dates' => array_map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }, $dates),
            'powerPlants' => $powerPlants
        ];
    }

    public function exportExcel(Request $request)
    {
        $tab = $request->get('tab', 'data-engine');
        $date = $request->get('date', now()->format('Y-m-d'));
        $month = $request->get('month', now()->format('Y-m'));

        $powerPlants = PowerPlant::with(['machines'])->get();

        switch ($tab) {
            case 'data-engine':
                $data = $this->getDataEngineData($date, $powerPlants);
                break;
            case 'daily-summary':
                $data = $this->getDailySummaryData(\Carbon\Carbon::createFromFormat('Y-m', $month)->format('Y-m-d'), $powerPlants);
                break;
            case 'meeting-shift':
                $data = $this->getMeetingShiftData(\Carbon\Carbon::createFromFormat('Y-m', $month)->format('Y-m-d'), $powerPlants);
                break;
            case 'bahan-bakar':
                $data = $this->getBahanBakarData($month);
                break;
            case 'pelumas':
                $data = $this->getPelumasData($month);
                break;
            case 'laporan-kit':
                $data = $this->getLaporanKitData($month);
                break;
            case 'flm':
                $data = $this->getFlmData($month);
                break;
            case '5s5r':
                $data = $this->get5s5rData($month);
                break;
            case 'bahan-kimia':
                $data = $this->getBahanKimiaData($month);
                break;
            case 'patrol-check':
                $data = $this->getPatrolCheckData($month);
                break;
            default:
                $data = $this->getDataEngineData($date, $powerPlants);
        }

        return Excel::download(new MonitoringDatakompakExport($data, $tab), 'monitoring-datakompak-'.$tab.'-'.now()->format('Ymd_His').'.xlsx');
    }

    public function formatUnitName($name)
    {
        // Split the name by spaces and take first 3 words
        $words = explode(' ', $name);
        $firstThreeWords = array_slice($words, 0, 2);
        return implode(' ', $firstThreeWords);
    }

    private function getMonitoringSummary($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        $powerPlants = PowerPlant::where('name', '!=', 'UP KENDARI')->get();
        $summary = [];

        foreach ($powerPlants as $powerPlant) {
            $shortName = trim(explode('(', $powerPlant->name)[0]);
            $summary[$powerPlant->name] = [
                'operator' => [
                    'data_engine' => $this->calculateCompletionRate(
                        $powerPlant->id,
                        $startDate,
                        $endDate,
                        'EngineData',
                        'machine_id'
                    ),
                    'daily_summary' => $this->calculateCompletionRate(
                        $powerPlant->id,
                        $startDate,
                        $endDate,
                        'DailySummary',
                        'power_plant_id'
                    ),
                    'meeting_shift' => $this->calculateCompletionRate(
                        $shortName,
                        $startDate,
                        $endDate,
                        'MeetingShift',
                        'sync_unit_origin'
                    ),
                    'laporan_kit' => $this->calculateCompletionRate(
                        $powerPlant->unit_source,
                        $startDate,
                        $endDate,
                        'LaporanKit',
                        'unit_source'
                    )
                ],
                'operasi' => [
                    'bahan_bakar' => $this->calculateCompletionRate(
                        $powerPlant->id,
                        $startDate,
                        $endDate,
                        'BahanBakar',
                        'unit_id'
                    ),
                    'pelumas' => $this->calculateCompletionRate(
                        $powerPlant->id,
                        $startDate,
                        $endDate,
                        'Pelumas',
                        'unit_id'
                    ),
                    'bahan_kimia' => $this->calculateCompletionRate(
                        $powerPlant->id,
                        $startDate,
                        $endDate,
                        'BahanKimia',
                        'unit_id'
                    ),
                    'flm' => $this->calculateCompletionRate(
                        $shortName,
                        $startDate,
                        $endDate,
                        'FlmInspection',
                        'sync_unit_origin'
                    ),
                    '5s5r' => $this->calculateCompletionRate(
                        $shortName,
                        $startDate,
                        $endDate,
                        'FiveS5rBatch',
                        'sync_unit_origin'
                    ),
                    'patrol_check' => $this->calculateCompletionRate(
                        $shortName,
                        $startDate,
                        $endDate,
                        'PatrolCheck',
                        'sync_unit_origin'
                    )
                ]
            ];

            // Calculate average scores
            $operatorScores = collect($summary[$powerPlant->name]['operator'])->pluck('percentage');
            $operasiScores = collect($summary[$powerPlant->name]['operasi'])->pluck('percentage');

            $summary[$powerPlant->name]['average_score'] = (int) round(
                ($operatorScores->sum() + $operasiScores->sum()) /
                ($operatorScores->count() + $operasiScores->count())
            );
        }

        return $summary;
    }

    private function calculateCompletionRate($identifier, $startDate, $endDate, $modelName, $columnName)
    {
        $modelClass = "App\\Models\\{$modelName}";
        $query = $modelClass::query();

        // Set the date column name based on model - only EngineData and DailySummary use 'date'
        $dateColumn = match($modelName) {
            'EngineData', 'DailySummary' => 'date',
            default => 'created_at'
        };

        // Special handling for EngineData which uses machine_id
        if ($modelName === 'EngineData') {
            // Get all machine IDs for this power plant
            $machineIds = Machine::where('power_plant_id', $identifier)->pluck('id');
            $query->whereIn('machine_id', $machineIds);
        } else if ($columnName === 'sync_unit_origin') {
            // Add quotes for string comparison
            $query->where($columnName, '=', $identifier);
        } else {
            $query->where($columnName, $identifier);
        }

        $query->whereBetween($dateColumn, [$startDate, $endDate]);

        // Get the total number of days in the date range
        $totalDays = (int) $startDate->diffInDays($endDate) + 1;

        // Special handling for EngineData which should have 24 entries per day
        if ($modelName === 'EngineData') {
            $totalExpected = $totalDays * 24 * count($machineIds);
            $actualCount = (int) $query->count();
            $percentage = $totalExpected > 0 ? (int) round(($actualCount / $totalExpected) * 100) : 0;
            $missingInputs = $totalExpected - $actualCount;
        } else {
            // For other models, count distinct dates from created_at
            $daysWithData = (int) $query->distinct(DB::raw("DATE($dateColumn)"))->count();
            $percentage = $totalDays > 0 ? (int) round(($daysWithData / $totalDays) * 100) : 0;
            $missingInputs = $totalDays - $daysWithData;
        }

        return [
            'percentage' => $percentage,
            'missing_inputs' => $missingInputs
        ];
    }

    public function getSummaryData(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        return response()->json([
            'summary' => $this->getMonitoringSummary($startDate, $endDate)
        ]);
    }
}
