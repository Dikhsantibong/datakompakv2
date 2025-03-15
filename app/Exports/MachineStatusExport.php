<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MachineStatusExport implements FromView
{
    protected $powerPlants;
    protected $logs;
    protected $hops;
    protected $date;
    protected $selectedInputTime;

    public function __construct($powerPlants, $logs, $hops, $date, $selectedInputTime)
    {
        $this->powerPlants = $powerPlants;
        $this->logs = $logs;
        $this->hops = $hops;
        $this->date = $date;
        $this->selectedInputTime = $selectedInputTime;
    }

    public function view(): View
    {
        return view('admin.machine-status.export-excel', [
            'powerPlants' => $this->powerPlants,
            'logs' => $this->logs,
            'hops' => $this->hops,
            'date' => $this->date,
            'selectedInputTime' => $this->selectedInputTime
        ]);
    }
} 