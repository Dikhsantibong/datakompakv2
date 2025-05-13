<!DOCTYPE html>
<html>
<head>
    <title>Daily Summary Report</title>
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
            page-break-inside: avoid;
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
        .date-section {
            page-break-before: always;
        }
        .date-section:first-child {
            page-break-before: avoid;
        }
        .date-header {
            background-color: #e3e3e3;
            padding: 10px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>
<body>
    @foreach($allData as $data)
    <div class="date-section">
        <div class="header">
            <img src="{{ public_path('logo/navlog1.png') }}" class="logo-left">
            <img src="{{ public_path('logo/k3_logo.png') }}" class="logo-right">
            <h1 class="report-title">
                LAPORAN IKHTISAR HARIAN<br>
                {{ \Carbon\Carbon::parse($data['date'])->isoFormat('dddd, D MMMM Y') }}
            </h1>
        </div>

        @foreach($data['units'] as $unit)
        <div class="unit-section">
            <table>
                <thead>
                    <tr>
                        <th colspan="18">{{ strtoupper($unit->name) }}</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Mesin</th>
                        <th>Daya (kW)</th>
                        <th>Beban (kW)</th>
                        <th>Rasio</th>
                        <th>Produksi (kWh)</th>
                        <th>Pemakaian Sendiri</th>
                        <th>Jam</th>
                        <th>Jam Operasi</th>
                        <th>Trip</th>
                        <th>Derating</th>
                        <th>Kinerja (%)</th>
                        <th>CF</th>
                        <th>NOF</th>
                        <th>JSI</th>
                        <th>Bahan Bakar</th>
                        <th>Pelumas</th>
                        <th>Efisiensi</th>
                        <th>Keterangan</th>
                    </tr>
                    <tr class="subcol-header">
                        <th>Terpasang | DMN | Mampu</th>
                        <th>Siang | Malam</th>
                        <th>KIT</th>
                        <th>Bruto | Netto</th>
                        <th>PS | Trafo | %PS</th>
                        <th>Periode</th>
                        <th>OH | SB | PO | MO | FO</th>
                        <th>Mesin | Listrik</th>
                        <th>EFDH | EPDH | EUDH | ESDH</th>
                        <th>EAF | SOF | EFOR | SDOF</th>
                        <th>NCF</th>
                        <th>NOF</th>
                        <th>JSI</th>
                        <th>HSD | B35 | B40 | MFO | Total | Air</th>
                        <th>Meditran | Salyx 420 | Salyx 430 | Travolube A | Turbolube 46 | Turbolube 68 | Total</th>
                        <th>SFC/SCC | NPHR | SLC</th>
                        <th>Notes</th>
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
                                {{ $summary ? number_format($summary->b40_fuel, 2) : '-' }} | 
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
                            {{ number_format($unit->dailySummaries->sum('b40_fuel'), 2) }} | 
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
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    @endforeach
</body>
</html> 