<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patrol Check KIT</title>
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
            background-color: #009BB9 !important;
            color: #fff !important;
        }
        .section-title {
            margin-top: 20px;
            margin-bottom: 10px;
            color: #333;
            font-size: 18px;
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
            <span style="position: absolute; left: 52px; right: 0; top: 22px; font-size: 16px; color: #406a7d; font-family: Arial, sans-serif; text-align: center; font-weight: normal; white-space: nowrap;">{{ optional($patrol->creator)->name }}</span>
        </div>
    </div>
    <!-- Judul dan detail di tengah -->
    <div style="text-align: center; margin-bottom: 30px; margin-top: 0;">
        <h1 style="margin: 0; color: #333; font-size: 28px;">Patrol Check KIT</h1>
        <p style="margin: 5px 0; color: #666;">Tanggal: {{ optional($patrol->created_at)->format('d F Y H:i') }}</p>
        <p style="margin: 5px 0; color: #666;">Status: {{ ucfirst($patrol->status) }}</p>
        <p style="margin: 5px 0; color: #666;">Dibuat oleh: {{ optional($patrol->creator)->name }}</p>
    </div>

    <div class="section-title">Kondisi Umum Peralatan Bantu</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Sistem</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patrol->condition_systems as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['system'] }}</td>
                <td>{{ ucfirst($row['condition']) }}</td>
                <td>{{ $row['notes'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if(!empty($patrol->abnormal_equipments))
    <div class="section-title">Data Kondisi Abnormal Alat Bantu</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Alat Bantu</th>
                <th>Kondisi Abnormal</th>
                <th>FLM</th>
                <th>SR</th>
                <th>Lainnya</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patrol->abnormal_equipments as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['equipment'] }}</td>
                <td>{{ $row['condition'] }}</td>
                <td>{{ $row['flm'] ? 'Ya' : '-' }}</td>
                <td>{{ $row['sr'] ? 'Ya' : '-' }}</td>
                <td>{{ $row['other'] ? 'Ya' : '-' }}</td>
                <td>{{ $row['notes'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</body>
</html> 