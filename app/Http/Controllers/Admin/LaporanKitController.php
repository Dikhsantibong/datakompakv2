<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\LaporanKit;
use App\Models\LaporanKitBbm;
use App\Models\LaporanKitBbmStorageTank;
use App\Models\LaporanKitBbmServiceTank;
use App\Models\LaporanKitBbmFlowmeter;
use App\Models\LaporanKitKwh;
use App\Models\LaporanKitKwhProductionPanel;
use App\Models\LaporanKitKwhPsPanel;
use App\Models\LaporanKitPelumas;
use App\Models\LaporanKitPelumasStorageTank;
use App\Models\LaporanKitPelumasDrum;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKitExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Log as LogFacade;

class LaporanKitController extends Controller
{
    public function index(Request $request)
    {
        $unitSource = $request->get('unit_source');
        $powerPlants = PowerPlant::orderBy('name')->get();
        if ($unitSource) {
            $machines = Machine::whereHas('powerPlant', function($q) use ($unitSource) {
                $q->where('unit_source', $unitSource);
            })->orderBy('name')->get();
        } else {
            $machines = Machine::orderBy('name')->take(1)->get();
        }
        return view('admin.laporan-kit.index', compact('machines', 'powerPlants', 'unitSource'));
    }

    public function create()
    {
        $powerPlant = PowerPlant::with('machines')->first();
        $machines = $powerPlant ? $powerPlant->machines : collect([]);
        
        return view('admin.laporan-kit.create', compact('machines'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi sesuai kebutuhan
            $validated = $request->validate([
                'tanggal' => 'required|date',
                'unit_source' => 'nullable|string',
            ]);

            // Simpan ke tabel laporan_kits
            $laporanKit = LaporanKit::create([
                'tanggal' => $validated['tanggal'],
                'unit_source' => $validated['unit_source'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Simpan BBM dengan struktur baru
            if ($request->has('bbm')) {
                // Create single BBM record for all rows
                $bbm = new LaporanKitBbm([
                    'total_stok' => collect($request->bbm)->sum('total_stok') ?? 0,
                    'service_total_stok' => collect($request->bbm)->sum('service_total_stok') ?? 0,
                    'total_stok_tangki' => collect($request->bbm)->sum('total_stok_tangki') ?? 0,
                    'terima_bbm' => collect($request->bbm)->sum('terima_bbm') ?? 0,
                    'total_pakai' => collect($request->bbm)->sum('total_pakai') ?? 0
                ]);
                $laporanKit->bbm()->save($bbm);

                foreach ($request->bbm as $bbmData) {
                    // Create storage tanks
                    for ($i = 1; isset($bbmData["storage_tank_{$i}_cm"]); $i++) {
                        $storageTank = new LaporanKitBbmStorageTank([
                            'tank_number' => $i,
                            'cm' => $bbmData["storage_tank_{$i}_cm"],
                            'liter' => $bbmData["storage_tank_{$i}_liter"]
                        ]);
                        $bbm->storageTanks()->save($storageTank);
                    }

                    // Create service tanks
                    for ($i = 1; isset($bbmData["service_tank_{$i}_liter"]); $i++) {
                        $serviceTank = new LaporanKitBbmServiceTank([
                            'tank_number' => $i,
                            'liter' => $bbmData["service_tank_{$i}_liter"],
                            'percentage' => $bbmData["service_tank_{$i}_percentage"]
                        ]);
                        $bbm->serviceTanks()->save($serviceTank);
                    }

                    // Create flowmeters - Modified to handle correct flowmeter numbers
                    $processedFlowmeters = [];
                    
                    foreach ($bbmData as $key => $value) {
                        if (preg_match('/^flowmeter_(\d+)_awal$/', $key, $matches)) {
                            $flowmeterNumber = $matches[1];
                            
                            // Skip if we've already processed this flowmeter number
                            if (in_array($flowmeterNumber, $processedFlowmeters)) {
                                continue;
                            }
                            
                            if (
                                isset($bbmData["flowmeter_{$flowmeterNumber}_awal"]) &&
                                isset($bbmData["flowmeter_{$flowmeterNumber}_akhir"]) &&
                                isset($bbmData["flowmeter_{$flowmeterNumber}_pakai"])
                            ) {
                                $flowmeter = new LaporanKitBbmFlowmeter([
                                    'flowmeter_number' => $flowmeterNumber,
                                    'awal' => $bbmData["flowmeter_{$flowmeterNumber}_awal"],
                                    'akhir' => $bbmData["flowmeter_{$flowmeterNumber}_akhir"],
                                    'pakai' => $bbmData["flowmeter_{$flowmeterNumber}_pakai"]
                                ]);
                                $bbm->flowmeters()->save($flowmeter);
                                $processedFlowmeters[] = $flowmeterNumber;
                                
                                Log::info("Created flowmeter #{$flowmeterNumber} for BBM ID: {$bbm->id}", [
                                    'awal' => $bbmData["flowmeter_{$flowmeterNumber}_awal"],
                                    'akhir' => $bbmData["flowmeter_{$flowmeterNumber}_akhir"],
                                    'pakai' => $bbmData["flowmeter_{$flowmeterNumber}_pakai"]
                                ]);
                            }
                        }
                    }

                    Log::info("Total flowmeters created: " . count($processedFlowmeters) . " for BBM ID: {$bbm->id}");
                }
            }

            // Store Pelumas with new structure
            if ($request->has('pelumas')) {
                // Create single Pelumas record
                $pelumas = new LaporanKitPelumas([
                    'tank_total_stok' => collect($request->pelumas)->sum('tank_total_stok') ?? 0,
                    'drum_total_stok' => collect($request->pelumas)->sum('drum_total_stok') ?? 0,
                    'total_stok_tangki' => collect($request->pelumas)->sum('total_stok_tangki') ?? 0,
                    'terima_pelumas' => collect($request->pelumas)->sum('terima_pelumas') ?? 0,
                    'total_pakai' => collect($request->pelumas)->sum('total_pakai') ?? 0,
                    'jenis' => $request->pelumas[0]['jenis'] ?? null
                ]);
                $laporanKit->pelumas()->save($pelumas);

                foreach ($request->pelumas as $pelumasData) {
                    // Create storage tanks
                    for ($i = 1; isset($pelumasData["tank_{$i}_cm"]); $i++) {
                        $storageTank = new LaporanKitPelumasStorageTank([
                            'tank_number' => $i,
                            'cm' => $pelumasData["tank_{$i}_cm"],
                            'liter' => $pelumasData["tank_{$i}_liter"]
                        ]);
                        $pelumas->storageTanks()->save($storageTank);
                    }

                    // Create drums
                    for ($i = 1; isset($pelumasData["drum_area{$i}"]); $i++) {
                        $drum = new LaporanKitPelumasDrum([
                            'area_number' => $i,
                            'jumlah' => $pelumasData["drum_area{$i}"]
                        ]);
                        $pelumas->drums()->save($drum);
                    }
                }
            }

            // Store KWH with new structure
            if ($request->has('kwh')) {
                // Create single KWH record
                $kwh = new LaporanKitKwh([
                    'prod_total' => collect($request->kwh)->sum('prod_total') ?? 0,
                    'ps_total' => collect($request->kwh)->sum('ps_total') ?? 0
                ]);
                $laporanKit->kwh()->save($kwh);

                foreach ($request->kwh as $kwhData) {
                    // Create production panels
                    for ($i = 1; isset($kwhData["prod_panel{$i}_awal"]); $i++) {
                        $prodPanel = new LaporanKitKwhProductionPanel([
                            'panel_number' => $i,
                            'awal' => $kwhData["prod_panel{$i}_awal"],
                            'akhir' => $kwhData["prod_panel{$i}_akhir"]
                        ]);
                        $kwh->productionPanels()->save($prodPanel);
                    }

                    // Create PS panels
                    for ($i = 1; isset($kwhData["ps_panel{$i}_awal"]); $i++) {
                        $psPanel = new LaporanKitKwhPsPanel([
                            'panel_number' => $i,
                            'awal' => $kwhData["ps_panel{$i}_awal"],
                            'akhir' => $kwhData["ps_panel{$i}_akhir"]
                        ]);
                        $kwh->psPanels()->save($psPanel);
                    }
                }
            }

            // Simpan Bahan Kimia
            if ($request->has('bahan_kimia')) {
                foreach ($request->bahan_kimia as $bahan_kimia) {
                    $laporanKit->bahanKimia()->create($bahan_kimia);
                }
            }

            // Simpan Jam Operasi
            if ($request->has('mesin')) {
                foreach ($request->mesin as $machineId => $mesin) {
                    $mesin['machine_id'] = $machineId;
                    $laporanKit->jamOperasi()->create($mesin);
                }
            }

            // Simpan Beban Tertinggi
            if ($request->has('beban')) {
                foreach ($request->beban as $machineId => $beban) {
                    $beban['machine_id'] = $machineId;
                    $laporanKit->bebanTertinggi()->create($beban);
                }
            }

            // Simpan Gangguan
            if ($request->has('gangguan')) {
                foreach ($request->gangguan as $machineId => $gangguan) {
                    if (!empty($gangguan['mekanik']) || !empty($gangguan['elektrik'])) {
                        $gangguan['machine_id'] = $machineId;
                        $laporanKit->gangguan()->create($gangguan);
                    }
                }
            }

            // Reload relationships to ensure all data is synced
            $laporanKit->load([
                'jamOperasi',
                'gangguan',
                'bbm',
                'bbm.storageTanks',
                'bbm.serviceTanks',
                'bbm.flowmeters',
                'kwh',
                'kwh.productionPanels',
                'kwh.psPanels',
                'pelumas',
                'pelumas.storageTanks',
                'pelumas.drums',
                'bahanKimia',
                'bebanTertinggi'
            ]);

            DB::commit();
            return redirect()->route('admin.laporan-kit.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing LaporanKit:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        $query = LaporanKit::with([
            'jamOperasi', 'gangguan', 'bbm', 'kwh', 'pelumas', 'bahanKimia', 'bebanTertinggi', 'creator'
        ]);

        // Apply unit source filter
        if ($request->has('unit_source') && $request->unit_source !== '') {
            $query->where('unit_source', $request->unit_source);
        }

        // Apply date range filter
        if ($request->has('start_date') && $request->start_date !== '') {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date !== '') {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Order by date descending
        $query->orderBy('tanggal', 'desc');

        $laporanKits = $query->paginate(10);
        $powerPlants = PowerPlant::orderBy('name')->get();

        return view('admin.laporan-kit.list', compact('laporanKits', 'powerPlants'));
    }

    public function show($id)
    {
        $laporan = LaporanKit::with([
            'jamOperasi', 
            'gangguan', 
            'bbm',
            'bbm.storageTanks',
            'bbm.serviceTanks',
            'bbm.flowmeters',
            'kwh',
            'kwh.productionPanels',
            'kwh.psPanels',
            'pelumas',
            'pelumas.storageTanks',
            'pelumas.drums',
            'bahanKimia', 
            'bebanTertinggi', 
            'creator'
        ])->findOrFail($id);

        // Debug data loading
        if ($laporan->bbm->isEmpty()) {
            LogFacade::info('No BBM data found for laporan ID: ' . $id);
        } else {
            LogFacade::info('BBM data found. First BBM record ID: ' . $laporan->bbm->first()->id);
            LogFacade::info('Service Tanks count: ' . $laporan->bbm->first()->serviceTanks->count());
            LogFacade::info('Storage Tanks count: ' . $laporan->bbm->first()->storageTanks->count());
            LogFacade::info('Flowmeters count: ' . $laporan->bbm->first()->flowmeters->count());

            // Log detailed tank information
            foreach ($laporan->bbm->first()->storageTanks as $index => $tank) {
                LogFacade::info("Storage Tank #{$tank->tank_number}: CM = {$tank->cm}, Liter = {$tank->liter}");
            }
            foreach ($laporan->bbm->first()->serviceTanks as $index => $tank) {
                LogFacade::info("Service Tank #{$tank->tank_number}: Liter = {$tank->liter}, Percentage = {$tank->percentage}");
            }
            foreach ($laporan->bbm->first()->flowmeters as $index => $meter) {
                LogFacade::info("Flowmeter #{$meter->flowmeter_number}: Awal = {$meter->awal}, Akhir = {$meter->akhir}, Pakai = {$meter->pakai}");
            }
        }

        // Get max tank numbers for view
        $maxStorageTanks = 0;
        $maxServiceTanks = 0;
        $maxFlowmeters = 0;
        if (!$laporan->bbm->isEmpty()) {
            $maxStorageTanks = $laporan->bbm->first()->storageTanks->max('tank_number');
            $maxServiceTanks = $laporan->bbm->first()->serviceTanks->max('tank_number');
            $maxFlowmeters = $laporan->bbm->first()->flowmeters->max('flowmeter_number');
        }

        return view('admin.laporan-kit.show', compact('laporan', 'maxStorageTanks', 'maxServiceTanks', 'maxFlowmeters'));
    }

    public function exportPdf($id)
    {
        $laporan = LaporanKit::with([
            'jamOperasi', 'gangguan', 'bbm', 'kwh', 'pelumas', 'bahanKimia', 'bebanTertinggi', 'creator'
        ])->findOrFail($id);

        $powerPlants = \App\Models\PowerPlant::orderBy('name')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan-kit.pdf', compact('laporan', 'powerPlants'));
        return $pdf->download('laporan_kit_'.$laporan->id.'.pdf');
    }

    public function exportExcel(Request $request)
    {
        try {
            $id = $request->route('id');
            
            if ($id) {
                // Single report export
                $laporan = LaporanKit::find($id);
                if (!$laporan) {
                    return redirect()->back()
                        ->with('error', 'Laporan tidak ditemukan');
                }
                $filename = 'laporan_kit_' . date('Y_m_d', strtotime($laporan->tanggal)) . '.xlsx';
            } else {
                // Export current date if no specific report
                $filename = 'laporan_kit_' . date('Y_m_d') . '.xlsx';
            }

            return Excel::download(new LaporanKitExport($id), $filename);
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengekspor laporan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $laporan = LaporanKit::with([
            'jamOperasi', 'gangguan', 'bbm', 'kwh', 'pelumas', 'bahanKimia', 'bebanTertinggi', 'creator', 'powerPlant'
        ])->findOrFail($id);

        $powerPlants = \App\Models\PowerPlant::orderBy('name')->get();
        $machines = \App\Models\Machine::orderBy('name')->get();

        return view('admin.laporan-kit.edit', compact('laporan', 'powerPlants', 'machines'));
    }

    public function update(Request $request, $id)
    {
        $laporan = LaporanKit::findOrFail($id);

        // Validasi dan update data utama
        $laporan->update($request->all());

        // Update relasi detail jika perlu (jamOperasi, gangguan, dst)
        // ... (tambahkan logic sesuai kebutuhan)

        return redirect()->route('admin.laporan-kit.list')->with('success', 'Laporan berhasil diupdate!');
    }
} 