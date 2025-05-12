<!-- Excel Export Template -->
<table>
    <!-- Header Section -->
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 2) }}" height="60"></td>
    </tr>
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 2) }}" style="text-align: center; font-size: 14px; font-weight: bold;">
            PT PLN NUSANTARA POWER
        </td>
    </tr>
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 2) }}" style="text-align: center; font-size: 14px; font-weight: bold;">
            Rencana Daya Mampu - {{ $date }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 2) }}" style="text-align: right; font-size: 10px;">
            Dicetak pada: {{ now()->format('d/m/Y') }} | datakompak.com
        </td>
    </tr>
    <tr><td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 2) }}"></td></tr>

    <!-- Table Headers -->
    <tr>
        <th rowspan="2" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">No</th>
        <th rowspan="2" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">Sistem Kelistrikan</th>
        <th rowspan="2" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">Mesin Pembangkit</th>
        <th rowspan="2" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">DMN SLO</th>
        <th rowspan="2" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">DMP PT</th>
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
            <th colspan="2" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">{{ $i }}</th>
        @endfor
    </tr>
    <tr>
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
            <th style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">Rencana</th>
            <th style="background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center;">Realisasi</th>
        @endfor
    </tr>

    <!-- Table Body -->
    @php $no = 1; @endphp
    @foreach($powerPlants as $plant)
        @foreach($plant->machines as $machine)
            <tr>
                <td style="text-align: center;">{{ $no++ }}</td>
                <td>{{ $plant->name }}</td>
                <td>{{ $machine->name }}</td>
                <td style="text-align: center;">{{ $machine->dmn_slo ?? '-' }}</td>
                <td style="text-align: center;">{{ $machine->dmp_pt ?? '-' }}</td>
                @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
                    @php
                        $date = sprintf('%s-%s-%02d', $year, $month, $i);
                        $data = $machine->rencanaDayaMampu->first()?->getDailyValue($date) ?? [];
                    @endphp
                    <td style="background-color: #dbeafe;">
                        @php
                            $rencanaRows = $data['rencana'] ?? [];
                            $rencanaBeban = collect($rencanaRows)->pluck('beban')->filter()->implode(', ');
                        @endphp
                        {{ $rencanaBeban ?: '-' }}
                    </td>
                    <td style="background-color: #dcfce7;">
                        @php
                            $realisasi = $data['realisasi'] ?? [];
                            $realisasiBeban = $realisasi['beban'] ?? '-';
                        @endphp
                        {{ $realisasiBeban }}
                    </td>
                @endfor
            </tr>
        @endforeach
    @endforeach
</table> 