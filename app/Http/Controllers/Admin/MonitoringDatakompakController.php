<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\DailySummary;
use App\Models\MachineStatusLog;
use App\Models\EngineData;
use App\Models\PowerPlant;
use App\Models\MachineLog;
use App\Models\MeetingShift;
use Illuminate\Support\Collection;

class MonitoringDatakompakController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $activeTab = $request->get('tab', 'data-engine');

        // Get all power plants
        $powerPlants = PowerPlant::with(['machines'])->get();

        // Parse the month to get start and end dates
        $date = Carbon::createFromFormat('Y-m', $month)->format('Y-m-d');

        // Calculate stats and get categorized units
        $statsData = $this->calculateStats($powerPlants, $date);
        $stats = $statsData['stats'];
        $categorizedUnits = $statsData['units'];

        // Get data based on active tab
        switch ($activeTab) {
            case 'data-engine':
                $data = $this->getDataEngineData($date, $powerPlants);
                break;
            case 'daily-summary':
                $data = $this->getDailySummaryData($date, $powerPlants);
                break;
            case 'meeting-shift':
                $data = $this->getMeetingShiftData($date, $powerPlants);
                break;
            default:
                $data = $this->getDataEngineData($date, $powerPlants);
        }

        if ($request->ajax()) {
            return view('admin.monitoring-datakompak._table', compact('data', 'activeTab', 'date'));
        }

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('admin.monitoring-datakompak', compact(
            'data',
            'activeTab',
            'month',
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
        for ($i = 0; $i < 24; $i++) {
            $hours[] = Carbon::parse($date)->startOfDay()->addHours($i)->format('Y-m-d H:i:s');
        }

        foreach ($powerPlants as $powerPlant) {
            $hourlyStatus = [];
            foreach ($hours as $hour) {
                $hasData = MachineLog::whereIn('machine_id', $powerPlant->machines->pluck('id'))
                    ->whereDate('date', Carbon::parse($hour)->format('Y-m-d'))
                    ->whereTime('time', Carbon::parse($hour)->format('H:i:s'))
                    ->exists();
                $hourlyStatus[$hour] = $hasData;
            }
            $powerPlant->hourlyStatus = $hourlyStatus;
        }

        return [
            'type' => 'data-engine',
            'hours' => $hours,
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
                    $hasData = MeetingShift::whereDate('tanggal', $date)
                        ->where('current_shift', $shift)
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
} 