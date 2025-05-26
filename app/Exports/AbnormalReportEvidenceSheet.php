<?php

namespace App\Exports;

use App\Models\AbnormalReport;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;

class AbnormalReportEvidenceSheet implements FromView, WithTitle, WithStyles, WithDrawings, WithEvents
{
    protected $report;

    public function __construct(AbnormalReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('admin.abnormal-report.excel-evidence', [
            'report' => $this->report
        ]);
    }

    public function drawings()
    {
        // PLN Logo (kiri)
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(45);
        $plnDrawing->setCoordinates('A1');
        $plnDrawing->setOffsetX(5);
        $plnDrawing->setOffsetY(5);

        // Get current user name
        $userName = Auth::user()->name ?? '';
        
        // Unit Logo (kanan)
        $unitDrawing = new Drawing();
        $unitDrawing->setName('Unit Logo');
        $unitDrawing->setDescription('Unit Logo');
        
        // Set logo path based on user name
        if (stripos($userName, 'PLTD POASIA') !== false) {
            $logoPath = 'logo/PLTD_POASIA.png';
        } else {
            $logoPath = 'logo/UP_KENDARI.png'; // Default logo
        }
        
        $unitDrawing->setPath(public_path($logoPath));
        $unitDrawing->setHeight(45);
        $unitDrawing->setCoordinates('D1');
        $unitDrawing->setOffsetX(5);
        $unitDrawing->setOffsetY(5);

        return [$plnDrawing, $unitDrawing];
    }

    public function title(): string
    {
        return 'Evidence';
    }

    public function styles(Worksheet $sheet)
    {
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);     // No
        $sheet->getColumnDimension('B')->setWidth(40);    // File
        $sheet->getColumnDimension('C')->setWidth(15);    // Download
        $sheet->getColumnDimension('D')->setWidth(50);    // Deskripsi

        // Hide columns E to H
        $sheet->getColumnDimension('E')->setVisible(false);
        $sheet->getColumnDimension('F')->setVisible(false);
        $sheet->getColumnDimension('G')->setVisible(false);
        $sheet->getColumnDimension('H')->setVisible(false);

        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(50);     // Logo space
        $sheet->getRowDimension(2)->setRowHeight(30);     // Title
        $sheet->getRowDimension(3)->setRowHeight(20);     // Spacing
        $sheet->getRowDimension(4)->setRowHeight(25);     // Header row

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

        // Remove all borders first
        $sheet->getStyle($sheet->calculateWorksheetDimension())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_NONE
                ]
            ]
        ]);

        // Style for title row
        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Get the data range (excluding header and footer rows)
        $lastRow = $sheet->getHighestRow();
        $dataStartRow = 4; // Header row
        $dataEndRow = $lastRow - 2; // Excluding spacing and footer rows

        // Style for table headers and data
        $tableRange = 'A' . $dataStartRow . ':D' . $dataEndRow;
        $sheet->getStyle($tableRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style for table headers
        $headerRange = 'A4:D4';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);

        // Center align specific columns
        $sheet->getStyle('A4:A' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C4:C' . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style for download links
        $sheet->getStyle('C5:C' . $dataEndRow)->applyFromArray([
            'font' => [
                'color' => ['rgb' => '0000FF'],
                'underline' => true
            ]
        ]);

        // Style for footer
        $footerRow = $lastRow;
        $sheet->getStyle('A' . $footerRow . ':D' . $footerRow)->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['rgb' => '666666']
            ]
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Set print area
                $lastRow = $sheet->getHighestRow();
                $sheet->getPageSetup()->setPrintArea('A1:D' . $lastRow);

                // Set page orientation to portrait
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
                
                // Enable text wrapping for description column
                $sheet->getStyle('D4:D' . $lastRow)->getAlignment()->setWrapText(true);

                // Fit to page width
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Set zoom level
                $sheet->getSheetView()->setZoomScale(100);

                // Merge cells for title
                $sheet->mergeCells('A2:D2');
            }
        ];
    }
} 