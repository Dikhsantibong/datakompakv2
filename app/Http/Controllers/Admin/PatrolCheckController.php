<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\PatrolCheck;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PatrolCheckController extends Controller
{
    public function index()
    {
        return view('admin.patrol-check.index');
    }

    public function list()
    {
        $query = PatrolCheck::with('creator');

        // Filter by shift
        if (request('shift')) {
            $query->where('shift', '=', request('shift'));
        }

        // Filter by status
        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        // Filter by creator
        if (request()->has('created_by')) {
            $query->where('created_by', request('created_by'));
        }

        // Filter by unit origin
        if (request()->has('unit_origin')) {
            $query->where('sync_unit_origin', request('unit_origin'));
        }

        // Filter by date range
        if (request()->has('start_date')) {
            $query->whereDate('created_at', '>=', request('start_date'));
        }
        if (request()->has('end_date')) {
            $query->whereDate('created_at', '<=', request('end_date'));
        }

        $patrols = $query->orderByDesc('created_at')->paginate(10);

        // Get unique unit origins for filter dropdown
        $unitOrigins = PatrolCheck::select('sync_unit_origin')
            ->whereNotNull('sync_unit_origin')
            ->distinct()
            ->pluck('sync_unit_origin')
            ->toArray();

        return view('admin.patrol-check.list', compact('patrols', 'unitOrigins'));
    }

    public function show($id)
    {
        $patrol = PatrolCheck::with('creator')->findOrFail($id);
        return view('admin.patrol-check.show', compact('patrol'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shift' => 'required|in:A,B,C,D',
            'time' => 'required|date_format:H:i',
            'condition' => 'required|array',
            'notes' => 'required|array',
            'abnormal' => 'nullable|array',
            'condition_after' => 'nullable|array',
        ]);

        // Get unit source from current session
        $unitSource = session('unit', 'mysql');
        $unitMapping = [
            'mysql_poasia' => 'PLTD POASIA',
            'mysql_kolaka' => 'PLTD KOLAKA',
            'mysql_bau_bau' => 'PLTD BAU BAU',
            'mysql_wua_wua' => 'PLTM WUA WUA',
            'mysql_winning' => 'PLTM WINNING',
            'mysql_erkee' => 'PLTD ERKEE',
            'mysql_ladumpi' => 'PLTD LADUMPI',
            'mysql_langara' => 'PLTD LANGARA',
            'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
            'mysql_pasarwajo' => 'PLTD PASARWAJO',
            'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
            'mysql_raha' => 'PLTD RAHA',
            'mysql_wajo' => 'PLTD WAJO',
            'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
            'mysql_rongi' => 'PLTM RONGI',
            'mysql_sabilambo' => 'PLTM SABILAMBO',
            'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
            'mysql_pltmg_kendari' => 'PLTD KENDARI',
            'mysql_baruta' => 'PLTD BARUTA',
            'mysql_moramo' => 'PLTD MORAMO',
        ];
        
        $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

        $condition_systems = collect($request->input('condition'))->map(function($val, $idx) use ($request) {
            $systems = ['Exhaust', 'Pelumas', 'BBM', 'JCW/HT', 'CW/LT'];
            return [
                'system' => $systems[$idx] ?? '',
                'condition' => $val,
                'notes' => $request->input('notes')[$idx] ?? null,
            ];
        });

        $abnormal_equipments = collect($request->input('abnormal', []))->map(function($row) {
            return [
                'equipment' => $row['equipment'] ?? '',
                'condition' => $row['condition'] ?? '',
                'flm' => !empty($row['flm']),
                'sr' => !empty($row['sr']),
                'other' => !empty($row['other']),
                'notes' => $row['notes'] ?? '',
            ];
        });

        $condition_after = collect($request->input('condition_after', []))->map(function($row) {
            return [
                'equipment' => $row['equipment'] ?? '',
                'condition' => $row['condition'] ?? '',
                'notes' => $row['notes'] ?? '',
            ];
        });

        $status = $condition_systems->contains(fn($item) => $item['condition'] === 'abnormal') ? 'abnormal' : 'normal';

        try {
            $patrol = PatrolCheck::create([
                'created_by' => Auth::id(),
                'shift' => $request->input('shift'),
                'time' => $request->input('time'),
                'condition_systems' => $condition_systems,
                'abnormal_equipments' => $abnormal_equipments,
                'condition_after' => $condition_after,
                'status' => $status,
                'sync_unit_origin' => $unitName
            ]);

            return redirect()->route('admin.patrol-check.list')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $patrol = PatrolCheck::findOrFail($id);
        return view('admin.patrol-check.index', compact('patrol'));
    }

    public function update(Request $request, $id)
    {
        $patrol = PatrolCheck::findOrFail($id);
        $request->validate([
            'shift' => 'required|in:A,B,C,D',
            'time' => 'required|date_format:H:i',
            'condition' => 'required|array',
            'notes' => 'required|array',
            'abnormal' => 'nullable|array',
            'condition_after' => 'nullable|array',
        ]);

        // Get unit source from current session
        $unitSource = session('unit', 'mysql');
        $unitMapping = [
            'mysql_poasia' => 'PLTD POASIA',
            'mysql_kolaka' => 'PLTD KOLAKA',
            'mysql_bau_bau' => 'PLTD BAU BAU',
            'mysql_wua_wua' => 'PLTD WUA WUA',
            'mysql_winning' => 'PLTM WINNING',
            'mysql_erkee' => 'PLTD ERKEE',
            'mysql_ladumpi' => 'PLTD LADUMPI',
            'mysql_langara' => 'PLTD LANGARA',
            'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
            'mysql_pasarwajo' => 'PLTD PASARWAJO',
            'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
            'mysql_raha' => 'PLTD RAHA',
            'mysql_wajo' => 'PLTD WAJO',
            'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
            'mysql_rongi' => 'PLTM RONGI',
            'mysql_sabilambo' => 'PLTM SABILAMBO',
            'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
            'mysql_pltmg_kendari' => 'PLTD KENDARI',
            'mysql_baruta' => 'PLTD BARUTA',
            'mysql_moramo' => 'PLTD MORAMO',
            'mysql_mikuasi' => 'PLTM MIKUASI',
        ];
        
        $unitName = $unitMapping[$unitSource] ?? 'UP Kendari';

        $condition_systems = collect($request->input('condition'))->map(function($val, $idx) use ($request) {
            $systems = ['Exhaust', 'Pelumas', 'BBM', 'JCW/HT', 'CW/LT'];
            return [
                'system' => $systems[$idx] ?? '',
                'condition' => $val,
                'notes' => $request->input('notes')[$idx] ?? null,
            ];
        });

        $abnormal_equipments = collect($request->input('abnormal', []))->map(function($row) {
            return [
                'equipment' => $row['equipment'] ?? '',
                'condition' => $row['condition'] ?? '',
                'flm' => !empty($row['flm']),
                'sr' => !empty($row['sr']),
                'other' => !empty($row['other']),
                'notes' => $row['notes'] ?? '',
            ];
        });

        $condition_after = collect($request->input('condition_after', []))->map(function($row) {
            return [
                'equipment' => $row['equipment'] ?? '',
                'condition' => $row['condition'] ?? '',
                'notes' => $row['notes'] ?? '',
            ];
        });

        $status = $condition_systems->contains(fn($item) => $item['condition'] === 'abnormal') ? 'abnormal' : 'normal';

        $patrol->update([
            'shift' => $request->input('shift'),
            'time' => $request->input('time'),
            'condition_systems' => $condition_systems,
            'abnormal_equipments' => $abnormal_equipments,
            'condition_after' => $condition_after,
            'status' => $status,
            'sync_unit_origin' => $unitName
        ]);

        return redirect()->route('admin.patrol-check.list')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        $patrol = PatrolCheck::findOrFail($id);
        $patrol->delete();
        return redirect()->route('admin.patrol-check.list')->with('success', 'Data berhasil dihapus');
    }

    public function exportExcel($id)
    {
        return (new \App\Exports\PatrolCheckExport($id))->download('patrol-check-' . $id . '.xlsx');
    }

    public function exportPdf($id)
    {
        $patrol = PatrolCheck::with('creator')->findOrFail($id);
        $pdf = Pdf::loadView('admin.patrol-check.pdf', compact('patrol'));
        return $pdf->download('patrol-check-' . $patrol->id . '.pdf');
    }
} 