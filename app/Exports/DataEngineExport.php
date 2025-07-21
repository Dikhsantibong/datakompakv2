<?php

namespace App\Exports;

use App\Models\PowerPlant;
use App\Models\Machine;
use App\Models\MachineLog;
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
use Illuminate\Support\Facades\DB;

class DataEngineExport implements FromView, WithTitle, WithEvents, WithStyles, WithDrawings
{
    use Exportable;

    protected $date;
    protected $powerPlantId;
    protected $powerPlants;

    public function __construct($date, $powerPlantId = null)
    {
        $this->date = $date;
        $this->powerPlantId = $powerPlantId;
        $this->powerPlants = $this->getPowerPlants();
    }

    protected function getPowerPlants()
    {
        // Build query for power plants
        $query = PowerPlant::with(['machines' => function ($query) {
            $query->orderBy('name');
        }]);

        // Apply power plant filter if specified
        if ($this->powerPlantId) {
            $query->where('id', $this->powerPlantId);
        }

        $powerPlants = $query->get();

        // Load the latest logs for each power plant and machine
        $powerPlants->each(function ($powerPlant) {
            // Get power plant logs
            $latestLog = DB::table('power_plant_logs')
                ->where('power_plant_id', $powerPlant->id)
                ->where('date', $this->date)
                ->orderBy('time', 'desc')
                ->first();

            $powerPlant->hop = $latestLog?->hop;
            $powerPlant->tma = $latestLog?->tma;
            $powerPlant->inflow = $latestLog?->inflow;

            // Get machine logs
            $powerPlant->machines->each(function ($machine) {
                $latestLog = $machine->getLatestLog($this->date);
                $machine->kw = $latestLog?->kw;
                $machine->kvar = $latestLog?->kvar;
                $machine->cos_phi = $latestLog?->cos_phi;
                $machine->status = $latestLog?->status;
                $machine->keterangan = $latestLog?->keterangan;
                $machine->log_time = $latestLog?->time;
            });
        });

        return $powerPlants;
    }

    public function view(): View
    {
        return view('admin.data-engine.excel', [
            'date' => $this->date,
            'powerPlants' => $this->powerPlants
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

        // PLN-bg Logo (ganti Engine Logo)
        $plnBgDrawing = new Drawing();
        $plnBgDrawing->setName('PLN-bg Logo');
        $plnBgDrawing->setDescription('PLN-bg Logo');
        $plnBgDrawing->setPath(public_path('logo/UP_KENDARI.png'));
        $plnBgDrawing->setHeight(60);
        $plnBgDrawing->setCoordinates('H1');
        $plnBgDrawing->setOffsetX(5);
        $plnBgDrawing->setOffsetY(5);

        return [$plnDrawing, $plnBgDrawing];
    }

    public function title(): string
    {
        return 'Data Engine ' . date('d/m/Y', strtotime($this->date));
    }

    public function styles(Worksheet $sheet)
    {
        // Set default styles
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        $sheet->getDefaultColumnDimension()->setWidth(12);
        
        // Set specific column widths
        $sheet->getColumnDimension('A')->setWidth(5);    // No
        $sheet->getColumnDimension('B')->setWidth(20);   // Unit
        $sheet->getColumnDimension('C')->setWidth(15);   // Status
        $sheet->getColumnDimension('D')->setWidth(15);   // KW
        $sheet->getColumnDimension('E')->setWidth(15);   // KVAR
        $sheet->getColumnDimension('F')->setWidth(15);   // Cos Phi
        $sheet->getColumnDimension('G')->setWidth(30);   // Keterangan

        // Set row height for logo row
        $sheet->getRowDimension(1)->setRowHeight(50);

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

        // Apply styles to section headers
        $sheet->getStyle('A5:G5')->applyFromArray($sectionHeaderStyle);  // Power Plants Section

        // Apply styles to table headers
        $sheet->getStyle('A6:G6')->applyFromArray($tableHeaderStyle);  // Power Plants Headers

        // Main title styling (now on row 3)
        return [
            3 => [
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

                // Freeze first row after title and logos
                $sheet->freezePane('A7');
            }
        ];
    }
} 