<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineLog;
use App\Exports\DataEngineExport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class DataEngineController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        
        $powerPlants = PowerPlant::with(['machines' => function ($query) {
            $query->orderBy('name');
        }])->get();

        // Load the latest logs for each machine on the selected date
        $powerPlants->each(function ($powerPlant) use ($date) {
            $powerPlant->machines->each(function ($machine) use ($date) {
                $latestLog = $machine->getLatestLog($date);
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
                $machine->log_time = $latestLog?->time;
            });
        });

        if ($request->ajax()) {
            return view('admin.data-engine._table', compact('powerPlants', 'date'))->render();
        }

        return view('admin.data-engine.index', compact('powerPlants', 'date'));
    }
    
    public function edit($date)
    {
        $powerPlants = PowerPlant::with(['machines' => function ($query) {
            $query->orderBy('name');
        }])->get();

        // Load the latest logs for each machine on the selected date
        $powerPlants->each(function ($powerPlant) use ($date) {
            $powerPlant->machines->each(function ($machine) use ($date) {
                $latestLog = $machine->getLatestLog($date);
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
                $machine->log_time = $latestLog?->time;
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
                // Validate required fields
                if (empty($data['time'])) {
                    throw new \Exception('Waktu harus diisi untuk semua mesin.');
                }

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

    public function exportExcel(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        
        $powerPlants = PowerPlant::with(['machines' => function ($query) {
            $query->orderBy('name');
        }])->get();

        // Load the latest logs for each machine
        $powerPlants->each(function ($powerPlant) use ($date) {
            $powerPlant->machines->each(function ($machine) use ($date) {
                $latestLog = $machine->getLatestLog($date);
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
            });
        });

        $fileName = 'data_engine_' . Carbon::parse($date)->format('Y_m_d') . '.xlsx';
        
        return Excel::download(new DataEngineExport($powerPlants, $date), $fileName);
    }

    public function exportPdf(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        
        $powerPlants = PowerPlant::with(['machines' => function ($query) {
            $query->orderBy('name');
        }])->get();

        // Load the latest logs for each machine
        $powerPlants->each(function ($powerPlant) use ($date) {
            $powerPlant->machines->each(function ($machine) use ($date) {
                $latestLog = $machine->getLatestLog($date);
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
            });
        });

        $pdf = PDF::loadView('admin.data-engine.exports.pdf', compact('powerPlants', 'date'));
        
        return $pdf->download('data_engine_' . Carbon::parse($date)->format('Y_m_d') . '.pdf');
    }
} 