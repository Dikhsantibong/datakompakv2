<?php

namespace App\Http\Controllers;

use App\Models\Pelumas;
use App\Models\PowerPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use PDF;
use App\Exports\PelumasExport;

use Excel;

class PelumasController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelumas::with('unit');

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter berdasarkan jenis pelumas
        if ($request->filled('jenis_pelumas')) {
            $query->where('jenis_pelumas', $request->jenis_pelumas);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        // Ambil data unit untuk dropdown
        $units = PowerPlant::orderBy('name')->get();
        
        // Ambil data pelumas dengan filter
        $pelumas = $query->latest('tanggal')->get();

        return view('admin.energiprimer.pelumas', compact('pelumas', 'units'));
    }

    public function create()
    {
        $units = PowerPlant::orderBy('name')->get();
        
        // Cek apakah sudah ada data
        $hasData = Pelumas::count() > 0;
        
        return view('admin.energiprimer.pelumas-create', compact('units', 'hasData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'unit_id' => 'required|exists:power_plants,id',
            'jenis_pelumas' => 'required|string|max:255',
            'penerimaan' => 'required|numeric|min:0',
            'pemakaian' => 'required|numeric|min:0',
        ]);

        // Cek apakah ini data pertama untuk unit dan jenis pelumas tersebut
        $previousBalance = Pelumas::where('unit_id', $request->unit_id)
            ->where('jenis_pelumas', $request->jenis_pelumas)
            ->where('tanggal', '<', $request->tanggal)
            ->orderBy('tanggal', 'desc')
            ->first();

        if (!$previousBalance) {
            // Jika tidak ada data sebelumnya, validasi saldo awal harus diisi
            $request->validate([
                'saldo_awal' => 'required|numeric|min:0',
            ]);
            $saldoAwal = $request->saldo_awal;
            $isOpeningBalance = true;
        } else {
            // Jika ada data sebelumnya, gunakan saldo akhir sebelumnya
            $saldoAwal = $previousBalance->saldo_akhir;
            $isOpeningBalance = false;
        }

        // Hitung saldo akhir
        $saldoAkhir = $saldoAwal + $request->penerimaan - $request->pemakaian;

        try {
            DB::beginTransaction();

            Pelumas::create([
                'tanggal' => $request->tanggal,
                'unit_id' => $request->unit_id,
                'jenis_pelumas' => $request->jenis_pelumas,
                'saldo_awal' => $saldoAwal,
                'penerimaan' => $request->penerimaan,
                'pemakaian' => $request->pemakaian,
                'saldo_akhir' => $saldoAkhir,
                'is_opening_balance' => $isOpeningBalance
            ]);

            DB::commit();
            return redirect()->route('admin.energiprimer.pelumas')
                           ->with('success', 'Data berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function edit($id)
    {
        $pelumas = Pelumas::findOrFail($id);
        $units = PowerPlant::orderBy('name')->get();
        
        return view('admin.energiprimer.pelumas-edit', compact('pelumas', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'unit_id' => 'required|exists:power_plants,id',
            'jenis_pelumas' => 'required|string|max:255',
            'penerimaan' => 'required|numeric|min:0',
            'pemakaian' => 'required|numeric|min:0',
        ]);

        $pelumas = Pelumas::findOrFail($id);
        
        try {
            DB::beginTransaction();

            // Hitung saldo akhir
            $saldoAkhir = $pelumas->saldo_awal + $request->penerimaan - $request->pemakaian;

            $pelumas->update([
                'tanggal' => $request->tanggal,
                'unit_id' => $request->unit_id,
                'jenis_pelumas' => $request->jenis_pelumas,
                'penerimaan' => $request->penerimaan,
                'pemakaian' => $request->pemakaian,
                'saldo_akhir' => $saldoAkhir
            ]);

            DB::commit();
            return redirect()->route('admin.energiprimer.pelumas')
                           ->with('success', 'Data berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $pelumas = Pelumas::findOrFail($id);
            $pelumas->delete();

            DB::commit();
            return redirect()->route('admin.energiprimer.pelumas')
                           ->with('success', 'Data berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = Pelumas::with('unit');
        $units = PowerPlant::orderBy('name')->get();

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter berdasarkan jenis pelumas
        if ($request->filled('jenis_pelumas')) {
            $query->where('jenis_pelumas', 'like', '%' . $request->jenis_pelumas . '%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        $pelumas = $query->latest('tanggal')->get();

        $pdf = PDF::loadView('admin.energiprimer.exports.pelumas-pdf', compact('pelumas', 'units'));
        return $pdf->download('data-pelumas.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new PelumasExport($request), 'data-pelumas.xlsx');
    }
} 