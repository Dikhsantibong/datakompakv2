<?php
namespace App\Exports;

use App\Models\LaporanKit;
use App\Models\PowerPlant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKitExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $powerPlants = PowerPlant::all()->keyBy('unit_source');
        return LaporanKit::with('creator')->get()->map(function($laporan) use ($powerPlants) {
            return [
                'tanggal' => $laporan->tanggal,
                'unit' => $powerPlants[$laporan->unit_source]->name ?? '-',
                'dibuat_oleh' => $laporan->creator->name ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['Tanggal', 'Unit', 'Dibuat Oleh'];
    }
}
