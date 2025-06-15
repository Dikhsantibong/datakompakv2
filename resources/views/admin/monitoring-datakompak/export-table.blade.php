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
    // Hitung jumlah kolom dinamis sesuai tab
    if ($tab === 'meeting-shift') {
        $colspan = 1 + count($data['dates']) * count($data['shifts']);
        $tanggalInfo = 'Bulan: ' . (isset($data['dates'][0]) ? \Carbon\Carbon::parse($data['dates'][0])->isoFormat('MMMM Y') : '');
    } elseif ($tab === 'data-engine') {
        $colspan = 1 + count($data['hours']);
        $tanggalInfo = 'Tanggal: ' . \Carbon\Carbon::parse($data['date'])->isoFormat('D MMMM Y');
    } elseif (isset($data['dates'])) {
        $colspan = 1 + count($data['dates']);
        $tanggalInfo = 'Bulan: ' . (isset($data['month']) ? \Carbon\Carbon::parse($data['month'])->isoFormat('MMMM Y') : '');
    } else {
        $colspan = 10;
        $tanggalInfo = '';
    }
@endphp

<table style="width:100%; margin-bottom: 20px;">
    <tr>
        <td style="vertical-align:middle; width:140px; padding:10px;" rowspan="2">
            @if(file_exists($logoPath))
                <img src="{{ $logoPath }}" alt="Logo" width="120" style="vertical-align:middle;">
            @endif
        </td>
        <td colspan="{{ $colspan - 1 }}" style="font-size:24px; font-weight:bold; text-align:center; vertical-align:middle; padding:15px;">
            {{ $judul }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ $colspan - 1 }}" style="text-align:center; font-size:16px; padding:10px;">
            {{ $tanggalInfo }}
        </td>
    </tr>
    <tr><td colspan="{{ $colspan }}" style="height:20px;"></td></tr>
</table>

@if($tab === 'meeting-shift')
<table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse:collapse; margin-top:10px;">
    <thead>
        <tr>
            <th style="background:#f3f4f6; font-weight:bold; text-align:center; border:1px solid #000; font-size:14px; padding:10px;">Unit</th>
            @foreach($data['dates'] as $date)
                <th style="background:#f3f4f6; font-weight:bold; text-align:center; border:1px solid #000; font-size:14px; padding:10px;" colspan="4">
                    {{ \Carbon\Carbon::parse($date)->format('d/m') }}
                </th>
            @endforeach
        </tr>
        <tr>
            <th style="background:#f3f4f6; font-weight:bold; border:1px solid #000; padding:10px;"></th>
            @foreach($data['dates'] as $date)
                @foreach($data['shifts'] as $shift)
                    <th style="background:#f3f4f6; font-weight:bold; border:1px solid #000; font-size:14px; padding:10px;">
                        {{ $shift }}
                    </th>
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($data['powerPlants'] as $powerPlant)
            @if($powerPlant->name !== 'UP KENDARI')
                <tr>
                    <td style="padding:10px; border:1px solid #000;">{{ $powerPlant->name }}</td>
                    @foreach($data['dates'] as $date)
                        @foreach($data['shifts'] as $shift)
                            @php $checked = $powerPlant->shiftStatus[$date . '_' . $shift]; @endphp
                            <td style="text-align:center; {{ $checked ? 'background:#c6efce; color:#006100;' : 'background:#ffc7ce; color:#9c0006;' }} padding:10px; border:1px solid #000;">
                                {{ $checked ? '✔' : '✘' }}
                            </td>
                        @endforeach
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@elseif($tab === 'data-engine')
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
                        @php $checked = $powerPlant->hourlyStatus[$hour]; @endphp
                        <td style="text-align:center; {{ $checked ? 'background:#c6efce; color:#006100;' : 'background:#ffc7ce; color:#9c0006;' }}">
                            {{ $checked ? '✔' : '✘' }}
                        </td>
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
                        @php $checked = $powerPlant->dailyStatus[$date]; @endphp
                        <td style="text-align:center; {{ $checked ? 'background:#c6efce; color:#006100;' : 'background:#ffc7ce; color:#9c0006;' }}">
                            {{ $checked ? '✔' : '✘' }}
                        </td>
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
                            $checked = $dayData['status'];
                        @endphp
                        <td style="text-align:center; {{ $checked ? 'background:#c6efce; color:#006100;' : 'background:#ffc7ce; color:#9c0006;' }}">
                            {{ $checked ? '✔' : '✘' }}
                        </td>
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
                            $checked = $dayData['status'];
                        @endphp
                        <td style="text-align:center; {{ $checked ? 'background:#c6efce; color:#006100;' : 'background:#ffc7ce; color:#9c0006;' }}">
                            {{ $checked ? '✔' : '✘' }}
                        </td>
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
                            $checked = $dayData['status'];
                        @endphp
                        <td style="text-align:center; {{ $checked ? 'background:#c6efce; color:#006100;' : 'background:#ffc7ce; color:#9c0006;' }}">
                            {{ $checked ? '✔' : '✘' }}
                        </td>
                    @endforeach
                </tr>
            @endif
        @endforeach
    </tbody>
</table>
@endif 