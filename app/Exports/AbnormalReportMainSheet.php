<?php

namespace App\Exports;

use App\Models\AbnormalReport;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;

class AbnormalReportMainSheet implements FromView, WithTitle, WithEvents, WithStyles
{
    protected $report;
    protected $sectionRows;

    public function __construct(AbnormalReport $report)
    {
        $this->report = $report;
        $this->sectionRows = [];
    }

    public function view(): View
    {
        return view('admin.abnormal-report.excel', [
            'report' => $this->report
        ]);
    }

    public function title(): string
    {
        return 'Laporan Abnormal ' . $this->report->created_at->format('d/m/Y');
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        
        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(8);   // No/Pukul
        $sheet->getColumnDimension('B')->setWidth(25);  // Uraian kejadian
        $sheet->getColumnDimension('C')->setWidth(20);  // Visual
        $sheet->getColumnDimension('D')->setWidth(20);  // Parameter
        $sheet->getColumnDimension('E')->setWidth(12);  // Turun beban
        $sheet->getColumnDimension('F')->setWidth(12);  // CBG OFF
        $sheet->getColumnDimension('G')->setWidth(12);  // Stop
        $sheet->getColumnDimension('H')->setWidth(12);  // TL Ophar
        $sheet->getColumnDimension('I')->setWidth(12);  // TL OP
        $sheet->getColumnDimension('J')->setWidth(12);  // TL HAR
        $sheet->getColumnDimension('K')->setWidth(12);  // MUL

        // Default style for all cells
        $sheet->getParent()->getDefaultStyle()->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 11
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Set logo row height
        $sheet->getRowDimension(1)->setRowHeight(60);

        // Style array for main header
        $mainHeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        // Style array for section headers
        $sectionHeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F1F5F9']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        // Style array for table headers
        $tableHeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'F8FAFC']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ];

        // Apply main header style (now on row 3)
        $sheet->getStyle('A3:K3')->applyFromArray($mainHeaderStyle);
        $sheet->getRowDimension(3)->setRowHeight(30);

        // Find and style section headers
        $this->sectionRows = $this->findSectionHeaderRows($sheet);
        foreach ($this->sectionRows as $row) {
            $sheet->getStyle("A{$row}:K{$row}")->applyFromArray($sectionHeaderStyle);
            $sheet->getRowDimension($row)->setRowHeight(25);
            
            // Style the table header rows that follow each section header
            $tableHeaderRow = $row + 1;
            $sheet->getStyle("A{$tableHeaderRow}:K{$tableHeaderRow}")->applyFromArray($tableHeaderStyle);
            $sheet->getRowDimension($tableHeaderRow)->setRowHeight(20);

            // For Kronologi section, style both header rows
            if (str_contains($sheet->getCell("A{$row}")->getValue(), 'Kronologi Kejadian')) {
                $sheet->getStyle("A{$tableHeaderRow}:K" . ($tableHeaderRow + 1))->applyFromArray($tableHeaderStyle);
                $sheet->getRowDimension($tableHeaderRow + 1)->setRowHeight(20);
            }
        }

        return [
            1 => [ // Header row
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]
        ];
    }

    protected function findSectionHeaderRows(Worksheet $sheet): array
    {
        $rows = [];
        $lastRow = $sheet->getHighestRow();
        
        for ($row = 1; $row <= $lastRow; $row++) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if (str_contains($cellValue, 'Kronologi Kejadian') ||
                str_contains($cellValue, 'Mesin/Peralatan Terdampak') ||
                str_contains($cellValue, 'Tindak Lanjut') ||
                str_contains($cellValue, 'Rekomendasi') ||
                str_contains($cellValue, 'Tindak Lanjut Administrasi')) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }

    protected function findKronologiHeaderRow(Worksheet $sheet): ?int
    {
        foreach ($this->sectionRows as $row) {
            $cellValue = $sheet->getCell("A{$row}")->getValue();
            if (str_contains($cellValue, 'Kronologi Kejadian')) {
                return $row + 1;
            }
        }
        return null;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Set page orientation to landscape
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Enable text wrapping for all cells
                $sheet->getStyle('A1:K' . $lastRow)->getAlignment()->setWrapText(true);

                // Add borders to all cells
                $sheet->getStyle('A1:K' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:K' . $lastRow);

                // Fit to page
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Set zoom level
                $sheet->getSheetView()->setZoomScale(85);

                // Freeze header row (now after logo and title)
                $sheet->freezePane('A6');

                // Apply section header styles after all content is rendered
                foreach ($this->sectionRows as $row) {
                    // Merge cells for section headers
                    $sheet->mergeCells("A{$row}:K{$row}");
                }

                // Handle specific merges for the Kronologi section
                $kronologiHeaderRow = $this->findKronologiHeaderRow($sheet);
                if ($kronologiHeaderRow) {
                    // Merge cells for headers
                    $sheet->mergeCells("C{$kronologiHeaderRow}:D{$kronologiHeaderRow}"); // Pengamatan
                    $sheet->mergeCells("E{$kronologiHeaderRow}:G{$kronologiHeaderRow}"); // Tindakan Isolasi
                    $sheet->mergeCells("H{$kronologiHeaderRow}:K{$kronologiHeaderRow}"); // Koordinasi
                }

                // Style validation section
                $validationRow = $lastRow - 1; // Row before footer
                $sheet->getStyle("A{$validationRow}:K{$validationRow}")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);
                
                // Set row height for signature space
                $sheet->getRowDimension($validationRow)->setRowHeight(120);

                // Add drawing for logo
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo');
                $drawing->setDescription('Logo');
                $drawing->setPath(public_path('logo/navlog1.png'));
                $drawing->setHeight(60);
                $drawing->setCoordinates('A1');
                $drawing->setWorksheet($sheet);
            }
        ];
    }
} 