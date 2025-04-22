<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class DataEngineExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize
{
    protected $powerPlants;
    protected $date;

    public function __construct($powerPlants, $date)
    {
        $this->powerPlants = $powerPlants;
        $this->date = $date;
    }

    public function collection()
    {
        $data = collect();
        
        foreach ($this->powerPlants as $powerPlant) {
            foreach ($powerPlant->machines as $index => $machine) {
                $latestLog = $machine->getLatestLog($this->date);
                
                $data->push([
                    'power_plant' => $powerPlant->name,
                    'no' => $index + 1,
                    'machine' => $machine->name,
                    'time' => $latestLog ? Carbon::parse($latestLog->time)->format('H:i') : '-',
                    'kw' => $machine->kw ?? '-',
                    'kvar' => $machine->kvar ?? '-',
                    'cos_phi' => $machine->cos_phi ?? '-',
                    'status' => $machine->status ?? '-',
                    'keterangan' => $machine->keterangan ?? '-',
                ]);
            }
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Unit',
            'No',
            'Mesin',
            'Jam',
            'Beban (kW)',
            'kVAR',
            'Cos φ',
            'Status',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row['power_plant'],
            $row['no'],
            $row['machine'],
            $row['time'],
            $row['kw'],
            $row['kvar'],
            $row['cos_phi'],
            $row['status'],
            $row['keterangan']
        ];
    }

    public function title(): string
    {
        return 'Data Engine - ' . Carbon::parse($this->date)->format('d F Y');
    }
} 