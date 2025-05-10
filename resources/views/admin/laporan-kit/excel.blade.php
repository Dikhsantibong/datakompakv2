<table>
    <!-- Logo spacing row -->
    <tr>
        <td colspan="3"></td>
        <td></td>
        <td colspan="3"></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- Header -->
    <tr class="main-header">
        <td colspan="7">Laporan KIT 00.00 - {{ date('d F Y', strtotime($laporan->tanggal)) }}</td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- BBM Section -->
    <tr class="section-header">
        <td colspan="7">Data BBM</td>
    </tr>
    <tr class="table-header">
        <th colspan="5">Storage Tank</th>
        <th colspan="3">Service Tank</th>
        <th rowspan="2">Total Stok Tangki</th>
        <th rowspan="2">Terima BBM</th>
        <th colspan="7">Flowmeter</th>
    </tr>
    <tr class="table-header">
        <th colspan="2">Tank 1</th>
        <th colspan="2">Tank 2</th>
        <th>Total Stok</th>
        <th colspan="2">Tank 1</th>
        <th>Tank 2</th>
        <th colspan="3">Flowmeter 1</th>
        <th colspan="3">Flowmeter 2</th>
        <th>Total Pakai</th>
    </tr>
    <tr class="table-header">
        <th>cm</th>
        <th>liter</th>
        <th>cm</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>%</th>
        <th>liter</th>
        <th>%</th>
        <th>liter</th>
        <th>liter</th>
        <th>Awal</th>
        <th>Akhir</th>
        <th>Pakai 1</th>
        <th>Awal</th>
        <th>Akhir</th>
        <th>Pakai 2</th>
    </tr>
    @foreach($laporan->bbm as $bbm)
    <tr>
        <td>{{ $bbm->storage_tank_1_cm }}</td>
        <td>{{ $bbm->storage_tank_1_liter }}</td>
        <td>{{ $bbm->storage_tank_2_cm }}</td>
        <td>{{ $bbm->storage_tank_2_liter }}</td>
        <td>{{ $bbm->total_stok }}</td>
        <td>{{ $bbm->service_tank_1_liter }}</td>
        <td>{{ $bbm->service_tank_1_percentage }}</td>
        <td>{{ $bbm->service_tank_2_liter }}</td>
        <td>{{ $bbm->service_tank_2_percentage }}</td>
        <td>{{ $bbm->total_stok_tangki }}</td>
        <td>{{ $bbm->terima_bbm }}</td>
        <td>{{ $bbm->flowmeter_1_awal }}</td>
        <td>{{ $bbm->flowmeter_1_akhir }}</td>
        <td>{{ $bbm->flowmeter_1_pakai }}</td>
        <td>{{ $bbm->flowmeter_2_awal }}</td>
        <td>{{ $bbm->flowmeter_2_akhir }}</td>
        <td>{{ $bbm->flowmeter_2_pakai }}</td>
    </tr>
    @endforeach
    <tr><td colspan="17"></td></tr>

    <!-- KWH Section -->
    <tr class="section-header">
        <td colspan="17">Data KWH</td>
    </tr>
    <tr class="table-header">
        <th colspan="5">KWH Produksi</th>
        <th colspan="5">KWH Pemakaian Sendiri (PS)</th>
    </tr>
    <tr class="table-header">
        <th colspan="2">Panel 1</th>
        <th colspan="2">Panel 2</th>
        <th rowspan="2">Total Prod. kWH</th>
        <th colspan="2">Panel 1</th>
        <th colspan="2">Panel 2</th>
        <th rowspan="2">Total Prod. kWH</th>
    </tr>
    <tr class="table-header">
        <th>Awal</th>
        <th>Akhir</th>
        <th>Awal</th>
        <th>Akhir</th>
        <th>Awal</th>
        <th>Akhir</th>
        <th>Awal</th>
        <th>Akhir</th>
    </tr>
    @foreach($laporan->kwh as $kwh)
    <tr>
        <td>{{ $kwh->prod_panel1_awal }}</td>
        <td>{{ $kwh->prod_panel1_akhir }}</td>
        <td>{{ $kwh->prod_panel2_awal }}</td>
        <td>{{ $kwh->prod_panel2_akhir }}</td>
        <td>{{ $kwh->prod_total }}</td>
        <td>{{ $kwh->ps_panel1_awal }}</td>
        <td>{{ $kwh->ps_panel1_akhir }}</td>
        <td>{{ $kwh->ps_panel2_awal }}</td>
        <td>{{ $kwh->ps_panel2_akhir }}</td>
        <td>{{ $kwh->ps_total }}</td>
    </tr>
    @endforeach
    <tr><td colspan="10"></td></tr>

    <!-- Pelumas Section -->
    <tr class="section-header">
        <td colspan="12">Data Pelumas</td>
    </tr>
    <tr class="table-header">
        <th colspan="5">Storage Tank</th>
        <th colspan="3">Drum Pelumas</th>
        <th rowspan="2">Total Stok Tangki</th>
        <th rowspan="2">Terima Pelumas</th>
        <th rowspan="2">Total Pakai Pelumas</th>
        <th rowspan="2">Jenis Pelumas</th>
    </tr>
    <tr class="table-header">
        <th colspan="2">Tank 1</th>
        <th colspan="2">Tank 2</th>
        <th>Total Stok</th>
        <th>Area 1</th>
        <th>Area 2</th>
        <th>Total Stok</th>
    </tr>
    <tr class="table-header">
        <th>cm</th>
        <th>liter</th>
        <th>cm</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>liter</th>
        <th>text</th>
    </tr>
    @foreach($laporan->pelumas as $pelumas)
    <tr>
        <td>{{ $pelumas->tank1_cm }}</td>
        <td>{{ $pelumas->tank1_liter }}</td>
        <td>{{ $pelumas->tank2_cm }}</td>
        <td>{{ $pelumas->tank2_liter }}</td>
        <td>{{ $pelumas->tank_total_stok }}</td>
        <td>{{ $pelumas->drum_area1 }}</td>
        <td>{{ $pelumas->drum_area2 }}</td>
        <td>{{ $pelumas->drum_total_stok }}</td>
        <td>{{ $pelumas->total_stok_tangki }}</td>
        <td>{{ $pelumas->terima_pelumas }}</td>
        <td>{{ $pelumas->total_pakai }}</td>
        <td>{{ $pelumas->jenis }}</td>
    </tr>
    @endforeach
    <tr><td colspan="12"></td></tr>

    <!-- Bahan Kimia Section -->
    <tr class="section-header">
        <td colspan="4">Data Bahan Kimia</td>
    </tr>
    <tr class="table-header">
        <th>Jenis Bahan Kimia</th>
        <th>Stok Awal</th>
        <th>Terima Bahan Kimia</th>
        <th>Total Pakai</th>
    </tr>
    @foreach($laporan->bahanKimia as $bahan)
    <tr>
        <td>{{ $bahan->jenis }}</td>
        <td>{{ $bahan->stok_awal }}</td>
        <td>{{ $bahan->terima }}</td>
        <td>{{ $bahan->total_pakai }}</td>
    </tr>
    @endforeach
    <tr><td colspan="4"></td></tr>

    <!-- Footer -->
    <tr><td colspan="17"></td></tr>
    <tr class="footer">
        <td colspan="17">Dibuat oleh: {{ $laporan->creator->name ?? 'System' }} | Tanggal: {{ date('d/m/Y H:i', strtotime($laporan->created_at)) }}</td>
    </tr>
</table> 