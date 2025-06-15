<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineOperation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubsistemBaubauController extends Controller
{
    public function index()
    {

        $machines = Machine::where(' power_plant_id',29)->with('operations');
        $powerPlant = PowerPlant::where('unit_source', 'mysql_baruta')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];

        return view('admin.subsistem.bau-bau', compact('powerPlant', 'specificTimes', 'machines'));
    }

    public function create()
    {

        $machines = Machine::where('power_plant_id',29)->get();


        $powerPlant = PowerPlant::where('unit_source', 'mysql_baruta')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];

        return view('admin.subsistem.bau-bau-create', compact('powerPlant', 'specificTimes','machines'));
    }
}