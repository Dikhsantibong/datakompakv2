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
        // dd("tes");
        $powerPlant = PowerPlant::where('unit_source', 'mysql_moramo')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];

        return view('admin.subsistem.kendari', compact('powerPlant', 'specificTimes'));
    }

    public function create()
    {
        $powerPlant = PowerPlant::where('unit_source', 'mysql_moramo')->first();
        $specificTimes = ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];

        return view('admin.subsistem.kendari-create', compact('powerPlant', 'specificTimes'));
    }
}