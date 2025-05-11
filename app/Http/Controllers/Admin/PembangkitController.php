<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineOperation;
use App\Models\MachineStatusLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnitOperationHour;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Events\MachineStatusUpdated;

class PembangkitController extends Controller
{
    public function ready()
    {
        $units = PowerPlant::orderByRaw("
            CASE 
                WHEN name LIKE 'PLTU%' THEN 1
                WHEN name LIKE 'PLTM%' THEN 2
                WHEN name LIKE 'PLTD%' THEN 3
                WHEN name LIKE 'PLTMG%' THEN 4
                ELSE 5
            END
        ")->get();
        $machines = Machine::with('issues', 'metrics')->get();
        
        // Modifikasi query operations untuk include unit_id
        $operations = MachineOperation::select('machine_operations.*', 'machines.power_plant_id as unit_id')
            ->join('machines', 'machines.id', 'machine_operations.machine_id')
            ->get();
        
        // Ambil status log dengan relasi machine dan updated_at
        $todayLogs = MachineStatusLog::with(['machine'])
            ->select('machine_status_logs.*', 'machines.power_plant_id as unit_id')
            ->join('machines', 'machines.id', 'machine_status_logs.machine_id')
            ->whereDate('tanggal', Carbon::today())
            ->get();

        $todayHops = UnitOperationHour::whereDate('tanggal', Carbon::today())->get();

        return view('admin.pembangkit.ready', compact('units', 'machines', 'operations', 'todayLogs', 'todayHops'));
    }

    public function saveStatus(Request $request)
    {
        // Validasi incoming request
        $request->validate([
            'logs' => 'required|array',
            'hops' => 'required|array',
            'inputTime' => 'required|string',
        ]);

        // Extract input time
        $inputTime = $request->input('inputTime');
        $currentSession = session('unit', 'mysql');

        DB::beginTransaction();
        try {
            Log::info("Starting saveStatus operation", [
                'session' => $currentSession,
                'connection' => DB::connection()->getName()
            ]);
            
            // Cek duplikasi input untuk waktu yang sama
            if (!empty($request->logs)) {
                $firstLog = $request->logs[0];
                $existingLog = MachineStatusLog::where('tanggal', $firstLog['tanggal'])
                    ->where('input_time', $inputTime)
                    ->first();
                
                if ($existingLog) {
                    throw new \Exception("Data untuk jam {$inputTime} sudah diinputkan sebelumnya");
                }
            }
            
            // Simpan data HOP/Inflow/TMA
            foreach ($request->hops as $hopData) {
                $powerPlant = PowerPlant::find($hopData['power_plant_id']);
                if (!$powerPlant) {
                    throw new \Exception("Power Plant not found for id: {$hopData['power_plant_id']}");
                }

                // Cek apakah unit adalah PLTM
                $isPLTM = Str::startsWith(strtoupper($powerPlant->name), 'PLTM');

                // Cek apakah data sudah ada
                $existingHop = UnitOperationHour::where([
                    'power_plant_id' => $hopData['power_plant_id'],
                    'tanggal' => $hopData['tanggal'],
                    'input_time' => $inputTime
                ])->first();

                // Prepare data berdasarkan tipe unit
                $updateData = [
                    'unit_source' => session('unit', 'mysql'),
                    'input_time' => $inputTime
                ];

                if ($isPLTM) {
                    $updateData['inflow'] = $hopData['inflow'] ?? 0;
                    $updateData['tma'] = $hopData['tma'] ?? 0;
                    $updateData['hop_value'] = 0; // Set default value 0 untuk PLTM
                } else {
                    $updateData['hop_value'] = $hopData['hop_value'] ?? 0; // Set default value 0 jika tidak ada
                    $updateData['inflow'] = 0;
                    $updateData['tma'] = 0;
                }

                if ($existingHop) {
                    $existingHop->update($updateData);
                } else {
                    UnitOperationHour::create(array_merge($updateData, [
                        'power_plant_id' => $hopData['power_plant_id'],
                        'tanggal' => $hopData['tanggal']
                    ]));
                }
            }

            // Simpan data status mesin
            $machineStatusLog = new MachineStatusLog();
            $machineStatusLog->setConnection($currentSession);

            foreach ($request->logs as $log) {
                    // Pastikan nilai DMN, DMP, dan load_value adalah numerik
                $dmn = is_numeric($log['dmn']) ? floatval($log['dmn']) : 0;
                $dmp = is_numeric($log['dmp']) ? floatval($log['dmp']) : 0;
                $loadValue = is_numeric($log['load_value']) ? floatval($log['load_value']) : 0;

                // Prepare data untuk update/create
                $updateData = [
                    'status' => $log['status'],
                    'dmn' => $dmn,
                    'dmp' => $dmp,
                    'load_value' => $loadValue,
                    'deskripsi' => $log['deskripsi'] ?? null,
                    'action_plan' => $log['action_plan'] ?? null,
                    'progres' => $log['progres'] ?? null,
                    'kronologi' => $log['kronologi'] ?? null,
                    'tanggal_mulai' => $log['tanggal_mulai'] ?? null,
                    'target_selesai' => $log['target_selesai'] ?? null,
                    'unit_source' => $currentSession,
                    'input_time' => $inputTime
                ];

                // Debug log untuk melihat data yang akan disimpan
                Log::info("Saving machine status", [
                    'machine_id' => $log['machine_id'],
                    'dmn' => $dmn,
                    'dmp' => $dmp,
                    'load_value' => $loadValue
                ]);

                // Cek existing log
                $existingLog = $machineStatusLog->where([
                    'machine_id' => $log['machine_id'],
                    'tanggal' => $log['tanggal'],
                    'input_time' => $inputTime
                ])->first();

                if ($existingLog) {
                    // Update existing log
                    $existingLog->update($updateData);
                    $updatedLog = $existingLog;
                } else {
                    // Create new log
                    $uuid = (string) Str::uuid();
                    $newLog = new MachineStatusLog(array_merge($updateData, [
                        'machine_id' => $log['machine_id'],
                        'tanggal' => $log['tanggal'],
                        'uuid' => $uuid
                    ]));
                    
                    $newLog->setConnection($currentSession);
                    $newLog->save();
                    $updatedLog = $newLog;

                    // Verifikasi data tersimpan
                    $savedLog = $machineStatusLog->newQuery()
                        ->where('uuid', $uuid)
                        ->first();

                    if (!$savedLog) {
                        throw new \Exception("Failed to save/verify data in unit database");
                    }

                    // Trigger event untuk sinkronisasi
                    event(new MachineStatusUpdated(
                        $updatedLog,
                        $existingLog ? 'update' : 'create'
                    ));
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saving data', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session' => $currentSession ?? null,
                'connection' => DB::connection()->getName()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getStatus(Request $request)
    {
        $date = $request->query('tanggal');
        $inputTime = $request->query('input_time');

        // Fetch logs based on date and input time
        $logs = MachineStatusLog::whereDate('tanggal', $date)
            ->when($inputTime, function ($query) use ($inputTime) {
                return $query->where('input_time', $inputTime);
            })
            ->get();

        try {
            $tanggal = $request->tanggal ?? now()->toDateString();
            $currentSession = session('unit', 'mysql');

            // Subquery untuk mendapatkan tanggal terakhir update untuk setiap mesin
            $lastUpdateDates = MachineStatusLog::select('machine_id', DB::raw('MAX(tanggal) as last_date'))
                ->groupBy('machine_id');

            // Modifikasi query untuk mengambil data terakhir untuk setiap mesin
            $logs = MachineStatusLog::with(['machine.powerPlant'])
                ->select('machine_status_logs.*', 'machines.power_plant_id as unit_id')
                ->join('machines', 'machines.id', 'machine_status_logs.machine_id')
                ->joinSub($lastUpdateDates, 'last_updates', function ($join) {
                    $join->on('machine_status_logs.machine_id', '=', 'last_updates.machine_id')
                        ->on('machine_status_logs.tanggal', '=', 'last_updates.last_date');
                })
                ->get();

            // Ambil data HOP terakhir untuk setiap unit
            $lastHopDates = UnitOperationHour::select('power_plant_id', DB::raw('MAX(tanggal) as last_date'))
                ->groupBy('power_plant_id');

            $hops = UnitOperationHour::with('powerPlant')
                ->joinSub($lastHopDates, 'last_updates', function ($join) {
                    $join->on('unit_operation_hours.power_plant_id', '=', 'last_updates.power_plant_id')
                        ->on('unit_operation_hours.tanggal', '=', 'last_updates.last_date');
                })
                ->when($currentSession !== 'mysql', function($query) use ($currentSession) {
                    $query->whereHas('powerPlant', function($q) use ($currentSession) {
                        $q->where('unit_source', $currentSession);
                    });
                })
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'logs' => $logs->map(function($log) {
                        return [
                            'machine_id' => $log->machine_id,
                            'unit_id' => $log->unit_id,
                            'tanggal' => $log->tanggal,
                            'status' => $log->status ?? '',
                            'dmn' => $log->dmn,
                            'dmp' => $log->dmp,
                            'load_value' => $log->load_value,
                            'equipment' => $log->equipment ?? '',
                            'deskripsi' => $log->deskripsi,
                            'updated_at' => $log->updated_at
                        ];
                    }),
                    'hops' => $hops->map(function($hop) {
                        $isPLTM = Str::startsWith(strtoupper($hop->powerPlant->name), 'PLTM');
                        return [
                            'power_plant_id' => $hop->power_plant_id,
                            'tanggal' => $hop->tanggal,
                            'hop_value' => $isPLTM ? null : $hop->hop_value,
                            'inflow' => $isPLTM ? $hop->inflow : null,
                            'tma' => $isPLTM ? $hop->tma : null,
                            'unit_source' => $hop->unit_source,
                            'is_pltm' => $isPLTM
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getStatus', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'session' => session('unit')
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ]);
        }
    }

    public function getStatusHistory(Request $request)
    {
        try {
            $startDate = $request->start_date ?? Carbon::now()->subDays(30);
            $endDate = $request->end_date ?? Carbon::now();
            $machineId = $request->machine_id;

            // Query untuk mengambil history status
            $history = DB::table('machine_status_logs as msl')
                ->select([
                    'msl.tanggal',
                    'msl.status',
                    'msl.deskripsi',
                    'm.name as machine_name',
                    'pp.name as unit_name'
                ])
                ->join('machines as m', 'm.id', '=', 'msl.machine_id')
                ->join('power_plants as pp', 'pp.id', '=', 'm.power_plant_id')
                ->when($machineId, function($query) use ($machineId) {
                    return $query->where('msl.machine_id', $machineId);
                })
                ->whereBetween('msl.tanggal', [$startDate, $endDate])
                ->orderBy('msl.tanggal', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $history
            ]);
            
        } catch(\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil history: ' . $e->getMessage()
            ]);
        }
    }

    public function report(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        
        $logs = MachineStatusLog::with(['machine.powerPlant'])
            ->select([
                'machine_status_logs.*',
                'machines.name as machine_name',
                'power_plants.name as power_plant_name'
            ])
            ->join('machines', 'machines.id', '=', 'machine_status_logs.machine_id')
            ->join('power_plants', 'power_plants.id', '=', 'machines.power_plant_id')
            ->whereDate('tanggal', $date)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('admin.pembangkit.report-table', compact('logs'))->render()
            ]);
        }

        return view('admin.pembangkit.report', compact('logs'));
    }

    public function downloadReport(Request $request)
    {
        $logs = MachineStatusLog::with(['machine.powerPlant'])
            ->select([
                'machine_status_logs.*',
                'machines.name as machine_name',
                'power_plants.name as power_plant_name'
            ])
            ->join('machines', 'machines.id', '=', 'machine_status_logs.machine_id')
            ->join('power_plants', 'power_plants.id', '=', 'machines.power_plant_id')
            ->whereDate('tanggal', $request->date ?? now())
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = PDF::loadView('admin.pembangkit.report-pdf', compact('logs'));
        
        return $pdf->download('laporan-kesiapan-pembangkit.pdf');
    }

    public function printReport(Request $request)
    {
        $date = $request->get('date', date('Y-m-d'));
        
        $logs = MachineStatusLog::with(['machine.powerPlant'])
            ->whereDate('tanggal', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pembangkit.report-print', compact('logs'));
    }

    public function resetStatus(Request $request)
    {
        try {
            $tanggal = $request->tanggal ?? now()->format('Y-m-d');
            
            // Ambil semua log status pada tanggal tersebut
            $currentLogs = MachineStatusLog::whereDate('tanggal', $tanggal)->get();
            
            // Ambil data mesin yang sedang gangguan dan masih dalam periode gangguan
            $activeIssues = MachineStatusLog::where('status', 'Gangguan')
                ->where(function($query) use ($tanggal) {
                    $query->whereNull('target_selesai')
                        ->orWhereDate('target_selesai', '>=', $tanggal);
                })
                ->whereDate('tanggal_mulai', '<=', $tanggal)
                ->get();
            
            // Kumpulkan machine_id yang sedang gangguan
            $machineIdsWithIssues = $activeIssues->pluck('machine_id')->toArray();
            
            // Cek apakah ada input baru dengan status lain untuk mesin yang gangguan
            $newInputsForIssues = MachineStatusLog::whereIn('machine_id', $machineIdsWithIssues)
                ->where('status', '!=', 'Gangguan')
                ->whereDate('tanggal', $tanggal)
                ->get()
                ->pluck('machine_id')
                ->toArray();
            
            // Machine IDs yang akan dipertahankan (tidak direset)
            $preservedMachineIds = array_diff($machineIdsWithIssues, $newInputsForIssues);
            
            DB::beginTransaction();
            
            foreach ($currentLogs as $log) {
                // Jika mesin tidak dalam daftar yang dipreservasi, reset datanya
                if (!in_array($log->machine_id, $preservedMachineIds)) {
                    // Simpan DMN dan DMP
                    $dmn = $log->dmn;
                    $dmp = $log->dmp;
                    
                    // Update log dengan nilai default kecuali DMN dan DMP
                    $log->update([
                        'status' => 'Operasi',
                        'equipment' => '',
                        'deskripsi' => '',
                        'load_value' => '',
                        'dmn' => $dmn,
                        'dmp' => $dmp
                    ]);
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil direset',
                'preserved_machines' => $preservedMachineIds
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Reset Status Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset data: ' . $e->getMessage()
            ]);
        }
    }
}