<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Bahan Kimia</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .filter-info {
            margin-bottom: 10px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('navlogo.png') }}" class="logo">
        <h2>Data Bahan Kimia</h2>
        <p>PT PLN Nusantara Power</p>
    </div>

    @if(request()->has('unit_id') || request()->has('jenis_bahan') || request()->has('start_date') || request()->has('end_date'))
        <div class="filter-info">
            Filter:
            @if(request('unit_id'))
                Unit: {{ $units->find(request('unit_id'))->name }},
            @endif
            @if(request('jenis_bahan'))
                Bahan: {{ request('jenis_bahan') }},
            @endif
            @if(request('start_date'))
                Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                @if(request('end_date'))
                    - {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                @endif
            @endif
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Unit</th>
                <th>Jenis Bahan</th>
                <th>Saldo Awal</th>
                <th>Penerimaan</th>
                <th>Pemakaian</th>
                <th>Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bahanKimia as $item)
                <tr>
                    <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $item->unit->name }}</td>
                    <td>{{ $item->jenis_bahan }}</td>
                    <td>{{ number_format($item->saldo_awal, 2) }}</td>
                    <td>{{ number_format($item->penerimaan, 2) }}</td>
                    <td>{{ number_format($item->pemakaian, 2) }}</td>
                    <td>{{ number_format($item->saldo_akhir, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; font-size: 10px;">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 