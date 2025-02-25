<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\RencanaDayaMampu;
use Illuminate\Http\Request;

class RencanaDayaMampuController extends Controller
{
    public function index(Request $request)
    {
        // Get unit source from session or request
        $unitSource = session('unit') === 'mysql' ? 
            $request->get('unit_source', 'mysql') : 
            session('unit');

        // Get current month's data
        $currentMonth = now()->format('Y-m');
        
        // Get power plants with their machines and rencana daya mampu data
        $powerPlants = PowerPlant::when($unitSource !== 'mysql', function($query) use ($unitSource) {
            return $query->where('unit_source', $unitSource);
        })->with(['machines' => function($query) use ($currentMonth) {
            $query->orderBy('name')
                ->with(['rencanaDayaMampu' => function($query) use ($currentMonth) {
                    $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$currentMonth]);
                }]);
        }])->orderBy('name')->get();

        return view('admin.rencana-daya-mampu.index', compact('powerPlants', 'unitSource'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'tanggal' => 'required|date',
            'rencana' => 'nullable|numeric',
            'realisasi' => 'nullable|numeric',
            'daya_pjbtl_silm' => 'nullable|numeric',
            'dmp_existing' => 'nullable|numeric'
        ]);

        RencanaDayaMampu::updateOrCreate(
            [
                'machine_id' => $request->machine_id,
                'tanggal' => $request->tanggal,
            ],
            [
                'rencana' => $request->rencana,
                'realisasi' => $request->realisasi,
                'daya_pjbtl_silm' => $request->daya_pjbtl_silm,
                'dmp_existing' => $request->dmp_existing,
                'unit_source' => session('unit')
            ]
        );

        return response()->json(['message' => 'Data berhasil disimpan']);
    }
} 