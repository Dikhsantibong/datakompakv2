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

class MonitoringDatakompakController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Get all power plants
        $powerPlants = PowerPlant::with(['machines'])->get();
        
        $monitoringData = [];
        $stats = [
            'total_units' => 0,
            'completed' => 0,
            'pending' => 0,
            'overdue' => 0
        ];

        foreach ($powerPlants as $plant) {
            $plantData = [
                'id' => $plant->id,
                'name' => $plant->name,
                'type' => $this->getPlantType($plant->name),
                'status' => 'pending',
                'daily_summary' => null,
                'machine_status' => null,
                'engine_data' => null,
                'last_update' => null,
                'machines_count' => $plant->machines->count(),
                'completed_inputs' => 0
            ];

            // Check DailySummary
            $dailySummary = DailySummary::where('power_plant_id', $plant->id)
                ->whereDate('date', $today)
                ->latest()
                ->first();

            // Check MachineStatusLog
            $machineStatus = MachineStatusLog::whereIn('machine_id', $plant->machines->pluck('id'))
                ->whereDate('tanggal', $today)
                ->latest()
                ->first();

            // Check EngineData
            $engineData = EngineData::whereIn('machine_id', $plant->machines->pluck('id'))
                ->whereDate('date', $today)
                ->latest()
                ->first();

            // Calculate completion status
            $hasDaily = !is_null($dailySummary);
            $hasStatus = !is_null($machineStatus);
            $hasEngine = !is_null($engineData);

            $plantData['daily_summary'] = $dailySummary;
            $plantData['machine_status'] = $machineStatus;
            $plantData['engine_data'] = $engineData;

            // Calculate last update
            $dates = array_filter([
                $dailySummary?->created_at,
                $machineStatus?->created_at,
                $engineData?->created_at
            ]);
            
            $plantData['last_update'] = !empty($dates) ? max($dates) : null;
            
            // Calculate status
            if ($hasDaily && $hasStatus && $hasEngine) {
                $plantData['status'] = 'completed';
                $plantData['completed_inputs'] = 3;
                $stats['completed']++;
            } else {
                $hoursLate = $plantData['last_update'] 
                    ? Carbon::now()->diffInHours($plantData['last_update'])
                    : 24;
                
                if ($hoursLate > 6) {
                    $plantData['status'] = 'overdue';
                    $stats['overdue']++;
                } else {
                    $plantData['status'] = 'pending';
                    $stats['pending']++;
                }
                
                $plantData['completed_inputs'] = ($hasDaily ? 1 : 0) + 
                                               ($hasStatus ? 1 : 0) + 
                                               ($hasEngine ? 1 : 0);
            }

            $monitoringData[] = $plantData;
            $stats['total_units']++;
        }

        // Group plants by status
        $completedUnits = collect($monitoringData)->where('status', 'completed')->values();
        $pendingUnits = collect($monitoringData)->where('status', 'pending')->values();
        $overdueUnits = collect($monitoringData)->where('status', 'overdue')->values();

        // Calculate unit types for chart
        $unitTypes = [
            'PLTD' => collect($monitoringData)->where('type', 'PLTD')->count(),
            'PLTM' => collect($monitoringData)->where('type', 'PLTM')->count(),
            'PLTU' => collect($monitoringData)->where('type', 'PLTU')->count(),
            'PLTMG' => collect($monitoringData)->where('type', 'PLTMG')->count(),
            'Other' => collect($monitoringData)->whereNotIn('type', ['PLTD', 'PLTM', 'PLTU', 'PLTMG'])->count(),
        ];

        // Get system performance stats
        $systemStats = $this->getSystemStats();

        // Get recent activities
        $recentActivities = $this->getRecentActivities();

        return view('admin.monitoring-datakompak', compact(
            'monitoringData',
            'completedUnits',
            'pendingUnits',
            'overdueUnits',
            'stats',
            'unitTypes',
            'systemStats',
            'recentActivities'
        ));
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