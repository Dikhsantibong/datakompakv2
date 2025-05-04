<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Meeting Shift Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
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
            background-color: #f5f5f5;
        }
        .section-title {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
            font-size: 18px;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-operasi { background-color: #e3f2fd; color: #1976d2; }
        .status-standby { background-color: #f3e5f5; color: #7b1fa2; }
        .status-gangguan { background-color: #ffebee; color: #c62828; }
        table thead tr th {
            background-color: #009BB9 !important;
            color: #fff !important;
        }
    </style>
</head>
<body>
    <!-- Header container -->
    <div style="position: relative; width: 100%; min-height: 80px;">
        <!-- Logo kiri atas -->
        <img src="{{ public_path('logo/navlog1.png') }}" alt="Logo" style="position: absolute; top: 0; left: 0; height: 55px;">
        <!-- Logo kanan atas dan username overlap -->
        <div style="position: absolute; top: 0; right: 0; width: 170px; height: 70px;">
            <img src="{{ public_path('logo/PLN-bg.png') }}" alt="PLN Logo" style="height: 55px; display: block; margin: 0 auto;">
            <span style="position: absolute; left: 52px; right: 0; top: 22px; font-size: 16px; color: #406a7d; font-family: Arial, sans-serif; text-align: center; font-weight: normal; white-space: nowrap;">{{ Auth::user()->name }}</span>
        </div>
    </div>
    <!-- Judul dan detail di tengah, tanpa margin atas besar -->
    <div style="text-align: center; margin-bottom: 30px; margin-top: 0;">
        <h1 style="margin: 0; color: #333; font-size: 28px;">Meeting Shift Report</h1>
        <p style="margin: 5px 0; color: #666;">Tanggal: {{ $meetingShift->tanggal->format('d F Y') }}</p>
        <p style="margin: 5px 0; color: #666;">Shift: {{ $meetingShift->current_shift }}</p>
        <p style="margin: 5px 0; color: #666;">Dibuat oleh: {{ $meetingShift->creator->name }}</p>
    </div>

    <!-- Machine Statuses -->
    <div class="section-title">Status Mesin</div>
    <table>
        <thead>
            <tr style="background-color: #009BB9; color: #fff;">
                <th>Mesin</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->machineStatuses as $status)
            <tr>
                <td>{{ $status->machine->name }}</td>
                <td>
                    @php
                        $statuses = json_decode($status->status);
                    @endphp
                    @foreach($statuses as $stat)
                        <span class="status-badge status-{{ strtolower($stat) }}">{{ $stat }}</span>
                    @endforeach
                </td>
                <td>{{ $status->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Auxiliary Equipment -->
    <div class="section-title">Peralatan Bantu</div>
    <table>
        <thead>
            <tr style="background-color: #009BB9; color: #fff;">
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
                        $statuses = json_decode($equipment->status);
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

    <!-- Resources -->
    <div class="section-title">Resources</div>
    <table>
        <thead>
            <tr style="background-color: #009BB9; color: #fff;">
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

    <!-- K3L -->
    <div class="section-title">K3L</div>
    <table>
        <thead>
            <tr style="background-color: #009BB9; color: #fff;">
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

    <!-- Notes -->
    <div class="section-title">Catatan</div>
    <table>
        <thead>
            <tr style="background-color: #009BB9; color: #fff;">
                <th>Tipe</th>
                <th>Konten</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meetingShift->notes as $note)
            <tr>
                <td>{{ ucfirst($note->type) }}</td>
                <td>{{ $note->content }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Resume -->
    @if($meetingShift->resume)
    <div class="section-title">Resume</div>
    <p>{{ $meetingShift->resume->content }}</p>
    @endif

    <!-- Attendance -->
    <div class="section-title">Kehadiran</div>
    <table>
        <thead>
            <tr style="background-color: #009BB9; color: #fff;">
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
</body>
</html> 