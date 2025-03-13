<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithProperties;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class RencanaDayaMampuExport implements FromView, ShouldAutoSize, WithStyles, WithProperties, WithDrawings
{
    protected $powerPlants;
    protected $month;
    protected $year;
    protected $unitSource;

    public function __construct($powerPlants, $month, $year, $unitSource)
    {
        $this->powerPlants = $powerPlants;
        $this->month = $month;
        $this->year = $year;
        $this->unitSource = $unitSource;
    }

    public function view(): View
    {
        $date = \Carbon\Carbon::createFromFormat('Y-m', "{$this->year}-{$this->month}");
        return view('admin.rencana-daya-mampu.excel', [
            'powerPlants' => $this->powerPlants,
            'month' => $this->month,
            'year' => $this->year,
            'date' => $date->format('F Y'),
            'unitSource' => $this->unitSource
        ]);
    }

    public function drawings()
    {
        $drawings = [];
        
        // Logo PLN
        $drawingPLN = new Drawing();
        $drawingPLN->setName('Logo PLN');
        $drawingPLN->setDescription('PLN Logo');
        $drawingPLN->setPath(public_path('logo/navlog1.png'));
        $drawingPLN->setHeight(60);
        $drawingPLN->setCoordinates('B1');
        $drawings[] = $drawingPLN;

        // Logo K3
        $drawingK3 = new Drawing();
        $drawingK3->setName('Logo K3');
        $drawingK3->setDescription('K3 Logo');
        $drawingK3->setPath(public_path('logo/k3_logo.png'));
        $drawingK3->setHeight(60);
        $drawingK3->setCoordinates('H1');
        $drawings[] = $drawingK3;

        return $drawings;
    }

    public function styles(Worksheet $sheet)
    {
        // Set row height for logo
        $sheet->getRowDimension(1)->setRowHeight(45);
        $sheet->getRowDimension(2)->setRowHeight(30);
        
        // Merge cells for company name
        $sheet->mergeCells('B2:H2');
        
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(8 + cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year));
        
        return [
            // Company name style
            'B2:H2' => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Header utama dengan background biru
            'A4:H4' => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0A749B']
                ],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
            // Sub-header tanpa background
            'A5:' . $lastColumn . '5' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
            // Data rows styling
            'A6:' . $lastColumn . (count($this->powerPlants) * 2 + 5) => [
                'borders' => ['allBorders' => ['borderStyle' => 'thin']]
            ],
            // Center alignment for specific columns
            'A6:A' . (count($this->powerPlants) * 2 + 5) => [
                'alignment' => ['horizontal' => 'center']
            ],
            'E6:' . $lastColumn . (count($this->powerPlants) * 2 + 5) => [
                'alignment' => ['horizontal' => 'center']
            ]
        ];
    }

    public function properties(): array
    {
        return [
            'creator' => 'DataKompak',
            'title' => 'Rencana Daya Mampu',
            'description' => 'Laporan Rencana Daya Mampu',
            'subject' => 'Rencana Daya Mampu',
            'keywords' => 'rencana,daya,mampu,report',
            'category' => 'Report',
        ];
    }
} 