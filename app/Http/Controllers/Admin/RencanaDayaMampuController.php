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

            // Decode daily_data JSON
            $dailyData = json_decode($request->daily_data, true) ?? [];

            foreach ($request->all() as $key => $values) {
                if (in_array($key, ['_token', 'daily_data'])) continue;

                foreach ($values as $machineId => $value) {
                    $machine = Machine::findOrFail($machineId);
                    
                    // Get or create record
                    $record = RencanaDayaMampu::firstOrNew([
                        'machine_id' => $machineId,
                        'tanggal' => now()->format('Y-m-d')
                    ]);

                    // Update text values
                    if ($key === 'rencana') $record->rencana = $value;
                    if ($key === 'realisasi') $record->realisasi = $value;
                    
                    // Update numeric values
                    if ($key === 'daya_pjbtl') $record->daya_pjbtl_silm = floatval($value);
                    if ($key === 'dmp_existing') $record->dmp_existing = floatval($value);

                    // Update daily values
                    if (isset($dailyData[$machineId])) {
                        $record->daily_data = $dailyData[$machineId];
                    }

                    $record->unit_source = session('unit');
                    $record->updateSummary();
                    $record->save();
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
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
} 