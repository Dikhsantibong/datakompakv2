<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\RencanaDayaMampu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RencanaDayaMampuExport;

class RencanaDayaMampuController extends Controller
{
    public function index(Request $request)
    {
        // Get all power plants for dropdown
        $allPowerPlants = PowerPlant::getAllPowerPlants();

        // Initialize variables
        $powerPlants = collect();
        $totalDayaPJBTL = 0;
        $totalDMPExisting = 0;
        $totalRencana = 0;
        $totalRealisasi = 0;
        
        // Get unit source from request or session
        $unitSource = null;
        if ($request->has('unit_source')) {
            $unitSource = $request->get('unit_source');
        } elseif (session('unit') !== 'mysql') {
            $unitSource = session('unit');
        }

        // Only fetch data if a unit is selected
        if ($unitSource) {
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

            // Calculate totals for highlight cards
            $powerPlants->each(function($plant) use ($currentMonth, &$totalDayaPJBTL, &$totalDMPExisting, &$totalRencana, &$totalRealisasi) {
                $plant->machines->each(function($machine) use ($currentMonth, &$totalDayaPJBTL, &$totalDMPExisting, &$totalRencana, &$totalRealisasi) {
                    // Get the latest record for the month
                    $record = $machine->rencanaDayaMampu->first();

                    if ($record) {
                        // Set summary values as text
                        $machine->rencana = $record->rencana;
                        $machine->realisasi = $record->realisasi;
                        // Set numeric values
                        $machine->daya_pjbtl_silm = $record->daya_pjbtl_silm;
                        $machine->dmp_existing = $record->dmp_existing;

                        // Add to totals
                        $totalDayaPJBTL += floatval($record->daya_pjbtl_silm);
                        $totalDMPExisting += floatval($record->dmp_existing);
                        $totalRencana += is_numeric($record->rencana) ? floatval($record->rencana) : 0;
                        $totalRealisasi += is_numeric($record->realisasi) ? floatval($record->realisasi) : 0;

                        // Set daily values from JSON
                        $machine->daily_values = $record->getDailyData($currentMonth);
                    }
                });
            });
        }

        return view('admin.rencana-daya-mampu.index', compact(
            'powerPlants',
            'unitSource',
            'totalDayaPJBTL',
            'totalDMPExisting',
            'totalRencana',
            'totalRealisasi',
            'allPowerPlants'
        ));
    }

    public function update(Request $request)
    {
        $data = $request->isJson() ? $request->json()->all() : $request->all();
        try {
            DB::beginTransaction();

            // Log received data for debugging
            Log::info('Received data:', ['data' => $data]);

            $currentSession = session('unit');
            $isMainDatabase = $currentSession === 'mysql';

            // Validate input data
            if (empty($data['rencana']) && empty($data['realisasi'])) {
                throw new \Exception('Data rencana atau realisasi harus diisi');
            }

            // Process each machine's data
            foreach ($data['rencana'] ?? [] as $machineId => $dates) {
                $machine = Machine::findOrFail($machineId);

                foreach ($dates as $date => $rencanaRows) {
                    Log::info("Processing rencana data for machine $machineId on date $date", [
                        'rows_count' => count($rencanaRows),
                        'rows_data' => $rencanaRows
                    ]);

                    $record = RencanaDayaMampu::firstOrNew([
                        'machine_id' => $machineId,
                        'tanggal' => $date
                    ]);

                    // Get existing or initialize new daily data
                    $dailyData = $record->daily_data ?? [];
                    if (!isset($dailyData[$date])) {
                        $dailyData[$date] = RencanaDayaMampu::getEmptyDayTemplate();
                    }

                    // Process rencana data
                    $formattedRencana = [];
                    foreach ($rencanaRows as $row) {
                        // Add all rows, including empty ones
                        $formattedRencana[] = [
                            'beban' => $row['beban'] ?? '',
                            'durasi' => $row['durasi'] ?? '',
                            'keterangan' => $row['keterangan'] ?? '',
                            'on' => $row['on'] ?? '',
                            'off' => $row['off'] ?? ''
                        ];
                    }

                    Log::info("Formatted rencana data:", ['formatted_data' => $formattedRencana]);

                    $dailyData[$date]['rencana'] = $formattedRencana;

                    // Save the record
                    $record->daily_data = $dailyData;
                    $record->unit_source = $currentSession;
                    $record->save();

                    Log::info("Saved record data:", ['daily_data' => $record->daily_data]);

                    // Sync data if needed
                    if (!$isMainDatabase) {
                        $this->syncToMainDatabase($record);
                    }
                    if ($isMainDatabase) {
                        $this->syncToLocalDatabase($record);
                    }
                }
            }

            // Process realisasi data
            foreach ($data['realisasi'] ?? [] as $machineId => $dates) {
                $machine = Machine::findOrFail($machineId);

                foreach ($dates as $date => $realisasiRows) {
                    Log::info("Processing realisasi data for machine $machineId on date $date", [
                        'rows_count' => count($realisasiRows),
                        'rows_data' => $realisasiRows
                    ]);

                    $record = RencanaDayaMampu::firstOrNew([
                        'machine_id' => $machineId,
                        'tanggal' => $date
                    ]);

                    // Get existing or initialize new daily data
                    $dailyData = $record->daily_data ?? [];
                    if (!isset($dailyData[$date])) {
                        $dailyData[$date] = RencanaDayaMampu::getEmptyDayTemplate();
                    }

                    // Process realisasi data
                    $formattedRealisasi = [];
                    foreach ($realisasiRows as $row) {
                        // Add all rows, including empty ones
                        $formattedRealisasi[] = [
                            'beban' => $row['beban'] ?? '',
                            'keterangan' => $row['keterangan'] ?? ''
                        ];
                    }

                    Log::info("Formatted realisasi data:", ['formatted_data' => $formattedRealisasi]);

                    $dailyData[$date]['realisasi'] = $formattedRealisasi;

                    // Save the record
                    $record->daily_data = $dailyData;
                    $record->unit_source = $currentSession;
                    $record->save();

                    Log::info("Saved record data:", ['daily_data' => $record->daily_data]);

                    // Sync data if needed
                    if (!$isMainDatabase) {
                        $this->syncToMainDatabase($record);
                    }
                    if ($isMainDatabase) {
                        $this->syncToLocalDatabase($record);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan dan disinkronkan',
                'icon' => 'success',
                'title' => 'Berhasil!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('RencanaDayaMampu update error: ' . $e->getMessage());
            Log::error('Error details:', ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                'icon' => 'error',
                'title' => 'Error!'
            ], 500);
        }
    }

    private function syncToMainDatabase($record)
    {
        try {
            // Temporarily change database connection to main database
            $mainRecord = RencanaDayaMampu::on('mysql')->firstOrNew([
                'machine_id' => $record->machine_id,
                'tanggal' => $record->tanggal
            ]);

            // Copy data
            $mainRecord->daily_data = $record->daily_data;
            $mainRecord->daya_pjbtl_silm = $record->daya_pjbtl_silm;
            $mainRecord->dmp_existing = $record->dmp_existing;
            $mainRecord->unit_source = $record->unit_source;
            
            $mainRecord->save();
        } catch (\Exception $e) {
            Log::error('Sync to main database failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function syncToLocalDatabase($record)
    {
        try {
            // Get the machine's power plant to determine which local database to sync to
            $powerPlant = Machine::find($record->machine_id)->powerPlant;
            
            if ($powerPlant && $powerPlant->unit_source !== 'mysql') {
                // Sync to the corresponding local database
                $localRecord = RencanaDayaMampu::on($powerPlant->unit_source)->firstOrNew([
                    'machine_id' => $record->machine_id,
                    'tanggal' => $record->tanggal
                ]);

                // Copy data
                $localRecord->daily_data = $record->daily_data;
                $localRecord->daya_pjbtl_silm = $record->daya_pjbtl_silm;
                $localRecord->dmp_existing = $record->dmp_existing;
                $localRecord->unit_source = $powerPlant->unit_source;
                
                $localRecord->save();
            }
        } catch (\Exception $e) {
            Log::error('Sync to local database failed: ' . $e->getMessage());
            throw $e;
        }
    }

    // Helper method to get daily value
    public function getDayValue($machine, $day)
    {
        $date = Carbon::createFromFormat('Y-m-d', now()->format('Y-m-') . sprintf('%02d', $day));
        $record = $machine->rencanaDayaMampu->first();
        
        return $record ? $record->getDailyValue($date->format('Y-m-d'), 'rencana') : null;
    }

    public function manage(Request $request)
    {
        // Get all power plants for dropdown
        $allPowerPlants = PowerPlant::getAllPowerPlants();

        $unitSource = session('unit') === 'mysql' ? 
            $request->get('unit_source', '') : 
            session('unit');

        // Get selected month and year (default to current)
        $selectedMonth = $request->get('month', now()->format('m'));
        $selectedYear = $request->get('year', now()->format('Y'));
        $selectedDate = $selectedYear . '-' . $selectedMonth;

        // Get power plants with their machines and rencana daya mampu data
        $powerPlants = PowerPlant::when($unitSource !== 'mysql', function($query) use ($unitSource) {
            return $query->where('unit_source', $unitSource);
        })->with(['machines' => function($query) use ($selectedDate) {
            $query->orderBy('name')
                ->with(['rencanaDayaMampu' => function($query) use ($selectedDate) {
                    $query->whereRaw("DATE_FORMAT(tanggal, '%Y-%m') = ?", [$selectedDate]);
                }]);
        }])->orderBy('name')->get();

        return view('admin.rencana-daya-mampu.manage', compact(
            'powerPlants', 
            'unitSource', 
            'selectedMonth', 
            'selectedYear',
            'allPowerPlants'
        ));
    }

    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'pdf');
            $month = $request->get('month', now()->format('m'));
            $year = $request->get('year', now()->format('Y'));
            $unitSource = $request->get('unit_source', session('unit', 'mysql'));
            
            // Format tanggal untuk nama file
            $date = Carbon::createFromFormat('Y-m', "$year-$month");
            $formattedDate = $date->format('F Y');
            
            // Get data with eager loading
            $powerPlants = PowerPlant::when($unitSource !== 'mysql', function($query) use ($unitSource) {
                return $query->where('unit_source', $unitSource);
            })->with(['machines' => function($query) {
                $query->orderBy('name');
            }])->orderBy('name')->get();

            // Get all rencana daya mampu data for the month
            $rencanaDayaMampu = RencanaDayaMampu::whereYear('tanggal', $year)
                ->whereMonth('tanggal', $month)
                ->get()
                ->keyBy('machine_id');

            // Attach rencana daya mampu data to machines
            $powerPlants->each(function($plant) use ($rencanaDayaMampu) {
                $plant->machines->each(function($machine) use ($rencanaDayaMampu) {
                    $machine->rencanaDayaMampu = collect([$rencanaDayaMampu->get($machine->id)]);
                });
            });

            if ($format === 'pdf') {
                $pdf = PDF::loadView('admin.rencana-daya-mampu.pdf', [
                    'powerPlants' => $powerPlants,
                    'month' => $month,
                    'year' => $year,
                    'date' => $formattedDate,
                    'unitSource' => $unitSource
                ]);
                
                $pdf->setPaper('A4', 'landscape');
                return $pdf->download("Rencana Daya Mampu ($formattedDate).pdf");
            } else {
                return Excel::download(
                    new RencanaDayaMampuExport($powerPlants, $month, $year, $unitSource),
                    "Rencana Daya Mampu ($formattedDate).xlsx"
                );
            }
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengexport data');
        }
    }
}   