<?php
namespace App\Exports;

use App\Models\LaporanKit;
use App\Models\PowerPlant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
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
use Illuminate\Support\Facades\Log;

class LaporanKitExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $laporan;
    protected $sectionRows;

    public function __construct($id = null)
    {
        if ($id) {
            // Single report export
            $this->laporan = LaporanKit::with([
                'jamOperasi.machine', 
                'gangguan.machine', 
                'bbm', 
                'kwh', 
                'pelumas', 
                'bahanKimia', 
                'bebanTertinggi.machine', 
                'creator'
            ])->find($id);

            if (!$this->laporan) {
                Log::error("LaporanKit with ID {$id} not found");
                throw new \Exception("Laporan tidak ditemukan");
            }
        } else {
            // Get today's report if exists, or create empty template
            $this->laporan = LaporanKit::with([
                'jamOperasi.machine', 
                'gangguan.machine', 
                'bbm', 
                'kwh', 
                'pelumas', 
                'bahanKimia', 
                'bebanTertinggi.machine', 
                'creator'
            ])
            ->whereDate('tanggal', today())
            ->first();

            if (!$this->laporan) {
                $this->laporan = new LaporanKit();
                $this->laporan->tanggal = today();
            }
        }
        $this->sectionRows = [];
    }

    public function view(): View
    {
        return view('admin.laporan-kit.excel', [
            'laporan' => $this->laporan
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

        // PLN-bg Logo (ganti Kit Logo)
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
        return 'Laporan KIT ' . ($this->laporan ? date('d/m/Y', strtotime($this->laporan->tanggal)) : 'Report');
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        $sheet->getDefaultColumnDimension()->setWidth(12);
        
        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(5);    // No
        $sheet->getColumnDimension('B')->setWidth(20);   // Jenis/Nama
        $sheet->getColumnDimension('C')->setWidth(15);   // Stock/Data 1
        $sheet->getColumnDimension('D')->setWidth(15);   // Penerimaan/Data 2
        $sheet->getColumnDimension('E')->setWidth(15);   // Pemakaian/Data 3
        $sheet->getColumnDimension('F')->setWidth(15);   // Stock Akhir/Data 4
        $sheet->getColumnDimension('G')->setWidth(30);   // Keterangan

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
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray($sectionHeaderStyle);
            $sheet->getRowDimension($row)->setRowHeight(25);
        }

        // Apply styles to table headers (rows after section headers)
        foreach ($this->sectionRows as $row) {
            $tableHeaderRow = $row + 1;
            $sheet->getStyle("A{$tableHeaderRow}:G{$tableHeaderRow}")->applyFromArray($tableHeaderStyle);
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
            if (in_array($cellValue, [
                'Data BBM',
                'Data KWH',
                'Data Pelumas',
                'Data Bahan Kimia',
                'Jam Operasi Mesin',
                'Beban Tertinggi',
                'Gangguan'
            ])) {
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
            }
        ];
    }
}