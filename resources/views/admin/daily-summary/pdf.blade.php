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
            font-size: 8px;
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
            font-size: 10px;
        }
        table { 
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td { 
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            font-size: 7px;
        }
        th { 
            background-color: #B8CCE4;
            color: #000000;
            font-weight: bold;
        }
        .sub-header {
            background-color: #B8CCE4;
            color: #000000;
            font-weight: bold;
            text-align: center;
        }
        .unit-name-row td {
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #000;
        }
        .total-row, .average-row, .min-row, .max-row {
            font-weight: bold;
        }
        .min-row td { color: #009933; }
        .max-row td { color: #cc3300; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo/navlog1.png') }}" class="logo-left" alt="PLN Logo">
        <h1 class="report-title">Ikhtisar Harian  Report - {{ $date }}</h1>
        <h1 class="report-title">UNIT PEMBANGKITAN KENDARI </h1>
        <img src="{{ public_path('logo/k3_logo.png') }}" class="logo-right" alt="K3 Logo">
    </div>

    @foreach($units as $i => $unit)
        <table @if($i > 0) style="page-break-before: always;" @endif>
            <thead>
                <tr>
                    <td colspan="48" style="text-align: center; font-size: 10px; font-weight: bold; border:1px solid #000;">
                        Ikhtisar Harian Report - {{ $date }}
                    </td>
                </tr>
                <tr>
                    <td colspan="48" style="border:1px solid #000;"></td>
                </tr>
                <tr>
                    <th rowspan="2">Mesin</th>
                    <th colspan="3">Daya (MW)</th>
                    <th colspan="2">Beban Puncak (kW)</th>
                    <th rowspan="2">Ratio Daya Kit (%)</th>
                    <th colspan="2">Produksi (kWh)</th>
                    <th colspan="3">Pemakaian Sendiri</th>
                    <th rowspan="2">Jam Periode</th>
                    <th colspan="5">Jam Operasi</th>
                    <th colspan="2" class="sub-header">Trip Non OMC</th>
                    <th colspan="4" class="sub-header">Derating</th>
                    <th colspan="4" class="sub-header">Kinerja Pembangkit</th>
                    <th rowspan="2" class="sub-header">CF (%)</th>
                    <th rowspan="2" class="sub-header">NOF (%)</th>
                    <th rowspan="2" class="sub-header">JSI</th>
                    <th colspan="6" class="sub-header">Pemakaian BBM</th>
                    <th colspan="7" class="sub-header">Pemakaian Pelumas</th>
                    <th colspan="3" class="sub-header">Effisiensi</th>
                    <th rowspan="2" class="sub-header">Ket.</th>
                </tr>
                <tr>
                    <th>Terpasang</th>
                    <th>DMN</th>
                    <th>Mampu</th>
                    <th>Siang</th>
                    <th>Malam</th>
                    <th>Bruto</th>
                    <th>Netto</th>
                    <th>Aux</th>
                    <th>Susut</th>
                    <th>%</th>
                    <th>OPR</th>
                    <th>STBY</th>
                    <th>PO</th>
                    <th>MO</th>
                    <th>FO</th>
                    <th class="sub-header">Mesin</th>
                    <th class="sub-header">Listrik</th>
                    <th class="sub-header">EFDH</th>
                    <th class="sub-header">EPDH</th>
                    <th class="sub-header">EUDH</th>
                    <th class="sub-header">ESDH</th>
                    <th class="sub-header">EAF</th>
                    <th class="sub-header">SOF</th>
                    <th class="sub-header">EFOR</th>
                    <th class="sub-header">SdOF</th>
                    <th class="sub-header">HSD</th>
                    <th class="sub-header">B30</th>
                    <th class="sub-header">B40</th>
                    <th class="sub-header">MFO</th>
                    <th class="sub-header">Total</th>
                    <th class="sub-header">Air</th>
                    <th class="sub-header">Med</th>
                    <th class="sub-header">S420</th>
                    <th class="sub-header">S430</th>
                    <th class="sub-header">TrvA</th>
                    <th class="sub-header">T46</th>
                    <th class="sub-header">T68</th>
                    <th class="sub-header">Tot</th>
                    <th class="sub-header">SFC</th>
                    <th class="sub-header">NPHR</th>
                    <th class="sub-header">SLC</th>
                </tr>
            </thead>
            <tbody>
                <tr class="unit-name-row">
                    <td colspan="48">{{ $unit->name }}</td>
                </tr>
                @foreach($unit->machines as $machine)
                    @php
                        $summary = $unit->dailySummaries->first(function($summary) use ($machine) {
                            if ($summary->machine_name === $machine->name) {
                                return true;
                            }
                            $mirrleesMachineName = str_replace('MIRR', 'MIRRLEES', $machine->name);
                            return $summary->machine_name === $mirrleesMachineName;
                        });
                    @endphp
                    <tr>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $summary ? number_format($summary->installed_power, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->dmn_power, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->capable_power, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->peak_load_day, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->peak_load_night, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->kit_ratio, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->gross_production, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->net_production, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->aux_power, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->transformer_losses, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->usage_percentage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->period_hours, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->operating_hours, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->standby_hours, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->planned_outage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->maintenance_outage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->forced_outage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->trip_machine, 0) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->trip_electrical, 0) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->efdh, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->epdh, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->eudh, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->esdh, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->eaf, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->sof, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->efor, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->sdof, 0) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->ncf, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->nof, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->jsi, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->hsd_fuel, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->b35_fuel, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->b40_fuel, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->mfo_fuel, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->total_fuel, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->water_usage, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->meditran_oil, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->salyx_420, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->salyx_430, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->travolube_a, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->turbolube_46, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->turbolube_68, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->total_oil, 2) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->sfc_scc, 3) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->nphr, 3) : '-' }}</td>
                        <td>{{ $summary ? number_format($summary->slc, 3) : '-' }}</td>
                        <td>{{ $summary ? $summary->notes : '-' }}</td>
                    </tr>
                @endforeach
                <!-- Totals, Average, Min, Max rows dapat disalin dari excel.blade.php jika ingin konsisten -->
            </tbody>
        </table>
    @endforeach
</body>
</html> 