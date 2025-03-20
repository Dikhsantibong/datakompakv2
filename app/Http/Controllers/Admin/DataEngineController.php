<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DataEngineController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $powerPlants = PowerPlant::with(['machines' => function ($query) {
            $query->orderBy('name');
        }])->get();

        return view('admin.data-engine.index', compact('powerPlants', 'date'));
    }
} 