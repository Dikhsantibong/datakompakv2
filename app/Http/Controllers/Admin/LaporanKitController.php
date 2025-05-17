<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\LaporanKit;
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
                $bbm = $laporanKit->bbm()->create([
                    'total_stok' => collect($request->bbm)->sum('total_stok') ?? 0,
                    'service_total_stok' => collect($request->bbm)->sum('service_total_stok') ?? 0,
                    'total_stok_tangki' => collect($request->bbm)->sum('total_stok_tangki') ?? 0,
                    'terima_bbm' => collect($request->bbm)->sum('terima_bbm') ?? 0,
                    'total_pakai' => collect($request->bbm)->sum('total_pakai') ?? 0
                ]);

                // Initialize arrays for batch insertion
                $storageTanks = [];
                $serviceTanks = [];
                $flowmeters = [];

                // Process all rows to collect tank and flowmeter data
                foreach ($request->bbm as $bbmData) {
                    // Collect storage tanks data
                    for ($i = 1; isset($bbmData["storage_tank_{$i}_cm"]); $i++) {
                        $storageTanks[] = [
                            'laporan_kit_bbm_id' => $bbm->id,
                            'tank_number' => $i,
                            'cm' => $bbmData["storage_tank_{$i}_cm"],
                            'liter' => $bbmData["storage_tank_{$i}_liter"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    // Collect service tanks data
                    for ($i = 1; isset($bbmData["service_tank_{$i}_liter"]); $i++) {
                        $serviceTanks[] = [
                            'laporan_kit_bbm_id' => $bbm->id,
                            'tank_number' => $i,
                            'liter' => $bbmData["service_tank_{$i}_liter"],
                            'percentage' => $bbmData["service_tank_{$i}_percentage"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    // Collect flowmeters data
                    for ($i = 1; isset($bbmData["flowmeter_{$i}_awal"]); $i++) {
                        $flowmeters[] = [
                            'laporan_kit_bbm_id' => $bbm->id,
                            'flowmeter_number' => $i,
                            'awal' => $bbmData["flowmeter_{$i}_awal"],
                            'akhir' => $bbmData["flowmeter_{$i}_akhir"],
                            'pakai' => $bbmData["flowmeter_{$i}_pakai"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                // Batch insert all collected data
                if (!empty($storageTanks)) {
                    $bbm->storageTanks()->insert($storageTanks);
                }
                if (!empty($serviceTanks)) {
                    $bbm->serviceTanks()->insert($serviceTanks);
                }
                if (!empty($flowmeters)) {
                    $bbm->flowmeters()->insert($flowmeters);
                }
            }

            // Store Pelumas with new structure
            if ($request->has('pelumas')) {
                // Create single Pelumas record for all rows
                $pelumas = $laporanKit->pelumas()->create([
                    'tank_total_stok' => collect($request->pelumas)->sum('tank_total_stok') ?? 0,
                    'drum_total_stok' => collect($request->pelumas)->sum('drum_total_stok') ?? 0,
                    'total_stok_tangki' => collect($request->pelumas)->sum('total_stok_tangki') ?? 0,
                    'terima_pelumas' => collect($request->pelumas)->sum('terima_pelumas') ?? 0,
                    'total_pakai' => collect($request->pelumas)->sum('total_pakai') ?? 0,
                    'jenis' => $request->pelumas[0]['jenis'] ?? null
                ]);

                // Initialize arrays for batch insertion
                $storageTanks = [];
                $drums = [];

                // Process all rows to collect tank and drum data
                foreach ($request->pelumas as $pelumasData) {
                    // Debug log for pelumas data
                    Log::info('Processing pelumas data:', ['data' => $pelumasData]);

                    // Collect storage tanks data - Fix the field names to match the form
                    for ($i = 1; isset($pelumasData["tank_{$i}_cm"]); $i++) {
                        $storageTanks[] = [
                            'laporan_kit_pelumas_id' => $pelumas->id,
                            'tank_number' => $i,
                            'cm' => $pelumasData["tank_{$i}_cm"],
                            'liter' => $pelumasData["tank_{$i}_liter"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                        // Debug log for storage tank data
                        Log::info("Added storage tank data:", end($storageTanks));
                    }

                    // Collect drums data
                    for ($i = 1; isset($pelumasData["drum_area{$i}"]); $i++) {
                        $drums[] = [
                            'laporan_kit_pelumas_id' => $pelumas->id,
                            'area_number' => $i,
                            'jumlah' => $pelumasData["drum_area{$i}"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                // Debug log for final collections
                Log::info('Storage tanks to insert:', ['count' => count($storageTanks), 'data' => $storageTanks]);
                Log::info('Drums to insert:', ['count' => count($drums), 'data' => $drums]);

                // Batch insert all collected data
                if (!empty($storageTanks)) {
                    $pelumas->storageTanks()->insert($storageTanks);
                }
                if (!empty($drums)) {
                    $pelumas->drums()->insert($drums);
                }
            }

            // Simpan KWH
            if ($request->has('kwh')) {
                // Create single KWH record for all rows
                $kwh = $laporanKit->kwh()->create([
                    'prod_total' => collect($request->kwh)->sum('prod_total') ?? 0,
                    'ps_total' => collect($request->kwh)->sum('ps_total') ?? 0
                ]);

                // Process all rows to collect panel data
                $productionPanels = [];
                $psPanels = [];

                foreach ($request->kwh as $kwhData) {
                    // Collect production panels data
                    for ($i = 1; isset($kwhData["prod_panel{$i}_awal"]); $i++) {
                        $productionPanels[] = [
                            'laporan_kit_kwh_id' => $kwh->id,
                            'panel_number' => $i,
                            'awal' => $kwhData["prod_panel{$i}_awal"],
                            'akhir' => $kwhData["prod_panel{$i}_akhir"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }

                    // Collect PS panels data
                    for ($i = 1; isset($kwhData["ps_panel{$i}_awal"]); $i++) {
                        $psPanels[] = [
                            'laporan_kit_kwh_id' => $kwh->id,
                            'panel_number' => $i,
                            'awal' => $kwhData["ps_panel{$i}_awal"],
                            'akhir' => $kwhData["ps_panel{$i}_akhir"],
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                }

                // Insert all production panels at once
                if (!empty($productionPanels)) {
                    $kwh->productionPanels()->insert($productionPanels);
                }

                // Insert all PS panels at once
                if (!empty($psPanels)) {
                    $kwh->psPanels()->insert($psPanels);
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

            DB::commit();
            return redirect()->route('admin.laporan-kit.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
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
        }

        return view('admin.laporan-kit.show', compact('laporan'));
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