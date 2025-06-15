<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MonitoringDatakompakExport implements FromView, ShouldAutoSize, WithEvents
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set page orientation to landscape
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Set print area to fit to page
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);

                // Set column widths
                $event->sheet->getColumnDimension('A')->setWidth(30); // Unit column
                
                // Set row height for header
                $event->sheet->getRowDimension(1)->setRowHeight(30);
                $event->sheet->getRowDimension(2)->setRowHeight(25);

                // Set print titles (repeat header rows)
                $event->sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 4);
            },
        ];
    }
} 