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
            font-size: 14px;
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
        .doc-image-card {
            width: 210px;
            height: 170px;
            background: #fff;
            border: 1px solid #bbb;
            border-radius: 6px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            padding: 8px 4px 2px 4px;
        }
        .doc-image-card img {
            width: 200px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #eee;
            background: #fafafa;
            display: block;
        }
        .doc-image-caption {
            font-size: 10px;
            color: #333;
            margin-top: 4px;
            text-align: center;
        }
    </style>
</head> 
<body>
    <div class="header" style="padding:0; background-color:#D1D5DB;">
        <table width="100%" style="border:none; background:none;">
            <tr>
                <td style="width:80px; text-align:left; border:none;">
                    <img src="{{ public_path('logo/navlog1.png') }}" alt="PLN Logo" style="height:50px;">
                </td>
                <td style="text-align:center; border:none;">
                    <h1 style="margin:0; font-size:14px; color:#000;">
                        Form Pemeriksaan FLM - {{ $flmData->first()->tanggal->format('d F Y') }}<br>
                        Unit: 
                        @php
                            $unitMapping = [
                                'mysql_poasia' => 'PLTD POASIA',
                                'mysql_kolaka' => 'PLTD KOLAKA',
                                'mysql_bau_bau' => 'PLTD BAU BAU',
                                'mysql_wua_wua' => 'PLTD WUA WUA',
                                'mysql_winning' => 'PLTD WINNING',
                                'mysql_erkee' => 'PLTD ERKEE',
                                'mysql_ladumpi' => 'PLTD LADUMPI',
                                'mysql_langara' => 'PLTD LANGARA',
                                'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                                'mysql_pasarwajo' => 'PLTD PASARWAJO',
                                'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                                'mysql_raha' => 'PLTD RAHA',
                                'mysql_wajo' => 'PLTD WAJO',
                                'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
                                'mysql_rongi' => 'PLTD RONGI',
                                'mysql_sabilambo' => 'PLTM SABILAMBO',
                                'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
                                'mysql_pltmg_kendari' => 'PLTD KENDARI',
                                'mysql_baruta' => 'PLTD BARUTA',
                                'mysql_moramo' => 'PLTD MORAMO',
                                'mysql_mikuasi' => 'PLTM MIKUASI',
                            ];
                            $unitCode = $flmData->first()->sync_unit_origin ?? null;
                            $unitName = $unitCode ? ($unitMapping[$unitCode] ?? $unitCode) : 'UP Kendari';
                        @endphp
                        {{ $unitName }}
                    </h1>
                </td>
                @php
                    // Ambil nama unit dari mapping, fallback ke UP Kendari
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
                    // $unitName sudah didapat dari mapping di atas
                    $logoPath = $unitLogoMap[$unitName] ?? 'logo/UP_KENDARI.png';
                @endphp
                <td style="width:80px; text-align:right; border:none;">
                    <img src="{{ public_path($logoPath) }}" alt="Unit Logo" style="height:50px;">
                </td>
            </tr>
        </table>
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
                    <td style="text-align: center; border: none; vertical-align: top;">
                        <div class="doc-image-card">
                            <img src="{{ storage_path('app/public/flm/eviden/' . basename($item->eviden_sebelum)) }}" alt="Kondisi Sebelum">
                            <div class="doc-image-caption">Kondisi Sebelum #{{ $index + 1 }}</div>
                        </div>
                    </td>
                    @endif
                    @if($item->eviden_sesudah)
                    <td style="text-align: center; border: none; vertical-align: top;">
                        <div class="doc-image-card">
                            <img src="{{ storage_path('app/public/flm/eviden/' . basename($item->eviden_sesudah)) }}" alt="Kondisi Sesudah">
                            <div class="doc-image-caption">Kondisi Sesudah #{{ $index + 1 }}</div>
                        </div>
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