<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MonitoringDatakompakExport implements FromView
{
    protected $data;
    protected $tab;

    public function __construct($data, $tab)
    {
        $this->data = $data;
        $this->tab = $tab;
    }

    public function view(): View
    {
        return view('admin.monitoring-datakompak.export-table', [
            'data' => $this->data,
            'tab' => $this->tab,
        ]);
    }
} 