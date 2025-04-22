<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonitoringDatakompakController extends Controller
{
    public function index()
    {
        // Dummy data for website performance
        $websiteStats = [
            'visitors_today' => rand(100, 1000),
            'page_views' => rand(500, 5000),
            'avg_load_time' => rand(1, 5) . '.' . rand(0, 9) . 's',
            'uptime_percentage' => rand(98, 100) . '.' . rand(0, 9) . '%',
        ];

        // Dummy data for incomplete pages
        $incompletePages = [
            [
                'name' => 'Meeting Shift',
                'last_update' => Carbon::now()->subHours(rand(1, 24))->format('Y-m-d H:i:s'),
                'status' => 'pending',
                'responsible' => 'Operator Shift A'
            ],
            [
                'name' => 'Laporan Abnormal',
                'last_update' => Carbon::now()->subHours(rand(1, 24))->format('Y-m-d H:i:s'),
                'status' => 'pending',
                'responsible' => 'Operator Shift B'
            ],
            [
                'name' => 'Data FLM',
                'last_update' => Carbon::now()->subHours(rand(1, 24))->format('Y-m-d H:i:s'),
                'status' => 'pending',
                'responsible' => 'Supervisor'
            ],
        ];

        // Dummy data for hourly activity
        $hourlyActivity = [];
        for ($i = 0; $i < 24; $i++) {
            $hourlyActivity[] = [
                'hour' => str_pad($i, 2, '0', STR_PAD_LEFT) . ':00',
                'activity_count' => rand(10, 100)
            ];
        }

        // Dummy data for user activity
        $userActivity = [
            'total_users' => rand(50, 200),
            'active_users' => rand(10, 50),
            'inactive_users' => rand(5, 20),
        ];

        return view('admin.monitoring-datakompak', compact(
            'websiteStats',
            'incompletePages',
            'hourlyActivity',
            'userActivity'
        ));
    }
} 