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

            // Tambahkan logging untuk debug
            Log::info('Received data:', ['data' => $data]);

            $currentDate = now()->format('Y-m-d');
            $currentSession = session('unit');
            $isMainDatabase = $currentSession === 'mysql';

            // Validasi data input
            if (empty($data['rencana']) && empty($data['realisasi'])) {
                throw new \Exception('Data rencana atau realisasi harus diisi');
            }

            // Kumpulkan data harian dari request
            $dailyData = [];
            
            // Process rencana data
            foreach ($data['rencana'] ?? [] as $machineId => $rencanaArr) {
                // Convert machineId to integer if it's a valid numeric string
                $machineId = is_numeric($machineId) ? intval($machineId) : null;
                
                if ($machineId === null) {
                    Log::error('Invalid machine_id received:', ['machine_id' => $machineId]);
                    throw new \Exception('ID mesin tidak valid: ' . $machineId);
                }

                // Verify machine exists
                $machine = Machine::find($machineId);
                if (!$machine) {
                    throw new \Exception('Mesin dengan ID ' . $machineId . ' tidak ditemukan');
                }

                foreach ($rencanaArr as $date => $rencanaRows) {
                    // Validasi format tanggal
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                        throw new \Exception('Format tanggal tidak valid: ' . $date);
                    }

                    if (!isset($dailyData[$machineId])) {
                        $dailyData[$machineId] = [];
                    }
                    if (!isset($dailyData[$machineId][$date])) {
                        $dailyData[$machineId][$date] = RencanaDayaMampu::getEmptyDayTemplate();
                    }
                    
                    // Format data rencana
                    $formattedRencana = [];
                    foreach ($rencanaRows as $row) {
                        // Validasi data row
                        if (!empty($row['beban']) || !empty($row['on']) || !empty($row['off'])) {
                            // Validasi format waktu
                            if (!empty($row['on']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row['on'])) {
                                throw new \Exception('Format waktu ON tidak valid');
                            }
                            if (!empty($row['off']) && !preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $row['off'])) {
                                throw new \Exception('Format waktu OFF tidak valid');
                            }

                            $formattedRencana[] = [
                                'beban' => $row['beban'] ?? '',
                                'durasi' => $row['durasi'] ?? '',
                                'keterangan' => $row['keterangan'] ?? '',
                                'on' => $row['on'] ?? '',
                                'off' => $row['off'] ?? ''
                            ];
                }
            }
                    
                    if (!empty($formattedRencana)) {
                        $dailyData[$machineId][$date]['rencana'] = $formattedRencana;
                    }
                }
            }

            // Process realisasi data dengan validasi yang sama
            foreach ($data['realisasi'] ?? [] as $machineId => $realisasiArr) {
                $machineId = is_numeric($machineId) ? intval($machineId) : null;
                
                if ($machineId === null) {
                    Log::error('Invalid machine_id received in realisasi:', ['machine_id' => $machineId]);
                    throw new \Exception('ID mesin tidak valid pada realisasi: ' . $machineId);
                }

                // Verify machine exists
                $machine = Machine::find($machineId);
                if (!$machine) {
                    throw new \Exception('Mesin dengan ID ' . $machineId . ' tidak ditemukan pada realisasi');
                }

                foreach ($realisasiArr as $date => $realisasiValue) {
                    // Validasi format tanggal
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
                        throw new \Exception('Format tanggal tidak valid pada realisasi: ' . $date);
                    }

                    if (!isset($dailyData[$machineId])) {
                        $dailyData[$machineId] = [];
                    }
                    if (!isset($dailyData[$machineId][$date])) {
                        $dailyData[$machineId][$date] = RencanaDayaMampu::getEmptyDayTemplate();
                    }
                    $dailyData[$machineId][$date]['realisasi'] = [
                        'beban' => $realisasiValue['beban'] ?? '',
                        'keterangan' => $realisasiValue['keterangan'] ?? ''
                    ];
                }
            }

            // Log processed data
            Log::info('Processed daily data:', ['dailyData' => $dailyData]);

            // Process each machine's data
            foreach ($dailyData as $machineId => $dates) {
                foreach ($dates as $date => $values) {
                    $record = RencanaDayaMampu::firstOrNew([
                        'machine_id' => $machineId,
                        'tanggal' => $date
                    ]);

                    // Merge dengan data yang sudah ada
                    $oldDailyData = $record->daily_data ?? [];
                    if (!isset($oldDailyData[$date])) {
                        $oldDailyData[$date] = RencanaDayaMampu::getEmptyDayTemplate();
                    }
                    
                    // Update data
                    $oldDailyData[$date] = array_merge($oldDailyData[$date], $values);
                    $record->daily_data = $oldDailyData;
                    $record->unit_source = $currentSession;
                    $record->save();

                    // Sync data
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
            
            // Get data
            $powerPlants = PowerPlant::when($unitSource !== 'mysql', function($query) use ($unitSource) {
                return $query->where('unit_source', $unitSource);
            })->with(['machines' => function($query) use ($year, $month) {
                $query->orderBy('name')
                    ->with(['rencanaDayaMampu' => function($query) use ($year, $month) {
                        $query->whereYear('tanggal', $year)
                              ->whereMonth('tanggal', $month);
                    }]);
            }])->orderBy('name')->get();

            if ($format === 'pdf') {
                $pdf = PDF::loadView('admin.rencana-daya-mampu.pdf', [
                    'powerPlants' => $powerPlants,
                    'month' => $month,
                    'year' => $year,
                    'date' => $formattedDate,
                    'unitSource' => $unitSource
                ]);
                
                // Set paper orientation to landscape and size to A4
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