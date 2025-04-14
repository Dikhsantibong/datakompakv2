<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailySummary;
use Illuminate\Http\Request;

class MonitorKinerjaController extends Controller
{
    public function index()
    {
        // Get the latest daily summary
        $latestSummary = DailySummary::latest()->first();

        // Get data for the chart (last 7 days)
        $chartData = [
            'labels' => [],
            'eaf' => [],
            'sof' => [],
            'efor' => [],
            'ncf' => [],
            'production' => [],
            'fuel' => [
                'hsd' => [],
                'mfo' => [],
                'b35' => []
            ]
        ];

        // Fetch last 7 days data
        $dailyData = DailySummary::select(
            'eaf', 'sof', 'efor', 'ncf', 
            'net_production', 'hsd_fuel', 
            'mfo_fuel', 'b35_fuel', 
            'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->take(7)
        ->get()
        ->reverse();

        foreach ($dailyData as $data) {
            $chartData['labels'][] = $data->created_at->format('D');
            $chartData['eaf'][] = $data->eaf;
            $chartData['sof'][] = $data->sof;
            $chartData['efor'][] = $data->efor;
            $chartData['ncf'][] = $data->ncf;
            $chartData['production'][] = $data->net_production;
            $chartData['fuel']['hsd'][] = $data->hsd_fuel;
            $chartData['fuel']['mfo'][] = $data->mfo_fuel;
            $chartData['fuel']['b35'][] = $data->b35_fuel;
        }

        // Prepare data for the view
        $data = [
            'performance' => [
                'eaf' => $latestSummary->eaf ?? 0,
                'sof' => $latestSummary->sof ?? 0,
                'efor' => $latestSummary->efor ?? 0,
                'sdof' => $latestSummary->sdof ?? 0,
                'ncf' => $latestSummary->ncf ?? 0,
            ],
            'operatingStats' => [
                'operating_hours' => $latestSummary->operating_hours ?? 0,
                'standby_hours' => $latestSummary->standby_hours ?? 0,
                'planned_outage' => $latestSummary->planned_outage ?? 0,
                'maintenance_outage' => $latestSummary->maintenance_outage ?? 0,
            ],
            'productionStats' => [
                'gross_production' => $latestSummary->gross_production ?? 0,
                'net_production' => $latestSummary->net_production ?? 0,
                'peak_load_day' => $latestSummary->peak_load_day ?? 0,
                'peak_load_night' => $latestSummary->peak_load_night ?? 0,
            ],
            'fuelUsage' => [
                'hsd' => $latestSummary->hsd_fuel ?? 0,
                'b35' => $latestSummary->b35_fuel ?? 0,
                'mfo' => $latestSummary->mfo_fuel ?? 0,
                'total' => $latestSummary->total_fuel ?? 0,
            ],
            'oilUsage' => [
                'meditran' => $latestSummary->meditran_oil ?? 0,
                'salyx_420' => $latestSummary->salyx_420 ?? 0,
                'salyx_430' => $latestSummary->salyx_430 ?? 0,
                'total' => $latestSummary->total_oil ?? 0,
            ],
            'technicalParams' => [
                'sfc_scc' => $latestSummary->sfc_scc ?? 0,
                'nphr' => $latestSummary->nphr ?? 0,
                'slc' => $latestSummary->slc ?? 0,
            ],
            'transformerLosses' => [
                'current' => $latestSummary->transformer_losses ?? 0,
                'unit' => 'kWh',
                'description' => 'Susut Trafo'
            ],
            'chartData' => $chartData,
        ];

        return view('admin.monitor-kinerja.index', $data);
    }
} 