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
            'created_by' => auth()->id(),
        ]);

        // Simpan detail lain (jam operasi, gangguan, dst) jika ada

        return redirect()->route('admin.laporan-kit.index')
            ->with('success', 'Data berhasil disimpan');
    }

    public function list(Request $request)
    {
        $laporanKits = LaporanKit::with([
            'jamOperasi', 'gangguan', 'bbm', 'kwh', 'pelumas', 'bahanKimia', 'bebanTertinggi', 'creator'
        ])->orderBy('tanggal', 'desc')->paginate(10);
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
        return Excel::download(new LaporanKitExport, 'laporan_kit.xlsx');
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