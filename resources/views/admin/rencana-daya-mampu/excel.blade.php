<table>
    <!-- Row for logos -->
    <tr>
        <td colspan="{{ 8 + cal_days_in_month(CAL_GREGORIAN, $month, $year) }}" height="60"></td>
    </tr>
    <!-- Company name -->
    <tr>
        <td colspan="{{ 8 + cal_days_in_month(CAL_GREGORIAN, $month, $year) }}" style="text-align: center; font-size: 14px; font-weight: bold;">
            PT PLN NUSANTARA POWER
        </td>
    </tr>
    <!-- Title -->
    <tr>
        <td colspan="{{ 8 + cal_days_in_month(CAL_GREGORIAN, $month, $year) }}" style="text-align: center; font-size: 14px; font-weight: bold;">
            Rencana Daya Mampu - {{ $date }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ 8 + cal_days_in_month(CAL_GREGORIAN, $month, $year) }}" style="text-align: right; font-size: 10px;">
            Dicetak pada: {{ now()->format('d/m/Y') }} | datakompak.com
        </td>
    </tr>
    <tr><td colspan="{{ 8 + cal_days_in_month(CAL_GREGORIAN, $month, $year) }}"></td></tr>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Sistem Kelistrikan</th>
        <th rowspan="2">Mesin Pembangkit</th>
        <th rowspan="2">Site Pembangkit</th>
        <th colspan="2">Rencana Realisasi</th>
        <th rowspan="2">Daya PJBTL SILM</th>
        <th rowspan="2">DMP Existing</th>
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
            <th>{{ $i }}</th>
        @endfor
    </tr>
    <tr>
        <th>Rencana</th>
        <th>Realisasi</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($powerPlants as $plant)
        @foreach($plant->machines as $machine)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $plant->name }}</td>
                <td>{{ $machine->name }}</td>
                <td>{{ $plant->name }}</td>
                <td>{{ $machine->rencana ?? '-' }}</td>
                <td>{{ $machine->realisasi ?? '-' }}</td>
                <td>{{ $machine->daya_pjbtl_silm ?? '-' }}</td>
                <td>{{ $machine->dmp_existing ?? '-' }}</td>
                @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
                    @php
                        $date = sprintf('%s-%s-%02d', $year, $month, $i);
                        $dailyValue = $machine->rencanaDayaMampu->first()?->getDailyValue($date, 'rencana');
                    @endphp
                    <td>{{ $dailyValue ?? '-' }}</td>
                @endfor
            </tr>
        @endforeach
    @endforeach
</table> 