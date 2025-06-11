<?php

namespace App\Exports;

use App\Models\K3KampReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class K3KampExport implements FromCollection, WithTitle, WithEvents, WithStyles, WithDrawings, WithColumnWidths, WithHeadings
{
    use Exportable;

    protected $report;

    public function __construct(K3KampReport $report)
    {
        $this->report = $report;
    }

    public function collection()
    {
        $data = collect();
        
        // Add empty rows for logos
        $data->push(['', '', '', '', '', '', '']);
        $data->push(['', '', '', '', '', '', '']);
        
        // Add header
        $data->push([
            'LAPORAN K3 KAMP DAN LINGKUNGAN',
            '',
            '',
            '',
            '',
            '',
            ''
        ]);
        
        // Add date
        $data->push([
            'Tanggal: ' . $this->report->date->format('d/m/Y'),
            '',
            '',
            '',
            '',
            '',
            ''
        ]);
        
        // Add creator
        $data->push([
            'Dibuat oleh: ' . $this->report->sync_unit_origin,
            '',
            '',
            '',
            '',
            '',
            ''
        ]);
        
        // Add empty row
        $data->push(['', '', '', '', '', '', '']);
        
        // Add K3 & Keamanan section
        $data->push(['K3 & Keamanan', '', '', '', '', '', '']);
        
        // Add K3 & Keamanan headers
        $data->push(['No', 'Kategori', 'Item', 'Status', 'Kondisi', 'Keterangan', 'Eviden']);
        
        // Add K3 & Keamanan items
        $no = 1;
        foreach ($this->report->items->where('item_type', 'k3_keamanan') as $item) {
            $data->push([
                $no++,
                'K3 & Keamanan',
                $item->item_name,
                ucfirst($item->status),
                ucfirst($item->kondisi),
                $item->keterangan,
                $item->media->isNotEmpty() ? 'Ada' : '-'
            ]);
        }
        
        // Add empty row
        $data->push(['', '', '', '', '', '', '']);
        
        // Add Lingkungan section
        $data->push(['Lingkungan', '', '', '', '', '', '']);
        
        // Add Lingkungan headers
        $data->push(['No', 'Kategori', 'Item', 'Status', 'Kondisi', 'Keterangan', 'Eviden']);
        
        // Add Lingkungan items
        $no = 1;
        foreach ($this->report->items->where('item_type', 'lingkungan') as $item) {
            $data->push([
                $no++,
                'Lingkungan',
                $item->item_name,
                ucfirst($item->status),
                ucfirst($item->kondisi),
                $item->keterangan,
                $item->media->isNotEmpty() ? 'Ada' : '-'
            ]);
        }
        
        return $data;
    }

    public function headings(): array
    {
        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // No
            'B' => 15,   // Kategori
            'C' => 20,   // Item
            'D' => 15,   // Status
            'E' => 15,   // Kondisi
            'F' => 30,   // Keterangan
            'G' => 20,   // Eviden
        ];
    }

    public function drawings()
    {
        $drawings = [];

        // PLN Logo (kiri)
        $plnDrawing = new Drawing();
        $plnDrawing->setName('PLN Logo');
        $plnDrawing->setDescription('PLN Logo');
        $plnDrawing->setPath(public_path('logo/navlog1.png'));
        $plnDrawing->setHeight(60);
        $plnDrawing->setCoordinates('A1');
        $plnDrawing->setOffsetX(5);
        $plnDrawing->setOffsetY(5);
        $drawings[] = $plnDrawing;

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
        $drawings[] = $unitDrawing;

        // Add K3 & Keamanan items
        $row = 9; // Start after headers and logos
        foreach ($this->report->items->where('item_type', 'k3_keamanan') as $item) {
            if ($item->media->isNotEmpty()) {
                $media = $item->media->first();
                $filePath = Storage::disk('public')->path($media->file_path);
                
                if (file_exists($filePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('K3 Image');
                    $drawing->setDescription('K3 Image');
                    $drawing->setPath($filePath);
                    $drawing->setHeight(100);
                    $drawing->setCoordinates('G' . $row);
                    $drawings[] = $drawing;
                }
            }
            $row++;
        }

        // Add Lingkungan items
        $row = $row + 2; // Skip empty row and section header
        foreach ($this->report->items->where('item_type', 'lingkungan') as $item) {
            if ($item->media->isNotEmpty()) {
                $media = $item->media->first();
                $filePath = Storage::disk('public')->path($media->file_path);
                
                if (file_exists($filePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Lingkungan Image');
                    $drawing->setDescription('Lingkungan Image');
                    $drawing->setPath($filePath);
                    $drawing->setHeight(100);
                    $drawing->setCoordinates('G' . $row);
                    $drawings[] = $drawing;
                }
            }
            $row++;
        }

        return $drawings;
    }

    public function title(): string
    {
        return 'K3 KAMP Report ' . $this->report->date->format('d/m/Y');
    }

    public function styles(Worksheet $sheet)
    {
        // Default style
        $sheet->getDefaultRowDimension()->setRowHeight(15);
        
        // Header style
        $headerStyle = [
            'font' => [
                'bold' => true,
                'size' => 11
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ];

        // Apply styles
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A1:G' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ]);

        // Apply header style
        $sheet->getStyle('A3:G3')->applyFromArray($headerStyle); // Main header
        $sheet->getStyle('A8:G8')->applyFromArray($headerStyle); // K3 & Keamanan headers
        $sheet->getStyle('A' . ($lastRow - 2) . ':G' . ($lastRow - 2))->applyFromArray($headerStyle); // Lingkungan headers

        // Center align main header
        $sheet->getStyle('A3:G3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A3:G3')->getFont()->setSize(14);

        return $sheet;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Set page orientation to landscape
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Set print area
                $sheet->getPageSetup()->setPrintArea('A1:G' . $sheet->getHighestRow());
                
                // Set zoom level
                $sheet->getSheetView()->setZoomScale(85);
                
                // Freeze first row
                $sheet->freezePane('A2');
            }
        ];
    }
} 