<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;

class BlackstartController extends Controller
{
    public function index()
    {
        $powerPlants = PowerPlant::orderBy('name', 'asc')->get();
        return view('admin.blackstart.index', compact('powerPlants'));
    }

    public function show()
    {
        $powerPlants = PowerPlant::orderBy('name', 'asc')->get();
        return view('admin.blackstart.show', compact('powerPlants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'unit.*' => 'required',
            'pembangkit.*' => 'required|in:tersedia,tidak_tersedia',
            'black_start.*' => 'required|in:tersedia,tidak_tersedia',
            'sop.*' => 'required|in:tersedia,tidak_tersedia',
            'load_set.*' => 'required|in:tersedia,tidak_tersedia',
            'line_energize.*' => 'required|in:tersedia,tidak_tersedia',
            'status_jaringan.*' => 'required|in:normal,tidak_normal',
            'pic.*' => 'required|string',
            'status.*' => 'required|in:open,close',
        ]);

        // TODO: Add your storage logic here

        return redirect()->route('admin.blackstart.show')
            ->with('success', 'Data blackstart berhasil disimpan');
    }
} 