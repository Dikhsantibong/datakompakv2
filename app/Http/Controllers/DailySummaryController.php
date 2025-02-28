<?php

namespace App\Http\Controllers;

use App\Models\PowerPlant; // Import model PowerPlant
use App\Models\DailySummary; // Import model DailySummary
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            
            // Filter hanya data yang memiliki nilai
            $filledData = array_filter($machineData, function($data) {
                return !empty($data['installed_power']) || !empty($data['dmn_power']) || 
                       !empty($data['capable_power']) || !empty($data['peak_load_day']) || 
                       !empty($data['peak_load_night']) || !empty($data['kit_ratio']);
            });

            if (empty($filledData)) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data yang diisi untuk disimpan!')
                    ->withInput();
            }

            foreach ($filledData as $machineId => $data) {
                // Pastikan data yang dikirim sesuai dengan kolom database
                $dataToSave = [
                    'power_plant_id' => $data['power_plant_id'],
                    'machine_name' => $data['machine_name'],
                    'installed_power' => $data['installed_power'] ?? null,
                    'dmn_power' => $data['dmn_power'] ?? null,
                    'capable_power' => $data['capable_power'] ?? null,
                    'peak_load_day' => $data['peak_load_day'] ?? null,
                    'peak_load_night' => $data['peak_load_night'] ?? null,
                    'kit_ratio' => $data['kit_ratio'] ?? null,
                    'gross_production' => $data['gross_production'] ?? null,
                    'net_production' => $data['net_production'] ?? null,
                    'aux_power' => $data['aux_power'] ?? null,
                    'transformer_losses' => $data['transformer_losses'] ?? null,
                    'usage_percentage' => $data['usage_percentage'] ?? null,
                    'period_hours' => $data['period_hours'] ?? null,
                    'operating_hours' => $data['operating_hours'] ?? null,
                    'standby_hours' => $data['standby_hours'] ?? null,
                    'planned_outage' => $data['planned_outage'] ?? null,
                    'maintenance_outage' => $data['maintenance_outage'] ?? null,
                    'forced_outage' => $data['forced_outage'] ?? null,
                    'trip_machine' => $data['trip_machine'] ?? null,
                    'trip_electrical' => $data['trip_electrical'] ?? null,
                    'efdh' => $data['efdh'] ?? null,
                    'epdh' => $data['epdh'] ?? null,
                    'eudh' => $data['eudh'] ?? null,
                    'esdh' => $data['esdh'] ?? null,
                    'eaf' => $data['eaf'] ?? null,
                    'sof' => $data['sof'] ?? null,
                    'efor' => $data['efor'] ?? null,
                    'sdof' => $data['sdof'] ?? null,
                    'ncf' => $data['ncf'] ?? null,
                    'nof' => $data['nof'] ?? null,
                    'jsi' => $data['jsi'] ?? null,
                    'hsd_fuel' => $data['hsd_fuel'] ?? null,
                    'b35_fuel' => $data['b35_fuel'] ?? null,
                    'mfo_fuel' => $data['mfo_fuel'] ?? null,
                    'total_fuel' => $data['total_fuel'] ?? null,
                    'water_usage' => $data['water_usage'] ?? null,
                    'meditran_oil' => $data['meditran_oil'] ?? null,
                    'salyx_420' => $data['salyx_420'] ?? null,
                    'salyx_430' => $data['salyx_430'] ?? null,
                    'travolube_a' => $data['travolube_a'] ?? null,
                    'turbolube_46' => $data['turbolube_46'] ?? null,
                    'turbolube_68' => $data['turbolube_68'] ?? null,
                    'total_oil' => $data['total_oil'] ?? null,
                    'sfc_scc' => $data['sfc_scc'] ?? null,
                    'nphr' => $data['nphr'] ?? null,
                    'slc' => $data['slc'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ];

                // Konversi nilai string kosong menjadi null
                foreach ($dataToSave as $key => $value) {
                    if ($value === '') {
                        $dataToSave[$key] = null;
                    }
                }

                // Debug: Log data yang akan disimpan
                \Log::info('Data yang akan disimpan:', $dataToSave);

                // Simpan data
                DailySummary::create($dataToSave);
            }

            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Error saving daily summary: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function results()
    {
        // Group daily summaries by power plant
        $units = PowerPlant::with(['dailySummaries' => function($query) {
            // You might want to add date filtering here later
            $query->latest();
        }])->get();

        return view('admin.daily-summary.daily-summary-results', compact('units'));
    }
} 