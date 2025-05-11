<!DOCTYPE html>
<html>
<head>
    <title>Daily Summary Report - {{ $date }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            size: landscape;
            margin: 1cm;
        }
        body { 
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            position: relative;
            width: 100%;
            height: 50px;
            margin-bottom: 20px;
        }
        .logo-left {
            position: absolute;
            left: 0;
            top: 0;
            height: 40px;
        }
        .logo-right {
            position: absolute;
            right: 0;
            top: 0;
            height: 40px;
        }
        .report-title {
            text-align: center;
            padding-top: 10px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
            font-size: 8px;
        }
        th { 
            background-color: #f4f4f4;
            font-weight: bold;
        }
        h1 { 
            text-align: center;
            color: #333;
            font-size: 14px;
        }
        .unit-name { 
            margin-top: 20px;
            margin-bottom: 10px;
            color: #666;
            font-size: 12px;
        }
        .subcol-header {
            font-size: 7px;
            padding: 2px;
        }
        .grid-cols-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }
        .grid-cols-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
        }
        .grid-cols-4 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
        }
        .grid-cols-5 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr;
        }
        .grid-cols-7 {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }
        .border-r {
            border-right: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo/navlog1.png') }}" class="logo-left" alt="PLN Logo">
        <h1 class="report-title">Ikhtisar Harian  Report - {{ $date }}</h1>
        <h1 class="report-title">UNIT PEMBANGKITAN KENDARI </h1>
        
        <img src="{{ public_path('logo/k3_logo.png') }}" class="logo-right" alt="K3 Logo">
    </div>

    @foreach($units as $unit)
        <h2 class="unit-name">{{ $unit->name }}</h2>
        <table>
            <thead>
                <tr>
                    <th>Mesin</th>
                    <th>Daya (MW)</th>
                    <th>Beban Puncak (kW)</th>
                    <th>Ratio Daya Kit (%)</th>
                    <th>Produksi (kWh)</th>
                    <th>Pemakaian Sendiri</th>
                    <th>Jam Periode</th>
                    <th>Jam Operasi</th>
                    <th>Trip Non OMC</th>
                    <th>Derating</th>
                    <th>Kinerja Pembangkit</th>
                    <th>CF (%)</th>
                    <th>NOF (%)</th>
                    <th>JSI</th>
                    <th>Pemakaian BBM</th>
                    <th>Pemakaian Pelumas</th>
                    <th>Effisiensi</th>
                    <th>Ket.</th>
                </tr>
                <tr class="subcol-header">
                    <th></th>
                    <th>Terpasang | DMN | Mampu</th>
                    <th>Siang | Malam</th>
                    <th>Kit</th>
                    <th>Bruto | Netto</th>
                    <th>Aux | Susut | %</th>
                    <th>Jam</th>
                    <th>OPR | STBY | PO | MO | FO</th>
                    <th>Mesin | Listrik</th>
                    <th>EFDH | EPDH | EUDH | ESDH</th>
                    <th>EAF | SOF | EFOR | SdOF</th>
                    <th>NCF</th>
                    <th>NOF</th>
                    <th>Jam</th>
                    <th>HSD | B35 | MFO | Total | Air</th>
                    <th>Med | S420 | S430 | TrvA | T46 | T68 | Tot</th>
                    <th>SFC | NPHR | SLC</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($unit->machines as $machine)
                    @php
                        $summary = $unit->dailySummaries->where('machine_name', $machine->name)->first();
                    @endphp
                    <tr>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $summary ? number_format($summary->installed_power, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->dmn_power, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->capable_power, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->peak_load_day, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->peak_load_night, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->kit_ratio, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->gross_production, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->net_production, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->aux_power, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->transformer_losses, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->usage_percentage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->period_hours, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->operating_hours, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->standby_hours, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->planned_outage, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->maintenance_outage, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->forced_outage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->trip_machine, 0) : '-' }} | 
                            {{ $summary ? number_format($summary->trip_electrical, 0) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->efdh, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->epdh, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->eudh, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->esdh, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->eaf, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->sof, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->efor, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->sdof, 0) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->ncf, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->nof, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->jsi, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->hsd_fuel, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->b35_fuel, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->mfo_fuel, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->total_fuel, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->water_usage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->meditran_oil, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->salyx_420, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->salyx_430, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->travolube_a, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->turbolube_46, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->turbolube_68, 2) : '-' }} | 
                            {{ $summary ? number_format($summary->total_oil, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->sfc_scc, 3) : '-' }} | 
                            {{ $summary ? number_format($summary->nphr, 3) : '-' }} | 
                            {{ $summary ? number_format($summary->slc, 3) : '-' }}</td>
                        <td>{{ $summary ? $summary->notes : '-' }}</td>
                    </tr>
                @endforeach
                
                <!-- Totals Row -->
                <tr style="font-weight: bold;">
                    <td>TOTAL PLTD</td>
                    <td>
                        {{ number_format($unit->machines->sum('installed_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('dmn_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('capable_power'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('peak_load_day'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('peak_load_night'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->avg('kit_ratio'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('gross_production'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('net_production'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('aux_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('transformer_losses'), 2) }} | 
                        {{ number_format($unit->dailySummaries->avg('usage_percentage'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->sum('period_hours'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('operating_hours'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('standby_hours'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('planned_outage'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('maintenance_outage'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('forced_outage'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('trip_machine'), 0) }} | 
                        {{ number_format($unit->dailySummaries->sum('trip_electrical'), 0) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('efdh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('epdh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('eudh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('esdh'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->avg('eaf'), 2) }} | 
                        {{ number_format($unit->dailySummaries->avg('sof'), 2) }} | 
                        {{ number_format($unit->dailySummaries->avg('efor'), 2) }} | 
                        {{ number_format($unit->dailySummaries->avg('sdof'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->avg('ncf'), 2) }}</td>
                    <td>{{ number_format($unit->dailySummaries->avg('nof'), 2) }}</td>
                    <td>{{ number_format($unit->dailySummaries->sum('jsi'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('hsd_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('b35_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('mfo_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('total_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('water_usage'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->sum('meditran_oil'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('salyx_420'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('salyx_430'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('travolube_a'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('turbolube_46'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('turbolube_68'), 2) }} | 
                        {{ number_format($unit->dailySummaries->sum('total_oil'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->avg('sfc_scc'), 3) }} | 
                        {{ number_format($unit->dailySummaries->avg('nphr'), 3) }} | 
                        {{ number_format($unit->dailySummaries->avg('slc'), 3) }}
                    </td>
                    <td>-</td>
                </tr>

                <!-- Minimum Row -->
                <tr>
                    <td style="color: #009933;">MINIMUM</td>
                    <td>
                        {{ number_format($unit->machines->min('installed_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('dmn_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('capable_power'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('peak_load_day'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('peak_load_night'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->min('kit_ratio'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('gross_production'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('net_production'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('aux_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('transformer_losses'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('usage_percentage'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->min('period_hours'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('operating_hours'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('standby_hours'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('planned_outage'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('maintenance_outage'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('forced_outage'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('trip_machine'), 0) }} | 
                        {{ number_format($unit->dailySummaries->min('trip_electrical'), 0) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('efdh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('epdh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('eudh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('esdh'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('eaf'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('sof'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('efor'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('sdof'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->min('ncf'), 2) }}</td>
                    <td>{{ number_format($unit->dailySummaries->min('nof'), 2) }}</td>
                    <td>{{ number_format($unit->dailySummaries->min('jsi'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('hsd_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('b35_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('mfo_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('total_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('water_usage'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('meditran_oil'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('salyx_420'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('salyx_430'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('travolube_a'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('turbolube_46'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('turbolube_68'), 2) }} | 
                        {{ number_format($unit->dailySummaries->min('total_oil'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->min('sfc_scc'), 3) }} | 
                        {{ number_format($unit->dailySummaries->min('nphr'), 3) }} | 
                        {{ number_format($unit->dailySummaries->min('slc'), 3) }}
                    </td>
                    <td>-</td>
                </tr>

                <!-- Maximum Row -->
                <tr>
                    <td style="color: #cc3300;">MAXIMUM</td>
                    <td>
                        {{ number_format($unit->machines->max('installed_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('dmn_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('capable_power'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('peak_load_day'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('peak_load_night'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->max('kit_ratio'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('gross_production'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('net_production'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('aux_power'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('transformer_losses'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('usage_percentage'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->max('period_hours'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('operating_hours'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('standby_hours'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('planned_outage'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('maintenance_outage'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('forced_outage'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('trip_machine'), 0) }} | 
                        {{ number_format($unit->dailySummaries->max('trip_electrical'), 0) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('efdh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('epdh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('eudh'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('esdh'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('eaf'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('sof'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('efor'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('sdof'), 2) }}
                    </td>
                    <td>{{ number_format($unit->dailySummaries->max('ncf'), 2) }}</td>
                    <td>{{ number_format($unit->dailySummaries->max('nof'), 2) }}</td>
                    <td>{{ number_format($unit->dailySummaries->max('jsi'), 2) }}</td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('hsd_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('b35_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('mfo_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('total_fuel'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('water_usage'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('meditran_oil'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('salyx_420'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('salyx_430'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('travolube_a'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('turbolube_46'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('turbolube_68'), 2) }} | 
                        {{ number_format($unit->dailySummaries->max('total_oil'), 2) }}
                    </td>
                    <td>
                        {{ number_format($unit->dailySummaries->max('sfc_scc'), 3) }} | 
                        {{ number_format($unit->dailySummaries->max('nphr'), 3) }} | 
                        {{ number_format($unit->dailySummaries->max('slc'), 3) }}
                    </td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    @endforeach
</body>
</html> 