<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Engine Report - {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .power-plant-name {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Engine Report</h1>
        <p>Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
    </div>

    @foreach($powerPlants as $powerPlant)
        <div class="power-plant-name">{{ $powerPlant->name }}</div>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mesin</th>
                    <th>Jam</th>
                    <th>Beban (kW)</th>
                    <th>kVAR</th>
                    <th>Cos φ</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($powerPlant->machines as $index => $machine)
                    @php
                        $latestLog = $machine->getLatestLog($date);
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $latestLog ? \Carbon\Carbon::parse($latestLog->time)->format('H:i') : '-' }}</td>
                        <td>{{ $machine->kw ?? '-' }}</td>
                        <td>{{ $machine->kvar ?? '-' }}</td>
                        <td>{{ $machine->cos_phi ?? '-' }}</td>
                        <td>{{ $machine->status ?? '-' }}</td>
                        <td>{{ $machine->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center;">Tidak ada data mesin untuk unit ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endforeach
</body>
</html> 