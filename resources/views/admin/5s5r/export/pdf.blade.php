<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan 5S5R</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            width: 100%;
            min-height: 80px;
            position: relative;
            margin-bottom: 30px;
        }
        .header-logo-left {
            position: absolute;
            top: 0;
            left: 0;
            height: 55px;
        }
        .header-logo-right {
            position: absolute;
            top: 0;
            right: 0;
            height: 55px;
        }
        .header-title {
            text-align: center;
            color: #333;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
            padding-top: 10px;
        }
        .section-title {
            background-color: #f4f4f4;
            padding: 5px;
            font-weight: bold;
            text-align: center;
            color: #333;
            font-size: 16px;
            border-bottom: 2px solid #009BB9;
            margin-top: 30px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #009BB9 !important;
            color: #fff !important;
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            background: #e5e7eb;
        }
        .text-center {
            text-align: center;
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
        $pic = isset($pemeriksaan) && $pemeriksaan->count() ? $pemeriksaan->first()->pic : '';
        $logoPath = 'logo/UP_KENDARI.png';
        foreach ($unitLogoMap as $key => $path) {
            if (stripos($pic, $key) !== false) {
                $logoPath = $path;
                break;
            }
        }
    @endphp
    <div class="header">
        <img src="{{ public_path('logo/navlog1.png') }}" alt="Logo" class="header-logo-left">
        <img src="{{ public_path($logoPath) }}" alt="Unit Logo" class="header-logo-right">
        <div class="header-title">Laporan 5S5R</div>
    </div>

    <table style="margin-bottom: 30px;">
        <tr>
            <th width="25%">Tanggal</th>
            <td>{{ isset($pemeriksaan) && $pemeriksaan->count() ? date('d/m/Y', strtotime($pemeriksaan->first()->created_at)) : '-' }}</td>
        </tr>
        <tr>
            <th>PIC</th>
            <td>{{ isset($pemeriksaan) && $pemeriksaan->count() ? $pemeriksaan->first()->pic : '-' }}</td>
        </tr>
    </table>

    <div class="section-title">Detail Pemeriksaan 5S5R</div>
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Kondisi Awal</th>
                <th>PIC</th>
                <th>Area Kerja</th>
                <th>Area Produksi</th>
                <th>Tindakan</th>
                <th>Kondisi Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemeriksaan as $item)
            <tr>
                <td>{{ $item->kategori }}</td>
                <td>{{ $item->kondisi_awal }}</td>
                <td>{{ $item->pic }}</td>
                <td>{{ $item->area_kerja }}</td>
                <td>{{ $item->area_produksi }}</td>
                <td>
                    @if($item->membersihkan) <span class="badge">Membersihkan</span> @endif
                    @if($item->merapikan) <span class="badge">Merapikan</span> @endif
                    @if($item->membuang_sampah) <span class="badge">Membuang Sampah</span> @endif
                    @if($item->mengecat) <span class="badge">Mengecat</span> @endif
                    @if($item->lainnya) <span class="badge">Lainnya</span> @endif
                </td>
                <td>{{ $item->kondisi_akhir }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Program Kerja 5R</div>
    <table>
        <thead>
            <tr>
                <th>Program Kerja</th>
                <th>Goal</th>
                <th>Kondisi Awal</th>
                <th>Progress</th>
                <th>Kondisi Akhir</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($programKerja as $program)
            <tr>
                <td>{{ $program->program_kerja }}</td>
                <td>{{ $program->goal }}</td>
                <td>{{ $program->kondisi_awal }}</td>
                <td><span class="badge">{{ $program->progress }}</span></td>
                <td>{{ $program->kondisi_akhir }}</td>
                <td>{{ $program->catatan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 