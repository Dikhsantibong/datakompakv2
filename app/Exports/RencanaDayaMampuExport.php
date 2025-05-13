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
        // Calculate total columns needed
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $this->month, $this->year);
        $lastColumn = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex(5 + ($daysInMonth * 7)); // 5 fixed columns + 7 columns per day

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);  // No
        $sheet->getColumnDimension('B')->setWidth(25); // Sistem Kelistrikan
        $sheet->getColumnDimension('C')->setWidth(25); // Mesin Pembangkit
        $sheet->getColumnDimension('D')->setWidth(12); // DMN SLO
        $sheet->getColumnDimension('E')->setWidth(12); // DMP PT

        // Set row height for logo and headers
        $sheet->getRowDimension(1)->setRowHeight(60);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->getRowDimension(3)->setRowHeight(30);
        
        // Count total rows
        $totalRows = 0;
        foreach ($this->powerPlants as $plant) {
            foreach ($plant->machines as $machine) {
                $maxRows = 1; // Minimum 1 row per machine
                if ($machine->rencanaDayaMampu->isNotEmpty()) {
                    foreach ($machine->rencanaDayaMampu as $record) {
                        if ($record) {
                            foreach (range(1, $daysInMonth) as $day) {
                                $date = sprintf('%s-%s-%02d', $this->year, $this->month, $day);
                                $data = $record->getDailyValue($date) ?? [];
                                $rencanaCount = count($data['rencana'] ?? []);
                                $realisasiCount = is_array($data['realisasi']) ? count($data['realisasi']) : 1;
                                $maxRows = max($maxRows, max($rencanaCount, $realisasiCount));
                            }
                        }
                    }
                }
                $totalRows += $maxRows;
            }
        }

        // Style array
        $styles = [
            // Logo and title section
            'A1:' . $lastColumn . '1' => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center']
            ],
            // Headers
            'A3:' . $lastColumn . '5' => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F3F4F6']
                ]
            ],
            // Rencana headers
            'F4:' . $lastColumn . '4' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DBEAFE']
                ]
            ],
            // Realisasi headers
            'G4:' . $lastColumn . '4' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DCFCE7']
                ]
            ],
            // Data cells
            'A6:' . $lastColumn . ($totalRows + 5) => [
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]
                ],
                'alignment' => [
                    'vertical' => 'center'
                ]
            ],
            // Center alignment for specific columns
            'A6:A' . ($totalRows + 5) => ['alignment' => ['horizontal' => 'center']],
            'D6:' . $lastColumn . ($totalRows + 5) => ['alignment' => ['horizontal' => 'center']]
        ];

        // Apply text wrap for all cells
        $sheet->getStyle('A1:' . $lastColumn . ($totalRows + 5))->getAlignment()->setWrapText(true);

        // Freeze panes
        $sheet->freezePane('F6');

        return $styles;
    }

    public function properties(): array
    {
        return [
            'creator' => 'DataKompak',
            'lastModifiedBy' => 'DataKompak Export System',
            'title' => 'Rencana Daya Mampu - ' . date('F Y', mktime(0, 0, 0, $this->month, 1, $this->year)),
            'description' => 'Laporan Rencana Daya Mampu',
            'subject' => 'Rencana Daya Mampu',
            'keywords' => 'rencana,daya,mampu,report,pln',
            'category' => 'Report',
            'manager' => 'DataKompak System',
            'company' => 'PT PLN NUSANTARA POWER',
        ];
    }
} 