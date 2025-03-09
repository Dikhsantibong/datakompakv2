<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\RencanaDayaMampu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RencanaDayaMampuController extends Controller
{
    public function index(Request $request)
    {
        // Get unit source from session or request
        $unitSource = session('unit') === 'mysql' ? 
            $request->get('unit_source', 'mysql') : 
            session('unit');

        // Get current month's data
        $currentMonth = now()->format('Y-m');
        
        // Get power plants with their machines and rencana daya mampu data
        $powerPlants = PowerPlant::when($unitSource !== 'mysql', function($query) use ($unitSource) {
            return $query->where('unit_source', $unitSource);
        })->with(['machines' => function($query) use ($currentMonth) {
            $query->orderBy('name')
                ->with(['rencanaDayaMampu' => function($query) use ($currentMonth) {
                    $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$currentMonth]);
                }]);
        }])->orderBy('name')->get();

        // Transform data to include daily values
        $powerPlants->each(function($plant) use ($currentMonth) {
            $plant->machines->each(function($machine) use ($currentMonth) {
                // Get the latest record for the month
                $record = $machine->rencanaDayaMampu->first();

                if ($record) {
                    // Set summary values as text
                    $machine->rencana = $record->rencana;
                    $machine->realisasi = $record->realisasi;
                    // Set numeric values
                    $machine->daya_pjbtl_silm = $record->daya_pjbtl_silm;
                    $machine->dmp_existing = $record->dmp_existing;

                    // Set daily values from JSON
                    $machine->daily_values = $record->getDailyData($currentMonth);
                }
            });
        });

        return view('admin.rencana-daya-mampu.index', compact('powerPlants', 'unitSource'));
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $currentDate = now()->format('Y-m-d');
            $unitSource = session('unit');

            // Process each machine's data
            foreach ($request->rencana ?? [] as $machineId => $rencanaValue) {
                $record = RencanaDayaMampu::firstOrNew([
                    'machine_id' => $machineId,
                    'tanggal' => $currentDate
                ]);

                // Basic data
                $record->rencana = trim($rencanaValue);
                $record->realisasi = trim($request->realisasi[$machineId] ?? '');
                $record->daya_pjbtl_silm = floatval($request->daya_pjbtl[$machineId] ?? 0);
                $record->dmp_existing = floatval($request->dmp_existing[$machineId] ?? 0);
                
                // Daily data if exists
                if ($request->daily_data) {
                    $dailyData = json_decode($request->daily_data, true);
                    if (isset($dailyData[$machineId])) {
                        $record->daily_data = $dailyData[$machineId];
                    }
                }

                $record->unit_source = $unitSource;
                $record->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'icon' => 'success',
                'title' => 'Berhasil!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('RencanaDayaMampu update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                'icon' => 'error',
                'title' => 'Error!'
            ], 500);
        }
    }

    // Helper method to get daily value
    public function getDayValue($machine, $day)
    {
        $date = Carbon::createFromFormat('Y-m-d', now()->format('Y-m-') . sprintf('%02d', $day));
        $record = $machine->rencanaDayaMampu->first();
        
        return $record ? $record->getDailyValue($date->format('Y-m-d'), 'rencana') : null;
    }

    public function manage(Request $request)
    {
        $unitSource = session('unit') === 'mysql' ? 
            $request->get('unit_source', 'mysql') : 
            session('unit');

        // Get selected month and year (default to current)
        $selectedMonth = $request->get('month', now()->format('m'));
        $selectedYear = $request->get('year', now()->format('Y'));
        $selectedDate = $selectedYear . '-' . $selectedMonth;

        // Get power plants with their machines and rencana daya mampu data
        $powerPlants = PowerPlant::when($unitSource !== 'mysql', function($query) use ($unitSource) {
            return $query->where('unit_source', $unitSource);
        })->with(['machines' => function($query) use ($selectedDate) {
            $query->orderBy('name')
                ->with(['rencanaDayaMampu' => function($query) use ($selectedDate) {
                    $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$selectedDate]);
                }]);
        }])->orderBy('name')->get();

        return view('admin.rencana-daya-mampu.manage', compact(
            'powerPlants', 
            'unitSource', 
            'selectedMonth', 
            'selectedYear'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'pdf');
        $month = $request->get('month', now()->format('m'));
        $year = $request->get('year', now()->format('Y'));
        
        // Logic untuk export akan ditambahkan nanti
        // Untuk sementara return response kosong
        return response()->json(['message' => 'Export feature coming soon']);
    }
} 