<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\Machine;
use App\Models\MachineIssue;
use App\Models\MachineHealthCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;
use App\Models\MachineStatusLog;
use App\Models\MachineOperation;
use App\Models\PowerPlant;
use App\Models\Issue;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\MachineLog;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class MachineMonitorController extends Controller
{
    public function index(Request $request): View
    {
        $powerPlants = PowerPlant::all();
        $machines = Machine::all();

        // Get latest logs with single query
        $latestLogs = MachineLog::with('machine')
            ->whereIn('id', function($query) {
                $query->selectRaw('MAX(id)')
                    ->from('machine_logs')
                    ->groupBy('machine_id');
            })
            ->get();

        // Get all logs for current month to calculate durations
        $currentMonth = now()->startOfMonth();
        $monthlyLogs = MachineLog::with('machine')
            ->whereMonth('date', $currentMonth->month)
            ->whereYear('date', $currentMonth->year)
            ->orderBy('date')
            ->orderBy('time')
            ->get();

        // Initialize status counts and durations
        $statusCounts = [
            'OPS' => 0,
            'RSH' => 0,
            'FO' => 0,
            'MO' => 0,
            'PO' => 0,
            'MB' => 0
        ];

        $statusDurations = [
            'OPS' => 0,
            'RSH' => 0,
            'FO' => 0,
            'MO' => 0,
            'PO' => 0,
            'MB' => 0
        ];

        // Calculate status counts from latest logs
        foreach ($latestLogs as $log) {
            if (isset($statusCounts[$log->status])) {
                $statusCounts[$log->status]++;
            }
        }

        // Calculate durations from monthly logs
        $previousLog = null;
        foreach ($monthlyLogs as $log) {
            if ($previousLog && $previousLog->machine_id === $log->machine_id) {
                $duration = $log->time->diffInHours($previousLog->time);
                if (isset($statusDurations[$previousLog->status])) {
                    $statusDurations[$previousLog->status] += $duration;
                }
            }
            $previousLog = $log;
        }

        // Process data for charts
        $processedData = [
            'labels' => [],
            'kw' => [],
            'silm_slo' => [],
            'dmp_performance' => [],
            'volt' => [],
            'amp' => []
        ];

        $maxBeban = 0;
        $lastUpdate = null;

        foreach ($latestLogs as $log) {
            // Process machine name
            $machineName = $log->machine->name ?? 'Unknown';
            $processedData['labels'][] = $machineName;

            // Process numeric values
            $processedData['kw'][] = $this->parseNumeric($log->kw);
            $processedData['silm_slo'][] = $this->parseNumeric($log->silm_slo);
            $processedData['dmp_performance'][] = $this->parseNumeric($log->dmp_performance);
            $processedData['volt'][] = $this->parseNumeric($log->volt);
            $processedData['amp'][] = $this->parseNumeric($log->amp);

            // Update maxBeban
            $currentKw = $this->parseNumeric($log->kw);
            if ($currentKw > $maxBeban) {
                $maxBeban = $currentKw;
            }

            // Update lastUpdate
            if ($log->date && (!$lastUpdate || $log->date > $lastUpdate)) {
                $lastUpdate = $log->date;
            }
        }

        return view('admin.machine-monitor.index', compact(
            'powerPlants',
            'machines',
            'latestLogs',
            'statusCounts',
            'statusDurations',
            'processedData',
            'maxBeban',
            'lastUpdate'
        ));
    }

    private function parseNumeric($value): float
    {
        if (is_numeric($value)) {
            return (float) $value;
        }
        return 0.0;
    }

    public function storeIssue(Request $request)
    {
        $validated = $request->validate([
            'machine_id' => 'required|integer',
            'category_id' => 'required|integer',
            'description' => 'required|string',
        ]);

        // Simpan issue baru (gunakan session untuk testing)
        session()->flash('success', 'Issue reported successfully');
        Alert::success('Berhasil', 'Masalah berhasil dilaporkan');
        return redirect()->back();
    }

    public function updateMachineStatus(Request $request, $machineId)
    {
        $validated = $request->validate([
            'status' => 'required|in:START,STOP,PARALLEL',
        ]);

        return response()->json(['success' => true]);
    }

    public function updateMetrics(Request $request, $machineId)
    {
        $validated = $request->validate([
            'metrics.*.name' => 'required|string',
            'metrics.*.value' => 'required|numeric',
            'metrics.*.target' => 'required|numeric',
        ]);

        return response()->json(['success' => true]);
    }

    public function storeMachine(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:50|unique:machines',
                'category_id' => 'required|exists:machine_categories,id',
                'location' => 'required|string|max:255',
                'status' => 'required|in:START,STOP,PARALLEL',
                'description' => 'nullable|string'
            ]);

            // Debug: tampilkan data yang divalidasi
            \Log::info('Validated data:', $validated);

            // Buat mesin baru
            $machine = Machine::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mesin berhasil ditambahkan',
                'data' => $machine
            ]);

        } catch (\Exception $e) {
            \Log::error('Error creating machine:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan mesin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validate the request
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'serial_number' => 'required|string|max:50',
                'power_plant_id' => 'required|exists:power_plants,id',
                'dmn' => 'required|numeric|decimal:0,3',
                'dmp' => 'required|numeric|decimal:0,3',
                'load_value' => 'required|numeric|decimal:0,3',
                'installed_power' => 'required|numeric|decimal:0,3',
            ]);

            // Get PowerPlant untuk unit_source
            $powerPlant = PowerPlant::findOrFail($validated['power_plant_id']);

            // Create the machine first
            $machine = Machine::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'serial_number' => $validated['serial_number'],
                'power_plant_id' => $validated['power_plant_id'],
                'status' => 'STOP',
                'unit_source' => $powerPlant->unit_source
            ]);

            // Then create the machine operation
            if ($machine) {
                MachineOperation::create([
                    'machine_id' => $machine->id,
                    'dmn' => $validated['dmn'],
                    'dmp' => $validated['dmp'],
                    'load_value' => $validated['load_value'],
                    'installed_power' => $validated['installed_power'],
                    'recorded_at' => now(),
                    'unit_source' => $powerPlant->unit_source
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Data mesin berhasil ditambahkan!',
                'redirect_url' => route('admin.machine-monitor.show')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to create machine: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan mesin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showMachine(Machine $machine)
    {
        $machine->load(['metrics', 'issues']);
        $powerPlants = PowerPlant::all(); // Ambil semua power plants untuk filter
        return view('admin.machine-monitor.show', compact('machine', 'powerPlants'));
    }

    public function updateMachine(Request $request, Machine $machine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:machines,code,' . $machine->id,
            'status' => 'required|in:START,STOP,PARALLEL'
        ]);

        try {
            $machine->update($validated);
            Alert::success('Berhasil', 'Mesin berhasil diperbarui');
            return response()->json([
                'success' => true,
                'message' => 'Mesin berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            Alert::error('Gagal', 'Gagal memperbarui mesin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui mesin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyMachine(Machine $machine)
    {
        DB::beginTransaction();
        try {
            // Verifikasi bahwa mesin ada
            if (!$machine) {
                throw new \Exception('Mesin tidak ditemukan');
            }

            // Catat informasi mesin sebelum dihapus untuk logging
            $machineInfo = [
                'id' => $machine->id,
                'name' => $machine->name,
                'unit_source' => $machine->unit_source
            ];

            // Hapus mesin (akan mentrigger event deleting di model)
            $machine->delete();

            DB::commit();

            Log::info('Machine deleted successfully', $machineInfo);

            Alert::success('Berhasil', 'Mesin berhasil dihapus');
            return response()->json([
                'success' => true,
                'message' => 'Mesin berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Failed to delete machine:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            Alert::error('Gagal', 'Gagal menghapus mesin: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus mesin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $machines = Machine::all(); // Ambil semua kategori mesin
        return view('admin.machine-monitor.create', compact('machines'));
    }

    public function crud()
    {
        return view('admin.machine-monitor.crud');
    }

    public function show(Request $request)
    {
        $query = Machine::with(['powerPlant', 'operations' => function($query) {
            $query->latest('recorded_at')->take(1);
        }]);

        // Filter berdasarkan power plant jika ada
        if ($request->has('power_plant_id')) {
            $query->where('power_plant_id', $request->power_plant_id);
        }

        // Pencarian
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('type', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('serial_number', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('powerPlant', function($q) use ($searchTerm) {
                      $q->where('name', 'LIKE', "%{$searchTerm}%");
                  })
                  ->orWhereHas('operations', function($q) use ($searchTerm) {
                      $q->where('dmn', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('dmp', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('load_value', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('hop', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('keterangan', 'LIKE', "%{$searchTerm}%");
                  });
            });
        }

        $machines = $query->orderBy('id')->paginate(10);

        if ($request->ajax()) {
            return view('admin.machine-monitor.table-body', compact('machines'))->render();
        }

        return view('admin.machine-monitor.show', compact('machines'));
    }

    public function edit($id)
    {
        $item = Machine::with(['operations' => function($query) {
            $query->latest('recorded_at');
        }])->findOrFail($id);

        return view('admin.machine-monitor.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required',
                'type' => 'required',
                'serial_number' => 'required',
                'power_plant_id' => 'required|exists:power_plants,id',
                'dmn' => 'required|numeric|decimal:0,3',
                'dmp' => 'required|numeric|decimal:0,3',
                'load_value' => 'required|numeric|decimal:0,3',
                'installed_power' => 'required|numeric|decimal:0,3',
            ]);

            $machine = Machine::findOrFail($id);

            // Update data mesin
            $machine->update([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'serial_number' => $validated['serial_number'],
                'power_plant_id' => $validated['power_plant_id'],
            ]);

            // Update atau buat data operasi mesin baru
            MachineOperation::updateOrCreate(
                ['machine_id' => $machine->id],
                [
                    'dmn' => $validated['dmn'],
                    'dmp' => $validated['dmp'],
                    'load_value' => $validated['load_value'],
                    'installed_power' => $validated['installed_power'],
                    'recorded_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Mesin berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error updating machine: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showAll()
    {
        $machines = Machine::with(['powerPlant'])
                          ->orderBy('id')
                          ->paginate(10);

        return view('admin.machine-monitor.show', compact('machines'));
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Verifikasi password
            if (!Hash::check($request->password, Auth::user()->password)) {
                return back()->with('error', 'Password yang Anda masukkan salah');
            }

            $machine = Machine::findOrFail($id);
            $machine->delete();

            return redirect()->route('admin.machine-monitor')
                ->with('success', 'Data mesin berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data mesin: ' . $e->getMessage());
        }
    }

    public function filter(Request $request)
    {
        try {
            \Log::info('Filter request received', ['time_filter' => $request->input('time_filter')]);

            $query = MachineStatusLog::with('machine')
                ->whereDate('tanggal', Carbon::today());

            $selectedTime = $request->input('time_filter');

            if ($selectedTime !== 'all') {
                $query->whereTime('input_time', '=', $selectedTime.':00');
            }

            $logs = $query->get();

            \Log::info('Filtered logs count: ' . $logs->count());

            if ($request->ajax()) {
                return view('admin.machine-monitor.partials.status-table', [
                    'logs' => $logs
                ])->render();
            }

            return response()->json(['error' => 'Request harus menggunakan AJAX'], 400);

        } catch (\Exception $e) {
            \Log::error('Filter error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}