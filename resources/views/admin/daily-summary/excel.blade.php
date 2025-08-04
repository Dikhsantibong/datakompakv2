<table border="1" style="border-collapse: collapse;">
    <thead>
        <tr>
            <td colspan="48" style="text-align: center; font-size: 14px; font-weight: bold; border:1px solid #000;">
                Ikhtisar Harian Report - {{ $date }}
            </td>
        </tr>
        <tr>
            <td colspan="48" style="border:1px solid #000;"></td> <!-- Empty row for spacing -->
        </tr>
        <tr>
            <th rowspan="2" style="border:1px solid #000;">Mesin</th>
            <th colspan="3" style="border:1px solid #000;">Daya (MW)</th>
            <th colspan="2" style="border:1px solid #000;">Beban Puncak (kW)</th>
            <th rowspan="2" style="border:1px solid #000;">Ratio Daya Kit (%)</th>
            <th colspan="2" style="border:1px solid #000;">Produksi (kWh)</th>
            <th colspan="3" style="border:1px solid #000;">Pemakaian Sendiri</th>
            <th rowspan="2" style="border:1px solid #000;">Jam Periode</th>
            <th colspan="5" style="border:1px solid #000;">Jam Operasi</th>
            <th colspan="2" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Trip Non OMC</th>
            <th colspan="4" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Derating</th>
            <th colspan="4" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Kinerja Pembangkit</th>
            <th rowspan="2" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">CF (%)</th>
            <th rowspan="2" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">NOF (%)</th>
            <th rowspan="2" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">JSI</th>
            <th colspan="6" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Pemakaian BBM</th>
            <th colspan="7" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Pemakaian Pelumas</th>
            <th colspan="3" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Effisiensi</th>
            <th rowspan="2" style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Ket.</th>
        </tr>
        <tr>
            <th style="border:1px solid #000;">Terpasang</th>
            <th style="border:1px solid #000;">DMN</th>
            <th style="border:1px solid #000;">Mampu</th>
            <th style="border:1px solid #000;">Siang</th>
            <th style="border:1px solid #000;">Malam</th>
            <th style="border:1px solid #000;">Bruto</th>
            <th style="border:1px solid #000;">Netto</th>
            <th style="border:1px solid #000;">Aux</th>
            <th style="border:1px solid #000;">Susut</th>
            <th style="border:1px solid #000;">%</th>
            <th style="border:1px solid #000;">OPR</th>
            <th style="border:1px solid #000;">STBY</th>
            <th style="border:1px solid #000;">PO</th>
            <th style="border:1px solid #000;">MO</th>
            <th style="border:1px solid #000;">FO</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Mesin</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Listrik</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">EFDH</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">EPDH</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">EUDH</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">ESDH</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">EAF</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">SOF</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">EFOR</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">SdOF</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">HSD</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">B30</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">B40</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">MFO</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Total</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Air</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Med</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">S420</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">S430</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">TrvA</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">T46</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">T68</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">Tot</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">SFC</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">NPHR</th>
            <th style="border:1px solid #000; background-color: #B8CCE4; color: #000000; font-weight: bold; text-align: center;">SLC</th>
        </tr>
    </thead>
    <tbody>
    @foreach($units as $unit)
        <tr>
            <td colspan="48" style="font-size: 12px; font-weight: bold; border:1px solid #000;">{{ $unit->name }}</td>
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
                <td style="border:1px solid #000;">{{ $machine->name }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->installed_power, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->dmn_power, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->capable_power, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->peak_load_day, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->peak_load_night, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->kit_ratio, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->gross_production, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->net_production, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->aux_power, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->transformer_losses, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->usage_percentage, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->period_hours, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->operating_hours, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->standby_hours, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->planned_outage, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->maintenance_outage, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->forced_outage, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->trip_machine, 0) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->trip_electrical, 0) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->efdh, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->epdh, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->eudh, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->esdh, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->eaf, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->sof, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->efor, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->sdof, 0) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->ncf, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->nof, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->jsi, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->hsd_fuel, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->b35_fuel, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->b40_fuel, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->mfo_fuel, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->total_fuel, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->water_usage, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->meditran_oil, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->salyx_420, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->salyx_430, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->travolube_a, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->turbolube_46, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->turbolube_68, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->total_oil, 2) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->sfc_scc, 3) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->nphr, 3) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? number_format($summary->slc, 3) : '-' }}</td>
                <td style="border:1px solid #000;">{{ $summary ? $summary->notes : '-' }}</td>
            </tr>
        @endforeach

        <!-- Totals Row -->
        <tr style="font-weight: bold;">
            <td style="border:1px solid #000;">TOTAL PLTD</td>
            <td style="border:1px solid #000;">{{ number_format($unit->machines->sum('installed_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('dmn_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('capable_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('peak_load_day'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('peak_load_night'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('kit_ratio'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('gross_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('net_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('aux_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('transformer_losses'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('usage_percentage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('period_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('operating_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('standby_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('planned_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('maintenance_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('forced_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('trip_machine'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('trip_electrical'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('efdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('epdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('eudh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('esdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('eaf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('sof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('efor'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('sdof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('ncf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('nof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('jsi'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('hsd_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('b35_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('b40_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('mfo_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('total_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('water_usage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('meditran_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('salyx_420'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('salyx_430'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('travolube_a'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('turbolube_46'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('turbolube_68'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->sum('total_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('sfc_scc'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('nphr'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('slc'), 3) }}</td>
            <td style="border:1px solid #000;">-</td>
        </tr>

        <!-- Average Row -->
        <tr style="font-weight: bold; color: #B8CCE4;">
            <td style="border:1px solid #000;">RATA-RATA</td>
            <td style="border:1px solid #000;">{{ number_format($unit->machines->avg('installed_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('dmn_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('capable_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('peak_load_day'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('peak_load_night'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('kit_ratio'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('gross_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('net_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('aux_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('transformer_losses'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('usage_percentage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('period_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('operating_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('standby_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('planned_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('maintenance_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('forced_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('trip_machine'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('trip_electrical'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('efdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('epdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('eudh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('esdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('eaf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('sof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('efor'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('sdof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('ncf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('nof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('jsi'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('hsd_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('b35_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('b40_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('mfo_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('total_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('water_usage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('meditran_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('salyx_420'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('salyx_430'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('travolube_a'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('turbolube_46'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('turbolube_68'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('total_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('sfc_scc'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('nphr'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->avg('slc'), 3) }}</td>
            <td style="border:1px solid #000;">-</td>
        </tr>

        <!-- Minimum Row -->
        <tr style="font-weight: bold; color: #009933;">
            <td style="border:1px solid #000;">MINIMUM</td>
            <td style="border:1px solid #000;">{{ number_format($unit->machines->min('installed_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('dmn_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('capable_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('peak_load_day'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('peak_load_night'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('kit_ratio'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('gross_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('net_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('aux_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('transformer_losses'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('usage_percentage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('period_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('operating_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('standby_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('planned_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('maintenance_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('forced_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('trip_machine'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('trip_electrical'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('efdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('epdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('eudh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('esdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('eaf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('sof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('efor'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('sdof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('ncf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('nof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('jsi'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('hsd_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('b35_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('b40_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('mfo_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('total_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('water_usage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('meditran_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('salyx_420'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('salyx_430'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('travolube_a'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('turbolube_46'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('turbolube_68'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('total_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('sfc_scc'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('nphr'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->min('slc'), 3) }}</td>
            <td style="border:1px solid #000;">-</td>
        </tr>

        <!-- Maximum Row -->
        <tr style="font-weight: bold; color: #cc3300;">
            <td style="border:1px solid #000;">MAXIMUM</td>
            <td style="border:1px solid #000;">{{ number_format($unit->machines->max('installed_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('dmn_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('capable_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('peak_load_day'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('peak_load_night'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('kit_ratio'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('gross_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('net_production'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('aux_power'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('transformer_losses'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('usage_percentage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('period_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('operating_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('standby_hours'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('planned_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('maintenance_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('forced_outage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('trip_machine'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('trip_electrical'), 0) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('efdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('epdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('eudh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('esdh'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('eaf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('sof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('efor'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('sdof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('ncf'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('nof'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('jsi'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('hsd_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('b35_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('b40_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('mfo_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('total_fuel'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('water_usage'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('meditran_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('salyx_420'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('salyx_430'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('travolube_a'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('turbolube_46'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('turbolube_68'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('total_oil'), 2) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('sfc_scc'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('nphr'), 3) }}</td>
            <td style="border:1px solid #000;">{{ number_format($unit->dailySummaries->max('slc'), 3) }}</td>
            <td style="border:1px solid #000;">-</td>
        </tr>
        
        <tr>
            <td colspan="48" style="border:1px solid #000;"></td> <!-- Empty row for spacing between units -->
        </tr>
    @endforeach
</table> 