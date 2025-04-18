<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CalendarExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $schedules;

    public function __construct($schedules)
    {
        $this->schedules = $schedules;
    }

    public function collection()
    {
        return collect($this->schedules);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Judul',
            'Waktu Mulai',
            'Waktu Selesai',
            'Lokasi',
            'Deskripsi',
            'Status',
            'Peserta'
        ];
    }

    public function map($schedule): array
    {
        return [
            $schedule['date'] ?? '-',
            $schedule['title'] ?? '-',
            $schedule['start_time'] ?? '-',
            $schedule['end_time'] ?? '-',
            $schedule['location'] ?? '-',
            $schedule['description'] ?? '-',
            ucfirst($schedule['status'] ?? 'scheduled'),
            is_array($schedule['participants'] ?? null) ? implode(', ', $schedule['participants']) : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:H1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4A90E2']
                ],
                'font' => ['color' => ['rgb' => 'FFFFFF']]
            ]
        ];
    }
} 