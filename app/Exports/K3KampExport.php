<?php

namespace App\Exports;

use App\Models\K3KampReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class K3KampExport implements FromCollection, WithHeadings, WithMapping, WithTitle, WithStyles, ShouldAutoSize
{
    protected $report;

    public function __construct(K3KampReport $report)
    {
        $this->report = $report;
    }

    public function collection()
    {
        return $this->report->items;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kategori',
            'Item',
            'Status',
            'Kondisi',
            'Keterangan'
        ];
    }

    public function map($item): array
    {
        static $no = 1;
        return [
            $no++,
            ucfirst($item->item_type),
            $item->item_name,
            ucfirst($item->status),
            ucfirst($item->kondisi),
            $item->keterangan ?? '-'
        ];
    }

    public function title(): string
    {
        return 'K3 KAMP Report';
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'E2E8F0',
                ],
            ],
        ]);

        // Menambahkan judul laporan
        $sheet->insertNewRowBefore(1, 2);
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'Laporan K3 KAMP dan Lingkungan - ' . $this->report->date->format('d F Y'));
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // Menambahkan footer
        $lastRow = $sheet->getHighestRow() + 1;
        $sheet->mergeCells('A' . $lastRow . ':F' . $lastRow);
        $sheet->setCellValue('A' . $lastRow, 'Dibuat oleh: ' . ($this->report->creator->name ?? 'System') . 
            ' | Tanggal: ' . $this->report->created_at->format('d/m/Y H:i'));
        
        // Border untuk seluruh cell yang berisi data
        $sheet->getStyle('A1:F' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ]);

        // Auto-size columns
        foreach(range('A','F') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [
            3 => ['font' => ['bold' => true]], // Header row (setelah judul)
        ];
    }
} 