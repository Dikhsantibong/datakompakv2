<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blackstart;
use App\Models\PeralatanBlackstart;
use App\Models\PowerPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BlackstartExport;
use PDF;

class BlackstartController extends Controller
{
    public function index()
    {
        $powerPlants = PowerPlant::all();
        return view('admin.blackstart.index', compact('powerPlants'));
    }

    public function show(Request $request)
    {
        $query = Blackstart::with(['powerPlant', 'peralatanBlackstarts']);

        // Filter by unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        // Filter by pembangkit status
        if ($request->filled('pembangkit_status')) {
            $query->where('pembangkit_status', $request->pembangkit_status);
        }

        // Filter by black start status
        if ($request->filled('black_start_status')) {
            $query->where('black_start_status', $request->black_start_status);
        }

        // Filter by PIC
        if ($request->filled('pic')) {
            $query->where('pic', 'like', '%' . $request->pic . '%');
        }

        $blackstarts = $query->orderBy('tanggal', 'desc')->get();
        $powerPlants = PowerPlant::all();

        return view('admin.blackstart.show', compact('blackstarts', 'powerPlants'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate main blackstart data
            $request->validate([
                'tanggal' => 'required|date',
                'unit_id.*' => 'required|exists:power_plants,id',
                'pembangkit_status.*' => 'required|in:tersedia,tidak_tersedia',
                'black_start_status.*' => 'required|in:tersedia,tidak_tersedia',
                'sop_status.*' => 'required|in:tersedia,tidak_tersedia',
                'load_set_status.*' => 'required|in:tersedia,tidak_tersedia',
                'line_energize_status.*' => 'required|in:tersedia,tidak_tersedia',
                'status_jaringan.*' => 'required|in:normal,tidak_normal',
                'pic.*' => 'required|string|max:100',
                'status.*' => 'required|in:open,close'
            ]);

            // Store multiple blackstart entries
            foreach ($request->unit_id as $key => $unit_id) {
                $blackstart = Blackstart::create([
                    'tanggal' => $request->tanggal,
                    'unit_id' => $unit_id,
                    'pembangkit_status' => $request->pembangkit_status[$key],
                    'black_start_status' => $request->black_start_status[$key],
                    'sop_status' => $request->sop_status[$key],
                    'load_set_status' => $request->load_set_status[$key],
                    'line_energize_status' => $request->line_energize_status[$key],
                    'status_jaringan' => $request->status_jaringan[$key],
                    'pic' => $request->pic[$key],
                    'status' => $request->status[$key]
                ]);

                // Store peralatan blackstart data
                if (isset($request->peralatan[$key])) {
                    foreach ($request->peralatan[$key] as $peralatan) {
                        PeralatanBlackstart::create([
                            'blackstart_id' => $blackstart->id,
                            'unit_id' => $unit_id,
                            'kompresor_diesel_jumlah' => $peralatan['kompresor_diesel_jumlah'] ?? null,
                            'kompresor_diesel_satuan' => $peralatan['kompresor_diesel_satuan'] ?? 'bh',
                            'kompresor_diesel_kondisi' => $peralatan['kompresor_diesel_kondisi'] ?? null,
                            'tabung_udara_jumlah' => $peralatan['tabung_udara_jumlah'] ?? null,
                            'tabung_udara_satuan' => $peralatan['tabung_udara_satuan'] ?? 'bh',
                            'tabung_udara_kondisi' => $peralatan['tabung_udara_kondisi'] ?? null,
                            'ups_kondisi' => $peralatan['ups_kondisi'] ?? null,
                            'lampu_emergency_jumlah' => $peralatan['lampu_emergency_jumlah'] ?? null,
                            'lampu_emergency_kondisi' => $peralatan['lampu_emergency_kondisi'] ?? null,
                            'battery_catudaya_jumlah' => $peralatan['battery_catudaya_jumlah'] ?? null,
                            'battery_catudaya_satuan' => $peralatan['battery_catudaya_satuan'] ?? 'bh',
                            'battery_catudaya_kondisi' => $peralatan['battery_catudaya_kondisi'] ?? null,
                            'battery_blackstart_jumlah' => $peralatan['battery_blackstart_jumlah'] ?? null,
                            'battery_blackstart_satuan' => $peralatan['battery_blackstart_satuan'] ?? 'bh',
                            'battery_blackstart_kondisi' => $peralatan['battery_blackstart_kondisi'] ?? null,
                            'radio_komunikasi_kondisi' => $peralatan['radio_komunikasi_kondisi'] ?? null,
                            'radio_kompresor_kondisi' => $peralatan['radio_kompresor_kondisi'] ?? null,
                            'panel_kondisi' => $peralatan['panel_kondisi'] ?? null,
                            'simulasi_blackstart' => $peralatan['simulasi_blackstart'] ?? null,
                            'start_kondisi_blackout' => $peralatan['start_kondisi_blackout'] ?? null,
                            'waktu_mulai' => $peralatan['waktu_mulai'] ?? null,
                            'waktu_selesai' => $peralatan['waktu_selesai'] ?? null,
                            'waktu_deadline' => $peralatan['waktu_deadline'] ?? null,
                            'pic' => $peralatan['pic'] ?? null,
                            'status' => $peralatan['status'] ?? 'normal'
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.blackstart.show')
                ->with('success', 'Data blackstart berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $blackstart = Blackstart::findOrFail($id);
            $blackstart->delete();
            
            return redirect()->route('admin.blackstart.show')
                ->with('success', 'Data blackstart berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            return Excel::download(new BlackstartExport($request), 'blackstart-data.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = Blackstart::with(['powerPlant', 'peralatanBlackstarts']);

            // Apply the same filters as show method
            if ($request->filled('unit_id')) {
                $query->where('unit_id', $request->unit_id);
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('tanggal', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('tanggal', '<=', $request->end_date);
            }

            $blackstarts = $query->orderBy('tanggal', 'desc')->get();

            $pdf = PDF::loadView('admin.blackstart.pdf', compact('blackstarts'));
            return $pdf->download('blackstart-data.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengexport PDF: ' . $e->getMessage());
        }
    }
} 