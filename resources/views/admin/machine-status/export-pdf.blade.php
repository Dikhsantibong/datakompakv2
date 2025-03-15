<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Status Mesin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
            padding-top: 20px;
        }
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            width: 120px;
            height: auto;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #1a5f7a;
        }
        .header p {
            font-size: 13px;
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        th {
            background-color: #009BB9;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 25px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        .stats-item {
            padding: 12px;
            border: 1px solid #e9ecef;
            background-color: white;
            border-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .stats-item strong {
            display: block;
            margin-bottom: 5px;
            color: #009BB9;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }
        .status-rsh { background-color: #dcfce7; color: #166534; }
        .status-fo { background-color: #fee2e2; color: #991b1b; }
        .status-mo { background-color: #dbeafe; color: #1e40af; }
        .status-p0 { background-color: #fff7ed; color: #9a3412; }
        .status-mb { background-color: #f3e8ff; color: #6b21a8; }
        .status-ops { background-color: #f3f4f6; color: #1f2937; }
        .section-title {
            color: #009BB9;
            font-size: 16px;
            margin: 20px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #009BB9;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo/navlogo.png') }}" class="logo" alt="PLN Logo">
        <h1>Laporan Status Mesin Pembangkit</h1>
        <p>Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
        <p>Waktu Input: {{ $selectedInputTime ?? 'Semua Waktu' }}</p>
    </div>

    @foreach($powerPlants as $powerPlant)
        @unless($powerPlant->name === 'UP KENDARI')
            <h2 class="section-title">{{ $powerPlant->name }}</h2>

            @php
                $filteredLogs = $logs->filter(function($log) use ($date) {
                    return $log->created_at->format('Y-m-d') === $date;
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

            <div class="stats-grid">
                <div class="stats-item">
                    <strong>DMN:</strong>
                    {{ number_format($totalDMN, 2) }} MW
                </div>
                <div class="stats-item">
                    <strong>DMP:</strong>
                    {{ number_format($totalDMP, 2) }} MW
                </div>
                <div class="stats-item">
                    <strong>Total Beban:</strong>
                    {{ number_format($totalBeban, 2) }} MW
                </div>
                <div class="stats-item">
                    <strong>Derating:</strong>
                    {{ number_format($totalDMN - $totalDMP, 2) }} MW
                </div>
                <div class="stats-item">
                    <strong>HOP:</strong>
                    {{ number_format($hopValue, 1) }} Hari
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 15%;">Mesin</th>
                        <th style="width: 10%;">DMN (MW)</th>
                        <th style="width: 10%;">DMP (MW)</th>
                        <th style="width: 10%;">Beban (MW)</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 25%;">Deskripsi</th>
                        <th style="width: 15%;">Waktu Input</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($powerPlant->machines as $index => $machine)
                        @php
                            $log = $filteredLogs->firstWhere('machine_id', $machine->id);
                            $status = $log?->status ?? '-';
                            $statusClass = match($status) {
                                'RSH' => 'status-rsh',
                                'FO' => 'status-fo',
                                'MO' => 'status-mo',
                                'P0' => 'status-p0',
                                'MB' => 'status-mb',
                                'OPS' => 'status-ops',
                                default => ''
                            };
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $machine->name }}</td>
                            <td>{{ $log?->dmn ?? '-' }}</td>
                            <td>{{ $log?->dmp ?? '-' }}</td>
                            <td>{{ $log?->load_value ?? '-' }}</td>
                            <td><span class="{{ $statusClass }}">{{ $status }}</span></td>
                            <td>{{ $log?->deskripsi ?? '-' }}</td>
                            <td>{{ $log?->input_time ? \Carbon\Carbon::parse($log->input_time)->format('H:i:s') : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">Tidak ada data mesin untuk unit ini</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @unless($loop->last)
                <div class="page-break"></div>
            @endunless
        @endunless
    @endforeach
</body>
</html> 