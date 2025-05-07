<?php

namespace App\Exports;

use App\Models\PengadaanBarang;
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

class PengadaanExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $request;
    protected $pengadaan;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->pengadaan = $this->getPengadaanData();
    }

    protected function getPengadaanData()
    {
        $query = PengadaanBarang::query();

        if ($this->request->filled('tahun')) {
            $query->where('tahun', $this->request->tahun);
        }

        if ($this->request->filled('jenis')) {
            $query->where('jenis', $this->request->jenis);
        }

        $statusFields = [
            'pengusulan',
            'proses_kontrak',
            'pengadaan',
            'pekerjaan_fisik',
            'pemberkasan',
            'pembayaran'
        ];

        foreach ($statusFields as $field) {
            if ($this->request->filled($field)) {
                $query->where($field, $this->request->input($field));
            }
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function view(): View
    {
        return view('admin.operasi-upkd.pengadaan.excel', [
            'pengadaan' => $this->pengadaan
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
        $plnDrawing->setCoordinates('B2');
        $plnDrawing->setOffsetX(30);
        $plnDrawing->setOffsetY(5);

        // K3 Logo
        $k3Drawing = new Drawing();
        $k3Drawing->setName('K3 Logo');
        $k3Drawing->setDescription('K3 Logo');
        $k3Drawing->setPath(public_path('logo/k3_logo.png'));
        $k3Drawing->setHeight(60);
        $k3Drawing->setCoordinates('K2');
        $k3Drawing->setOffsetX(30);
        $k3Drawing->setOffsetY(5);

        return [$plnDrawing, $k3Drawing];
    }

    public function title(): string
    {
        return 'Pengadaan Barang dan Jasa';
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        
        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);    // No
        $sheet->getColumnDimension('B')->setWidth(30);   // Item Pekerjaan
        $sheet->getColumnDimension('C')->setWidth(10);   // Tahun
        $sheet->getColumnDimension('D')->setWidth(15);   // Nilai Kontrak
        $sheet->getColumnDimension('E')->setWidth(15);   // No. PRK
        $sheet->getColumnDimension('F')->setWidth(12);   // Jenis
        $sheet->getColumnDimension('G')->setWidth(12);   // Intensitas
        $sheet->getColumnDimension('H')->setWidth(12);   // Pengusulan
        $sheet->getColumnDimension('I')->setWidth(12);   // Proses Kontrak
        $sheet->getColumnDimension('J')->setWidth(12);   // Pengadaan
        $sheet->getColumnDimension('K')->setWidth(12);   // Pekerjaan Fisik
        $sheet->getColumnDimension('L')->setWidth(12);   // Pemberkasan
        $sheet->getColumnDimension('M')->setWidth(12);   // Pembayaran
        $sheet->getColumnDimension('N')->setWidth(25);   // Keterangan

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

        // Header style
        $sheet->getStyle('A1:N1')->applyFromArray([
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
        ]);

        // Table header style
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

        // Find the table header row (should be row 5 or 6 depending on filters)
        $headerRow = $this->request->hasAny(['tahun', 'jenis', 'status']) ? 6 : 5;
        $sheet->getStyle("A{$headerRow}:N{$headerRow}")->applyFromArray($tableHeaderStyle);
        $sheet->getRowDimension($headerRow)->setRowHeight(20);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                $lastRow = $sheet->getHighestRow();
                $lastColumn = 'N';

                // Set page orientation to landscape
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Enable text wrapping for all cells
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->getAlignment()->setWrapText(true);

                // Add borders to all cells
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000']
                        ]
                    ]
                ]);

                // Center align the status columns
                $statusColumns = ['H', 'I', 'J', 'K', 'L', 'M'];
                foreach ($statusColumns as $col) {
                    $sheet->getStyle("{$col}1:{$col}{$lastRow}")
                          ->getAlignment()
                          ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // Right align the nilai kontrak column
                $sheet->getStyle("D1:D{$lastRow}")
                      ->getAlignment()
                      ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Set print area
                $sheet->getPageSetup()->setPrintArea("A1:{$lastColumn}{$lastRow}");

                // Fit to page
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                // Set zoom level
                $sheet->getSheetView()->setZoomScale(85);

                // Freeze panes
                $headerRow = $this->request->hasAny(['tahun', 'jenis', 'status']) ? 6 : 5;
                $sheet->freezePane("A" . ($headerRow + 1));
            }
        ];
    }
} 