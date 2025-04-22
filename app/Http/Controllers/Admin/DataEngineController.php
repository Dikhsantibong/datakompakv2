<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DataEngineController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        
        $powerPlants = PowerPlant::with(['machines' => function ($query) use ($date) {
            $query->orderBy('name')
                ->with(['logs' => function($q) use ($date) {
                    $q->where('date', $date)
                      ->latest('time')
                      ->limit(1);
                }]);
        }])->get();

        // Transform the data to include the latest log
        $powerPlants->each(function ($powerPlant) {
            $powerPlant->machines->each(function ($machine) {
                $latestLog = $machine->logs->first();
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
            });
        });

        return view('admin.data-engine.index', compact('powerPlants', 'date'));
    }
    
    public function edit($date)
    {
        $powerPlants = PowerPlant::with(['machines' => function ($query) use ($date) {
            $query->orderBy('name')
                ->with(['logs' => function($q) use ($date) {
                    $q->where('date', $date)
                      ->latest('time')
                      ->limit(1);
                }]);
        }])->get();

        // Transform the data to include the latest log
        $powerPlants->each(function ($powerPlant) {
            $powerPlant->machines->each(function ($machine) {
                $latestLog = $machine->logs->first();
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
            });
        });

        return view('admin.data-engine.edit', compact('powerPlants', 'date'));
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $date = $request->date;
            $machines = $request->input('machines', []);

            foreach ($machines as $machineId => $data) {
                MachineLog::create([
                    'machine_id' => $machineId,
                    'date' => $date,
                    'time' => $data['time'],
                    'kw' => $data['kw'],
                    'kvar' => $data['kvar'],
                    'cos_phi' => $data['cos_phi'],
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan']
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.data-engine.index', ['date' => $date])
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }
} 