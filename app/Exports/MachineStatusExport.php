<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class MachineStatusExport implements FromView, ShouldAutoSize, WithColumnWidths
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

    public function columnWidths(): array
    {
        return [
            'A' => 8,     // No
            'B' => 20,    // Mesin
            'C' => 15,    // DMN
            'D' => 15,    // DMP
            'E' => 15,    // Beban
            'F' => 15,    // Status
            'G' => 40,    // Deskripsi
            'H' => 15,    // Waktu Input
        ];
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
} 