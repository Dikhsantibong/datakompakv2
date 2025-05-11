<!-- Header with Logos -->
<table>
    <thead>
        <tr>
            <th colspan="2" style="text-align: left; width: 15%;">
                <img src="{{ $navlog_path }}" alt="PLN Logo" width="80" height="30">
            </th>
            <th colspan="4" style="text-align: center; font-size: 14px; font-weight: bold; width: 70%;">
                Laporan Status Mesin Pembangkit
            </th>
            {{-- <th colspan="2" style="text-align: right; width: 15%;">
                <img src="{{ $k3_path }}" alt="K3 Logo" width="60" height="20">
            </th> --}}
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                PT PLN Nusantara Power Unit Pembangkitan Kendari
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                Waktu Input: {{ $selectedInputTime ?? 'Semua Waktu' }}
            </th>
        </tr>
    </thead>
</table>

@foreach($powerPlants as $powerPlant)
    @unless($powerPlant->name === 'UP KENDARI')
        <table>
            <thead>
                <tr>
                    <th colspan="8" style="text-align: center; font-size: 12px; font-weight: bold;">
                        {{ $powerPlant->name }}
                    </th>
                </tr>
                @php
                    $filteredLogs = $logs->filter(function($log) use ($date) {
                        return $log->tanggal->format('Y-m-d') === $date;
                    });

                    $totalDMP = $filteredLogs->whereIn('machine_id', $powerPlant->machines->pluck('id'))
                        ->sum(fn($log) => (float) $log->dmp);
                    
                    $totalDMN = $filteredLogs->whereIn('machine_id', $powerPlant->machines->pluck('id'))
                        ->sum(fn($log) => (float) $log->dmn);
                    
                    $totalBeban = $filteredLogs->whereIn('machine_id', $powerPlant->machines->pluck('id'))
                        ->sum(function($log) {
                            if ($log->status === 'Operasi') {
                                return (float) $log->load_value;
                            }
                            return 0;
                        });

                    $hopValue = $hops->where('power_plant_id', $powerPlant->id)->first()?->hop_value ?? 0;
                @endphp
                <tr>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">DMN Total</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">DMP Total</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">Total Beban</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">Derating</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">HOP</th>
                </tr>
                <tr>
                    <td style="text-align: center;">{{ number_format($totalDMN, 2) }} MW</td>
                    <td style="text-align: center;">{{ number_format($totalDMP, 2) }} MW</td>
                    <td style="text-align: center;">{{ number_format($totalBeban, 2) }} MW</td>
                    <td style="text-align: center;">{{ number_format($totalDMN - $totalDMP, 2) }} MW</td>
                    <td style="text-align: center;">{{ number_format($hopValue, 1) }} Hari</td>
                </tr>
                <tr>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 40px;">No</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 100px;">Mesin</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">DMN (MW)</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">DMP (MW)</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">Beban (MW)</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">Status</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 200px;">Deskripsi</th>
                    <th style="font-weight: bold; background-color: #f2f2f2; width: 80px;">Waktu Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse($powerPlant->machines as $index => $machine)
                    @php
                        $log = $filteredLogs->firstWhere('machine_id', $machine->id);
                        $status = $log?->status ?? '-';
                    @endphp
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td>{{ $machine->name }}</td>
                        <td style="text-align: center;">{{ $log?->dmn ?? '-' }}</td>
                        <td style="text-align: center;">{{ $log?->dmp ?? '-' }}</td>
                        <td style="text-align: center;">{{ $log?->load_value ?? '-' }}</td>
                        <td style="text-align: center;">{{ $status }}</td>
                        <td>{{ $log?->deskripsi ?? '-' }}</td>
                        <td style="text-align: center;">{{ $log?->input_time ? \Carbon\Carbon::parse($log->input_time)->format('H:i:s') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data mesin untuk unit ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <br>
    @endunless
@endforeach 