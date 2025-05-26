<?php

namespace App\Exports;

use App\Models\AbnormalReport;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;

class AbnormalReportExport implements WithMultipleSheets
{
    use Exportable;

    protected $report;

    public function __construct(AbnormalReport $report)
    {
        $this->report = $report;
    }

    public function sheets(): array
    {
        return [
            new AbnormalReportMainSheet($this->report),
            new AbnormalReportEvidenceSheet($this->report),
        ];
    }
}

class AbnormalReportMainSheet implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    protected $report;

    public function __construct(AbnormalReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('admin.abnormal-report.excel', [
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
        return 'Main Report';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
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
            }
        ];
    }
}

class AbnormalReportEvidenceSheet implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
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
        return 'Evidence';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
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
            }
        ];
    }
} 