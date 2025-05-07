<?php

namespace App\Exports;

use App\Models\Blackstart;
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
use Illuminate\Http\Request;

class BlackstartExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $request;
    protected $sectionRows;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->sectionRows = [];
    }

    public function view(): View
    {
        $query = Blackstart::with(['powerPlant', 'peralatanBlackstarts']);

        // Apply filters from request
        if ($this->request->filled('unit_id')) {
            $query->where('unit_id', $this->request->unit_id);
        }
        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }
        if ($this->request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $this->request->start_date);
        }
        if ($this->request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $this->request->end_date);
        }

        $blackstarts = $query->orderBy('tanggal', 'desc')->get();

        return view('admin.blackstart.excel', compact('blackstarts'));
    }

    public function drawings()
    {
        // PLN Logo
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(60);
        $plnDrawing->setCoordinates('B2');
        $plnDrawing->setOffsetX(30);
        $plnDrawing->setOffsetY(5);

        // K3 Logo
        $k3Drawing = new Drawing();
        $k3Drawing->setName('K3 Logo');
        $k3Drawing->setDescription('K3 Logo');
        $k3Drawing->setPath(public_path('logo/k3_logo.png'));
        $k3Drawing->setHeight(60);
        $k3Drawing->setCoordinates('E2');
        $k3Drawing->setOffsetX(30);
        $k3Drawing->setOffsetY(5);

        return [$plnDrawing, $k3Drawing];
    }

    public function title(): string
    {
        return 'Data Blackstart';
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        
        // Set column widths for first table
        $sheet->getColumnDimension('A')->setWidth(5);   // No
        $sheet->getColumnDimension('B')->setWidth(25);  // Unit Layanan
        $sheet->getColumnDimension('C')->setWidth(15);  // Pembangkit
        $sheet->getColumnDimension('D')->setWidth(15);  // Black Start
        $sheet->getColumnDimension('E')->setWidth(15);  // SOP
        $sheet->getColumnDimension('F')->setWidth(15);  // Load Set
        $sheet->getColumnDimension('G')->setWidth(15);  // Line Energize
        $sheet->getColumnDimension('H')->setWidth(15);  // Status Jaringan
        $sheet->getColumnDimension('I')->setWidth(20);  // PIC
        $sheet->getColumnDimension('J')->setWidth(10);  // Status

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
            $sheet->getStyle("A{$row}:AA{$row}")->applyFromArray($sectionHeaderStyle);
            $sheet->getRowDimension($row)->setRowHeight(25);
        }

        // Apply styles to table headers (rows after section headers)
        foreach ($this->sectionRows as $row) {
            $tableHeaderRow = $row + 1;
            $sheet->getStyle("A{$tableHeaderRow}:AA{$tableHeaderRow}")->applyFromArray($tableHeaderStyle);
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
            if (in_array($cellValue, ['Komitmen dan Pembahasan', 'Data Peralatan Blackstart'])) {
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
                $lastColumn = $sheet->getHighestColumn();

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

                // Merge cells for section headers
                $sheet->mergeCells('A1:J1'); // Main header
                $sheet->mergeCells('A3:J3'); // Komitmen dan Pembahasan header
                $sheet->mergeCells('A13:AA13'); // Peralatan Blackstart header
            }
        ];
    }
} 