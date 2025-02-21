<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use App\Models\DailySummary;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil semua data dari DailySummary
        $dailySummaries = DailySummary::all();

        // Ambil nilai dari hasil query
        $totalNetProduction = $dailySummaries->sum('total_net_production') ?? 0;
        $totalGrossProduction = $dailySummaries->sum('total_gross_production') ?? 0;
        $peakLoad = $dailySummaries->sum('total_peak_load') ?? 0;
        $totalOperatingHours = $dailySummaries->sum('total_operating_hours') ?? 0;

        // Kirim data ke view
        return view('admin.dashboard', compact(
            'totalNetProduction',
            'totalGrossProduction',
            'peakLoad',
            'totalOperatingHours',
            'dailySummaries'
        ));
    }
}
