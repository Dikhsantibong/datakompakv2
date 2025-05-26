<?php

namespace App\Exports;

use App\Models\Pemeriksaan5s5r;
use App\Models\ProgramKerja5r;
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
use Illuminate\Support\Facades\Auth;

class FiveS5RExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $id;
    protected $pemeriksaan;
    protected $programKerja;
    protected $sectionRows;

    public function __construct($id = null)
    {
        $this->id = $id;
        if ($id) {
            $mainRecord = Pemeriksaan5s5r::findOrFail($id);
            $date = date('Y-m-d', strtotime($mainRecord->created_at));
            
            $this->pemeriksaan = Pemeriksaan5s5r::whereDate('created_at', $date)->get();
            $this->programKerja = ProgramKerja5r::whereDate('created_at', $date)->get();
        }
        $this->sectionRows = [];
    }

    public function view(): View
    {
        return view('admin.5s5r.excel', [
            'pemeriksaan' => $this->pemeriksaan,
            'programKerja' => $this->programKerja
        ]);
    }

    public function drawings()
    {
        // PLN Logo (kiri)
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(60);
        $plnDrawing->setCoordinates('B2');
        $plnDrawing->setOffsetX(30);
        $plnDrawing->setOffsetY(5);

        // Get current user name
        $userName = Auth::user()->name ?? '';
        
        // Unit Logo (kanan) - Choose logo based on user name
        $unitDrawing = new Drawing();
        $unitDrawing->setName('Unit Logo');
        $unitDrawing->setDescription('Unit Logo');
        
        // Set logo path based on user name
        if (stripos($userName, 'PLTU MORAMO') !== false) {
            $logoPath = 'logo/PLTU_MORAMO.png';
        } elseif (stripos($userName, 'PLTD WUA WUA') !== false || stripos($userName, 'PLTD WUA-WUA') !== false) {
            $logoPath = 'logo/PLTD_WUA_WUA.png';
        } elseif (stripos($userName, 'PLTD POASIA CONTAINERIZED') !== false) {
            $logoPath = 'logo/PLTD_POASIA_CONTAINERIZED.png';
        } elseif (stripos($userName, 'PLTD POASIA') !== false) {
            $logoPath = 'logo/PLTD_POASIA.png';
        } elseif (stripos($userName, 'PLTD KOLAKA') !== false) {
            $logoPath = 'logo/PLTD_KOLAKA.png';
        } elseif (stripos($userName, 'PLTD LANIPA NIPA') !== false || stripos($userName, 'PLTD LANIPANIPA') !== false) {
            $logoPath = 'logo/PLTD_LANIPA_NIPA.png';
        } elseif (stripos($userName, 'PLTD LADUMPI') !== false) {
            $logoPath = 'logo/PLTD_LADUMPI.png';
        } elseif (stripos($userName, 'PLTM SABILAMBO') !== false) {
            $logoPath = 'logo/PLTM_SABILAMBO.png';
        } elseif (stripos($userName, 'PLTM MIKUASI') !== false) {
            $logoPath = 'logo/PLTM_MIKUASI.png';
        } elseif (stripos($userName, 'PLTD BAU BAU') !== false || stripos($userName, 'PLTD BAU-BAU') !== false) {
            $logoPath = 'logo/PLTD_BAU_BAU.png';
        } elseif (stripos($userName, 'PLTD PASARWAJO') !== false) {
            $logoPath = 'logo/PLTD_PASARWAJO.png';
        } elseif (stripos($userName, 'PLTM WINNING') !== false) {
            $logoPath = 'logo/PLTM_WINNING.png';
        } elseif (stripos($userName, 'PLTD RAHA') !== false) {
            $logoPath = 'logo/PLTD_RAHA.png';
        } elseif (stripos($userName, 'PLTD WANGI WANGI') !== false || stripos($userName, 'PLTD WANGI-WANGI') !== false) {
            $logoPath = 'logo/PLTD_WANGI_WANGI.png';
        } elseif (stripos($userName, 'PLTD LANGARA') !== false) {
            $logoPath = 'logo/PLTD_LANGARA.png';
        } elseif (stripos($userName, 'PLTD EREKE') !== false) {
            $logoPath = 'logo/PLTD_EREKE.png';
        } elseif (stripos($userName, 'PLTMG KENDARI') !== false) {
            $logoPath = 'logo/PLTMG_KENDARI.png';
        } elseif (stripos($userName, 'PLTU BARUTA') !== false) {
            $logoPath = 'logo/PLTU_BARUTA.png';
        } elseif (stripos($userName, 'PLTMG BAU BAU') !== false || stripos($userName, 'PLTMG BAU-BAU') !== false) {
            $logoPath = 'logo/PLTMG_BAU_BAU.png';
        } elseif (stripos($userName, 'PLTM RONGI') !== false) {
            $logoPath = 'logo/PLTM_RONGI.png';
        } else {
            $logoPath = 'logo/UP_KENDARI.png';
        }
        
        $unitDrawing->setPath(public_path($logoPath));
        $unitDrawing->setHeight(60);
        $unitDrawing->setCoordinates('H2');
        $unitDrawing->setOffsetX(30);
        $unitDrawing->setOffsetY(5);

        return [$plnDrawing, $unitDrawing];
    }

    public function title(): string
    {
        return '5S5R ' . ($this->pemeriksaan ? $this->pemeriksaan->first()->created_at->format('d/m/Y') : 'Report');
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        $sheet->getDefaultColumnDimension()->setWidth(12);
        
        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(5);    // No
        $sheet->getColumnDimension('B')->setWidth(15);   // Uraian
        $sheet->getColumnDimension('C')->setWidth(30);   // Detail
        $sheet->getColumnDimension('D')->setWidth(25);   // Kondisi Awal
        $sheet->getColumnDimension('E')->setWidth(15);   // PIC
        $sheet->getColumnDimension('F')->setWidth(20);   // Area Kerja
        $sheet->getColumnDimension('G')->setWidth(20);   // Area Produksi
        $sheet->getColumnDimension('H')->setWidth(12);   // Membersihkan
        $sheet->getColumnDimension('I')->setWidth(12);   // Merapikan
        $sheet->getColumnDimension('J')->setWidth(15);   // Membuang Sampah
        $sheet->getColumnDimension('K')->setWidth(12);   // Mengecat
        $sheet->getColumnDimension('L')->setWidth(12);   // Lainnya
        $sheet->getColumnDimension('M')->setWidth(25);   // Kondisi Akhir

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
            $sheet->getStyle("A{$row}:M{$row}")->applyFromArray($sectionHeaderStyle);
            $sheet->getRowDimension($row)->setRowHeight(25);
        }

        // Apply styles to table headers (rows after section headers)
        foreach ($this->sectionRows as $row) {
            $tableHeaderRow = $row + 1;
            $sheet->getStyle("A{$tableHeaderRow}:M{$tableHeaderRow}")->applyFromArray($tableHeaderStyle);
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
            if (in_array($cellValue, ['Tabel Pemeriksaan 5S5R', 'Tabel Program Kerja 5R'])) {
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