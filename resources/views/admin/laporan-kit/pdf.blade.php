<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan KIT 00.00</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #e0e0e0;
            padding: 7px 10px;
            text-align: left;
            background: #fff;
        }
        th {
            background-color: #009BB9 !important;
            color: #fff !important;
            font-weight: bold;
            text-align: left;
            font-size: 14px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .section-title {
            margin-top: 24px;
            margin-bottom: 8px;
            color: #333;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header container -->
    <div style="position: relative; width: 100%; min-height: 80px;">
        <!-- Logo kiri atas -->
        <img src="{{ public_path('logo/navlog1.png') }}" alt="Logo" style="position: absolute; top: 0; left: 0; height: 55px;">
        <!-- Logo kanan atas dan username overlap -->
        <div style="position: absolute; top: 0; right: 0; width: 170px; height: 70px;">
            <img src="{{ public_path('logo/PLN-bg.png') }}" alt="PLN Logo" style="height: 55px; display: block; margin: 0 auto;">
            <span style="position: absolute; left: 52px; right: 0; top: 22px; font-size: 16px; color: #406a7d; font-family: Arial, sans-serif; text-align: center; font-weight: normal; white-space: nowrap;">{{ $laporan->creator->name ?? '' }}</span>
        </div>
    </div>
    <!-- Judul dan detail di tengah, tanpa margin atas besar -->
    <div style="text-align: center; margin-bottom: 30px; margin-top: 0;">
        <h1 style="margin: 0; color: #333; font-size: 28px;">Laporan KIT 00.00</h1>
        <p style="margin: 5px 0; color: #666;">Tanggal: {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}</p>
        <p style="margin: 5px 0; color: #666;">Unit: {{ $powerPlants->firstWhere('unit_source', $laporan->unit_source)?->name ?? '-' }}</p>
        <p style="margin: 5px 0; color: #666;">Dibuat oleh: {{ $laporan->creator->name ?? '-' }}</p>
    </div>

    <!-- Jam Operasi Mesin -->
    <div class="section-title">Jam Operasi Mesin</div>
    <table>
        <thead>
            <tr>
                <th>Mesin</th>
                <th>Ops</th>
                <th>Har</th>
                <th>Ggn</th>
                <th>Stby/Rsh</th>
                <th>Jam/Hari</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->jamOperasi as $row)
            <tr>
                <td>{{ $row->machine->name ?? '-' }}</td>
                <td>{{ $row->ops }}</td>
                <td>{{ $row->har }}</td>
                <td>{{ $row->ggn }}</td>
                <td>{{ $row->stby }}</td>
                <td>{{ $row->jam_hari }}</td>
            </tr>
            @empty
            <tr><td colspan="6">-</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Jenis Gangguan Mesin -->
    <div class="section-title">Jenis Gangguan Mesin</div>
    <table>
        <thead>
            <tr>
                <th>Mesin</th>
                <th>Mekanik</th>
                <th>Elektrik</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->gangguan as $row)
            <tr>
                <td>{{ $row->machine->name ?? '-' }}</td>
                <td>{{ $row->mekanik }}</td>
                <td>{{ $row->elektrik }}</td>
            </tr>
            @empty
            <tr><td colspan="3">-</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Data Pemeriksaan BBM -->
    <div class="section-title">Data Pemeriksaan BBM</div>
    <table>
        <thead>
            <tr>
                <th>Storage Tank 1 (cm)</th>
                <th>Storage Tank 1 (liter)</th>
                <th>Storage Tank 2 (cm)</th>
                <th>Storage Tank 2 (liter)</th>
                <th>Total Stok</th>
                <th>Service Tank 1 (liter)</th>
                <th>Service Tank 1 (%)</th>
                <th>Service Tank 2 (liter)</th>
                <th>Service Tank 2 (%)</th>
                <th>Total Stok Tangki</th>
                <th>Terima BBM</th>
                <th>Flowmeter 1 Awal</th>
                <th>Flowmeter 1 Akhir</th>
                <th>Flowmeter 1 Pakai</th>
                <th>Flowmeter 2 Awal</th>
                <th>Flowmeter 2 Akhir</th>
                <th>Flowmeter 2 Pakai</th>
                <th>Total Pakai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->bbm as $row)
            <tr>
                <td>{{ $row->storage_tank_1_cm }}</td>
                <td>{{ $row->storage_tank_1_liter }}</td>
                <td>{{ $row->storage_tank_2_cm }}</td>
                <td>{{ $row->storage_tank_2_liter }}</td>
                <td>{{ $row->total_stok }}</td>
                <td>{{ $row->service_tank_1_liter }}</td>
                <td>{{ $row->service_tank_1_percentage }}</td>
                <td>{{ $row->service_tank_2_liter }}</td>
                <td>{{ $row->service_tank_2_percentage }}</td>
                <td>{{ $row->total_stok_tangki }}</td>
                <td>{{ $row->terima_bbm }}</td>
                <td>{{ $row->flowmeter_1_awal }}</td>
                <td>{{ $row->flowmeter_1_akhir }}</td>
                <td>{{ $row->flowmeter_1_pakai }}</td>
                <td>{{ $row->flowmeter_2_awal }}</td>
                <td>{{ $row->flowmeter_2_akhir }}</td>
                <td>{{ $row->flowmeter_2_pakai }}</td>
                <td>{{ $row->total_pakai }}</td>
            </tr>
            @empty
            <tr><td colspan="18">-</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Data Pemeriksaan KWH -->
    <div class="section-title">Data Pemeriksaan KWH</div>
    <table>
        <thead>
            <tr>
                <th>Prod Panel1 Awal</th>
                <th>Prod Panel1 Akhir</th>
                <th>Prod Panel2 Awal</th>
                <th>Prod Panel2 Akhir</th>
                <th>Prod Total</th>
                <th>PS Panel1 Awal</th>
                <th>PS Panel1 Akhir</th>
                <th>PS Panel2 Awal</th>
                <th>PS Panel2 Akhir</th>
                <th>PS Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->kwh as $row)
            <tr>
                <td>{{ $row->prod_panel1_awal }}</td>
                <td>{{ $row->prod_panel1_akhir }}</td>
                <td>{{ $row->prod_panel2_awal }}</td>
                <td>{{ $row->prod_panel2_akhir }}</td>
                <td>{{ $row->prod_total }}</td>
                <td>{{ $row->ps_panel1_awal }}</td>
                <td>{{ $row->ps_panel1_akhir }}</td>
                <td>{{ $row->ps_panel2_awal }}</td>
                <td>{{ $row->ps_panel2_akhir }}</td>
                <td>{{ $row->ps_total }}</td>
            </tr>
            @empty
            <tr><td colspan="10">-</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Data Pemeriksaan Pelumas -->
    <div class="section-title">Data Pemeriksaan Pelumas</div>
    <table>
        <thead>
            <tr>
                <th>Tank1 (cm)</th>
                <th>Tank1 (liter)</th>
                <th>Tank2 (cm)</th>
                <th>Tank2 (liter)</th>
                <th>Tank Total Stok</th>
                <th>Drum Area1</th>
                <th>Drum Area2</th>
                <th>Drum Total Stok</th>
                <th>Total Stok Tangki</th>
                <th>Terima Pelumas</th>
                <th>Total Pakai</th>
                <th>Jenis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->pelumas as $row)
            <tr>
                <td>{{ $row->tank1_cm }}</td>
                <td>{{ $row->tank1_liter }}</td>
                <td>{{ $row->tank2_cm }}</td>
                <td>{{ $row->tank2_liter }}</td>
                <td>{{ $row->tank_total_stok }}</td>
                <td>{{ $row->drum_area1 }}</td>
                <td>{{ $row->drum_area2 }}</td>
                <td>{{ $row->drum_total_stok }}</td>
                <td>{{ $row->total_stok_tangki }}</td>
                <td>{{ $row->terima_pelumas }}</td>
                <td>{{ $row->total_pakai }}</td>
                <td>{{ $row->jenis }}</td>
            </tr>
            @empty
            <tr><td colspan="12">-</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pemeriksaan Bahan Kimia -->
    <div class="section-title">Pemeriksaan Bahan Kimia</div>
    <table>
        <thead>
            <tr>
                <th>Jenis Bahan Kimia</th>
                <th>Stok Awal</th>
                <th>Terima</th>
                <th>Total Pakai</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->bahanKimia as $row)
            <tr>
                <td>{{ $row->jenis }}</td>
                <td>{{ $row->stok_awal }}</td>
                <td>{{ $row->terima }}</td>
                <td>{{ $row->total_pakai }}</td>
            </tr>
            @empty
            <tr><td colspan="4">-</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Beban Tertinggi Harian -->
    <div class="section-title">Beban Tertinggi Harian</div>
    <table>
        <thead>
            <tr>
                <th>Mesin</th>
                <th>Siang (07:00-17:00)</th>
                <th>Malam (18:00-06:00)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan->bebanTertinggi as $row)
            <tr>
                <td>{{ $row->machine->name ?? '-' }}</td>
                <td>{{ $row->siang }}</td>
                <td>{{ $row->malam }}</td>
            </tr>
            @empty
            <tr><td colspan="3">-</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
