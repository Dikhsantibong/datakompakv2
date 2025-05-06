<!DOCTYPE html>
<html>
<head>
    <title>Rencana Daya Mampu - {{ $date }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page {
            margin: 0.5cm;
            size: landscape A3;
        }
        body { 
            font-family: Arial, sans-serif;
            font-size: 8px;
            margin: 0.5cm;
        }
        .logos {
            width: 100%;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo-pln {
            height: 40px;
            float: left;
        }
        .logo-k3 {
            height: 40px;
            float: right;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 3px; /* Padding lebih kecil */
            font-size: 9px; /* Ukuran font untuk tabel */
        }
        th { 
            background-color: #f3f4f6;
            white-space: nowrap; /* Prevent header text wrapping */
        }
        .text-center { 
            text-align: center; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px;
        }
        .print-info { 
            text-align: right; 
            font-size: 8px; 
            margin-bottom: 5px;
        }
        h2, h3 { 
            margin: 5px 0; 
        }
        .narrow-column {
            width: 30px; /* Untuk kolom nomor dan tanggal */
        }
        .medium-column {
            width: 80px; /* Untuk kolom data numerik */
        }
        /* Tambahan untuk header */
        .header-container {
            width: 100%;
            position: relative;
            margin-bottom: 20px;
        }
        .company-name {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 5px 0;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="logos">
            <img src="{{ public_path('logo/navlog1.png') }}" class="logo-pln" alt="PLN Logo">
            <img src="{{ public_path('logo/k3_logo.png') }}" class="logo-k3" alt="K3 Logo">
        </div>
        <div class="clear"></div>
        <div class="company-name">PT PLN NUSANTARA POWER</div>
    </div>

    <div class="print-info">
        Dicetak pada: {{ now()->format('d/m/Y') }} | datakompak.com
    </div>
    <div class="header">
        <h2>Rencana Daya Mampu</h2>
        <h3>{{ $date }}</h3>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="narrow-column" rowspan="2">No</th>
                <th rowspan="2">Sistem Kelistrikan</th>
                <th rowspan="2">Mesin Pembangkit</th>
                <th rowspan="2">Site Pembangkit</th>
                <th class="medium-column" rowspan="2">Daya PJBTL SILM</th>
                <th class="medium-column" rowspan="2">DMP Existing</th>
                @for ($i = 1; $i <= min(7, cal_days_in_month(CAL_GREGORIAN, $month, $year)); $i++)
                    <th colspan="3" class="narrow-column">{{ $i }}</th>
                @endfor
            </tr>
            <tr>
                @for ($i = 1; $i <= min(7, cal_days_in_month(CAL_GREGORIAN, $month, $year)); $i++)
                    <th class="narrow-column">Rencana</th>
                    <th class="narrow-column">Realisasi</th>
                    <th class="narrow-column">Keterangan</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($powerPlants as $plant)
                @foreach($plant->machines as $machine)
                    @php
                        $record = $machine->rencanaDayaMampu->first();
                    @endphp
                    <tr>
                        <td class="text-center">{{ $no++ }}</td>
                        <td>{{ $plant->name }}</td>
                        <td>{{ $machine->name }}</td>
                        <td>{{ $plant->name }}</td>
                        <td class="text-center">{{ $machine->daya_pjbtl_silm ?? '-' }}</td>
                        <td class="text-center">{{ $machine->dmp_existing ?? '-' }}</td>
                        @for ($i = 1; $i <= min(7, cal_days_in_month(CAL_GREGORIAN, $month, $year)); $i++)
                            @php
                                $date = sprintf('%s-%s-%02d', $year, $month, $i);
                                $daily = $record ? $record->getDailyValue($date) : [];
                            @endphp
                            <td class="text-center">{{ $daily['rencana'] ?? '-' }}</td>
                            <td class="text-center">{{ $daily['realisasi'] ?? '-' }}</td>
                            <td class="text-center">{{ $daily['keterangan'] ?? '-' }}</td>
                        @endfor
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html> 