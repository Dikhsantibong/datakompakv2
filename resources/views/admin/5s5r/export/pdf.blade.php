<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>5S5R Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
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
            background-color: #f8f9fa;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin: 20px 0 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
            background: #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan 5S5R</h1>
        <p>Tanggal: {{ date('Y-m-d', strtotime($pemeriksaan->first()->created_at)) }}</p>
    </div>

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