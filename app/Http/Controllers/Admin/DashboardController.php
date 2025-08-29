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
        $start = microtime(true);
        \Log::info('DASHBOARD: Mulai proses render', ['time' => $start]);
        // Get today's date
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        
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

        // Get machine status statistics for the current month
        $machineStats = DB::table('machine_logs as ml')
            ->whereBetween('ml.date', [$startOfMonth, $endOfMonth])
            ->select(
                'ml.status',
                DB::raw('COUNT(DISTINCT ml.machine_id) as count'),
                DB::raw('SUM(
                    TIMESTAMPDIFF(
                        HOUR,
                        ml.time,
                        COALESCE(
                            (
                                SELECT MIN(next_log.time)
                                FROM machine_logs as next_log
                                WHERE next_log.machine_id = ml.machine_id
                                AND next_log.time > ml.time
                                AND next_log.date <= "' . $endOfMonth->format('Y-m-d') . '"
                            ),
                            NOW()
                        )
                    )
                ) as hours')
            )
            ->groupBy('ml.status')
            ->get()
            ->mapWithKeys(function ($item) {
                return [strtolower($item->status) => [
                    'count' => $item->count,
                    'hours' => max(0, $item->hours) // Ensure we don't get negative hours
                ]];
            })
            ->toArray();

        // Set default values for all possible statuses
        $defaultStats = [
            'ops' => ['count' => 0, 'hours' => 0],
            'rsh' => ['count' => 0, 'hours' => 0],
            'fo' => ['count' => 0, 'hours' => 0],
            'mo' => ['count' => 0, 'hours' => 0],
            'po' => ['count' => 0, 'hours' => 0],
            'mb' => ['count' => 0, 'hours' => 0]
        ];

        // Merge with actual stats
        $machineStats = array_merge($defaultStats, $machineStats);

        // Get other required data
        $powerPlants = PowerPlant::with(['machines' => function($query) {
            $query->select('id', 'power_plant_id', 'name', 'status', 'capacity');
        }])->get();
        
        $machines = Machine::all();

        // Get operation schedules for today
        $operationSchedules = OperationSchedule::whereDate('schedule_date', $today)
            ->orderBy('start_time')
            ->get();
        \Log::info('DASHBOARD: Selesai proses render', [
            'total_time' => microtime(true) - $start
        ]);
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
