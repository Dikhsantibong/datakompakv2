<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailySummary;
use App\Models\PowerPlant;

use Illuminate\Support\Facades\DB;
use App\Models\OperationSchedule;

class DashboardController extends Controller
{
    public function index()
    {
        // Logging semua query dan waktu eksekusinya
        \DB::listen(function ($query) {
            \Log::info('QUERY', [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time // waktu dalam milidetik
            ]);
        });
        $start = microtime(true);
        \Log::info('DASHBOARD: Mulai proses render', ['time' => $start]);
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

        // Get other required data
        $powerPlants = PowerPlant::all();

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


}
