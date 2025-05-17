<?php

namespace App\Http\Controllers;

use App\Models\BahanBakar;
use App\Models\PowerPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BahanBakarExport;

class BahanBakarController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBakar::with(['unit' => function($query) {
            $query->withDefault([
                'name' => 'Unit Tidak Ditemukan'
            ]);
        }]);

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter berdasarkan jenis BBM
        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', $request->jenis_bbm);
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
        
        // Ambil data bahan bakar dengan filter
        $bahanBakar = $query->latest('tanggal')->get();

        return view('admin.energiprimer.bahan-bakar', compact('bahanBakar', 'units'));
    }

    public function create()
    {
        $units = PowerPlant::orderBy('name')->get();
        
        // Cek apakah sudah ada data
        $hasData = BahanBakar::count() > 0;
        
        return view('admin.energiprimer.bahan-bakar-create', compact('units', 'hasData'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'unit_id' => 'required|exists:power_plants,id',
            'jenis_bbm' => 'required|in:B40,B35,HSD,MFO',
            'penerimaan' => 'required|numeric|min:0',
            'pemakaian' => 'required|numeric|min:0',
            'hop' => 'required|numeric|min:0',
            'catatan_transaksi' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2048'
        ]);

        // Cek apakah ini data pertama untuk unit dan jenis BBM tersebut
        $previousBalance = BahanBakar::where('unit_id', $request->unit_id)
            ->where('jenis_bbm', $request->jenis_bbm)
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

            $data = [
                'tanggal' => $request->tanggal,
                'unit_id' => $request->unit_id,
                'jenis_bbm' => $request->jenis_bbm,
                'saldo_awal' => $saldoAwal,
                'penerimaan' => $request->penerimaan,
                'pemakaian' => $request->pemakaian,
                'saldo_akhir' => $saldoAkhir,
                'hop' => $request->hop,
                'is_opening_balance' => $isOpeningBalance,
                'catatan_transaksi' => $request->catatan_transaksi
            ];

            // Handle file upload
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/documents/bahan-bakar', $fileName);
                $data['document'] = $fileName;
            }

            BahanBakar::create($data);

            DB::commit();
            return redirect()->route('admin.energiprimer.bahan-bakar')
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
        $bahanBakar = BahanBakar::findOrFail($id);
        $units = PowerPlant::orderBy('name')->get();
        
        return view('admin.energiprimer.bahan-bakar-edit', compact('bahanBakar', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'unit_id' => 'required|exists:power_plants,id',
            'jenis_bbm' => 'required|in:B40,B35,HSD,MFO',
            'penerimaan' => 'required|numeric|min:0',
            'pemakaian' => 'required|numeric|min:0',
            'hop' => 'required|numeric|min:0',
            'catatan_transaksi' => 'nullable|string|max:1000',
            'document' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:2048'
        ]);

        $bahanBakar = BahanBakar::findOrFail($id);
        
        try {
            DB::beginTransaction();

            // Hitung saldo akhir
            $saldoAkhir = $bahanBakar->saldo_awal + $request->penerimaan - $request->pemakaian;

            $data = [
                'tanggal' => $request->tanggal,
                'unit_id' => $request->unit_id,
                'jenis_bbm' => $request->jenis_bbm,
                'penerimaan' => $request->penerimaan,
                'pemakaian' => $request->pemakaian,
                'saldo_akhir' => $saldoAkhir,
                'hop' => $request->hop,
                'catatan_transaksi' => $request->catatan_transaksi
            ];

            // Handle file upload
            if ($request->hasFile('document')) {
                // Delete old file if exists
                if ($bahanBakar->document) {
                    Storage::delete('public/documents/bahan-bakar/' . $bahanBakar->document);
                }

                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/documents/bahan-bakar', $fileName);
                $data['document'] = $fileName;
            }

            $bahanBakar->update($data);

            DB::commit();
            return redirect()->route('admin.energiprimer.bahan-bakar')
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
            
            $bahanBakar = BahanBakar::findOrFail($id);
            
            // Delete associated document if exists
            if ($bahanBakar->document) {
                Storage::delete('public/documents/bahan-bakar/' . $bahanBakar->document);
            }
            
            $bahanBakar->delete();

            DB::commit();
            return redirect()->route('admin.energiprimer.bahan-bakar')
                           ->with('success', 'Data berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function exportPdf(Request $request)
    {
        $query = BahanBakar::with('unit');
        $units = PowerPlant::orderBy('name')->get();

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter berdasarkan jenis BBM
        if ($request->filled('jenis_bbm')) {
            $query->where('jenis_bbm', 'like', '%' . $request->jenis_bbm . '%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }

        $bahanBakar = $query->latest('tanggal')->get();

        $pdf = PDF::loadView('admin.energiprimer.exports.bahan-bakar-pdf', compact('bahanBakar', 'units'));
        return $pdf->download('data-bahan-bakar.pdf');
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new BahanBakarExport($request), 'data-bahan-bakar.xlsx');
    }
} 