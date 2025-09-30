<!DOCTYPE html>
<html>
<head>
    <title>Print Data 5S5R Periode</title>
    <style>
        @media print {
            @page { size: landscape; }
        }
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background: #eee; }
        h3 { margin-bottom: 0; }
        .header-5s5r {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            width: 50%;
            text-align: left;
        }
        .header-logo {
            width: 50%;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header-5s5r flex">
        <div class="header-title">
            <h2>Data Pemeriksaan 5S5R<br>
                Periode: {{ $request->start_date }} s/d {{ $request->end_date }}
            </h2>
        </div>
        <div class="header-logo">
            <img src="{{ asset('logo/UP_KENDARI.png') }}" alt="PLN Logo" style="height: 50px;">
        </div>
    </div>
    @foreach($batches as $i => $batch)
        <h3>No: {{ $i+1 }} | Tanggal: {{ $batch->created_at->format('d/m/Y') }} | Unit: {{ $batch->sync_unit_origin }}</h3>
        <strong>Pemeriksaan 5S5R:</strong>
        <table>
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Detail</th>
                    <th>Kondisi Awal</th>
                    <th>PIC</th>
                    <th>Area Kerja</th>
                    <th>Area Produksi</th>
                    <th>Membersihkan</th>
                    <th>Merapikan</th>
                    <th>Membuang Sampah</th>
                    <th>Mengecat</th>
                    <th>Lainnya</th>
                    <th>Kondisi Akhir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batch->pemeriksaan as $p)
                <tr>
                    <td>{{ $p->kategori }}</td>
                    <td>{{ $p->detail }}</td>
                    <td>{{ $p->kondisi_awal }}</td>
                    <td>{{ $p->pic }}</td>
                    <td>{{ $p->area_kerja }}</td>
                    <td>{{ $p->area_produksi }}</td>
                    <td>{{ $p->membersihkan ? 'Ya' : '-' }}</td>
                    <td>{{ $p->merapikan ? 'Ya' : '-' }}</td>
                    <td>{{ $p->membuang_sampah ? 'Ya' : '-' }}</td>
                    <td>{{ $p->mengecat ? 'Ya' : '-' }}</td>
                    <td>{{ $p->lainnya ? 'Ya' : '-' }}</td>
                    <td>{{ $p->kondisi_akhir }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <strong>Program Kerja 5R:</strong>
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
                @foreach($batch->programKerja as $pk)
                <tr>
                    <td>{{ $pk->program_kerja }}</td>
                    <td>{{ $pk->goal }}</td>
                    <td>{{ $pk->kondisi_awal }}</td>
                    <td>{{ $pk->progress }}%</td>
                    <td>{{ $pk->kondisi_akhir }}</td>
                    <td>{{ $pk->catatan }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
    @endforeach
    <script>
        window.print();
    </script>
</body>
</html>
