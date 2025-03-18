<?php

namespace App\Http\Controllers;

use App\Models\BahanKimia;
use App\Models\PowerPlant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BahanKimiaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data unit untuk dropdown terlebih dahulu
        $units = PowerPlant::orderBy('name')->get();

        $query = BahanKimia::with('unit');

        // Filter berdasarkan unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter berdasarkan jenis bahan
        if ($request->filled('jenis_bahan')) {
            $query->where('jenis_bahan', 'like', '%' . $request->jenis_bahan . '%');
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('start_date')) {
            $query->where('tanggal', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('tanggal', '<=', $request->end_date);
        }
        
        // Ambil data bahan kimia dengan filter
        $bahanKimia = $query->latest('tanggal')->get();

        return view('admin.energiprimer.bahan-kimia', compact('bahanKimia', 'units'));
    }

    public function create()
    {
        $units = PowerPlant::orderBy('name')->get();
        return view('admin.energiprimer.bahan-kimia-create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'unit_id' => 'required|exists:power_plants,id',
            'jenis_bahan' => 'required|string',
            'penerimaan' => 'required|numeric|min:0',
            'pemakaian' => 'required|numeric|min:0',
        ]);

        // Cek apakah ini data pertama untuk unit dan jenis bahan tersebut
        $previousBalance = BahanKimia::where('unit_id', $request->unit_id)
            ->where('jenis_bahan', $request->jenis_bahan)
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

            BahanKimia::create([
                'tanggal' => $request->tanggal,
                'unit_id' => $request->unit_id,
                'jenis_bahan' => $request->jenis_bahan,
                'saldo_awal' => $saldoAwal,
                'penerimaan' => $request->penerimaan,
                'pemakaian' => $request->pemakaian,
                'saldo_akhir' => $saldoAkhir,
                'is_opening_balance' => $isOpeningBalance
            ]);

            DB::commit();
            return redirect()->route('admin.energiprimer.bahan-kimia')
                           ->with('success', 'Data berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                           ->withInput();
        }
    }
} 