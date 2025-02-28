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
                return !empty($data['installed_power']);
            });

            if (empty($filledData)) {
                return redirect()->back()
                    ->with('error', 'Tidak ada data yang diisi untuk disimpan!')
                    ->withInput();
            }

            foreach ($filledData as $machineId => $data) {
                // Pastikan semua nilai numerik dikonversi dengan benar
                $numericFields = [
                    'installed_power', 'dmn_power', 'capable_power', 
                    'peak_load_day', 'peak_load_night', 'kit_ratio',
                    'gross_production', 'net_production', 'aux_power',
                    'transformer_losses', 'usage_percentage', 'period_hours',
                    'operating_hours', 'standby_hours', 'planned_outage',
                    'maintenance_outage', 'forced_outage', 'trip_machine',
                    'trip_electrical', 'efdh', 'epdh', 'eudh', 'esdh',
                    'eaf', 'sof', 'efor', 'sdof', 'ncf', 'nof', 'jsi',
                    'hsd_fuel', 'b35_fuel', 'mfo_fuel', 'total_fuel',
                    'water_usage', 'meditran_oil', 'salyx_420', 'salyx_430',
                    'travolube_a', 'turbolube_46', 'turbolube_68', 'total_oil',
                    'sfc_scc', 'nphr', 'slc'
                ];

                foreach ($numericFields as $field) {
                    if (isset($data[$field])) {
                        $data[$field] = is_numeric($data[$field]) ? (float)$data[$field] : null;
                    } else {
                        $data[$field] = null;
                    }
                }

                // Validasi data
                $validator = Validator::make(['data' => [$machineId => $data]], [
                    "data.{$machineId}.power_plant_id" => 'required|exists:power_plants,id',
                    "data.{$machineId}.machine_name" => 'required|string|max:255',
                    "data.{$machineId}.installed_power" => 'required|numeric',
                    "data.{$machineId}.dmn_power" => 'nullable|numeric',
                    "data.{$machineId}.capable_power" => 'nullable|numeric',
                    "data.{$machineId}.peak_load_day" => 'nullable|numeric',
                    "data.{$machineId}.peak_load_night" => 'nullable|numeric',
                    "data.{$machineId}.kit_ratio" => 'nullable|numeric',
                    "data.{$machineId}.gross_production" => 'nullable|numeric',
                    "data.{$machineId}.net_production" => 'nullable|numeric',
                    "data.{$machineId}.aux_power" => 'nullable|numeric',
                    "data.{$machineId}.transformer_losses" => 'nullable|numeric',
                    "data.{$machineId}.usage_percentage" => 'nullable|numeric',
                    "data.{$machineId}.period_hours" => 'nullable|numeric',
                    "data.{$machineId}.operating_hours" => 'nullable|numeric',
                    "data.{$machineId}.standby_hours" => 'nullable|numeric',
                    "data.{$machineId}.planned_outage" => 'nullable|numeric',
                    "data.{$machineId}.maintenance_outage" => 'nullable|numeric',
                    "data.{$machineId}.forced_outage" => 'nullable|numeric',
                    "data.{$machineId}.trip_machine" => 'nullable|numeric',
                    "data.{$machineId}.trip_electrical" => 'nullable|numeric',
                    "data.{$machineId}.efdh" => 'nullable|numeric',
                    "data.{$machineId}.epdh" => 'nullable|numeric',
                    "data.{$machineId}.eudh" => 'nullable|numeric',
                    "data.{$machineId}.esdh" => 'nullable|numeric',
                    "data.{$machineId}.eaf" => 'nullable|numeric',
                    "data.{$machineId}.sof" => 'nullable|numeric',
                    "data.{$machineId}.efor" => 'nullable|numeric',
                    "data.{$machineId}.sdof" => 'nullable|numeric',
                    "data.{$machineId}.ncf" => 'nullable|numeric',
                    "data.{$machineId}.nof" => 'nullable|numeric',
                    "data.{$machineId}.jsi" => 'nullable|numeric',
                    "data.{$machineId}.hsd_fuel" => 'nullable|numeric',
                    "data.{$machineId}.b35_fuel" => 'nullable|numeric',
                    "data.{$machineId}.mfo_fuel" => 'nullable|numeric',
                    "data.{$machineId}.total_fuel" => 'nullable|numeric',
                    "data.{$machineId}.water_usage" => 'nullable|numeric',
                    "data.{$machineId}.meditran_oil" => 'nullable|numeric',
                    "data.{$machineId}.salyx_420" => 'nullable|numeric',
                    "data.{$machineId}.salyx_430" => 'nullable|numeric',
                    "data.{$machineId}.travolube_a" => 'nullable|numeric',
                    "data.{$machineId}.turbolube_46" => 'nullable|numeric',
                    "data.{$machineId}.turbolube_68" => 'nullable|numeric',
                    "data.{$machineId}.total_oil" => 'nullable|numeric',
                    "data.{$machineId}.sfc_scc" => 'nullable|numeric',
                    "data.{$machineId}.nphr" => 'nullable|numeric',
                    "data.{$machineId}.slc" => 'nullable|numeric',
                    "data.{$machineId}.notes" => 'nullable|string',
                ]);
                
                if ($validator->fails()) {
                    return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
                }

                // Simpan data
                DailySummary::create($data);
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