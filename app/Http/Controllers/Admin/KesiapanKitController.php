<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;
use App\Models\MachineLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KesiapanKitController extends Controller
{
    public function index(Request $request)
    {
        try {
            $date = $request->date ?? now()->format('Y-m-d');
            $specificTimes = ['06:00:00', '11:00:00', '14:00:00', '18:00:00', '19:00:00'];
            
            // Get all power plants for the filter dropdown
            $allPowerPlants = PowerPlant::orderBy('name')->get();
            
            // Build query for filtered power plants
            $powerPlantsQuery = PowerPlant::with(['machines' => function ($query) {
                $query->orderBy('name');
            }]);

            // Apply power plant filter if specified
            if ($request->filled('power_plant_id')) {
                $powerPlantsQuery->where('id', $request->power_plant_id);
            }

            $powerPlants = $powerPlantsQuery->get();

            // Load the latest logs for each power plant and machine on the selected date
            $powerPlants->each(function ($powerPlant) use ($date, $request, $specificTimes) {
                // Get power plant logs
                $latestLogQuery = DB::table('power_plant_logs')
                    ->where('power_plant_id', $powerPlant->id)
                    ->where('date', $date);
                
                if ($request->filled('time') && in_array($request->time, $specificTimes)) {
                    $latestLogQuery->where('time', $request->time);
                }
                
                $latestLog = $latestLogQuery->orderBy('time', 'desc')->first();

                $powerPlant->hop = $latestLog?->hop;
                $powerPlant->tma = $latestLog?->tma;
                $powerPlant->inflow = $latestLog?->inflow;

                // Get machine logs
                $powerPlant->machines->each(function ($machine) use ($date, $request, $specificTimes) {
                    $logQuery = $machine->logs()
                        ->where('date', $date);
                    
                    if ($request->filled('time') && in_array($request->time, $specificTimes)) {
                        $logQuery->where('time', $request->time);
                    } else {
                        $logQuery->whereIn('time', $specificTimes);
                    }
                    
                    $latestLog = $logQuery->orderBy('time', 'desc')->first();
                    
                    $machine->kw = $latestLog?->kw;
                    $machine->status = $latestLog?->status;
                    $machine->keterangan = $latestLog?->keterangan;
                    $machine->daya_terpasang = $latestLog?->daya_terpasang;
                    $machine->silm_slo = $latestLog?->silm_slo;
                    $machine->dmp_performance = $latestLog?->dmp_performance;
                    $machine->log_time = $latestLog?->time;
                });
            });

            if ($request->ajax()) {
                return view('admin.kesiapan-kit._table', compact('powerPlants', 'date'))->render();
            }

            return view('admin.kesiapan-kit.index', compact('powerPlants', 'allPowerPlants', 'date', 'specificTimes'));
        } catch (\Exception $e) {
            Log::error('Error in KesiapanKit index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memuat data'], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }
} 