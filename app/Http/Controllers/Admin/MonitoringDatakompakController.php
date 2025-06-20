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
use App\Models\BahanKimia;
use App\Models\RencanaDayaMampu;
use App\Models\AbnormalReport;
use App\Models\FiveS5r;
use App\Models\PatrolCheck;
use App\Models\FlmInspection;

class MonitoringDatakompakController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $date = $request->get('date', now()->format('Y-m-d'));
        $activeTab = $request->get('tab', 'data-engine');

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
            case 'flm-inspection':
                $data = $this->getFlmInspectionData($month, $powerPlants);
                break;
            case 'five-s5r':
                $data = $this->getFiveS5rData($month, $powerPlants);
                break;
            case 'patrol-check':
                $data = $this->getPatrolCheckData($month, $powerPlants);
                break;
            case 'bahan-kimia':
                $data = $this->getBahanKimiaData($month);
                break;
            default:
                $data = $this->getDataEngineData($date, $powerPlants);
        }

        if ($request->ajax()) {
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
            'categorizedUnits'
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

    private function getFlmInspectionData($month, $powerPlants)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        $dates = collect();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->format('Y-m-d'));
        }

        foreach ($powerPlants as $powerPlant) {
            $dailyData = collect();
            foreach ($dates as $date) {
                // Extract the first two words from power plant name for matching
                $powerPlantWords = explode(' ', $powerPlant->name);
                $searchName = $powerPlantWords[0] . ' ' . ($powerPlantWords[1] ?? '');
                $searchName = trim($searchName);

                $inspections = \App\Models\FlmInspection::whereDate('tanggal', $date)
                    ->where('sync_unit_origin', 'like', $searchName . '%')
                    ->get();

                $dailyData->put($date, [
                    'status' => $inspections->isNotEmpty(),
                    'data' => $inspections
                ]);
            }
            $powerPlant->dailyData = $dailyData;
        }

        return [
            'type' => 'flm-inspection',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'powerPlants' => $powerPlants
        ];
    }

    private function getFiveS5rData($month, $powerPlants)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        $dates = collect();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->format('Y-m-d'));
        }

        foreach ($powerPlants as $powerPlant) {
            $dailyData = collect();
            foreach ($dates as $date) {
                // Extract the first two words from power plant name for matching
                $powerPlantWords = explode(' ', $powerPlant->name);
                $searchName = $powerPlantWords[0] . ' ' . ($powerPlantWords[1] ?? '');
                $searchName = trim($searchName);

                $batches = \App\Models\FiveS5rBatch::whereDate('created_at', $date)
                    ->where('sync_unit_origin', 'like', $searchName . '%')
                    ->with(['pemeriksaan', 'programKerja'])
                    ->get();

                $dailyData->put($date, [
                    'status' => $batches->isNotEmpty(),
                    'data' => $batches
                ]);
            }
            $powerPlant->dailyData = $dailyData;
        }

        return [
            'type' => 'five-s5r',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
            'powerPlants' => $powerPlants
        ];
    }

    private function getPatrolCheckData($month, $powerPlants)
    {
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
        $dates = collect();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates->push($date->format('Y-m-d'));
        }

        foreach ($powerPlants as $powerPlant) {
            $dailyData = collect();
            foreach ($dates as $date) {
                // Extract the first two words from power plant name for matching
                $powerPlantWords = explode(' ', $powerPlant->name);
                $searchName = $powerPlantWords[0] . ' ' . ($powerPlantWords[1] ?? '');
                $searchName = trim($searchName);

                $patrols = \App\Models\PatrolCheck::whereDate('created_at', $date)
                    ->where('sync_unit_origin', 'like', $searchName . '%')
                    ->get();

                $dailyData->put($date, [
                    'status' => $patrols->isNotEmpty(),
                    'data' => $patrols
                ]);
            }
            $powerPlant->dailyData = $dailyData;
        }

        return [
            'type' => 'patrol-check',
            'month' => $month,
            'dates' => $dates->map(function($date) {
                return Carbon::parse($date)->format('d/m');
            }),
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

    public function accumulation(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(14)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $selectedUnit = $request->input('unit');

        // Get all power plants except UP KENDARI
        $powerPlantsQuery = PowerPlant::where('name', '!=', 'UP KENDARI');

        // Apply unit filter if selected
        if ($selectedUnit) {
            $powerPlantsQuery->where('id', $selectedUnit);
        }

        $powerPlants = $powerPlantsQuery->get();
        $allPowerPlants = PowerPlant::where('name', '!=', 'UP KENDARI')->get(); // For dropdown

        $accumulatedData = [];

        foreach ($powerPlants as $powerPlant) {
            $data = [
                'name' => $powerPlant->name,
                'operator_kit' => [
                    'data_engine' => $this->calculateCompletionRate($powerPlant, 'data_engine', $startDate, $endDate),
                    'meeting_shift' => $this->calculateCompletionRate($powerPlant, 'meeting_shift', $startDate, $endDate),
                    'patrol_check' => $this->calculateCompletionRate($powerPlant, 'patrol_check', $startDate, $endDate),
                    'five_s5r' => $this->calculateCompletionRate($powerPlant, 'five_s5r', $startDate, $endDate),
                    'flm' => $this->calculateCompletionRate($powerPlant, 'flm', $startDate, $endDate),
                    'abnormal_report' => $this->calculateCompletionRate($powerPlant, 'abnormal_report', $startDate, $endDate),
                    'k3_kam' => $this->calculateCompletionRate($powerPlant, 'k3_kam', $startDate, $endDate),
                ],
                'operasi_ul' => [
                    'bahan_bakar' => $this->calculateCompletionRate($powerPlant, 'bahan_bakar', $startDate, $endDate),
                    'pelumas' => $this->calculateCompletionRate($powerPlant, 'pelumas', $startDate, $endDate),
                    'daily_summary' => $this->calculateCompletionRate($powerPlant, 'daily_summary', $startDate, $endDate),
                    'bahan_kimia' => $this->calculateCompletionRate($powerPlant, 'bahan_kimia', $startDate, $endDate),
                    'rencana_daya_mampu' => $this->calculateCompletionRate($powerPlant, 'rencana_daya_mampu', $startDate, $endDate),
                ]
            ];

            $accumulatedData[] = $data;
        }

        return view('admin.monitoring-datakompak.accumulation', [
            'data' => $accumulatedData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedUnit' => $selectedUnit,
            'powerPlants' => $allPowerPlants
        ]);
    }

    private function calculateCompletionRate($powerPlant, $type, $startDate, $endDate)
    {
        $totalDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
        $filledDays = 0;
        $currentDate = Carbon::parse($startDate);

        while ($currentDate <= Carbon::parse($endDate)) {
            $date = $currentDate->format('Y-m-d');

            switch ($type) {
                case 'data_engine':
                    $filledDays += $this->checkDataEngineCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'daily_summary':
                    $filledDays += $this->checkDailySummaryCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'meeting_shift':
                    $filledDays += $this->checkMeetingShiftCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'bahan_kimia':
                    $filledDays += $this->checkBahanKimiaCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'patrol_check':
                    $filledDays += $this->checkPatrolCheckCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'five_s5r':
                    $filledDays += $this->checkFiveS5rCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'flm':
                    $filledDays += $this->checkFlmCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'abnormal_report':
                    $filledDays += $this->checkAbnormalReportCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'k3_kam':
                    $filledDays += $this->checkK3KamCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'bahan_bakar':
                    $filledDays += $this->checkBahanBakarCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'pelumas':
                    $filledDays += $this->checkPelumasCompletion($powerPlant, $date) ? 1 : 0;
                    break;
                case 'rencana_daya_mampu':
                    $filledDays += $this->checkRencanaDayaMampuCompletion($powerPlant, $date) ? 1 : 0;
                    break;
            }

            $currentDate->addDay();
        }

        $percentage = ($filledDays / $totalDays) * 100;
        $missingDays = $totalDays - $filledDays;

        return [
            'percentage' => round($percentage, 2),
            'missing_days' => $missingDays
        ];
    }

    private function checkDataEngineCompletion($powerPlant, $date)
    {
        return MachineLog::whereIn('machine_id', $powerPlant->machines->pluck('id'))
            ->whereDate('date', $date)
            ->exists();
    }

    private function checkDailySummaryCompletion($powerPlant, $date)
    {
        return DailySummary::where('power_plant_id', $powerPlant->id)
            ->whereDate('date', $date)
            ->exists();
    }

    private function checkMeetingShiftCompletion($powerPlant, $date)
    {
        return MeetingShift::whereDate('tanggal', $date)
            ->where('sync_unit_origin', $powerPlant->unit_source)
            ->exists();
    }

    private function checkBahanKimiaCompletion($powerPlant, $date)
    {
        return BahanKimia::where('unit_id', $powerPlant->id)
            ->whereDate('tanggal', $date)
            ->exists();
    }

    private function checkPatrolCheckCompletion($powerPlant, $date)
    {
        return DB::table('patrol_checks')
            ->where('sync_unit_origin', $powerPlant->name)
            ->whereDate('created_at', $date)
            ->exists();
    }

    private function checkFiveS5rCompletion($powerPlant, $date)
    {
        return DB::table('five_s5r_batches')
            ->where('sync_unit_origin', $powerPlant->name)
            ->whereDate('created_at', $date)
            ->exists();
    }

    private function checkFlmCompletion($powerPlant, $date)
    {
        return DB::table('flm_inspections')
            ->where('sync_unit_origin', $powerPlant->name)
            ->whereDate('tanggal', $date)
            ->exists();
    }

    private function checkAbnormalReportCompletion($powerPlant, $date)
    {
        return DB::table('abnormal_reports')
            ->where('sync_unit_origin', $powerPlant->name)
            ->whereDate('created_at', $date)
            ->exists();
    }

    private function checkK3KamCompletion($powerPlant, $date)
    {
        return DB::table('k3_kamp_reports')
            ->where('sync_unit_origin', $powerPlant->name)
            ->whereDate('date', $date)
            ->exists();
    }

    private function checkBahanBakarCompletion($powerPlant, $date)
    {
        return BahanBakar::where('unit_id', $powerPlant->id)
            ->whereDate('tanggal', $date)
            ->exists();
    }

    private function checkPelumasCompletion($powerPlant, $date)
    {
        return Pelumas::where('unit_id', $powerPlant->id)
            ->whereDate('tanggal', $date)
            ->exists();
    }

    private function checkRencanaDayaMampuCompletion($powerPlant, $date)
    {
        return DB::table('rencana_daya_mampu')
            ->where('unit_source', $powerPlant->unit_source)
            ->whereDate('tanggal', $date)
            ->exists();
    }
}
