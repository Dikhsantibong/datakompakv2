@php
    $logoPath = public_path('logo/navlogo.png');
    $judul = match($tab) {
        'data-engine' => 'Rekapitulasi Data Engine 24 Jam',
        'daily-summary' => 'Rekapitulasi Ikhtisar Harian',
        'meeting-shift' => 'Rekapitulasi Meeting Shift',
        'bahan-bakar' => 'Rekapitulasi Data Bahan Bakar',
        'pelumas' => 'Rekapitulasi Data Pelumas',
        'laporan-kit' => 'Rekapitulasi Laporan KIT 00.00',
        default => 'Rekapitulasi Monitoring Data',
    };
@endphp

<table>
    <tr>
        <td rowspan="3" style="vertical-align:middle; width:140px;">
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Logo" width="120" style="vertical-align:middle;">
            @endif
        </td>
        <td colspan="100" style="font-size:1.5em; font-weight:bold; text-align:center; vertical-align:middle;">
            {{ $judul }}
        </td>
    </tr>
    <tr>
        <td colspan="100" style="text-align:center; font-size:1em;">
            @if($tab === 'data-engine')
                Tanggal: {{ \Carbon\Carbon::parse($data['date'])->isoFormat('D MMMM Y') }}
            @elseif(in_array($tab, ['daily-summary','meeting-shift','bahan-bakar','pelumas','laporan-kit']))
                Bulan: {{ isset($data['month']) ? \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') : '' }}
            @endif
        </td>
    </tr>
    <tr><td colspan="100"></td></tr>
</table>

@if($tab === 'data-engine')
<table>
    <thead>
        <tr>
            <th style="background:#e5e7eb; font-weight:bold;">Unit</th>
            @foreach($data['hours'] as $hour)
                <th style="background:#e5e7eb; font-weight:bold;">{{ \Carbon\Carbon::parse($hour)->format('H:i') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td>{{ $powerPlant->name }}</td>
                    @foreach($data['hours'] as $hour)
                        <td style="text-align:center;">{{ $powerPlant->hourlyStatus[$hour] ? '✔' : '✘' }}</td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@elseif($tab === 'daily-summary')
<table>
    <thead>
        <tr>
            <th style="background:#e5e7eb; font-weight:bold;">Unit</th>
            @foreach($data['dates'] as $date)
                <th style="background:#e5e7eb; font-weight:bold;">{{ \Carbon\Carbon::parse($date)->format('d/m') }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td>{{ $powerPlant->name }}</td>
                    @foreach($data['dates'] as $date)
                        <td style="text-align:center;">{{ $powerPlant->dailyStatus[$date] ? '✔' : '✘' }}</td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@elseif($tab === 'meeting-shift')
<table>
    <thead>
        <tr>
            <th style="background:#e5e7eb; font-weight:bold;">Unit</th>
            @foreach($data['dates'] as $date)
                @foreach($data['shifts'] as $shift)
                    <th style="background:#e5e7eb; font-weight:bold;">{{ \Carbon\Carbon::parse($date)->format('d/m') }} {{ $shift }}</th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td>{{ $powerPlant->name }}</td>
                    @foreach($data['dates'] as $date)
                        @foreach($data['shifts'] as $shift)
                            <td style="text-align:center;">{{ $powerPlant->shiftStatus[$date . '_' . $shift] ? '✔' : '✘' }}</td>
                        @endforeach
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@elseif($tab === 'bahan-bakar')
<table>
    <thead>
        <tr>
            <th style="background:#e5e7eb; font-weight:bold;">Unit</th>
            @foreach($data['dates'] as $date)
                <th style="background:#e5e7eb; font-weight:bold;">{{ $date }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td>{{ $powerPlant->name }}</td>
                    @foreach($data['dates'] as $index => $date)
                        @php
                            $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                            $dayData = $powerPlant->dailyData[$fullDate];
                        @endphp
                        <td style="text-align:center;">{{ $dayData['status'] ? '✔' : '✘' }}</td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@elseif($tab === 'pelumas')
<table>
    <thead>
        <tr>
            <th style="background:#e5e7eb; font-weight:bold;">Unit</th>
            @foreach($data['dates'] as $date)
                <th style="background:#e5e7eb; font-weight:bold;">{{ $date }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td>{{ $powerPlant->name }}</td>
                    @foreach($data['dates'] as $index => $date)
                        @php
                            $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                            $dayData = $powerPlant->dailyData[$fullDate];
                        @endphp
                        <td style="text-align:center;">{{ $dayData['status'] ? '✔' : '✘' }}</td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@elseif($tab === 'laporan-kit')
<table>
    <thead>
        <tr>
            <th style="background:#e5e7eb; font-weight:bold;">Unit</th>
            @foreach($data['dates'] as $date)
                <th style="background:#e5e7eb; font-weight:bold;">{{ $date }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td>{{ $powerPlant->name }}</td>
                    @foreach($data['dates'] as $index => $date)
                        @php
                            $fullDate = \Carbon\Carbon::createFromFormat('d/m', $date)->format('Y-m-d');
                            $dayData = $powerPlant->dailyData[$fullDate];
                        @endphp
                        <td style="text-align:center;">{{ $dayData['status'] ? '✔' : '✘' }}</td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@endif 