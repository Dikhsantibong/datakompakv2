<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Carbon\Carbon;

class MachineStatusSheet implements FromView, WithTitle
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
            'selectedInputTime' => $this->selectedInputTime,
            'navlog_path' => public_path('logo/navlog1.png'),
            'k3_path' => public_path('logo/k3_logo.png')
        ]);
    }

    public function title(): string
    {
        return Carbon::parse($this->date)->format('d M Y');
    }
} 