<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class DailySummaryExport implements WithMultipleSheets
{
    protected $allData;

    public function __construct($allData)
    {
        $this->allData = $allData;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->allData as $data) {
            $sheets[] = new DailySummarySheet($data['date'], $data['units']);
        }

        return $sheets;
    }
}

class DailySummarySheet implements FromView, ShouldAutoSize, WithStyles, WithDrawings
{
    protected $date;
    protected $units;

    public function __construct($date, $units)
    {
        $this->date = $date;
        $this->units = $units;
    }

    public function view(): View
    {
        return view('admin.daily-summary.excel', [
            'date' => $this->date,
            'units' => $this->units
        ]);
    }

    public function drawings()
    {
        $pln_logo = new Drawing();
        $pln_logo->setName('PLN Logo');
        $pln_logo->setDescription('PLN Logo');
        $pln_logo->setPath(public_path('logo/navlog1.png'));
        $pln_logo->setHeight(40);
        $pln_logo->setCoordinates('A1');

        $k3_logo = new Drawing();
        $k3_logo->setName('K3 Logo');
        $k3_logo->setDescription('K3 Logo');
        $k3_logo->setPath(public_path('logo/k3_logo.png'));
        $k3_logo->setHeight(40);
        $k3_logo->setCoordinates('R1');

        return [$pln_logo, $k3_logo];
    }

    public function styles(Worksheet $sheet)
    {
        $styles = [
            // Default style for all cells
            'A1:R1000' => [
                'font' => ['size' => 10],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ],
            
            // Header style
            'A1:R4' => [
                'font' => ['bold' => true, 'size' => 12],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ]
            ]
        ];

        $currentRow = 6; // Start after header

        // For each unit section
        for ($i = 0; $i < count($this->units); $i++) {
            // Unit header row
            $styles["A" . ($currentRow) . ":R" . ($currentRow)] = [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DCE6F1']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ];

            // Column headers row
            $styles["A" . ($currentRow + 1) . ":R" . ($currentRow + 1)] = [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'B8CCE4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ];

            // Sub headers row
            $styles["A" . ($currentRow + 2) . ":R" . ($currentRow + 2)] = [
                'font' => ['bold' => true, 'size' => 10],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'B8CCE4']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ]
                ]
            ];

            // Data rows style
            $dataRowCount = count($this->units[$i]->machines);
            for ($row = 1; $row <= $dataRowCount + 4; $row++) {
                $styles["A" . ($currentRow + 2 + $row) . ":R" . ($currentRow + 2 + $row)] = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                        ]
                    ]
                ];
            }

            // Move to next unit position
            $currentRow += (3 + $dataRowCount + 4);
        }

        // Set specific column alignments if needed
        $sheet->getStyle('A:R')->getAlignment()->setWrapText(true);

        // Set sheet name to the date
        $sheet->setTitle(Carbon::parse($this->date)->format('d M Y'));

        return $styles;
    }
} 