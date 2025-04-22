<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringDatakompakController extends Controller
{
    public function index()
    {
        // Get all power plants and their status
        $powerPlants = [
            ['id' => 2, 'name' => 'PLTU MORAMO ( Subsistem Kendari )', 'last_update' => Carbon::now()->subHours(3), 'status' => 'pending'],
            ['id' => 3, 'name' => 'PLTD Wua Wua', 'last_update' => Carbon::now()->subHours(1), 'status' => 'completed'],
            ['id' => 4, 'name' => 'PLTD Poasia', 'last_update' => Carbon::now()->subHours(5), 'status' => 'pending'],
            ['id' => 5, 'name' => 'PLTD Poasia Containerized', 'last_update' => Carbon::now()->subHours(2), 'status' => 'completed'],
            ['id' => 7, 'name' => 'PLTD Kolaka', 'last_update' => Carbon::now()->subHours(6), 'status' => 'overdue'],
            ['id' => 8, 'name' => 'PLTD Lanipa Nipa', 'last_update' => Carbon::now()->subHours(1), 'status' => 'completed'],
            ['id' => 9, 'name' => 'PLTD Ladumpi', 'last_update' => Carbon::now()->subHours(7), 'status' => 'overdue'],
            ['id' => 10, 'name' => 'PLTM Sabilambo', 'last_update' => Carbon::now()->subHours(2), 'status' => 'completed'],
            ['id' => 11, 'name' => 'PLTM Mikuasi', 'last_update' => Carbon::now()->subHours(4), 'status' => 'pending'],
            ['id' => 12, 'name' => 'PLTD Bau Bau', 'last_update' => Carbon::now()->subHours(1), 'status' => 'completed'],
            ['id' => 13, 'name' => 'PLTD Pasarwajo', 'last_update' => Carbon::now()->subHours(8), 'status' => 'overdue'],
            ['id' => 15, 'name' => 'PLTM Winning', 'last_update' => Carbon::now()->subHours(2), 'status' => 'completed'],
            ['id' => 17, 'name' => 'PLTD Raha', 'last_update' => Carbon::now()->subHours(3), 'status' => 'pending'],
            ['id' => 18, 'name' => 'PLTD WANGI-WANGI', 'last_update' => Carbon::now()->subHours(1), 'status' => 'completed'],
            ['id' => 19, 'name' => 'PLTD LANGARA', 'last_update' => Carbon::now()->subHours(9), 'status' => 'overdue'],
            ['id' => 20, 'name' => 'PLTD EREKE', 'last_update' => Carbon::now()->subHours(2), 'status' => 'completed'],
            ['id' => 26, 'name' => 'PLTMG KENDARI', 'last_update' => Carbon::now()->subHours(4), 'status' => 'pending'],
            ['id' => 29, 'name' => 'PLTU BARUTA', 'last_update' => Carbon::now()->subHours(1), 'status' => 'completed'],
            ['id' => 30, 'name' => 'PLTMG BAU-BAU', 'last_update' => Carbon::now()->subHours(5), 'status' => 'pending'],
            ['id' => 31, 'name' => 'UP KENDARI', 'last_update' => Carbon::now()->subHours(2), 'status' => 'completed'],
            ['id' => 32, 'name' => 'PLTM RONGI', 'last_update' => Carbon::now()->subHours(10), 'status' => 'overdue']
        ];

        // Group plants by status
        $completedUnits = collect($powerPlants)->where('status', 'completed')->values();
        $pendingUnits = collect($powerPlants)->where('status', 'pending')->values();
        $overdueUnits = collect($powerPlants)->where('status', 'overdue')->values();

        // Calculate statistics
        $stats = [
            'total_units' => count($powerPlants),
            'completed' => $completedUnits->count(),
            'pending' => $pendingUnits->count(),
            'overdue' => $overdueUnits->count(),
            'completion_rate' => round(($completedUnits->count() / count($powerPlants)) * 100, 1)
        ];

        // Group by type for chart
        $unitTypes = [
            'PLTD' => collect($powerPlants)->filter(function($plant) { return str_contains($plant['name'], 'PLTD'); })->count(),
            'PLTM' => collect($powerPlants)->filter(function($plant) { return str_contains($plant['name'], 'PLTM'); })->count(),
            'PLTU' => collect($powerPlants)->filter(function($plant) { return str_contains($plant['name'], 'PLTU'); })->count(),
            'PLTMG' => collect($powerPlants)->filter(function($plant) { return str_contains($plant['name'], 'PLTMG'); })->count(),
            'Other' => collect($powerPlants)->filter(function($plant) { 
                return !str_contains($plant['name'], 'PLTD') && 
                       !str_contains($plant['name'], 'PLTM') && 
                       !str_contains($plant['name'], 'PLTU') && 
                       !str_contains($plant['name'], 'PLTMG');
            })->count(),
        ];

        // System performance stats
        $systemStats = [
            'uptime' => '99.8%',
            'avg_response' => '1.2s',
            'total_inputs_today' => rand(50, 200),
            'active_users' => rand(10, 30)
        ];

        return view('admin.monitoring-datakompak', compact(
            'powerPlants',
            'completedUnits',
            'pendingUnits',
            'overdueUnits',
            'stats',
            'unitTypes',
            'systemStats'
        ));
    }
} 