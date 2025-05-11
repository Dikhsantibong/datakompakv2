<?php

namespace App\Exports;

use App\Models\AbnormalReport;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Contracts\View\View;

class AbnormalReportEvidenceSheet implements FromView, WithTitle, WithStyles
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

    public function title(): string
    {
        return 'Evidence';
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(8);     // No
        $sheet->getColumnDimension('B')->setWidth(30);    // File
        $sheet->getColumnDimension('C')->setWidth(15);    // Link Download
        $sheet->getColumnDimension('D')->setWidth(50);    // Deskripsi

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

        // Style for main header
        $sheet->getStyle('A1:D1')->applyFromArray([
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
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Style for table header
        $sheet->getStyle('A3:D3')->applyFromArray([
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
                'vertical' => Alignment::VERTICAL_CENTER
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);
        $sheet->getRowDimension(3)->setRowHeight(20);

        // Add borders to all cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:D' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Style for download links
        $dataRows = $sheet->getHighestRow();
        for ($row = 4; $row <= $dataRows; $row++) {
            if ($sheet->getCell("C{$row}")->getValue() !== null) {
                $sheet->getStyle("C{$row}")->getFont()->getColor()->setARGB('FF0000FF'); // Blue color for links
                $sheet->getStyle("C{$row}")->getFont()->setUnderline(true);
            }
        }

        // Merge cells for header
        $sheet->mergeCells('A1:D1');

        // Enable text wrapping for all cells
        $sheet->getStyle('A1:D' . $lastRow)->getAlignment()->setWrapText(true);

        // Center align the download links
        $sheet->getStyle('C4:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
} 