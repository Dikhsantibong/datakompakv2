<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Pemeriksaan FLM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 12px;
            color: #666;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daftar Pemeriksaan FLM</h1>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Mesin/Peralatan</th>
                <th>Sistem Pembangkit</th>
                <th>Masalah</th>
                <th>Tindakan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flmData as $index => $data)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $data->tanggal }}</td>
                <td>{{ $data->mesin }}</td>
                <td>{{ $data->sistem }}</td>
                <td>{{ $data->masalah }}</td>
                <td>
                    @php
                        $tindakan = [];
                        if ($data->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
                        if ($data->tindakan_lumasi) $tindakan[] = 'Lumasi';
                        if ($data->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
                        if ($data->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
                        if ($data->tindakan_lainnya) $tindakan[] = 'Lainnya';
                    @endphp
                    {{ implode(', ', $tindakan) }}
                </td>
                <td>{{ $data->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 