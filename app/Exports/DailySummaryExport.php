<?php

namespace App\Exports;

use App\Models\PowerPlant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DailySummaryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
    }

    public function collection()
    {
        return PowerPlant::with(['dailySummaries' => function($query) {
            $query->whereDate('created_at', $this->date);
        }])->get()->flatMap(function ($unit) {
            return $unit->dailySummaries;
        });
    }

    public function headings(): array
    {
        return [
            'Unit',
            'Mesin',
            'Daya Terpasang (MW)',
            'Daya DMN (MW)',
            'Daya Mampu (MW)',
            'Beban Puncak Siang (kW)',
            'Beban Puncak Malam (kW)',
            'Ratio Daya Kit (%)',
            'Produksi Bruto (kWh)',
            'Produksi Netto (kWh)',
            'Aux Power (kWh)',
            'Susut Trafo (kWh)',
            'Pemakaian Sendiri (%)',
            'Jam Periode',
            'Jam Operasi',
            'Jam Standby',
            'Planned Outage',
            'Maintenance Outage',
            'Forced Outage',
            'Trip Mesin (kali)',
            'Trip Listrik (kali)',
            'EFDH',
            'EPDH',
            'EUDH',
            'ESDH',
            'EAF (%)',
            'SOF (%)',
            'EFOR (%)',
            'SdOF (Kali)',
            'NCF (%)',
            'NOF (%)',
            'JSI',
            'HSD (Liter)',
            'B35 (Liter)',
            'MFO (Liter)',
            'Total BBM (Liter)',
            'Air (M³)',
            'Meditran Oil (Liter)',
            'Salyx 420 (Liter)',
            'Salyx 430 (Liter)',
            'Travolube A (Liter)',
            'Turbolube 46 (Liter)',
            'Turbolube 68 (Liter)',
            'Total Oil (Liter)',
            'SFC/SCC (Liter/kWh)',
            'NPHR (kCal/kWh)',
            'SLC (cc/kWh)',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row->powerPlant->name,
            $row->machine_name,
            $row->installed_power,
            $row->dmn_power,
            $row->capable_power,
            $row->peak_load_day,
            $row->peak_load_night,
            $row->kit_ratio,
            $row->gross_production,
            $row->net_production,
            $row->aux_power,
            $row->transformer_losses,
            $row->usage_percentage,
            $row->period_hours,
            $row->operating_hours,
            $row->standby_hours,
            $row->planned_outage,
            $row->maintenance_outage,
            $row->forced_outage,
            $row->trip_machine,
            $row->trip_electrical,
            $row->efdh,
            $row->epdh,
            $row->eudh,
            $row->esdh,
            $row->eaf,
            $row->sof,
            $row->efor,
            $row->sdof,
            $row->ncf,
            $row->nof,
            $row->jsi,
            $row->hsd_fuel,
            $row->b35_fuel,
            $row->mfo_fuel,
            $row->total_fuel,
            $row->water_usage,
            $row->meditran_oil,
            $row->salyx_420,
            $row->salyx_430,
            $row->travolube_a,
            $row->turbolube_46,
            $row->turbolube_68,
            $row->total_oil,
            $row->sfc_scc,
            $row->nphr,
            $row->slc,
            $row->notes
        ];
    }
} 