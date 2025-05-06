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
            $machines = Machine::orderBy('name')->get();
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
        // Validasi sesuai kebutuhan
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'unit_source' => 'nullable|string',
            // ...tambahkan validasi lain sesuai kebutuhan
        ]);

        // Simpan ke tabel laporan_kits
        $laporanKit = LaporanKit::create([
            'tanggal' => $validated['tanggal'],
            'unit_source' => $validated['unit_source'] ?? null,
            'created_by' => Auth::id(),
        ]);

        // Simpan BBM
        if ($request->has('bbm')) {
            foreach ($request->bbm as $bbm) {
                $laporanKit->bbm()->create($bbm);
            }
        }
        // Simpan KWH
        if ($request->has('kwh')) {
            foreach ($request->kwh as $kwh) {
                $laporanKit->kwh()->create($kwh);
            }
        }
        // Simpan Pelumas
        if ($request->has('pelumas')) {
            foreach ($request->pelumas as $pelumas) {
                $laporanKit->pelumas()->create($pelumas);
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

        return redirect()->route('admin.laporan-kit.index')->with('success', 'Data berhasil disimpan');
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
            'jamOperasi', 'gangguan', 'bbm', 'kwh', 'pelumas', 'bahanKimia', 'bebanTertinggi', 'creator'
        ])->findOrFail($id);

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