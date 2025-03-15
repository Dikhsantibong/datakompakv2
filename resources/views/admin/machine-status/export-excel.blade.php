<table>
    <thead>
        <tr>
            <th colspan="8">Laporan Status Mesin Pembangkit</th>
        </tr>
        <tr>
            <th colspan="8">Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</th>
        </tr>
        <tr>
            <th colspan="8">Waktu Input: {{ $selectedInputTime ?? 'Semua Waktu' }}</th>
        </tr>
    </thead>
</table>

@foreach($powerPlants as $powerPlant)
    @unless($powerPlant->name === 'UP KENDARI')
        <table>
            <thead>
                <tr>
                    <th colspan="8">{{ $powerPlant->name }}</th>
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
                    <th>DMN Total</th>
                    <th>DMP Total</th>
                    <th>Total Beban</th>
                    <th>Derating</th>
                    <th>HOP</th>
                </tr>
                <tr>
                    <td>{{ number_format($totalDMN, 2) }} MW</td>
                    <td>{{ number_format($totalDMP, 2) }} MW</td>
                    <td>{{ number_format($totalBeban, 2) }} MW</td>
                    <td>{{ number_format($totalDMN - $totalDMP, 2) }} MW</td>
                    <td>{{ number_format($hopValue, 1) }} Hari</td>
                </tr>
                <tr>
                    <th>No</th>
                    <th>Mesin</th>
                    <th>DMN (MW)</th>
                    <th>DMP (MW)</th>
                    <th>Beban (MW)</th>
                    <th>Status</th>
                    <th>Deskripsi</th>
                    <th>Waktu Input</th>
                </tr>
            </thead>
            <tbody>
                @forelse($powerPlant->machines as $index => $machine)
                    @php
                        $log = $filteredLogs->firstWhere('machine_id', $machine->id);
                        $status = $log?->status ?? '-';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $log?->dmn ?? '-' }}</td>
                        <td>{{ $log?->dmp ?? '-' }}</td>
                        <td>{{ $log?->load_value ?? '-' }}</td>
                        <td>{{ $status }}</td>
                        <td>{{ $log?->deskripsi ?? '-' }}</td>
                        <td>{{ $log?->input_time ? \Carbon\Carbon::parse($log->input_time)->format('H:i:s') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Tidak ada data mesin untuk unit ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <br>
    @endunless
@endforeach 