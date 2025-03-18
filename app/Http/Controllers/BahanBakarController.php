<?php

namespace App\Http\Controllers;

use App\Models\BahanBakar;
use App\Models\PowerPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanBakarController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanBakar::with('unit');

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

            BahanBakar::create([
                'tanggal' => $request->tanggal,
                'unit_id' => $request->unit_id,
                'jenis_bbm' => $request->jenis_bbm,
                'saldo_awal' => $saldoAwal,
                'penerimaan' => $request->penerimaan,
                'pemakaian' => $request->pemakaian,
                'saldo_akhir' => $saldoAkhir,
                'is_opening_balance' => $isOpeningBalance
            ]);

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
} 