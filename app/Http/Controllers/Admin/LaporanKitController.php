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
            $machines = Machine::orderBy('name')->get();
        $unitSource = 'mysql'; // Set default unit source
        return view('admin.laporan-kit.index', compact('machines', 'unitSource'));
    }

    public function create()
    {
        $powerPlant = PowerPlant::with('machines')->first();
        $machines = $powerPlant ? $powerPlant->machines : collect([]);
        
        return view('admin.laporan-kit.create', compact('machines'));
    }

    public function store(Request $request)
    {
        // Log semua data yang diterima
        Log::info('Received form data:', [
            'all_data' => $request->all(),
            'bbm_data' => $request->input('bbm'),
            'pelumas_data' => $request->input('pelumas')
        ]);

        DB::beginTransaction();
        try {
            // Get the first PowerPlant's unit_source
            $powerPlant = PowerPlant::first();
            $unitSource = $powerPlant ? $powerPlant->unit_source : 'mysql';

            // Set today's date automatically and use PowerPlant's unit_source
            $request->merge([
                'tanggal' => now()->format('Y-m-d'),
                'unit_source' => $unitSource
            ]);

            // Simpan ke tabel laporan_kits
            $laporanKit = LaporanKit::create([
                'tanggal' => $request->tanggal,
                'unit_source' => $request->unit_source,
                'created_by' => Auth::id(),
            ]);

            Log::info('Created LaporanKit:', ['id' => $laporanKit->id]);

            // Store BBM data per machine
            if ($request->has('bbm')) {
                Log::info('Processing BBM data');
                foreach ($request->bbm as $machineId => $bbmData) {
                    Log::info('Processing BBM for machine:', [
                        'machine_id' => $machineId,
                        'bbm_data' => $bbmData
                    ]);

                    // Create BBM record for this machine
                    $bbm = new LaporanKitBbm([
                        'laporan_kit_id' => $laporanKit->id,
                        'machine_id' => $machineId,
                        'total_stok' => $bbmData['total_stok'] ?? 0,
                        'service_total_stok' => $bbmData['service_total_stok'] ?? 0,
                        'total_stok_tangki' => $bbmData['total_stok_tangki'] ?? 0,
                        'terima_bbm' => $bbmData['terima_bbm'] ?? 0,
                        'total_pakai' => $bbmData['total_pakai'] ?? 0
                    ]);

                    $saved = $laporanKit->bbm()->save($bbm);
                    Log::info('BBM record saved:', ['success' => $saved, 'bbm_id' => $bbm->id ?? null]);

                    // Storage Tanks
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($bbmData["storage_tank_{$i}_cm"])) {
                            $storageTank = new LaporanKitBbmStorageTank([
                                'tank_number' => $i,
                                'cm' => $bbmData["storage_tank_{$i}_cm"],
                                'liter' => $bbmData["storage_tank_{$i}_liter"]
                            ]);
                            $saved = $bbm->storageTanks()->save($storageTank);
                            Log::info("Storage tank {$i} saved:", [
                                'success' => $saved,
                                'tank_data' => $storageTank->toArray()
                            ]);
                        }
                    }

                    // Service Tanks
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($bbmData["service_tank_{$i}_liter"])) {
                            $serviceTank = new LaporanKitBbmServiceTank([
                                'tank_number' => $i,
                                'liter' => $bbmData["service_tank_{$i}_liter"],
                                'percentage' => $bbmData["service_tank_{$i}_percentage"]
                            ]);
                            $saved = $bbm->serviceTanks()->save($serviceTank);
                            Log::info("Service tank {$i} saved:", [
                                'success' => $saved,
                                'tank_data' => $serviceTank->toArray()
                            ]);
                        }
                    }

                    // Flowmeters
                    $processedFlowmeters = [];
                    foreach ($bbmData as $key => $value) {
                        if (preg_match('/^flowmeter_(\d+)_awal$/', $key, $matches)) {
                            $flowmeterNumber = $matches[1];
                            
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
                                $saved = $bbm->flowmeters()->save($flowmeter);
                                Log::info("Flowmeter {$flowmeterNumber} saved:", [
                                    'success' => $saved,
                                    'flowmeter_data' => $flowmeter->toArray()
                                ]);
                                $processedFlowmeters[] = $flowmeterNumber;
                            }
                        }
                    }
                }
            } else {
                Log::warning('No BBM data in request');
            }

            // Store Pelumas data per machine
            if ($request->has('pelumas')) {
                Log::info('Processing Pelumas data');
                foreach ($request->pelumas as $machineId => $pelumasData) {
                    Log::info('Processing Pelumas for machine:', [
                        'machine_id' => $machineId,
                        'pelumas_data' => $pelumasData
                    ]);

                    // Create Pelumas record for this machine
                    $pelumas = new LaporanKitPelumas([
                        'laporan_kit_id' => $laporanKit->id,
                        'machine_id' => $machineId,
                        'tank_total_stok' => $pelumasData['tank_total_stok'] ?? 0,
                        'drum_total_stok' => $pelumasData['drum_total_stok'] ?? 0,
                        'total_stok_tangki' => $pelumasData['total_stok_tangki'] ?? 0,
                        'terima_pelumas' => $pelumasData['terima_pelumas'] ?? 0,
                        'total_pakai' => $pelumasData['total_pakai'] ?? 0,
                        'jenis' => $pelumasData['jenis'] ?? null
                    ]);

                    $saved = $laporanKit->pelumas()->save($pelumas);
                    Log::info('Pelumas record saved:', ['success' => $saved, 'pelumas_id' => $pelumas->id ?? null]);

                    // Storage tanks
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($pelumasData["tank_{$i}_cm"])) {
                            $storageTank = new LaporanKitPelumasStorageTank([
                                'tank_number' => $i,
                                'cm' => $pelumasData["tank_{$i}_cm"],
                                'liter' => $pelumasData["tank_{$i}_liter"]
                            ]);
                            $saved = $pelumas->storageTanks()->save($storageTank);
                            Log::info("Pelumas storage tank {$i} saved:", [
                                'success' => $saved,
                                'tank_data' => $storageTank->toArray()
                            ]);
                        }
                    }

                    // Drums
                    for ($i = 1; $i <= 5; $i++) {
                        if (isset($pelumasData["drum_area{$i}"])) {
                            $drum = new LaporanKitPelumasDrum([
                                'area_number' => $i,
                                'jumlah' => $pelumasData["drum_area{$i}"]
                            ]);
                            $saved = $pelumas->drums()->save($drum);
                            Log::info("Pelumas drum {$i} saved:", [
                                'success' => $saved,
                                'drum_data' => $drum->toArray()
                            ]);
                        }
                    }
                }
            } else {
                Log::warning('No Pelumas data in request');
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
                if (!empty($gangguan['mekanik']) || !empty($gangguan['elektrik']) || !empty($gangguan['keterangan'])) {
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
            Log::info('Transaction committed successfully');
            return redirect()->route('admin.laporan-kit.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error storing data:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
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
            'bbm.machine',
            'kwh',
            'kwh.productionPanels',
            'kwh.psPanels',
            'pelumas',
            'pelumas.storageTanks',
            'pelumas.drums',
            'pelumas.machine',
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

    public function destroy($id)
    {
        try {
            $laporanKit = LaporanKit::findOrFail($id);
            $laporanKit->delete();
            
            return redirect()->route('admin.laporan-kit.list')
                ->with('success', 'Laporan KIT berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.laporan-kit.list')
                ->with('error', 'Gagal menghapus Laporan KIT.');
        }
    }
} 