<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Detail Pemeriksaan FLM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #D1D5DB;
        }
        .header h1 {
            font-size: 18px;
            margin: 0;
            color: #000;
        }
        .section-header {
            background-color: #E2E8F0;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #000;
            margin-top: 20px;
        }
        .table-header {
            background-color: #F8FAFC;
            font-weight: bold;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .detail-section {
            margin-top: 20px;
        }
        .detail-section th {
            width: 200px;
            background-color: #F8FAFC;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #666;
        }
        .images {
            margin-top: 20px;
        }
        .images img {
            max-width: 120px;
            margin: 10px;
            page-break-inside: avoid !important;
        }
        .images-row {
            display: flex;
            flex-direction: row;
            gap: 20px;
            justify-content: flex-start;
            align-items: flex-start;
        }
        .image-col {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        body, html, table, tr, td, th, div, img {
            page-break-inside: avoid !important;
            page-break-before: avoid !important;
            page-break-after: avoid !important;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Form Pemeriksaan FLM - {{ $flmData->first()->tanggal->format('d F Y') }}</h1>
    </div>

    <!-- FLM Data Section -->
    <div class="section-header">Data Pemeriksaan FLM</div>
    <table>
        <tr class="table-header">
            <th>No</th>
            <th>Tanggal</th>
            <th>Operator</th>
            <th>Mesin/Peralatan</th>
            <th>Sistem Pembangkit</th>
            <th>Masalah</th>
            <th>Tindakan</th>
            <th>Status</th>
        </tr>
        @foreach($flmData as $index => $item)
            @php
                $tindakan = [];
                if ($item->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
                if ($item->tindakan_lumasi) $tindakan[] = 'Lumasi';
                if ($item->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
                if ($item->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
                if ($item->tindakan_lainnya) $tindakan[] = 'Lainnya';
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                <td>{{ $item->operator }}</td>
                <td>{{ $item->mesin }}</td>
                <td>{{ $item->sistem }}</td>
                <td>{{ $item->masalah }}</td>
                <td>{{ implode(', ', $tindakan) }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
        @endforeach
    </table>

    <!-- Detail Section -->
    <div class="section-header">Detail Pemeriksaan</div>
    <table>
        <tr class="table-header">
            <th colspan="2">Item</th>
            <th colspan="6">Keterangan</th>
        </tr>
        @foreach($flmData as $index => $item)
            <tr>
                <td colspan="2">Kondisi Awal #{{ $index + 1 }}</td>
                <td colspan="6">{{ $item->kondisi_awal }}</td>
            </tr>
            <tr>
                <td colspan="2">Kondisi Akhir #{{ $index + 1 }}</td>
                <td colspan="6">{{ $item->kondisi_akhir }}</td>
            </tr>
            <tr>
                <td colspan="2">Catatan #{{ $index + 1 }}</td>
                <td colspan="6">{{ $item->catatan ?? '-' }}</td>
            </tr>
        @endforeach
    </table>

    <!-- Documentation Section -->
    @if($flmData->first()->eviden_sebelum || $flmData->first()->eviden_sesudah)
    <div class="section-header">Dokumentasi</div>
    <div class="images">
        @foreach($flmData as $index => $item)
            @if($item->eviden_sebelum || $item->eviden_sesudah)
            <table style="margin-bottom: 10px; border: none; width: auto;">
                <tr>
                    @if($item->eviden_sebelum)
                    <td style="text-align: center; border: none;">
                        <img src="{{ storage_path('app/public/flm/eviden/' . basename($item->eviden_sebelum)) }}" alt="Kondisi Sebelum"><br>
                        <span style="font-size:10px;">Kondisi Sebelum #{{ $index + 1 }}</span>
                    </td>
                    @endif
                    @if($item->eviden_sesudah)
                    <td style="text-align: center; border: none;">
                        <img src="{{ storage_path('app/public/flm/eviden/' . basename($item->eviden_sesudah)) }}" alt="Kondisi Sesudah"><br>
                        <span style="font-size:10px;">Kondisi Sesudah #{{ $index + 1 }}</span>
                    </td>
                    @endif
                </tr>
            </table>
            @endif
        @endforeach
    </div>
    @endif

    <div class="footer">
        FLM ID: {{ $flmData->first()->flm_id }} | Dibuat pada: {{ $flmData->first()->created_at->format('d/m/Y H:i') }}
    </div>
</body>
</html> 