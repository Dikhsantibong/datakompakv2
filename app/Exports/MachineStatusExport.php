<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class MachineStatusExport implements FromView, ShouldAutoSize, WithColumnWidths, WithMultipleSheets
{
    protected $powerPlants;
    protected $logs;
    protected $hops;
    protected $dates;
    protected $selectedInputTime;

    public function __construct($powerPlants, $logs, $hops, $dates, $selectedInputTime)
    {
        $this->powerPlants = $powerPlants;
        $this->logs = $logs;
        $this->hops = $hops;
        $this->dates = is_array($dates) ? $dates : [$dates, $dates];
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

    public function sheets(): array
    {
        $sheets = [];
        
        // Get all dates between start and end date
        $period = CarbonPeriod::create($this->dates[0], $this->dates[1]);
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $sheets[] = new MachineStatusSheet(
                $this->powerPlants,
                $this->logs->filter(function($log) use ($dateStr) {
                    return $log->tanggal->format('Y-m-d') === $dateStr;
                }),
                $this->hops->filter(function($hop) use ($dateStr) {
                    return $hop->tanggal->format('Y-m-d') === $dateStr;
                }),
                $dateStr,
                $this->selectedInputTime
            );
        }
        
        return $sheets;
    }

    public function view(): View
    {
        return view('admin.machine-status.export-excel', [
            'powerPlants' => $this->powerPlants,
            'logs' => $this->logs,
            'hops' => $this->hops,
            'date' => $this->dates[0]->format('Y-m-d') . ' to ' . $this->dates[1]->format('Y-m-d'),
            'selectedInputTime' => $this->selectedInputTime,
            'navlog_path' => public_path('logo/navlog1.png'),
            'k3_path' => public_path('logo/k3_logo.png')
        ]);
    }
} 