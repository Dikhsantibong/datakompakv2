<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kalender Operasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
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
            background-color: #4A90E2;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Kalender Operasi</h1>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Judul</th>
                <th>Waktu</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule['date'] ?? '-' }}</td>
                <td>{{ $schedule['title'] ?? '-' }}</td>
                <td>{{ ($schedule['start_time'] ?? '-') . ' - ' . ($schedule['end_time'] ?? '-') }}</td>
                <td>{{ $schedule['location'] ?? '-' }}</td>
                <td>{{ ucfirst($schedule['status'] ?? 'scheduled') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 