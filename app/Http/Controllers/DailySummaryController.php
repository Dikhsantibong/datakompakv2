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
        // Validasi input
        $validatedData = $request->validate([
            'power_plant_id' => 'required|exists:power_plants,id',
            'machine_name' => 'required|string|max:255',
            'installed_power' => 'required|numeric',
            'dmn_power' => 'nullable|numeric',
            'capable_power' => 'nullable|numeric',
            'peak_load_day' => 'nullable|numeric',
            'peak_load_night' => 'nullable|numeric',
            'kit_ratio' => 'nullable|numeric',
            'gross_production' => 'nullable|numeric',
            'net_production' => 'nullable|numeric',
            'aux_power' => 'nullable|numeric',
            'transformer_losses' => 'nullable|numeric',
            'usage_percentage' => 'nullable|numeric',
            'period_hours' => 'nullable|numeric',
            'operating_hours' => 'nullable|numeric',
            'standby_hours' => 'nullable|numeric',
            'planned_outage' => 'nullable|numeric',
            'maintenance_outage' => 'nullable|numeric',
            'forced_outage' => 'nullable|numeric',
            'trip_machine' => 'nullable|numeric',
            'trip_electrical' => 'nullable|numeric',
            'efdh' => 'nullable|numeric',
            'epdh' => 'nullable|numeric',
            'eudh' => 'nullable|numeric',
            'esdh' => 'nullable|numeric',
            'eaf' => 'nullable|numeric',
            'sof' => 'nullable|numeric',
            'efor' => 'nullable|numeric',
            'sdof' => 'nullable|numeric',
            'ncf' => 'nullable|numeric',
            'nof' => 'nullable|numeric',
        ]);

        // Simpan data ke dalam tabel daily_summaries
        DailySummary::create($validatedData);

        // Redirect atau kembali ke halaman sebelumnya
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }
} 