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
            'Dibuat oleh: ' . $this->report->creator->name,
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

        // Add logos
        if (file_exists(public_path('logo/navlog1.png'))) {
            $plnDrawing = new Drawing();
            $plnDrawing->setName('PLN Logo');
            $plnDrawing->setDescription('PLN Logo');
            $plnDrawing->setPath(public_path('logo/navlog1.png'));
            $plnDrawing->setHeight(60);
            $plnDrawing->setCoordinates('A1');
            $plnDrawing->setOffsetX(5);
            $plnDrawing->setOffsetY(5);
            $drawings[] = $plnDrawing;
        }

        if (file_exists(public_path('logo/PLN-bg.png'))) {
            $plnBgDrawing = new Drawing();
            $plnBgDrawing->setName('PLN-bg Logo');
            $plnBgDrawing->setDescription('PLN-bg Logo');
            $plnBgDrawing->setPath(public_path('logo/PLN-bg.png'));
            $plnBgDrawing->setHeight(60);
            $plnBgDrawing->setCoordinates('H1');
            $plnBgDrawing->setOffsetX(5);
            $plnBgDrawing->setOffsetY(5);
            $drawings[] = $plnBgDrawing;
        }

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