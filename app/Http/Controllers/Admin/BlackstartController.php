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

        // Filter by date range (monthly)
        if ($request->filled('start_date')) {
            $startDate = Carbon::parse($request->start_date)->startOfMonth();
            $query->whereDate('tanggal', '>=', $startDate);
        }

        if ($request->filled('end_date')) {
            $endDate = Carbon::parse($request->end_date)->endOfMonth();
            $query->whereDate('tanggal', '<=', $endDate);
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
            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'tanggal' => 'required|date_format:Y-m',
                'unit_id' => 'required|array',
                'unit_id.*' => 'required|exists:power_plants,id',
                'pembangkit_status' => 'required|array',
                'pembangkit_status.*' => 'required|in:tersedia,tidak_tersedia',
                'black_start_status' => 'required|array',
                'black_start_status.*' => 'required|in:tersedia,tidak_tersedia',
                'diagram_evidence' => 'nullable|array',
                'diagram_evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
                'sop_status' => 'required|array',
                'sop_status.*' => 'required|in:tersedia,tidak_tersedia',
                'sop_evidence' => 'nullable|array',
                'sop_evidence.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:2048',
                'load_set_status' => 'required|array',
                'load_set_status.*' => 'required|in:tersedia,tidak_tersedia',
                'line_energize_status' => 'required|array',
                'line_energize_status.*' => 'required|in:tersedia,tidak_tersedia',
                'status_jaringan' => 'required|array',
                'status_jaringan.*' => 'required|in:normal,tidak_normal',
                'pic' => 'required|array',
                'pic.*' => 'required|string|max:100',
                'status' => 'required|array',
                'status.*' => 'required|string|in:open,close',

                // Peralatan Blackstart Validation (Optional)
                'unit_layanan' => 'nullable|array',
                'unit_layanan.*' => 'nullable|exists:power_plants,id',
                'kompresor_jumlah' => 'nullable|array',
                'kompresor_jumlah.*' => 'nullable|numeric',
                'kompresor_satuan' => 'nullable|array',
                'kompresor_satuan.*' => 'nullable|string',
                'kompresor_kondisi' => 'nullable|array',
                'kompresor_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'tabung_jumlah' => 'nullable|array',
                'tabung_jumlah.*' => 'nullable|numeric',
                'tabung_satuan' => 'nullable|array',
                'tabung_satuan.*' => 'nullable|string',
                'tabung_kondisi' => 'nullable|array',
                'tabung_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'ups_kondisi' => 'nullable|array',
                'ups_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'lampu_jumlah' => 'nullable|array',
                'lampu_jumlah.*' => 'nullable|numeric',
                'lampu_satuan' => 'nullable|array',
                'lampu_satuan.*' => 'nullable|string',
                'lampu_kondisi' => 'nullable|array',
                'lampu_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'battery_catudaya_jumlah' => 'nullable|array',
                'battery_catudaya_jumlah.*' => 'nullable|numeric',
                'battery_catudaya_satuan' => 'nullable|array',
                'battery_catudaya_satuan.*' => 'nullable|string',
                'battery_catudaya_kondisi' => 'nullable|array',
                'battery_catudaya_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'battery_blackstart_jumlah' => 'nullable|array',
                'battery_blackstart_jumlah.*' => 'nullable|numeric',
                'battery_blackstart_satuan' => 'nullable|array',
                'battery_blackstart_satuan.*' => 'nullable|string',
                'battery_blackstart_kondisi' => 'nullable|array',
                'battery_blackstart_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'radio_komunikasi_kondisi' => 'nullable|array',
                'radio_komunikasi_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'radio_kompresor_kondisi' => 'nullable|array',
                'radio_kompresor_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'panel_kondisi' => 'nullable|array',
                'panel_kondisi.*' => 'nullable|in:normal,tidak_normal',
                'simulasi_blackstart' => 'nullable|array',
                'simulasi_blackstart.*' => 'nullable|in:pernah,belum_pernah',
                'start_kondisi_blackout' => 'nullable|array',
                'start_kondisi_blackout.*' => 'nullable|in:pernah,belum_pernah',
                'waktu_mulai' => 'nullable|array',
                'waktu_mulai.*' => 'nullable|date_format:H:i',
                'waktu_selesai' => 'nullable|array',
                'waktu_selesai.*' => 'nullable|date_format:H:i',
                'waktu_deadline' => 'nullable|array',
                'waktu_deadline.*' => 'nullable|date_format:H:i',
                'peralatan_pic' => 'nullable|array',
                'peralatan_pic.*' => 'nullable|string|max:100',
                'peralatan_status' => 'nullable|array',
                'peralatan_status.*' => 'nullable|in:open,close'
            ], [
                'tanggal.required' => 'Periode bulan harus diisi',
                'tanggal.date_format' => 'Format periode bulan tidak valid',
                'unit_id.required' => 'Unit harus diisi',
                'unit_id.*.required' => 'Unit harus diisi',
                'unit_id.*.exists' => 'Unit yang dipilih tidak valid',
                'pembangkit_status.required' => 'Status pembangkit harus diisi',
                'pembangkit_status.*.required' => 'Status pembangkit harus diisi',
                'pembangkit_status.*.in' => 'Status pembangkit tidak valid',
                'black_start_status.required' => 'Status black start harus diisi',
                'black_start_status.*.required' => 'Status black start harus diisi',
                'black_start_status.*.in' => 'Status black start tidak valid',
                'diagram_evidence.required' => 'Diagram evidence harus diisi',
                'diagram_evidence.*.required' => 'Diagram evidence harus diisi',
                'diagram_evidence.*.file' => 'Diagram evidence harus berupa file',
                'diagram_evidence.*.mimes' => 'Format file diagram evidence tidak valid',
                'diagram_evidence.*.max' => 'Ukuran file diagram evidence tidak boleh lebih dari 2MB',
                'sop_status.required' => 'Status SOP harus diisi',
                'sop_status.*.required' => 'Status SOP harus diisi',
                'sop_status.*.in' => 'Status SOP tidak valid',
                'sop_evidence.required' => 'SOP evidence harus diisi',
                'sop_evidence.*.required' => 'SOP evidence harus diisi',
                'sop_evidence.*.file' => 'SOP evidence harus berupa file',
                'sop_evidence.*.mimes' => 'Format file SOP evidence tidak valid',
                'sop_evidence.*.max' => 'Ukuran file SOP evidence tidak boleh lebih dari 2MB',
                'load_set_status.required' => 'Status load set harus diisi',
                'load_set_status.*.required' => 'Status load set harus diisi',
                'load_set_status.*.in' => 'Status load set tidak valid',
                'line_energize_status.required' => 'Status line energize harus diisi',
                'line_energize_status.*.required' => 'Status line energize harus diisi',
                'line_energize_status.*.in' => 'Status line energize tidak valid',
                'status_jaringan.required' => 'Status jaringan harus diisi',
                'status_jaringan.*.required' => 'Status jaringan harus diisi',
                'status_jaringan.*.in' => 'Status jaringan tidak valid',
                'pic.required' => 'PIC harus diisi',
                'pic.*.required' => 'PIC harus diisi',
                'status.required' => 'Status harus diisi',
                'status.*.required' => 'Status harus diisi',
                'status.*.in' => 'Status harus berupa open atau close'
            ]);

            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors()
                    ], 422);
                }
                return back()->withErrors($validator)->withInput();
            }

            // Check if data for this month already exists
            $existingData = Blackstart::whereYear('tanggal', Carbon::parse($request->tanggal)->year)
                ->whereMonth('tanggal', Carbon::parse($request->tanggal)->month)
                ->first();

            if ($existingData) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data untuk periode ' . Carbon::parse($request->tanggal)->format('F Y') . ' sudah ada'
                    ], 422);
                }
                return back()->with('error', 'Data untuk periode ' . Carbon::parse($request->tanggal)->format('F Y') . ' sudah ada')
                            ->withInput();
            }

            // Set the date to first day of the month for consistency
            $tanggal = Carbon::parse($request->tanggal)->startOfMonth();

            // Store multiple blackstart entries
            foreach ($request->unit_id as $key => $unit_id) {
                // Handle file uploads
                $diagramEvidencePath = null;
                $sopEvidencePath = null;

                if ($request->hasFile('diagram_evidence') && isset($request->file('diagram_evidence')[$key])) {
                    $diagramFile = $request->file('diagram_evidence')[$key];
                    $diagramEvidencePath = $diagramFile->store('blackstart/diagrams', 'public');
                }

                if ($request->hasFile('sop_evidence') && isset($request->file('sop_evidence')[$key])) {
                    $sopFile = $request->file('sop_evidence')[$key];
                    $sopEvidencePath = $sopFile->store('blackstart/sops', 'public');
                }

                $blackstart = Blackstart::create([
                    'tanggal' => $tanggal,
                    'unit_id' => $unit_id,
                    'pembangkit_status' => $request->pembangkit_status[$key],
                    'black_start_status' => $request->black_start_status[$key],
                    'diagram_evidence' => $diagramEvidencePath,
                    'sop_status' => $request->sop_status[$key],
                    'sop_evidence' => $sopEvidencePath,
                    'load_set_status' => $request->load_set_status[$key],
                    'line_energize_status' => $request->line_energize_status[$key],
                    'status_jaringan' => $request->status_jaringan[$key],
                    'pic' => $request->pic[$key],
                    'status' => strtolower($request->status[$key])
                ]);

                // Store peralatan blackstart data if exists
                if (isset($request->unit_layanan) && isset($request->unit_layanan[$key])) {
                    // Handle peralatan eviden uploads
                    $kompresorEvidenPath = null;
                    $tabungEvidenPath = null;
                    $lampuEvidenPath = null;
                    $catudayaEvidenPath = null;
                    $blackstartEvidenPath = null;
                    $radioEvidenPath = null;

                    if ($request->hasFile('kompresor_eviden') && isset($request->file('kompresor_eviden')[$key])) {
                        $file = $request->file('kompresor_eviden')[$key];
                        $kompresorEvidenPath = $file->store('blackstart/peralatan/kompresor', 'public');
                    }
                    if ($request->hasFile('tabung_eviden') && isset($request->file('tabung_eviden')[$key])) {
                        $file = $request->file('tabung_eviden')[$key];
                        $tabungEvidenPath = $file->store('blackstart/peralatan/tabung', 'public');
                    }
                    if ($request->hasFile('lampu_eviden') && isset($request->file('lampu_eviden')[$key])) {
                        $file = $request->file('lampu_eviden')[$key];
                        $lampuEvidenPath = $file->store('blackstart/peralatan/lampu', 'public');
                    }
                    if ($request->hasFile('catudaya_eviden') && isset($request->file('catudaya_eviden')[$key])) {
                        $file = $request->file('catudaya_eviden')[$key];
                        $catudayaEvidenPath = $file->store('blackstart/peralatan/catudaya', 'public');
                    }
                    if ($request->hasFile('blackstart_eviden') && isset($request->file('blackstart_eviden')[$key])) {
                        $file = $request->file('blackstart_eviden')[$key];
                        $blackstartEvidenPath = $file->store('blackstart/peralatan/blackstart', 'public');
                    }
                    if ($request->hasFile('radio_eviden') && isset($request->file('radio_eviden')[$key])) {
                        $file = $request->file('radio_eviden')[$key];
                        $radioEvidenPath = $file->store('blackstart/peralatan/radio', 'public');
                    }

                    PeralatanBlackstart::create([
                        'blackstart_id' => $blackstart->id,
                        'unit_id' => $unit_id,
                        'kompresor_diesel_jumlah' => $request->kompresor_jumlah[$key] ?? null,
                        'kompresor_diesel_kondisi' => $request->kompresor_kondisi[$key] ?? null,
                        'kompresor_eviden' => $kompresorEvidenPath,
                        'tabung_udara_jumlah' => $request->tabung_jumlah[$key] ?? null,
                        'tabung_udara_kondisi' => $request->tabung_kondisi[$key] ?? null,
                        'tabung_eviden' => $tabungEvidenPath,
                        'ups_kondisi' => $request->ups_kondisi[$key] ?? null,
                        'lampu_emergency_jumlah' => $request->lampu_jumlah[$key] ?? null,
                        'lampu_emergency_kondisi' => $request->lampu_kondisi[$key] ?? null,
                        'lampu_eviden' => $lampuEvidenPath,
                        'battery_catudaya_jumlah' => $request->battery_catudaya_jumlah[$key] ?? null,
                        'battery_catudaya_kondisi' => $request->battery_catudaya_kondisi[$key] ?? null,
                        'catudaya_eviden' => $catudayaEvidenPath,
                        'battery_blackstart_jumlah' => $request->battery_blackstart_jumlah[$key] ?? null,
                        'battery_blackstart_kondisi' => $request->battery_blackstart_kondisi[$key] ?? null,
                        'blackstart_eviden' => $blackstartEvidenPath,
                        'radio_jumlah' => $request->radio_jumlah[$key] ?? null,
                        'radio_komunikasi_kondisi' => $request->radio_komunikasi_kondisi[$key] ?? null,
                        'radio_eviden' => $radioEvidenPath,
                        'simulasi_blackstart' => $request->simulasi_blackstart[$key] ?? null,
                        'start_kondisi_blackout' => $request->start_kondisi_blackout[$key] ?? null,
                        'waktu_mulai' => $request->waktu_mulai[$key] ?? null,
                        'waktu_selesai' => $request->waktu_selesai[$key] ?? null,
                        'waktu_deadline' => $request->waktu_deadline[$key] ?? null,
                        'pic' => $request->pic[$key] ?? null,
                        'status' => $request->peralatan_status[$key] ?? 'open'
                    ]);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data blackstart untuk periode ' . $tanggal->format('F Y') . ' berhasil disimpan',
                    'redirect' => route('admin.blackstart.show')
                ]);
            }

            return redirect()->route('admin.blackstart.show')
                ->with('success', 'Data blackstart untuk periode ' . $tanggal->format('F Y') . ' berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollback();
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                        ->withInput();
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
            // Adjust date format for export if needed
            if ($request->filled('start_date')) {
                $request->merge(['start_date' => Carbon::parse($request->start_date)->startOfMonth()]);
            }
            if ($request->filled('end_date')) {
                $request->merge(['end_date' => Carbon::parse($request->end_date)->endOfMonth()]);
            }

            return Excel::download(new BlackstartExport($request), 'blackstart-data.xlsx');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengexport data: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        try {
            $query = Blackstart::with(['powerPlant', 'peralatanBlackstarts']);

            // Apply monthly filters
            if ($request->filled('start_date')) {
                $startDate = Carbon::parse($request->start_date)->startOfMonth();
                $query->whereDate('tanggal', '>=', $startDate);
            }

            if ($request->filled('end_date')) {
                $endDate = Carbon::parse($request->end_date)->endOfMonth();
                $query->whereDate('tanggal', '<=', $endDate);
            }

            $blackstarts = $query->orderBy('tanggal', 'desc')->get();

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.blackstart.pdf', compact('blackstarts'));
            return $pdf->download('blackstart-data.pdf');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengexport PDF: ' . $e->getMessage());
        }
    }
} 