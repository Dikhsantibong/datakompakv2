<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Shift Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .header-container {
            position: relative;
            width: 100%;
            height: 80px;
        }
        .logo {
            position: absolute;
            top: 0;
            left: 0;
            height: 60px;
        }
        .header {
            text-align: center;
            padding-top: 70px;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 16px;
            margin: 0;
            padding: 0;
        }
        .info {
            margin-bottom: 20px;
        }
        .info-item {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0 10px;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
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
        $unitName = $meetingShift->unit_name ?? '';
        $logoPath = 'logo/UP_KENDARI.png';
        foreach ($unitLogoMap as $key => $path) {
            if (stripos($unitName, $key) !== false) {
                $logoPath = $path;
                break;
            }
        }
    @endphp
    <div class="header-container">
        <img src="{{ public_path('logo/navlog1.png') }}" alt="PLN Nusantara Power Logo" class="logo">
        <img src="{{ public_path($logoPath) }}" alt="Unit Logo" style="position: absolute; top: 0; right: 0; height: 60px;">
    </div>

    <div class="header">
        <h1>LAPORAN MEETING DAN MUTASI SHIFT</h1>
    </div>

    <div class="info">
        <div class="info-item">
            <strong>Tanggal:</strong> {{ $meetingShift->tanggal->format('d F Y') }}
        </div>
        <div class="info-item">
            <strong>Shift:</strong> {{ $meetingShift->current_shift }}
        </div>
        <div class="info-item">
            <strong>Dibuat oleh:</strong> {{ $meetingShift->creator->name ?? '-' }}
        </div>
    </div>

    <!-- Machine Status Section -->
    <div class="section-title">Status Mesin</div>
    <table>
        <thead>
            <tr>
                <th>Mesin</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->machineStatuses as $status)
            <tr>
                <td>{{ $status->machine->name ?? '-' }}</td>
                <td>
                    @php
                        $statuses = is_array($status->status) ? $status->status : json_decode($status->status, true);
                    @endphp
                    @foreach($statuses as $stat)
                        <span class="status-badge">{{ $stat }}</span>
                    @endforeach
                </td>
                <td>{{ $status->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Auxiliary Equipment Section -->
    <div class="section-title">Peralatan Bantu</div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->auxiliaryEquipments as $equipment)
            <tr>
                <td>{{ $equipment->name }}</td>
                <td>
                    @php
                        $statuses = is_array($equipment->status) ? $equipment->status : json_decode($equipment->status, true);
                    @endphp
                    @foreach($statuses as $stat)
                        <span class="status-badge">{{ $stat }}</span>
                    @endforeach
                </td>
                <td>{{ $equipment->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Resources Section -->
    <div class="section-title">Sumber Daya</div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Kategori</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->resources as $resource)
            <tr>
                <td>{{ $resource->name }}</td>
                <td>{{ $resource->category }}</td>
                <td>{{ $resource->status }}</td>
                <td>{{ $resource->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- K3L Section -->
    <div class="section-title">K3L</div>
    <table>
        <thead>
            <tr>
                <th>Tipe</th>
                <th>Uraian</th>
                <th>Saran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->k3ls as $k3l)
            <tr>
                <td>{{ $k3l->type }}</td>
                <td>{{ $k3l->uraian }}</td>
                <td>{{ $k3l->saran }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Notes Section -->
    <div class="section-title">Catatan</div>
    <table>
        <tr>
            <th>Catatan Sistem</th>
            <td>{{ $meetingShift->systemNote->content ?? '-' }}</td>
        </tr>
        <tr>
            <th>Catatan Umum</th>
            <td>{{ $meetingShift->generalNote->content ?? '-' }}</td>
        </tr>
    </table>

    <!-- Resume Section -->
    <div class="section-title">Resume</div>
    <table>
        <tr>
            <td>{{ $meetingShift->resume->content ?? '-' }}</td>
        </tr>
    </table>

    <!-- Attendance Section -->
    <div class="section-title">Absensi</div>
    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Shift</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->attendances as $attendance)
            <tr>
                <td>{{ $attendance->nama }}</td>
                <td>{{ $attendance->shift }}</td>
                <td>{{ $attendance->status }}</td>
                <td>{{ $attendance->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html> 