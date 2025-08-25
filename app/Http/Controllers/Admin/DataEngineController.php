<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;
use App\Models\EngineData;
use App\Exports\DataEngineExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
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

            // Handle 24:00 time by converting it to 00:00 of the next day
            if ($time === '24:00:00') {
                $time = '00:00:00';
                $date = Carbon::parse($date)->addDay()->format('Y-m-d');
            }

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
                // Get power plant logs with improved time handling
                $latestLogQuery = DB::table('power_plant_logs')
                    ->where('power_plant_id', $powerPlant->id)
                    ->where('date', $date);

                if ($time) {
                    // First try to get exact time match
                    $exactTimeLog = clone $latestLogQuery;
                    $exactMatch = $exactTimeLog->where('time', $time)->first();

                    if ($exactMatch) {
                        $latestLog = $exactMatch;
                    } else {
                        // If no exact match, get the closest previous time
                        $latestLog = $latestLogQuery
                            ->where('time', '<=', $time)
                            ->orderBy('time', 'desc')
                            ->first();

                        // If no previous time exists, get the next closest time
                        if (!$latestLog) {
                            $latestLog = DB::table('power_plant_logs')
                                ->where('power_plant_id', $powerPlant->id)
                                ->where('date', $date)
                                ->where('time', '>', $time)
                                ->orderBy('time', 'asc')
                                ->first();
                }
                    }
                } else {
                    // If no time specified, get the latest log for the date
                $latestLog = $latestLogQuery->orderBy('time', 'desc')->first();
                }

                $powerPlant->hop = $latestLog?->hop;
                $powerPlant->tma = $latestLog?->tma;
                $powerPlant->inflow = $latestLog?->inflow;
                $powerPlant->log_time = $latestLog?->time; // Add this to track the actual time of the log

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

            // Handle 24:00 time by converting it to 00:00 of the next day
            if ($time === '24:00:00') {
                $time = '00:00:00';
                $date = Carbon::parse($date)->addDay()->format('Y-m-d');
            }

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

            $date = $request->input('date');
            if (empty($date)) {
                throw new \Exception('Tanggal harus diisi.');
            }

            $machines = $request->input('machines', []);
            $powerPlants = $request->input('power_plants', []);
            $currentSession = session('unit', 'mysql');
            $existingData = [];

            // Check for existing data first
            foreach ($machines as $machineId => $data) {
                if (empty($data['time'])) {
                    throw new \Exception('Waktu harus diisi untuk semua mesin.');
                }

                $machineDate = $date; // Use the form date by default

                // Handle 24:00 time entries
                if ($data['time'] === '24:00') {
                    $data['time'] = '00:00';
                    $machineDate = Carbon::parse($date)->addDay()->format('Y-m-d');
                }

                // Check if data exists
                $existing = MachineLog::checkExistingData($machineId, $machineDate, $data['time']);
                if ($existing) {
                    $existingData[] = [
                        'machine_id' => $machineId,
                        'time' => $data['time']
                    ];
                }

                // Store the processed date back in the data array
                $data['date'] = $machineDate;
                $machines[$machineId] = $data;
            }

            // If there's existing data, return warning response
            if (!empty($existingData)) {
                DB::rollBack();
                return response()->json([
                    'warning' => true,
                    'message' => 'Data sudah ada untuk beberapa mesin pada waktu tertentu. Apakah Anda ingin mengupdate data tersebut?',
                    'existingData' => $existingData
                ]);
            }

            // Process normal update
            $this->processUpdate($machines, $powerPlants, $date, $currentSession);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in DataEngine update:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function forceUpdate(Request $request)
    {
        try {
            DB::beginTransaction();

            $date = $request->input('date');
            if (empty($date)) {
                throw new \Exception('Tanggal harus diisi.');
            }

            $machines = $request->input('machines', []);
            $powerPlants = $request->input('power_plants', []);
            $currentSession = session('unit', 'mysql');

            // Process date for each machine
            foreach ($machines as $machineId => &$data) {
                if (empty($data['time'])) {
                    throw new \Exception('Waktu harus diisi untuk semua mesin.');
                }

                // Handle 24:00 time entries
                if ($data['time'] === '24:00') {
                    $data['time'] = '00:00';
                    $data['date'] = Carbon::parse($date)->addDay()->format('Y-m-d');
                } else {
                    $data['date'] = $date;
                }
            }

            // Process the update
            $this->processUpdate($machines, $powerPlants, $date, $currentSession);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in DataEngine forceUpdate:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupdate data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function processUpdate($machines, $powerPlants, $date, $currentSession)
    {
        // Save power plant logs
        foreach ($powerPlants as $powerPlantId => $data) {
            $time = now()->format('H:i:s');
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

            if ($currentSession !== 'mysql') {
                try {
                    DB::connection('mysql')->table('power_plant_logs')->insert($hopData);
                } catch (\Exception $e) {
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
            // Make sure we have a date
            if (empty($data['date'])) {
                $data['date'] = $date;
            }

            $logData = [
                'machine_id' => $machineId,
                'date' => $data['date'],
                'time' => $data['time'],
                'kw' => $data['kw'],
                'kvar' => $data['kvar'],
                'cos_phi' => $data['cos_phi'],
                'status' => $data['status'],
                'keterangan' => $data['keterangan'],
                'daya_terpasang' => $data['daya_terpasang'],
                'silm_slo' => $data['silm_slo'],
                'dmp_performance' => $data['dmp_performance']
            ];

            // For force update, we need to delete existing data first
            MachineLog::where('machine_id', $machineId)
                     ->where('date', $data['date'])
                     ->where('time', $data['time'])
                     ->delete();

            MachineLog::create($logData);

            Log::info('Machine log processed', [
                'machine_id' => $machineId,
                'date' => $data['date'],
                'time' => $data['time'],
                'session' => $currentSession
            ]);
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

    public function listDailyInputs(Request $request)
    {
        try {
            $date = $request->date ?? now()->format('Y-m-d');

            // Get all power plants
            $powerPlants = PowerPlant::orderBy('name')->get();

            // Generate hours array (00:00 to 23:00)
            $hours = collect(range(0, 23))->map(function($hour) {
                return str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            });

            // Get input status for each power plant and hour
            $powerPlants->each(function($powerPlant) use ($date, $hours) {
                $logs = DB::table('power_plant_logs')
                    ->where('power_plant_id', $powerPlant->id)
                    ->where('date', $date)
                    ->pluck('time')
                    ->map(function($time) {
                        return Carbon::parse($time)->format('H:i:00');
                    })
                    ->toArray();

                $powerPlant->hourlyStatus = $hours->mapWithKeys(function($hour) use ($logs) {
                    return [$hour => in_array($hour, $logs)];
                });
            });

            if ($request->ajax()) {
                return view('admin.data-engine._daily-list-table', compact('powerPlants', 'date', 'hours'))->render();
            }

            return view('admin.data-engine.daily-list', compact('powerPlants', 'date', 'hours'));
        } catch (\Exception $e) {
            Log::error('Error in DataEngine listDailyInputs:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json(['error' => 'Terjadi kesalahan saat memuat data: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data: ' . $e->getMessage());
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
