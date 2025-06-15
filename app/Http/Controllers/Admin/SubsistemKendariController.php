<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineOperation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubsistemKendariController extends Controller
{
    public function index()
    {
        
        $machines = Machine::where(' power_plant_id',2)->with('operations');//, 'machineOperations'
        $powerPlant = PowerPlant::where('unit_source', 'mysql_moramo')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];

        return view('admin.subsistem.kendari', compact('powerPlant', 'specificTimes', 'machines'));
    }

    public function create()
    {

        $machines = Machine::where('power_plant_id',2)->get();


        $powerPlant = PowerPlant::where('unit_source', 'mysql_moramo')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];

        return view('admin.subsistem.kendari-create', compact('powerPlant', 'specificTimes','machines'));
    }
}