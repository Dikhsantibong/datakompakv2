<?php

namespace App\Http\Controllers;

use App\Models\PowerPlant; // Import model PowerPlant
use App\Models\DailySummary; // Import model DailySummary
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DailySummaryController extends Controller
{
    public function index()
    {
        // Ambil data unit dari PowerPlant
        $units = PowerPlant::with('machines')->get(); // Tampilkan semua unit beserta mesin yang terkait

        // Logika untuk menampilkan ikhtisar harian
        return view('admin.daily-summary.daily-summary', compact('units')); // Kirim data unit ke view
    }

    public function store(Request $request)
    {
        try {
            $machineData = $request->input('data', []);
            $today = now()->format('Y-m-d');
            
            // Debug log untuk melihat data yang diterima
            Log::info('Received raw data:', ['data' => $machineData]);

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
                    ->with('error', 'Format input tidak valid! Pastikan semua data diisi dengan benar.')
                    ->withErrors($validator)
                    ->withInput();
            }

            foreach ($machineData as $index => $data) {
                // Filter data yang akan disimpan (hanya yang memiliki nilai)
                $dataToSave = array_filter($data, function($value) {
                    return $value !== '' && $value !== null;
                });

                // Konversi string ke float untuk field numerik
                foreach ($dataToSave as $key => $value) {
                    if ($key !== 'power_plant_id' && $key !== 'machine_name' && $key !== 'notes') {
                        $dataToSave[$key] = floatval($value);
                    }
                }

                // Cek apakah ada data yang perlu disimpan
                if (count($dataToSave) <= 2) { // Hanya ada power_plant_id dan machine_name
                    continue; // Skip jika tidak ada data numerik yang diisi
                }

                try {
                    // Cek record yang sudah ada
                    $existingRecord = DailySummary::where('power_plant_id', $data['power_plant_id'])
                        ->where('machine_name', $data['machine_name'])
                        ->whereDate('created_at', $today)
                        ->first();

                    if ($existingRecord) {
                        $existingRecord->update($dataToSave);
                        Log::info("Updated record for machine {$data['machine_name']}", $dataToSave);
                    } else {
                        DailySummary::create($dataToSave);
                        Log::info("Created new record for machine {$data['machine_name']}", $dataToSave);
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing machine {$data['machine_name']}: " . $e->getMessage());
                    throw $e;
                }
            }

            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            Log::error('Error in store method: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'data' => $request->all()
            ]);
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function results(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $units = PowerPlant::with(['dailySummaries' => function($query) use ($date) {
            $query->whereDate('created_at', $date);
        }])->get();

        if ($request->ajax()) {
            return view('admin.daily-summary.daily-summary-results', compact('units', 'date'))->render();
        }

        return view('admin.daily-summary.daily-summary-results', compact('units', 'date'));
    }
} 