<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineOperation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubsistemBauBauController extends Controller
{
    public function index()
    {
        $powerPlant = PowerPlant::where('unit_source', 'mysql_baruta')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];
        
        return view('admin.subsistem.bau-bau', compact('powerPlant', 'specificTimes'));
    }

    public function create()
    {
        $powerPlant = PowerPlant::where('unit_source', 'mysql_baruta')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];
        
        return view('admin.subsistem.bau-bau-create', compact('powerPlant', 'specificTimes'));
    }
} 