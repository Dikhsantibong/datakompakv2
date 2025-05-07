<?php

namespace App\Exports;

use App\Models\Pelumas;
use App\Models\PowerPlant;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PelumasExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Pelumas::with('unit');
        $units = PowerPlant::orderBy('name')->get();

        if ($this->request->filled('unit_id')) {
            $query->where('unit_id', $this->request->unit_id);
        }

        if ($this->request->filled('jenis_pelumas')) {
            $query->where('jenis_pelumas', $this->request->jenis_pelumas);
        }

        if ($this->request->filled('start_date')) {
            $query->where('tanggal', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->where('tanggal', '<=', $this->request->end_date);
        }

        $pelumas = $query->latest('tanggal')->get();

        return view('admin.energiprimer.exports.pelumas-excel', [
            'pelumas' => $pelumas,
            'units' => $units,
            'navlog_path' => public_path('images/navlog1.png'),
            'k3_path' => public_path('images/k3_logo.png')
        ]);
    }
} 