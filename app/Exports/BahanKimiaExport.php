<?php

namespace App\Exports;

use App\Models\BahanKimia;
use App\Models\PowerPlant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class BahanKimiaExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = BahanKimia::with('unit');
        $units = PowerPlant::orderBy('name')->get();

        if ($this->request->filled('unit_id')) {
            $query->where('unit_id', $this->request->unit_id);
        }

        if ($this->request->filled('jenis_bahan')) {
            $query->where('jenis_bahan', $this->request->jenis_bahan);
        }

        if ($this->request->filled('start_date')) {
            $query->where('tanggal', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->where('tanggal', '<=', $this->request->end_date);
        }

        $bahanKimia = $query->latest('tanggal')->get();

        return view('admin.energiprimer.exports.bahan-kimia-excel', [
            'bahanKimia' => $bahanKimia,
            'units' => $units,
            'navlog_path' => public_path('logo/navlog1.png'),
            'k3_path' => public_path('logo/k3_logo.png')
        ]);
    }
} 