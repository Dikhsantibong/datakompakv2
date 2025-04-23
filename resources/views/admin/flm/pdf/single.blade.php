<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pemeriksaan FLM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
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
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .detail-table th {
            width: 200px;
            text-align: left;
            padding: 8px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
        }
        .detail-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .images {
            margin-top: 20px;
        }
        .images img {
            max-width: 300px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Detail Pemeriksaan FLM</h1>
        <p>Tanggal Cetak: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <table class="detail-table">
        <tr>
            <th>Tanggal</th>
            <td>{{ $flmData->tanggal }}</td>
        </tr>
        <tr>
            <th>Mesin/Peralatan</th>
            <td>{{ $flmData->mesin }}</td>
        </tr>
        <tr>
            <th>Sistem Pembangkit</th>
            <td>{{ $flmData->sistem }}</td>
        </tr>
        <tr>
            <th>Masalah</th>
            <td>{{ $flmData->masalah }}</td>
        </tr>
        <tr>
            <th>Kondisi Awal</th>
            <td>{{ $flmData->kondisi_awal }}</td>
        </tr>
        <tr>
            <th>Tindakan</th>
            <td>
                @php
                    $tindakan = [];
                    if ($flmData->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
                    if ($flmData->tindakan_lumasi) $tindakan[] = 'Lumasi';
                    if ($flmData->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
                    if ($flmData->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
                    if ($flmData->tindakan_lainnya) $tindakan[] = 'Lainnya';
                @endphp
                {{ implode(', ', $tindakan) }}
            </td>
        </tr>
        <tr>
            <th>Kondisi Akhir</th>
            <td>{{ $flmData->kondisi_akhir }}</td>
        </tr>
        <tr>
            <th>Catatan</th>
            <td>{{ $flmData->catatan }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $flmData->status }}</td>
        </tr>
    </table>

    @if($flmData->eviden_sebelum || $flmData->eviden_sesudah)
    <div class="images">
        <h2>Dokumentasi</h2>
        @if($flmData->eviden_sebelum)
        <div>
            <p>Kondisi Sebelum:</p>
            <img src="{{ public_path(str_replace('/storage', 'storage/app/public', $flmData->eviden_sebelum)) }}" alt="Kondisi Sebelum">
        </div>
        @endif
        
        @if($flmData->eviden_sesudah)
        <div>
            <p>Kondisi Sesudah:</p>
            <img src="{{ public_path(str_replace('/storage', 'storage/app/public', $flmData->eviden_sesudah)) }}" alt="Kondisi Sesudah">
        </div>
        @endif
    </div>
    @endif
</body>
</html> 