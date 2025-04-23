<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Abnormal/Gangguan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
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
        h1, h2 {
            color: #333;
        }
    </style>
</head>
<body>
    <h1>Laporan Abnormal/Gangguan</h1>

    <table>
        <tr>
            <th>Tanggal</th>
            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Dibuat Oleh</th>
            <td>{{ $report->creator->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Mesin/Peralatan</th>
            <td>
                @foreach($report->affectedMachines as $machine)
                    {{ $machine->nama_mesin }}
                    @if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>Status</th>
            <td>
                @php
                    $hasRusak = $report->affectedMachines->contains('kondisi_rusak', true);
                    $hasAbnormal = $report->affectedMachines->contains('kondisi_abnormal', true);
                @endphp
                @if($hasRusak)
                    Rusak
                @elseif($hasAbnormal)
                    Abnormal
                @else
                    Normal
                @endif
            </td>
        </tr>
    </table>

    <h2>Kronologi</h2>
    <table>
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Uraian Kejadian</th>
                <th>Parameter Visual</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->chronologies as $chronology)
            <tr>
                <td>{{ $chronology->waktu }}</td>
                <td>{{ $chronology->uraian_kejadian }}</td>
                <td>{{ $chronology->visual_parameter }}</td>
                <td>
                    @if($chronology->turun_beban) Turun Beban, @endif
                    @if($chronology->off_cbg) Off CBG, @endif
                    @if($chronology->stop) Stop, @endif
                    @if($chronology->tl_ophar) TL OPHAR, @endif
                    @if($chronology->tl_op) TL OP, @endif
                    @if($chronology->tl_har) TL HAR, @endif
                    @if($chronology->mul) MUL @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Rekomendasi</h2>
    <table>
        <thead>
            <tr>
                <th>Rekomendasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->recommendations as $recommendation)
            <tr>
                <td>{{ $recommendation->rekomendasi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 