<table>
    <thead>
        <tr>
            <td colspan="18" style="text-align: center; font-size: 14px; font-weight: bold;">
                Ikhtisar Harian Report - {{ $date }}
            </td>
        </tr>
        <tr>
            <td colspan="18"></td> <!-- Empty row for spacing -->
        </tr>
    </thead>

    @foreach($units as $unit)
        <tr>
            <td colspan="18" style="font-size: 12px; font-weight: bold;">{{ $unit->name }}</td>
        </tr>
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
        <tr>
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
        
        <tr>
            <td colspan="18"></td> <!-- Empty row for spacing between units -->
        </tr>
    @endforeach
</table> 