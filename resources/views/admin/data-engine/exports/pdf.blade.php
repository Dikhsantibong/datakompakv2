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
    @php
        $unitLogoMap = [
            'PLTD POASIA' => 'logo/PLTD_POASIA.png',
            'PLTD KOLAKA' => 'logo/PLTD_KOLAKA.png',
            'PLTD BAU BAU' => 'logo/PLTD_BAU_BAU.png',
            'PLTD WUA WUA' => 'logo/PLTD_WUA_WUA.png',
            'PLTD WINNING' => 'logo/PLTM_WINNING.png',
            'PLTD ERKEE' => 'logo/PLTD_EREKE.png',
            'PLTD LADUMPI' => 'logo/PLTD_LADUMPI.png',
            'PLTD LANGARA' => 'logo/PLTD_LANGARA.png',
            'PLTD LANIPA-NIPA' => 'logo/PLTD_LANIPA_NIPA.png',
            'PLTD PASARWAJO' => 'logo/PLTD_PASARWAJO.png',
            'PLTD POASIA CONTAINERIZED' => 'logo/PLTD_POASIA_CONTAINERIZED.png',
            'PLTD RAHA' => 'logo/PLTD_RAHA.png',
            'PLTD WAJO' => 'logo/PLTD_WAJO.png',
            'PLTD WANGI-WANGI' => 'logo/PLTD_WANGI_WANGI.png',
            'PLTM RONGI' => 'logo/PLTM_RONGI.png',
            'PLTM SABILAMBO' => 'logo/PLTM_SABILAMBO.png',
            'PLTD KENDARI' => 'logo/PLTMG_KENDARI.png',
            'PLTD BARUTA' => 'logo/PLTU_BARUTA.png',
            'PLTD MORAMO' => 'logo/PLTU_MORAMO.png',
            'PLTM MIKUASI' => 'logo/PLTM_MIKUASI.png',
        ];
        $unitName = isset($powerPlants[0]) ? $powerPlants[0]->name : '';
        $logoPath = 'logo/UP_KENDARI.png';
        foreach ($unitLogoMap as $key => $path) {
            if (stripos($unitName, $key) !== false) {
                $logoPath = $path;
                break;
            }
        }
    @endphp
    <div class="header" style="position: relative; width: 100%; min-height: 80px; margin-bottom: 30px;">
        <img src="{{ public_path('logo/navlog1.png') }}" alt="Logo" style="position: absolute; top: 0; left: 0; height: 55px;">
        <img src="{{ public_path($logoPath) }}" alt="Unit Logo" style="position: absolute; top: 0; right: 0; height: 55px;">
        <div style="text-align: center;">
            <h1 style="margin: 0; font-size: 18px; color: #333;">Data Engine Report</h1>
            <p style="margin: 5px 0; color: #666;">Tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}</p>
        </div>
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