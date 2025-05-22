<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;
use App\Models\EngineData;
use App\Exports\DataEngineExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Machine;
use App\Models\MachineLog;
use App\Models\MachineOperation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataEngineController extends Controller
{
    public function index(Request $request)
    {
        try {
            $date = $request->date ?? now()->format('Y-m-d');
            $time = $request->time ?? null;
            
            // Get all power plants for the filter dropdown
            $allPowerPlants = PowerPlant::orderBy('name')->get();
            
            // Build query for filtered power plants
            $powerPlantsQuery = PowerPlant::with(['machines' => function ($query) {
                $query->orderBy('name');
            }]);

            // Apply power plant filter if specified
            if ($request->filled('power_plant_id')) {
                $powerPlantsQuery->where('id', $request->power_plant_id);
            }

            $powerPlants = $powerPlantsQuery->get();

            // Load the latest logs for each power plant and machine on the selected date
            $powerPlants->each(function ($powerPlant) use ($date, $time) {
                // Get power plant logs
                $latestLogQuery = DB::table('power_plant_logs')
                    ->where('power_plant_id', $powerPlant->id)
                    ->where('date', $date);
                
                if ($time) {
                    // Ambil log dengan waktu <= $time, urutkan desc, ambil satu
                    $latestLogQuery->where('time', '<=', $time);
                }
                
                $latestLog = $latestLogQuery->orderBy('time', 'desc')->first();

                $powerPlant->hop = $latestLog?->hop;
                $powerPlant->tma = $latestLog?->tma;
                $powerPlant->inflow = $latestLog?->inflow;

                // Get machine logs
                $powerPlant->machines->each(function ($machine) use ($date, $time) {
                    $latestLog = $machine->getLatestLog($date, $time);
                    $machine->kw = $latestLog?->kw;
                    $machine->kvar = $latestLog?->kvar;
                    $machine->cos_phi = $latestLog?->cos_phi;
                    $machine->status = $latestLog?->status;
                    $machine->keterangan = $latestLog?->keterangan;
                    $machine->daya_terpasang = $latestLog?->daya_terpasang;
                    $machine->silm_slo = $latestLog?->silm_slo;
                    $machine->dmp_performance = $latestLog?->dmp_performance;
                    $machine->log_time = $latestLog?->time;
                });
            });

            if ($request->ajax()) {
                return view('admin.data-engine._table', compact('powerPlants', 'date', 'time'))->render();
            }

            return view('admin.data-engine.index', compact('powerPlants', 'allPowerPlants', 'date', 'time'));
        } catch (\Exception $e) {
            Log::error('Error in DataEngine index:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memuat data'], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
        }
    }
    
    public function edit($date)
    {
        try {
            $time = request('time');
            $powerPlants = PowerPlant::with(['machines' => function ($query) use ($date) {
                $query->orderBy('name')
                    ->with(['latestOperation' => function($q) use ($date) {
                        $q->whereDate('recorded_at', '<=', $date)
                          ->orderBy('recorded_at', 'desc');
                    }]);
            }])->get();

            // Load the latest logs for each machine on the selected date
            $powerPlants->each(function ($powerPlant) use ($date, $time) {
                // Ambil log power plant sesuai jam (atau terakhir sebelum jam)
                $latestLogQuery = DB::table('power_plant_logs')
                    ->where('power_plant_id', $powerPlant->id)
                    ->where('date', $date);
                if ($time) {
                    $latestLogQuery->where('time', '<=', $time);
                }
                $latestLog = $latestLogQuery->orderBy('time', 'desc')->first();
                $powerPlant->hop = $latestLog?->hop;
                $powerPlant->tma = $latestLog?->tma;
                $powerPlant->inflow = $latestLog?->inflow;

                $powerPlant->machines->each(function ($machine) use ($date) {
                    $latestLog = $machine->getLatestLog($date);
                    $latestOperation = $machine->latestOperation;

                    $machine->kw = $latestLog?->kw;
                    $machine->kvar = $latestLog?->kvar;
                    $machine->cos_phi = $latestLog?->cos_phi;
                    $machine->status = $latestLog?->status;
                    $machine->keterangan = $latestLog?->keterangan;
                    
                    // Data dari MachineOperation
                    $machine->daya_terpasang = $latestOperation?->installed_power;
                    $machine->dmn = $latestOperation?->dmn;
                    $machine->dmp = $latestOperation?->dmp;
              
                    
                    $machine->log_time = $latestLog?->time;
                });
            });

            return view('admin.data-engine.edit', compact('powerPlants', 'date', 'time'));
        } catch (\Exception $e) {
            Log::error('Error in DataEngine edit:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat form edit: ' . $e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $date = $request->date;
            $machines = $request->input('machines', []);
            $powerPlants = $request->input('power_plants', []);
            $currentSession = session('unit', 'mysql');

            Log::info('Starting DataEngine update', [
                'date' => $date,
                'session' => $currentSession,
                'machine_count' => count($machines),
                'power_plant_count' => count($powerPlants)
            ]);

            // Save power plant logs
            foreach ($powerPlants as $powerPlantId => $data) {
                $time = now()->format('H:i:s'); // Use current time for power plant logs
                $hopData = [
                    'power_plant_id' => $powerPlantId,
                    'date' => $date,
                    'time' => $time,
                    'hop' => $data['hop'] ?? null,
                    'tma' => $data['tma'] ?? null,
                    'inflow' => $data['inflow'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('power_plant_logs')->insert($hopData);

                // Sinkronisasi ke database utama jika bukan di mysql
                if ($currentSession !== 'mysql') {
                    try {
                        DB::connection('mysql')->table('power_plant_logs')->insert($hopData);
                    } catch (\Exception $e) {
                        // Jika gagal insert (misal duplikat), lakukan update
                        DB::connection('mysql')->table('power_plant_logs')
                            ->where('power_plant_id', $powerPlantId)
                            ->where('date', $date)
                            ->where('time', $time)
                            ->update([
                                'hop' => $data['hop'] ?? null,
                                'tma' => $data['tma'] ?? null,
                                'inflow' => $data['inflow'] ?? null,
                                'updated_at' => now()
                            ]);
                    }
                }
            }

            // Save machine logs
            foreach ($machines as $machineId => $data) {
                // Validate required fields
                if (empty($data['time'])) {
                    throw new \Exception('Waktu harus diisi untuk semua mesin.');
                }

                // Create machine log - this will trigger sync event if needed
                MachineLog::create([
                    'machine_id' => $machineId,
                    'date' => $date,
                    'time' => $data['time'],
                    'kw' => $data['kw'],
                    'kvar' => $data['kvar'],
                    'cos_phi' => $data['cos_phi'],
                    'status' => $data['status'],
                    'keterangan' => $data['keterangan'],
                    'daya_terpasang' => $data['daya_terpasang'],
                    'silm_slo' => $data['silm_slo'],
                    'dmp_performance' => $data['dmp_performance']
                ]);

                Log::info('Machine log created', [
                    'machine_id' => $machineId,
                    'date' => $date,
                    'time' => $data['time'],
                    'session' => $currentSession
                ]);
            }

            DB::commit();

            return redirect()
                ->route('admin.data-engine.index', ['date' => $date])
                ->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error in DataEngine update:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $date = $request->get('date', now()->format('Y-m-d'));
            $powerPlantId = $request->get('power_plant_id');
            
            return Excel::download(
                new DataEngineExport($date, $powerPlantId),
                'data-engine-report-' . $date . '.xlsx'
            );
        } catch (\Exception $e) {
            Log::error('Error in DataEngine Excel export:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor Excel: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
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
        } catch (\Exception $e) {
            Log::error('Error in DataEngine PDF export:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengekspor PDF: ' . $e->getMessage());
        }
    }

    public function getLatestData(Request $request)
    {
        try {
            $date = $request->query('date');
            if (!$date) {
                return response()->json([
                    'success' => false,
                    'message' => 'Date parameter is required'
                ], 400);
            }

            $machines = Machine::with(['logs' => function($query) use ($date) {
                $query->where('date', $date)
                      ->latest('time');
            }])->get();

            $machineLogs = [];
            foreach ($machines as $machine) {
                $latestLog = $machine->logs->first();
                if ($latestLog) {
                    $machineLogs[$machine->id] = [
                        'kw' => $latestLog->kw,
                        'kvar' => $latestLog->kvar,
                        'cos_phi' => $latestLog->cos_phi,
                        'status' => $latestLog->status,
                        'keterangan' => $latestLog->keterangan
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'machineLogs' => $machineLogs
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching latest data: ' . $e->getMessage()
            ], 500);
        }
    }
} 