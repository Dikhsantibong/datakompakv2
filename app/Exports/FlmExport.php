<?php

namespace App\Exports;

use App\Models\FlmInspection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;

class FlmExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $flmData;
    protected $sectionRows;
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
        if ($id) {
            $mainRecord = FlmInspection::findOrFail($id);
            $this->flmData = FlmInspection::where('flm_id', $mainRecord->flm_id)
                                        ->orderBy('created_at')
                                        ->get();
        } else {
            $this->flmData = null;
        }
        $this->sectionRows = [];
    }

    public function view(): View
    {
        return view('admin.flm.excel', [
            'flmData' => $this->flmData
        ]);
    }

    public function drawings()
    {
        // PLN Logo
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(60);
        $plnDrawing->setCoordinates('A1');
        $plnDrawing->setOffsetX(5);
        $plnDrawing->setOffsetY(5);

        // PLN-bg Logo (ganti K3 Logo)
        $plnBgDrawing = new Drawing();
        $plnBgDrawing->setName('PLN-bg Logo');
        $plnBgDrawing->setDescription('PLN-bg Logo');
        $plnBgDrawing->setPath(public_path('logo/PLN-bg.png'));
        $plnBgDrawing->setHeight(60);
        $plnBgDrawing->setCoordinates('H1');
        $plnBgDrawing->setOffsetX(5);
        $plnBgDrawing->setOffsetY(5);

        return [$plnDrawing, $plnBgDrawing];
    }

    public function title(): string
    {
        if ($this->flmData && $this->flmData->count() > 0) {
            $firstRecord = $this->flmData->first();
            return 'FLM ' . $firstRecord->tanggal->format('d/m/Y') . ' - ' . $firstRecord->flm_id;
        }
        return 'FLM Report';
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        $sheet->getDefaultColumnDimension()->setWidth(12);
        
        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(15);  // Tanggal
        $sheet->getColumnDimension('C')->setWidth(20);  // Operator
        $sheet->getColumnDimension('D')->setWidth(25);  // Mesin/Peralatan
        $sheet->getColumnDimension('E')->setWidth(25);  // Sistem Pembangkit
        $sheet->getColumnDimension('F')->setWidth(30);  // Masalah
        $sheet->getColumnDimension('G')->setWidth(25);  // Tindakan
        $sheet->getColumnDimension('H')->setWidth(15);  // Status

        // Set row height for logo row
        $sheet->getRowDimension(2)->setRowHeight(50);

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

        // Find section header rows
        $this->sectionRows = $this->findSectionHeaderRows($sheet);

        // Style array for section headers
        $sectionHeaderStyle = [
            'font' => [
                'bold' => true,
                'size' => 12,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
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

        // Apply styles to all section headers
        foreach ($this->sectionRows as $row) {
            $sheet->getStyle("A{$row}:H{$row}")->applyFromArray($sectionHeaderStyle);
            $sheet->getRowDimension($row)->setRowHeight(25);
        }

        // Apply styles to table headers (rows after section headers)
        foreach ($this->sectionRows as $row) {
            $tableHeaderRow = $row + 1;
            $sheet->getStyle("A{$tableHeaderRow}:H{$tableHeaderRow}")->applyFromArray($tableHeaderStyle);
            $sheet->getRowDimension($tableHeaderRow)->setRowHeight(20);
        }

        // Main title styling
        return [
            1 => [ // Header row
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1D5DB']
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
            if (in_array($cellValue, ['Data Pemeriksaan FLM', 'Detail Pemeriksaan'])) {
                $rows[] = $row;
            }
        }
        
        return $rows;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = 'H';

                // Set page orientation to landscape
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Enable text wrapping for all cells
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setWrapText(true);

                // Add borders to all cells
                $sheet->getStyle('A1:' . $lastColumn . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:' . $lastColumn . $lastRow);

                // Fit to page
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Set zoom level
                $sheet->getSheetView()->setZoomScale(85);

                // Freeze first row
                $sheet->freezePane('A4');

                // Auto-size rows for better content fit
                foreach ($this->sectionRows as $row) {
                    $sheet->getRowDimension($row)->setRowHeight(25);
                    $sheet->getRowDimension($row + 1)->setRowHeight(20);
                }
            }
        ];
    }
} 