<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailySummary;
use App\Models\PowerPlant;
use Illuminate\Http\Request;

class MonitorKinerjaController extends Controller
{
    public function index()
    {
        // Get selected unit source from request
        $selectedUnitSource = request('unit_source');
        
        // Get all power plants for the filter dropdown
        $powerPlants = PowerPlant::all();

        // Base query for daily summaries
        $query = DailySummary::query();
        
        // Jika filter unit_source dipilih dan tidak kosong, filter berdasarkan unit_source
        if (!empty($selectedUnitSource)) {
            $query->where('unit_source', $selectedUnitSource);
        }

        // Get the latest daily summary based on filter
        $latestSummary = $query->latest()->first();

        // Get data for the chart (last 7 days) with unit filter
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
                'b35' => [],
                'b40' => []
            ],
            'kit_ratio' => [],
            'usage_percentage' => [],
            'water_usage' => [],
            'trip_machine' => [],
            'trip_electrical' => [],
            'efdh' => [],
            'epdh' => [],
            'eudh' => [],
            'esdh' => [],
            'jsi' => [],
        ];

        // Fetch last 7 days data with unit filter
        $dailyData = $query->select(
            'eaf', 'sof', 'efor', 'ncf', 
            'net_production', 'hsd_fuel', 
            'mfo_fuel', 'b35_fuel', 'b40_fuel',
            'kit_ratio', 'usage_percentage', 'water_usage',
            'trip_machine', 'trip_electrical',
            'efdh', 'epdh', 'eudh', 'esdh', 'jsi',
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
            $chartData['fuel']['b40'][] = $data->b40_fuel;
            $chartData['kit_ratio'][] = $data->kit_ratio;
            $chartData['usage_percentage'][] = $data->usage_percentage;
            $chartData['water_usage'][] = $data->water_usage;
            $chartData['trip_machine'][] = $data->trip_machine;
            $chartData['trip_electrical'][] = $data->trip_electrical;
            $chartData['efdh'][] = $data->efdh;
            $chartData['epdh'][] = $data->epdh;
            $chartData['eudh'][] = $data->eudh;
            $chartData['esdh'][] = $data->esdh;
            $chartData['jsi'][] = $data->jsi;
        }

        // Ambil semua summary, bisa difilter sesuai kebutuhan (misal: 30 hari terakhir)
        $dailySummaries = DailySummary::orderBy('date', 'desc')->get();

        // Prepare data for the view
        $data = [
            'performance' => [
                'eaf' => $latestSummary->eaf ?? 0,
                'sof' => $latestSummary->sof ?? 0,
                'efor' => $latestSummary->efor ?? 0,
                'sdof' => $latestSummary->sdof ?? 0,
                'ncf' => $latestSummary->ncf ?? 0,
                'kit_ratio' => $latestSummary->kit_ratio ?? 0,
                'usage_percentage' => $latestSummary->usage_percentage ?? 0,
                'jsi' => $latestSummary->jsi ?? 0,
            ],
            'operatingStats' => [
                'operating_hours' => $latestSummary->operating_hours ?? 0,
                'standby_hours' => $latestSummary->standby_hours ?? 0,
                'planned_outage' => $latestSummary->planned_outage ?? 0,
                'maintenance_outage' => $latestSummary->maintenance_outage ?? 0,
                'period_hours' => $latestSummary->period_hours ?? 0,
                'forced_outage' => $latestSummary->forced_outage ?? 0,
                'efdh' => $latestSummary->efdh ?? 0,
                'epdh' => $latestSummary->epdh ?? 0,
                'eudh' => $latestSummary->eudh ?? 0,
                'esdh' => $latestSummary->esdh ?? 0,
            ],
            'productionStats' => [
                'gross_production' => $latestSummary->gross_production ?? 0,
                'net_production' => $latestSummary->net_production ?? 0,
                'peak_load_day' => $latestSummary->peak_load_day ?? 0,
                'peak_load_night' => $latestSummary->peak_load_night ?? 0,
                'aux_power' => $latestSummary->aux_power ?? 0,
                'transformer_losses' => $latestSummary->transformer_losses ?? 0,
            ],
            'fuelUsage' => [
                'hsd' => $latestSummary->hsd_fuel ?? 0,
                'b35' => $latestSummary->b35_fuel ?? 0,
                'b40' => $latestSummary->b40_fuel ?? 0,
                'mfo' => $latestSummary->mfo_fuel ?? 0,
                'total' => $latestSummary->total_fuel ?? 0,
                'water' => $latestSummary->water_usage ?? 0,
            ],
            'oilUsage' => [
                'meditran' => $latestSummary->meditran_oil ?? 0,
                'salyx_420' => $latestSummary->salyx_420 ?? 0,
                'salyx_430' => $latestSummary->salyx_430 ?? 0,
                'travolube_a' => $latestSummary->travolube_a ?? 0,
                'turbolube_46' => $latestSummary->turbolube_46 ?? 0,
                'turbolube_68' => $latestSummary->turbolube_68 ?? 0,
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
            'powerPlants' => $powerPlants,
            'selectedUnitSource' => $selectedUnitSource,
            'dailySummaries' => $dailySummaries,
        ];

        return view('admin.monitor-kinerja.index', $data);
    }
} 