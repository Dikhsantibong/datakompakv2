<?php

namespace App\Http\Controllers;

use App\Models\PowerPlant; // Import model PowerPlant
use App\Models\DailySummary; // Import model DailySummary
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf; // Add this for PDF export
use Maatwebsite\Excel\Facades\Excel; // Add this for Excel export
use App\Exports\DailySummaryExport; // We'll create this class next
use Carbon\Carbon;

class DailySummaryController extends Controller
{
    public function index()
    {
        // Get unit source from session, request, or default to 'mysql'
        $unitSource = session('unit_source', request('unit_source', 'mysql'));

        // Base query for PowerPlant
        $query = PowerPlant::query();

        // Apply unit source filter if not viewing all units
        if ($unitSource !== 'all') {
            $query->where('unit_source', $unitSource);
        }

        // Get units ordered by source and name
        $units = $query->orderBy('unit_source')
            ->orderBy('name')
            ->with(['machines' => function($query) {
                $query->orderBy('name');
            }])
            ->get();

        // Get unique unit sources for dropdown
        $unitSources = PowerPlant::select('unit_source')
            ->distinct()
            ->pluck('unit_source')
            ->filter(); // Remove any null/empty values

        return view('admin.daily-summary.daily-summary', compact('units', 'unitSources', 'unitSource'));
    }

    public function store(Request $request)
    {
        try {
            $machineData = $request->input('data', []);
            $today = now()->format('Y-m-d');
            
            Log::info('Processing daily summary data', [
                'session' => session('unit', 'mysql'),
                'data_count' => count($machineData)
            ]);

            // Validasi format input
            $validator = Validator::make($machineData, [
                '*' => 'array',
                '*.power_plant_id' => 'required|exists:power_plants,id',
                '*.machine_name' => 'required|string|max:255',
                '*.installed_power' => 'nullable|numeric',
                '*.dmn_power' => 'nullable|numeric',
                '*.capable_power' => 'nullable|numeric',
                '*.peak_load_day' => 'nullable|numeric',
                '*.peak_load_night' => 'nullable|numeric',
                '*.kit_ratio' => 'nullable|numeric',
                '*.gross_production' => 'nullable|numeric',
                '*.net_production' => 'nullable|numeric',
                '*.aux_power' => 'nullable|numeric',
                '*.transformer_losses' => 'nullable|numeric',
                '*.usage_percentage' => 'nullable|numeric',
                '*.period_hours' => 'nullable|numeric',
                '*.operating_hours' => 'nullable|numeric',
                '*.standby_hours' => 'nullable|numeric',
                '*.planned_outage' => 'nullable|numeric',
                '*.maintenance_outage' => 'nullable|numeric',
                '*.forced_outage' => 'nullable|numeric',
                '*.trip_machine' => 'nullable|numeric',
                '*.trip_electrical' => 'nullable|numeric',
                '*.efdh' => 'nullable|numeric',
                '*.epdh' => 'nullable|numeric',
                '*.eudh' => 'nullable|numeric',
                '*.esdh' => 'nullable|numeric',
                '*.eaf' => 'nullable|numeric',
                '*.sof' => 'nullable|numeric',
                '*.efor' => 'nullable|numeric',
                '*.sdof' => 'nullable|numeric',
                '*.ncf' => 'nullable|numeric',
                '*.nof' => 'nullable|numeric',
                '*.jsi' => 'nullable|numeric',
                '*.hsd_fuel' => 'nullable|numeric',
                '*.b35_fuel' => 'nullable|numeric',
                '*.mfo_fuel' => 'nullable|numeric',
                '*.total_fuel' => 'nullable|numeric',
                '*.water_usage' => 'nullable|numeric',
                '*.meditran_oil' => 'nullable|numeric',
                '*.salyx_420' => 'nullable|numeric',
                '*.salyx_430' => 'nullable|numeric',
                '*.travolube_a' => 'nullable|numeric',
                '*.turbolube_46' => 'nullable|numeric',
                '*.turbolube_68' => 'nullable|numeric',
                '*.total_oil' => 'nullable|numeric',
                '*.sfc_scc' => 'nullable|numeric',
                '*.nphr' => 'nullable|numeric',
                '*.slc' => 'nullable|numeric',
                '*.notes' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', [
                    'errors' => $validator->errors()->toArray(),
                    'data' => $machineData
                ]);
                return redirect()->back()
                    ->with('error', 'Format input tidak valid!')
                    ->withErrors($validator)
                    ->withInput();
            }

            DB::beginTransaction();
            try {
                foreach ($machineData as $machineId => $data) {
                    // Filter data yang akan disimpan
                    $dataToSave = array_filter($data, function($value) {
                        return $value !== '' && $value !== null;
                    });

                    // Skip jika hanya ada power_plant_id dan machine_name
                    if (count($dataToSave) <= 2) {
                        continue;
                    }

                    // Tambahkan UUID dan unit_source
                    $powerPlant = PowerPlant::find($data['power_plant_id']);
                    if (!$powerPlant) {
                        throw new \Exception("Power Plant not found for id: {$data['power_plant_id']}");
                    }

                    // Calculate period hours
                    $firstDayOfMonth = now()->startOfMonth();
                    $lastMonthRecord = DailySummary::where('power_plant_id', $data['power_plant_id'])
                        ->where('machine_name', $data['machine_name'])
                        ->whereMonth('created_at', $firstDayOfMonth->copy()->subMonth()->month)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    $currentMonthRecord = DailySummary::where('power_plant_id', $data['power_plant_id'])
                        ->where('machine_name', $data['machine_name'])
                        ->whereMonth('created_at', now()->month)
                        ->orderBy('created_at', 'desc')
                        ->first();

                    // Calculate new period hours
                    if ($currentMonthRecord) {
                        // If we have records this month, increment by 24
                        $dataToSave['period_hours'] = $currentMonthRecord->period_hours + 24;
                    } else {
                        // If this is first record of the month, start from 24
                        $dataToSave['period_hours'] = 24;
                    }

                    $dataToSave['uuid'] = (string) Str::uuid();
                    $dataToSave['unit_source'] = session('unit', 'mysql');

                    // Cek record yang sudah ada
                    $existingRecord = DailySummary::where('power_plant_id', $data['power_plant_id'])
                        ->where('machine_name', $data['machine_name'])
                        ->whereDate('created_at', $today)
                        ->first();

                    if ($existingRecord) {
                        $existingRecord->update($dataToSave);
                        Log::info("Updated daily summary", [
                            'uuid' => $existingRecord->uuid,
                            'machine' => $data['machine_name']
                        ]);
                    } else {
                        DailySummary::create($dataToSave);
                        Log::info("Created new daily summary", [
                            'uuid' => $dataToSave['uuid'],
                            'machine' => $data['machine_name']
                        ]);
                    }
                }

                DB::commit();
                return redirect()->back()->with('success', 'Data berhasil disimpan!');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error saving daily summary:', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error in store method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function results(Request $request)
    {
        try {
            $date = $request->input('date', now()->format('Y-m-d'));

            $units = PowerPlant::with(['dailySummaries' => function($query) use ($date) {
                $query->whereDate('created_at', $date);
            }])->get();

            if ($request->ajax()) {
                return view('admin.daily-summary.daily-summary-results', compact('units', 'date'))->render();
            }

            return view('admin.daily-summary.daily-summary-results', compact('units', 'date'));

        } catch (\Exception $e) {
            Log::error('Error in results method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memuat data.'], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $date = $request->date;
            // Format tanggal untuk nama file
            $formattedDate = Carbon::parse($date)->format('d F Y');
            $fileName = "Ikhtisar Harian ({$formattedDate}).pdf";

            $units = PowerPlant::with(['machines', 'dailySummaries' => function($query) use ($date) {
                $query->whereDate('created_at', $date);
            }])->get();

            $pdf = Pdf::loadView('admin.daily-summary.pdf', [
                'date' => $date,
                'units' => $units
            ]);

            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Error in PDF export:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor PDF.');
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $date = $request->date;
            // Format tanggal untuk nama file
            $formattedDate = Carbon::parse($date)->format('d F Y');
            $fileName = "Ikhtisar Harian ({$formattedDate}).xlsx";

            $units = PowerPlant::with(['machines', 'dailySummaries' => function($query) use ($date) {
                $query->whereDate('created_at', $date);
            }])->get();

            return Excel::download(new DailySummaryExport($date, $units), $fileName);
        } catch (\Exception $e) {
            Log::error('Error in Excel export:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor Excel.');
        }
    }

    // Add method to handle unit source changes
    public function setUnitSource(Request $request)
    {
        $unitSource = $request->input('unit_source', 'mysql');
        
        // Store in session
        session(['unit_source' => $unitSource]);
        
        return response()->json([
            'success' => true,
            'message' => 'Unit source updated successfully'
        ]);
    }
} 