<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailySummary;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineStatusLog;
use Illuminate\Support\Facades\DB;
use App\Models\OperationSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's date
        $today = Carbon::today();
        
        // Fetch daily summaries for today
        $dailySummaries = DailySummary::whereDate('created_at', $today)->get();
        
        // Calculate totals
        $totalNetProduction = $dailySummaries->sum('net_production');
        $totalGrossProduction = $dailySummaries->sum('gross_production');
        $peakLoad = max(
            $dailySummaries->pluck('peak_load_day')->max() ?? 0,
            $dailySummaries->pluck('peak_load_night')->max() ?? 0
        );
        $totalPeriodHours = $dailySummaries->sum('period_hours');

        // Get latest machine status logs for today
        $machineStatusLogs = MachineStatusLog::whereDate('tanggal', $today)
            ->select('machine_id', 'status')
            ->latest('created_at')
            ->get()
            ->unique('machine_id');

        // Count machines by status
        $machineStats = [
            'total' => $machineStatusLogs->count(),
            'ops' => $machineStatusLogs->where('status', 'OPS')->count(),
            'rsh' => $machineStatusLogs->where('status', 'RSH')->count(),
            'fo' => $machineStatusLogs->where('status', 'FO')->count(),
            'mo' => $machineStatusLogs->where('status', 'MO')->count(),
            'po' => $machineStatusLogs->where('status', 'PO')->count(),
            'mb' => $machineStatusLogs->where('status', 'MB')->count(),
        ];

        // Get other required data
        $powerPlants = PowerPlant::with(['machines' => function($query) {
            $query->select('id', 'power_plant_id', 'name', 'status', 'capacity');
        }])->get();
        
        $machines = Machine::all();

        // Get operation schedules for today
        $operationSchedules = OperationSchedule::whereDate('schedule_date', $today)
            ->orderBy('start_time')
            ->get();

        // Pass all variables to the view
        return view('admin.dashboard', compact(
            'powerPlants',
            'machines',
            'machineStats',
            'dailySummaries',
            'totalNetProduction',
            'totalGrossProduction',
            'peakLoad',
            'totalPeriodHours',
            'operationSchedules'
        ));
    }

    private function formatActivityMessage($plantName, $status)
    {
        switch ($status) {
            case 'RUNNING':
                return "Unit {$plantName} beroperasi normal";
            case 'MAINTENANCE':
                return "Pemeliharaan rutin {$plantName}";
            case 'STOP':
                return "Unit {$plantName} berhenti beroperasi";
            case 'STANDBY':
                return "Unit {$plantName} dalam mode standby";
            default:
                return "Update status {$plantName}: {$status}";
        }
    }

    public function getMachines()
    {
        $machines = Machine::all();
        $stats = [
            'total' => $machines->count(),
            'ops' => $machines->where('status', 'OPS')->count(),
            'rsh' => $machines->where('status', 'RSH')->count(),
            'fo' => $machines->where('status', 'FO')->count(),
            'mo' => $machines->where('status', 'MO')->count(),
            'po' => $machines->where('status', 'PO')->count(),
            'mb' => $machines->where('status', 'MB')->count(),
        ];

        return response()->json($stats);
    }
}
