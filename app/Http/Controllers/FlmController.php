<?php

namespace App\Http\Controllers;

use App\Models\FlmInspection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FlmExport;
use Illuminate\Support\Str;

class FlmController extends Controller
{
    public function index()
    {
        return view('admin.flm.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'operator' => 'required|string|max:100',
            'shift' => 'required|in:A,B,C,D',
            'time' => 'required|date_format:H:i',
            'mesin.*' => 'required|string|max:100',
            'sistem.*' => 'required|string|max:100',
            'masalah.*' => 'required|string',
            'kondisi_awal.*' => 'required|string',
            'kondisi_akhir.*' => 'required|string',
            'catatan.*' => 'nullable|string',
            'eviden_sebelum.*' => 'nullable|image|max:2048',
            'eviden_sesudah.*' => 'nullable|image|max:2048',
        ]);

        // Generate unique flm_id untuk satu batch input
        $flm_id = 'FLM-' . date('Ymd') . '-' . Str::random(5);

        // Get unit source from current session
        $unitSource = session('unit', 'mysql');
        $unitMapping = [
            'mysql_poasia' => 'PLTD POASIA',
                    'mysql_kolaka' => 'PLTD KOLAKA',
                    'mysql_bau_bau' => 'PLTD BAU BAU',
                    'mysql_wua_wua' => 'PLTD WUA WUA',
                    'mysql_winning' => 'PLTD WINNING',
                    'mysql_erkee' => 'PLTD ERKEE',
                    'mysql_ladumpi' => 'PLTD LADUMPI',
                    'mysql_langara' => 'PLTD LANGARA',
                    'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                    'mysql_pasarwajo' => 'PLTD PASARWAJO',
                    'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                    'mysql_raha' => 'PLTD RAHA',
                    'mysql_wajo' => 'PLTD WAJO',
                    'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
                    'mysql_rongi' => 'PLTD RONGI',
                    'mysql_sabilambo' => 'PLTM SABILAMBO',
                    'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
                    'mysql_pltmg_kendari' => 'PLTD KENDARI',
                    'mysql_baruta' => 'PLTD BARUTA',
                    'mysql_moramo' => 'PLTD MORAMO',
                    'mysql_mikuasi' => 'PLTM MIKUASI',
        ];
        
        $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

        foreach ($request->mesin as $key => $mesin) {
            if (empty($mesin)) continue;

            $data = [
                'flm_id' => $flm_id,
                'tanggal' => $request->tanggal,
                'operator' => $request->operator,
                'shift' => $request->shift,
                'time' => $request->time,
                'mesin' => $mesin,
                'sistem' => $request->sistem[$key],
                'masalah' => $request->masalah[$key],
                'kondisi_awal' => $request->kondisi_awal[$key],
                'tindakan_bersihkan' => in_array('bersihkan', $request->tindakan[$key] ?? []),
                'tindakan_lumasi' => in_array('lumasi', $request->tindakan[$key] ?? []),
                'tindakan_kencangkan' => in_array('kencangkan', $request->tindakan[$key] ?? []),
                'tindakan_perbaikan_koneksi' => in_array('perbaikan_koneksi', $request->tindakan[$key] ?? []),
                'tindakan_lainnya' => in_array('lainnya', $request->tindakan[$key] ?? []),
                'kondisi_akhir' => $request->kondisi_akhir[$key],
                'catatan' => $request->catatan[$key],
                'status' => 'selesai',
                'sync_unit_origin' => $unitName
            ];

            // Handle file uploads
            if ($request->hasFile("eviden_sebelum.{$key}")) {
                $file = $request->file("eviden_sebelum.{$key}");
                $filename = time() . '_sebelum_' . $file->getClientOriginalName();
                Storage::putFileAs('public/flm/eviden', $file, $filename);
                $data['eviden_sebelum'] = 'flm/eviden/' . $filename;
            }

            if ($request->hasFile("eviden_sesudah.{$key}")) {
                $file = $request->file("eviden_sesudah.{$key}");
                $filename = time() . '_sesudah_' . $file->getClientOriginalName();
                Storage::putFileAs('public/flm/eviden', $file, $filename);
                $data['eviden_sesudah'] = 'flm/eviden/' . $filename;
            }

            FlmInspection::create($data);
        }

        return redirect()->route('admin.flm.list')->with('success', 'Data FLM berhasil disimpan');
    }

    public function list()
    {
        $query = FlmInspection::query();

        // Apply date range filter
        if (request('start_date')) {
            $query->whereDate('tanggal', '>=', request('start_date'));
        }
        if (request('end_date')) {
            $query->whereDate('tanggal', '<=', request('end_date'));
        }

        // Apply mesin filter
        if (request('mesin')) {
            $query->where('mesin', 'like', '%' . request('mesin') . '%');
        }

        // Apply sistem filter
        if (request('sistem')) {
            $query->where('sistem', 'like', '%' . request('sistem') . '%');
        }

        // Apply unit origin filter
        if (request('unit_origin')) {
            $query->where('sync_unit_origin', request('unit_origin'));
        }

        // Group by flm_id to show related entries together
        $query->orderBy('flm_id')
              ->orderBy('tanggal', 'desc')
              ->orderBy('created_at', 'desc');

        $flmData = $query->paginate(10)->withQueryString();

        return view('admin.flm.list', compact('flmData'));
    }

    public function show($id)
    {
        $mainData = FlmInspection::findOrFail($id);
        $flmDetails = FlmInspection::where('flm_id', $mainData->flm_id)->get();
        return view('admin.flm.show', compact('mainData', 'flmDetails'));
    }

    public function edit($id)
    {
        $flm = FlmInspection::findOrFail($id);
        return view('admin.flm.edit', compact('flm'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'operator' => 'required|string|max:100',
            'shift' => 'required|in:A,B,C,D',
            'time' => 'required|date_format:H:i',
            'mesin' => 'required|string|max:100',
            'sistem' => 'required|string|max:100',
            'masalah' => 'required|string',
            'kondisi_awal' => 'required|string',
            'kondisi_akhir' => 'required|string',
            'catatan' => 'nullable|string',
            'eviden_sebelum' => 'nullable|image|max:2048',
            'eviden_sesudah' => 'nullable|image|max:2048',
        ]);

        $flm = FlmInspection::findOrFail($id);
        
        $data = $request->except(['eviden_sebelum', 'eviden_sesudah']);
        
        // Handle file uploads
        if ($request->hasFile('eviden_sebelum')) {
            // Delete old file if exists
            if ($flm->eviden_sebelum) {
                Storage::delete('public/' . $flm->eviden_sebelum);
            }
            
            $file = $request->file('eviden_sebelum');
            $filename = time() . '_sebelum_' . $file->getClientOriginalName();
            Storage::putFileAs('public/flm/eviden', $file, $filename);
            $data['eviden_sebelum'] = 'flm/eviden/' . $filename;
        }

        if ($request->hasFile('eviden_sesudah')) {
            // Delete old file if exists
            if ($flm->eviden_sesudah) {
                Storage::delete('public/' . $flm->eviden_sesudah);
            }
            
            $file = $request->file('eviden_sesudah');
            $filename = time() . '_sesudah_' . $file->getClientOriginalName();
            Storage::putFileAs('public/flm/eviden', $file, $filename);
            $data['eviden_sesudah'] = 'flm/eviden/' . $filename;
        }

        $flm->update($data);

        return redirect()->route('admin.flm.list')->with('success', 'Data FLM berhasil diperbarui');
    }

    public function destroy($id)
    {
        $flm = FlmInspection::findOrFail($id);
        
        // Delete associated files
        if ($flm->eviden_sebelum) {
            Storage::delete($flm->eviden_sebelum);
        }
        if ($flm->eviden_sesudah) {
            Storage::delete($flm->eviden_sesudah);
        }
        
        $flm->delete();

        return redirect()->route('admin.flm.list')->with('success', 'Data FLM berhasil dihapus');
    }

    public function exportPdf($id = null)
    {
        if ($id) {
            $mainData = FlmInspection::findOrFail($id);
            $flmData = FlmInspection::where('flm_id', $mainData->flm_id)->get();
            $pdf = Pdf::loadView('admin.flm.pdf.single', compact('flmData'));
            return $pdf->download('flm-inspection-' . $mainData->flm_id . '.pdf');
        }

        $flmData = FlmInspection::orderBy('tanggal', 'desc')->get();
        $pdf = Pdf::loadView('admin.flm.pdf.list', compact('flmData'));
        return $pdf->download('flm-inspections.pdf');
    }

    public function exportExcel($id = null)
    {
        if ($id) {
            return Excel::download(new FlmExport($id), 'flm-inspection-' . $id . '.xlsx');
        }
        return Excel::download(new FlmExport, 'flm-inspections.xlsx');
    }
} 