<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;

class LaporanKitController extends Controller
{
    public function index()
    {
        $powerPlant = PowerPlant::with('machines')->first();
        $machines = $powerPlant ? $powerPlant->machines : collect([]);
        
        return view('admin.laporan-kit.index', compact('machines'));
    }

    public function create()
    {
        $powerPlant = PowerPlant::with('machines')->first();
        $machines = $powerPlant ? $powerPlant->machines : collect([]);
        
        return view('admin.laporan-kit.create', compact('machines'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            // Add validation rules based on your requirements
        ]);

        // Store the data
        // Add your storage logic here

        return redirect()->route('admin.laporan-kit.index')
            ->with('success', 'Data berhasil disimpan');
    }
} 