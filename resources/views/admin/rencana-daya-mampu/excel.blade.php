<!-- Excel Export Template -->
<table>
    <!-- Header Section -->
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 7) }}" height="60"></td>
    </tr>
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 7) }}" style="text-align: center; font-size: 14px; font-weight: bold;">
            PT PLN NUSANTARA POWER
        </td>
    </tr>
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 7) }}" style="text-align: center; font-size: 14px; font-weight: bold;">
            Rencana Daya Mampu - {{ $date }}
        </td>
    </tr>
    <tr>
        <td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 7) }}" style="text-align: right; font-size: 10px;">
            Dicetak pada: {{ now()->format('d/m/Y') }} | datakompak.com
        </td>
    </tr>
    <tr><td colspan="{{ 6 + (cal_days_in_month(CAL_GREGORIAN, $month, $year) * 7) }}"></td></tr>

    <!-- Table Headers -->
    <tr>
        <th rowspan="3" style="background-color: #f3f4f6; font-weight: bold; text-align: center; vertical-align: middle;">No</th>
        <th rowspan="3" style="background-color: #f3f4f6; font-weight: bold; text-align: center; vertical-align: middle;">Sistem Kelistrikan</th>
        <th rowspan="3" style="background-color: #f3f4f6; font-weight: bold; text-align: center; vertical-align: middle;">Mesin Pembangkit</th>
        <th rowspan="3" style="background-color: #f3f4f6; font-weight: bold; text-align: center; vertical-align: middle;">DMN SLO</th>
        <th rowspan="3" style="background-color: #f3f4f6; font-weight: bold; text-align: center; vertical-align: middle;">DMP PT</th>
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
            <th colspan="7" style="background-color: #f3f4f6; font-weight: bold; text-align: center;">{{ $i }}</th>
        @endfor
    </tr>
    <tr>
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
            <th colspan="5" style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">Rencana</th>
            <th colspan="2" style="background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center;">Realisasi</th>
        @endfor
    </tr>
    <tr>
        @for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++)
            <!-- Rencana Headers -->
            <th style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">Beban</th>
            <th style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">On</th>
            <th style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">Off</th>
            <th style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">Durasi</th>
            <th style="background-color: #dbeafe; color: #1e40af; font-weight: bold; text-align: center;">Keterangan</th>
            <!-- Realisasi Headers -->
            <th style="background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center;">Beban</th>
            <th style="background-color: #dcfce7; color: #166534; font-weight: bold; text-align: center;">Keterangan</th>
        @endfor
    </tr>

    <!-- Table Body -->
    @php $no = 1; @endphp
    @forelse($powerPlants as $plant)
        @foreach($plant->machines as $machine)
            @php
                $maxRows = 1; // Minimal 1 baris untuk setiap mesin
                $dailyData = [];

                // Siapkan data harian
                for ($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day++) {
                    $date = sprintf('%s-%s-%02d', $year, $month, $day);
                    $record = $machine->rencanaDayaMampu->first();
                    $data = $record ? $record->getDailyValue($date) : null;
                    
                    // Pastikan selalu ada minimal satu baris data
                    if (!$data || empty($data['rencana'])) {
                        $data['rencana'] = [['beban' => '', 'on' => '', 'off' => '', 'durasi' => '', 'keterangan' => '']];
                    }
                    if (!$data || empty($data['realisasi']) || !is_array($data['realisasi'])) {
                        $data['realisasi'] = [['beban' => '', 'keterangan' => '']];
                    }
                    
                    $dailyData[$date] = $data;
                }
            @endphp

            <tr>
                <td style="text-align: center; vertical-align: middle; border: 1px solid #e5e7eb;">{{ $no++ }}</td>
                <td style="vertical-align: middle; border: 1px solid #e5e7eb;">{{ $plant->name }}</td>
                <td style="vertical-align: middle; border: 1px solid #e5e7eb;">{{ $machine->name }}</td>
                <td style="text-align: center; vertical-align: middle; border: 1px solid #e5e7eb;">{{ $machine->dmn_slo ?? '-' }}</td>
                <td style="text-align: center; vertical-align: middle; border: 1px solid #e5e7eb;">{{ $machine->dmp_pt ?? '-' }}</td>

                @for($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day++)
                    @php
                        $date = sprintf('%s-%s-%02d', $year, $month, $day);
                        $data = $dailyData[$date];
                        $rencana = $data['rencana'][0] ?? ['beban' => '', 'on' => '', 'off' => '', 'durasi' => '', 'keterangan' => ''];
                        $realisasi = $data['realisasi'][0] ?? ['beban' => '', 'keterangan' => ''];
                    @endphp

                    <!-- Rencana Columns -->
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($rencana['beban']) ? number_format($rencana['beban'], 2) : '-' }}</td>
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($rencana['on']) ? \Carbon\Carbon::parse($rencana['on'])->format('H:i') : '-' }}</td>
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($rencana['off']) ? \Carbon\Carbon::parse($rencana['off'])->format('H:i') : '-' }}</td>
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($rencana['durasi']) ? number_format($rencana['durasi'], 2) : '-' }}</td>
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($rencana['keterangan']) ? $rencana['keterangan'] : '-' }}</td>

                    <!-- Realisasi Columns -->
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($realisasi['beban']) ? number_format($realisasi['beban'], 2) : '-' }}</td>
                    <td style="text-align: center; border: 1px solid #e5e7eb;">{{ !empty($realisasi['keterangan']) ? $realisasi['keterangan'] : '-' }}</td>
                @endfor
            </tr>
        @endforeach
    @empty
        <tr>
            <td style="text-align: center; vertical-align: middle; border: 1px solid #e5e7eb;">1</td>
            <td style="vertical-align: middle; border: 1px solid #e5e7eb;">-</td>
            <td style="vertical-align: middle; border: 1px solid #e5e7eb;">-</td>
            <td style="text-align: center; vertical-align: middle; border: 1px solid #e5e7eb;">-</td>
            <td style="text-align: center; vertical-align: middle; border: 1px solid #e5e7eb;">-</td>

            @for($day = 1; $day <= cal_days_in_month(CAL_GREGORIAN, $month, $year); $day++)
                <!-- Empty Rencana Columns -->
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>

                <!-- Empty Realisasi Columns -->
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>
                <td style="text-align: center; border: 1px solid #e5e7eb;">-</td>
            @endfor
        </tr>
    @endforelse
</table> 