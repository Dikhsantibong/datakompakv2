<!DOCTYPE html>
<html>
<head>
    <title>Print Data FLM Periode</title>
    <style>
        @media print {
            @page { size: landscape; }
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Data Pemeriksaan FLM<br>
        Periode: {{ $request->start_date }} s/d {{ $request->end_date }}
    </h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Shift</th>
                <th>Waktu</th>
                <th>Asal Unit</th>
                <th>Mesin</th>
                <th>Sistem</th>
                <th>Masalah</th>
                <th>Kondisi Awal</th>
                <th>Tindakan</th>
                <th>Kondisi Akhir</th>
                <th>Catatan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($flmData as $i => $item)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                <td>{{ $item->shift }}</td>
                <td>{{ $item->time->format('H:i') }}</td>
                <td>{{ $item->sync_unit_origin }}</td>
                <td>{{ $item->mesin }}</td>
                <td>{{ $item->sistem }}</td>
                <td>{{ $item->masalah }}</td>
                <td>{{ $item->kondisi_awal }}</td>
                <td>
                    @php
                        $tindakan = [];
                        if($item->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
                        if($item->tindakan_lumasi) $tindakan[] = 'Lumasi';
                        if($item->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
                        if($item->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
                        if($item->tindakan_lainnya) $tindakan[] = 'Lainnya';
                    @endphp
                    {{ implode(', ', $tindakan) }}
                </td>
                <td>{{ $item->kondisi_akhir }}</td>
                <td>{{ $item->catatan }}</td>
                <td>{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <script>
        window.print();
    </script>
</body>
</html>
