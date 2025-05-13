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
        try {
            // Get unit source from session
            $unitSource = session('unit', 'mysql');

            // Get input date from request or default to today
            $inputDate = request('input_date', now()->format('Y-m-d'));

            // Base query for PowerPlant
            $query = PowerPlant::query();

            // If logged in as UP KENDARI (mysql session)
            if ($unitSource === 'mysql') {
                // Allow filtering by unit source from request
                $selectedUnitSource = request('unit_source', 'all');
                if ($selectedUnitSource !== 'all') {
                    $query->where('unit_source', $selectedUnitSource);
                }
            } else {
                // For other units, only show their own data
                $query->where('unit_source', $unitSource);
            }

            // Get units ordered by source and name
            $units = $query->orderBy('unit_source')
                ->orderBy('name')
                ->with(['machines' => function($query) {
                    $query->orderBy('name');
                }])
                ->get();

            // Get existing data for the selected date
            $existingData = DailySummary::whereDate('date', $inputDate)
                ->get()
                ->keyBy(function($item) {
                    return $item->power_plant_id . '_' . $item->machine_name;
                });

            // Get unique unit sources for dropdown only if logged in as UP KENDARI
            $unitSources = [];
            if ($unitSource === 'mysql') {
                $unitSources = PowerPlant::select('unit_source')
                    ->distinct()
                    ->pluck('unit_source')
                    ->filter(); // Remove any null/empty values
            }

            // If this is a refresh request, show success message
            if (request('refresh')) {
                session()->flash('success', 'Data berhasil diperbarui!');
            }

            return view('admin.daily-summary.daily-summary', compact('units', 'unitSources', 'unitSource', 'inputDate', 'existingData'));
        } catch (\Exception $e) {
            Log::error('Error in index method:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function store(Request $request)
    {
        try {
            $machineData = $request->input('data', []);
            $inputDate = $request->input('input_date', now()->format('Y-m-d'));
            
            Log::info('Processing daily summary data', [
                'session' => session('unit', 'mysql'),
                'data_count' => count($machineData),
                'input_date' => $inputDate
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
                    // Skip if no significant data is entered
                    if (!$this->hasSignificantData($data)) {
                        continue;
                    }

                    // Prepare base data with only required fields
                    $dataToSave = [
                        'power_plant_id' => $data['power_plant_id'],
                        'machine_name' => $data['machine_name'],        
                        'uuid' => (string) Str::uuid(),
                        'unit_source' => session('unit', 'mysql'),
                        'date' => $inputDate,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];

                    // Add only the fields that have actual input
                    foreach ($data as $key => $value) {
                        if (!in_array($key, ['power_plant_id', 'machine_name']) && 
                            $value !== null && $value !== '') {
                            $dataToSave[$key] = $value;
                        }
                    }

                    // Check for existing record
                    $existingRecord = DailySummary::where('power_plant_id', $data['power_plant_id'])
                        ->where('machine_name', $data['machine_name'])
                        ->whereDate('date', $inputDate)
                        ->first();

                    try {
                        if ($existingRecord) {
                            $existingRecord->update($dataToSave);
                            Log::info("Updated daily summary", [
                                'uuid' => $existingRecord->uuid,
                                'machine' => $data['machine_name'],
                                'date' => $inputDate
                            ]);
                        } else {
                            DailySummary::create($dataToSave);
                            Log::info("Created new daily summary", [
                                'uuid' => $dataToSave['uuid'],
                                'machine' => $data['machine_name'],
                                'date' => $inputDate
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error saving daily summary", [
                            'message' => $e->getMessage(),
                            'data' => $dataToSave
                        ]);
                        throw $e;
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

    /**
     * Check if the machine data has any significant input
     * 
     * @param array $data
     * @return bool
     */
    private function hasSignificantData($data)
    {
        // Fields to check for significant data
        $significantFields = [
            'installed_power',
            'dmn_power',
            'capable_power',
            'peak_load_day',
            'peak_load_night',
            'kit_ratio',
            'gross_production',
            'net_production',
            'aux_power',
            'transformer_losses',
            'usage_percentage',
            'operating_hours',
            'standby_hours',
            'planned_outage',
            'maintenance_outage',
            'forced_outage',
            'trip_machine',
            'trip_electrical'
        ];

        foreach ($significantFields as $field) {
            if (isset($data[$field]) && $data[$field] !== '' && $data[$field] !== null) {
                return true;
            }
        }

        return false;
    }

    public function results(Request $request)
    {
        try {
            $date = $request->input('date', now()->format('Y-m-d'));
            $search = $request->input('search');

            // Get unit source from session
            $unitSource = session('unit', 'mysql');

            // Base query for PowerPlant
            $query = PowerPlant::query();

            // If logged in as UP KENDARI (mysql session)
            if ($unitSource === 'mysql') {
                // Allow filtering by unit source from request
                $selectedUnitSource = request('unit_source', 'all');
                if ($selectedUnitSource !== 'all') {
                    $query->where('unit_source', $selectedUnitSource);
                }
            } else {
                // For other units, only show their own data
                $query->where('unit_source', $unitSource);
            }

            // Get unique unit sources for dropdown only if logged in as UP KENDARI
            $unitSources = [];
            if ($unitSource === 'mysql') {
                $unitSources = PowerPlant::select('unit_source')
                    ->distinct()
                    ->pluck('unit_source')
                    ->filter();
            }

            // Add search functionality if search parameter is provided
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhereHas('machines', function($q) use ($search) {
                          $q->where('name', 'like', '%' . $search . '%');
                      });
                });
            }

            $units = $query->with(['dailySummaries' => function($query) use ($date) {
                $query->whereDate('date', $date);
            }])->get();

            if ($request->ajax()) {
                return view('admin.daily-summary.daily-summary-results', compact('units', 'date', 'unitSources', 'unitSource'))->render();
            }

            return view('admin.daily-summary.daily-summary-results', compact('units', 'date', 'unitSources', 'unitSource'));

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
                $query->whereDate('date', $date);
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
                $query->whereDate('date', $date);
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