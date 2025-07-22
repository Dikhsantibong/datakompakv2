<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan K3 KAMP & Lingkungan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 4px 8px; }
        th { background: #eee; }
        .header-table { width: 100%; margin-bottom: 10px; }
        .header-table td { border: none; vertical-align: middle; }
        .logo-left { width: 120px; }
        .logo-right { width: 120px; text-align: right; }
        .header-title { text-align: center; font-size: 18px; font-weight: bold; }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td class="logo-left">
                <img src="{{ public_path('logo/navlog1.png') }}" alt="PLN Logo" style="height:60px;">
            </td>
            <td class="header-title">
                Laporan K3 KAMP & Lingkungan
            </td>
            <td class="logo-right">
                <img src="{{ public_path('logo/UP_KENDARI.png') }}" alt="UP Kendari Logo" style="height:60px;">
            </td>
        </tr>
    </table>
    <p>Tanggal: {{ $report->date ? $report->date->format('d/m/Y') : '-' }}</p>
    <p>Unit: {{ $report->sync_unit_origin ?? '-' }}</p>
    <hr>
    <h3>K3 & Keamanan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Status</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
                <th>Eviden</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($report->items->where('item_type', 'k3_keamanan') as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->item_name ?? '-' }}</td>
                <td>{{ ucfirst($item->status ?? '-') }}</td>
                <td>{{ ucfirst($item->kondisi ?? '-') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>{{ $item->media && $item->media->isNotEmpty() ? 'Ada' : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3>Lingkungan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Status</th>
                <th>Kondisi</th>
                <th>Keterangan</th>
                <th>Eviden</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($report->items->where('item_type', 'lingkungan') as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item->item_name ?? '-' }}</td>
                <td>{{ ucfirst($item->status ?? '-') }}</td>
                <td>{{ ucfirst($item->kondisi ?? '-') }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
                <td>{{ $item->media && $item->media->isNotEmpty() ? 'Ada' : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 