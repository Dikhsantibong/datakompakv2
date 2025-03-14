<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailySummary;
use App\Models\PowerPlant;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data unit dan mesin
        $powerPlants = PowerPlant::with(['machines' => function($query) {
            $query->select('id', 'power_plant_id', 'name', 'status', 'capacity');
        }])->get();

        $machines = Machine::all();

        // Hitung statistik pembangkit
        $totalCapacity = $machines->sum('capacity');
        $runningCapacity = $machines->where('status', 'RUNNING')->sum('capacity');
        $capacityPercentage = $totalCapacity > 0 ? ($runningCapacity / $totalCapacity) * 100 : 0;

        // Hitung efisiensi pembangkit (contoh perhitungan)
        $dailySummaries = DailySummary::whereDate('created_at', Carbon::today())->get();
        $efficiencyPercentage = $dailySummaries->avg('efficiency') ?? 78; // Default 78% jika tidak ada data

        // Hitung ketersediaan unit
        $totalMachines = $machines->count();
        $availableMachines = $machines->whereIn('status', ['RUNNING', 'STANDBY'])->count();
        $availabilityPercentage = $totalMachines > 0 ? ($availableMachines / $totalMachines) * 100 : 0;

        // Ambil aktivitas terkini (contoh dari status log)
        $recentActivities = DB::table('machine_status_logs')
            ->join('machines', 'machine_status_logs.machine_id', '=', 'machines.id')
            ->join('power_plants', 'machines.power_plant_id', '=', 'power_plants.id')
            ->select(
                'power_plants.name as plant_name',
                'machine_status_logs.status',
                'machine_status_logs.created_at'
            )
            ->orderBy('machine_status_logs.created_at', 'desc')
            ->limit(4)
            ->get()
            ->map(function($activity) {
                return [
                    'message' => $this->formatActivityMessage($activity->plant_name, $activity->status),
                    'status' => $activity->status,
                    'time' => Carbon::parse($activity->created_at)->diffForHumans()
                ];
            });

        // Ambil data summary
        $totalNetProduction = $dailySummaries->sum('total_net_production') ?? 0;
        $totalGrossProduction = $dailySummaries->sum('total_gross_production') ?? 0;
        $peakLoad = $dailySummaries->sum('total_peak_load') ?? 0;
        $totalOperatingHours = $dailySummaries->sum('total_operating_hours') ?? 0;

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'powerPlants',
            'machines',
            'totalNetProduction',
            'totalGrossProduction',
            'peakLoad',
            'totalOperatingHours',
            'capacityPercentage',
            'efficiencyPercentage',
            'availabilityPercentage',
            'recentActivities'
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
