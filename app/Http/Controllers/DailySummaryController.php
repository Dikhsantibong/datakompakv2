<?php

namespace App\Http\Controllers;

use App\Models\PowerPlant; // Import model PowerPlant
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
} 