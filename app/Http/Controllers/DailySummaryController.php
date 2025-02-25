<?php

namespace App\Http\Controllers;

use App\Models\PowerPlant; // Import model PowerPlant
use App\Models\DailySummary; // Import model DailySummary
use Illuminate\Http\Request;

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
            // Data yang dikirim dalam bentuk array per mesin
            $machineData = $request->input('data');
            
            foreach ($machineData as $machineId => $data) {
                // Validasi untuk setiap data mesin
                $validatedData = $this->validate($request, [
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

                // Simpan data untuk setiap mesin
                DailySummary::create($data);
            }

            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
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