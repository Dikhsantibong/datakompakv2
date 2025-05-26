<?php

namespace App\Exports;

use App\Models\PatrolCheck;
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

class PatrolCheckExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $patrol;
    protected $sectionRows;
    protected $id;

    public function __construct($id = null)
    {
        $this->id = $id;
        $this->patrol = $id ? PatrolCheck::with('creator')->findOrFail($id) : null;
        $this->sectionRows = [];
    }

    public function view(): View
    {
        return view('admin.patrol-check.excel', [
            'patrol' => $this->patrol
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
        $plnDrawing->setCoordinates('A1');
        $plnDrawing->setOffsetX(5);
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
        $unitDrawing->setCoordinates('H1');
        $unitDrawing->setOffsetX(5);
        $unitDrawing->setOffsetY(5);

        return [$plnDrawing, $unitDrawing];
    }

    public function title(): string
    {
        return 'Patrol Check ' . ($this->patrol ? $this->patrol->created_at->format('d/m/Y') : 'Report');
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        $sheet->getDefaultColumnDimension()->setWidth(12);
        
        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(5);    // No
        $sheet->getColumnDimension('B')->setWidth(15);   // Tanggal
        $sheet->getColumnDimension('C')->setWidth(20);   // Sistem
        $sheet->getColumnDimension('D')->setWidth(15);   // Kondisi
        $sheet->getColumnDimension('E')->setWidth(30);   // Catatan
        $sheet->getColumnDimension('F')->setWidth(15);   // Status
        $sheet->getColumnDimension('G')->setWidth(20);   // Dibuat Oleh
        $sheet->getColumnDimension('H')->setWidth(15);   // Waktu Dibuat

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
            if (in_array($cellValue, ['Data Patrol Check', 'Data Peralatan Abnormal'])) {
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

                // Merge cell untuk header sesuai struktur PDF
                $sheet->mergeCells('A1:A4'); // Logo kiri
                $sheet->mergeCells('B1:G1'); // Judul
                $sheet->mergeCells('H1:H4'); // Kolom kanan (user besar)
                $sheet->mergeCells('B2:G2'); // Nama user
                $sheet->mergeCells('B3:G3'); // Shift & waktu
                $sheet->mergeCells('B4:G4'); // Tanggal

                // Judul
                $sheet->getStyle('B1')->applyFromArray([
                    'font' => [ 'bold' => true, 'size' => 20, 'color' => ['rgb' => '333333'] ],
                    'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_CENTER ]
                ]);
                // Nama user
                $sheet->getStyle('B2')->applyFromArray([
                    'font' => [ 'bold' => true, 'size' => 14, 'color' => ['rgb' => '406a7d'] ],
                    'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_RIGHT ]
                ]);
                // Shift & waktu
                $sheet->getStyle('B3')->applyFromArray([
                    'font' => [ 'size' => 13 ],
                    'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_CENTER ]
                ]);
                // Tanggal
                $sheet->getStyle('B4')->applyFromArray([
                    'font' => [ 'size' => 12, 'color' => ['rgb' => '666666'] ],
                    'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_CENTER ]
                ]);

                // Section title: bold, besar, border bawah biru
                foreach (range(5, $lastRow) as $row) {
                    $val = $sheet->getCell('A'.$row)->getValue();
                    if (stripos($val, 'Kondisi Umum Peralatan Bantu') !== false || stripos($val, 'Data Kondisi Alat Bantu') !== false) {
                        $sheet->mergeCells('A'.$row.':H'.$row);
                        $sheet->getStyle('A'.$row)->applyFromArray([
                            'font' => [ 'bold' => true, 'size' => 16, 'color' => ['rgb' => '333333'] ],
                            'borders' => [ 'bottom' => [ 'borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => '009BB9'] ] ],
                        ]);
                    }
                }

                // Header tabel: biru, putih, bold, rata tengah
                foreach (range(1, $lastRow) as $row) {
                    $val = $sheet->getCell('A'.$row)->getValue();
                    if (in_array($val, ['No', 'No'])) {
                        $sheet->getStyle('A'.$row.':H'.$row)->applyFromArray([
                            'font' => [ 'bold' => true, 'color' => ['rgb' => 'FFFFFF'] ],
                            'fill' => [ 'fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '009BB9'] ],
                            'alignment' => [ 'horizontal' => Alignment::HORIZONTAL_CENTER ]
                        ]);
                    }
                }

                // Border dan font isi tabel
                $sheet->getStyle('A1:'.$lastColumn.$lastRow)->applyFromArray([
                    'font' => [ 'name' => 'Arial', 'size' => 11 ],
                    'borders' => [ 'allBorders' => [ 'borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000'] ] ],
                ]);

                // Conditional formatting status (Normal hijau, Abnormal merah)
                foreach (range(1, $lastRow) as $row) {
                    $val = $sheet->getCell('C'.$row)->getValue();
                    if (strtolower($val) === 'normal') {
                        $sheet->getStyle('C'.$row)->applyFromArray([
                            'font' => [ 'color' => ['rgb' => '28a745'], 'bold' => true ]
                        ]);
                    } elseif (strtolower($val) === 'abnormal') {
                        $sheet->getStyle('C'.$row)->applyFromArray([
                            'font' => [ 'color' => ['rgb' => 'dc3545'], 'bold' => true ]
                        ]);
                    }
                }

                // Set page orientation to landscape
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setPrintArea('A1:' . $lastColumn . $lastRow);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getSheetView()->setZoomScale(85);
                $sheet->freezePane('A5');
            }
        ];
    }
} 