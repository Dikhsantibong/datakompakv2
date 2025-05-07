<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="10">Data Blackstart - {{ $blackstarts->first()->tanggal->format('F Y') }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="4"></td>
        <td></td>
        <td colspan="5"></td>
    </tr>
    <tr><td colspan="10"></td></tr>

    <!-- Blackstart Data Section -->
    <tr class="section-header">
        <td colspan="10">Komitmen dan Pembahasan</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Unit Layanan / Sentral</th>
        <th>Pembangkit</th>
        <th>Black Start</th>
        <th>SOP Black Start</th>
        <th>Load Set</th>
        <th>Line Energize</th>
        <th>Status Jaringan</th>
        <th>PIC</th>
        <th>Status</th>
    </tr>
    @foreach($blackstarts as $blackstart)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $blackstart->powerPlant->name }}</td>
        <td>{{ ucfirst($blackstart->pembangkit_status) }}</td>
        <td>{{ ucfirst($blackstart->black_start_status) }}</td>
        <td>{{ ucfirst($blackstart->sop_status) }}</td>
        <td>{{ ucfirst($blackstart->load_set_status) }}</td>
        <td>{{ ucfirst($blackstart->line_energize_status) }}</td>
        <td>{{ ucfirst($blackstart->status_jaringan) }}</td>
        <td>{{ $blackstart->pic }}</td>
        <td>{{ strtoupper($blackstart->status) }}</td>
    </tr>
    @endforeach
    <tr><td colspan="10"></td></tr>

    <!-- Peralatan Blackstart Section -->
    <tr class="section-header">
        <td colspan="27">Data Peralatan Blackstart</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Unit Layanan / Sentral</th>
        <th colspan="3">Kompresor Diesel</th>
        <th colspan="3">Tabung Udara</th>
        <th>UPS</th>
        <th colspan="2">Lampu Emergency</th>
        <th colspan="3">Battery Catudaya</th>
        <th colspan="3">Battery Black Start</th>
        <th>Radio Komunikasi</th>
        <th>Kondisi Radio Kompresor</th>
        <th>Panel</th>
        <th>Simulasi Black Start</th>
        <th>Start Kondisi Black Out</th>
        <th colspan="3">Target Waktu</th>
        <th>PIC</th>
        <th>Status</th>
    </tr>
    <tr class="table-subheader">
        <th></th>
        <th></th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Kondisi</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Kondisi</th>
        <th>Kondisi</th>
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Kondisi</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Kondisi</th>
        <th>Kondisi</th>
        <th>Kondisi</th>
        <th>Kondisi</th>
        <th>Status</th>
        <th>Status</th>
        <th>Mulai</th>
        <th>Selesai</th>
        <th>Deadline</th>
        <th></th>
        <th></th>
    </tr>
    @foreach($blackstarts as $blackstart)
        @foreach($blackstart->peralatanBlackstarts as $peralatan)
        <tr>
            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
            <td>{{ $blackstart->powerPlant->name }}</td>
            <td>{{ $peralatan->kompresor_diesel_jumlah }}</td>
            <td>{{ $peralatan->kompresor_diesel_satuan }}</td>
            <td>{{ ucfirst($peralatan->kompresor_diesel_kondisi) }}</td>
            <td>{{ $peralatan->tabung_udara_jumlah }}</td>
            <td>{{ $peralatan->tabung_udara_satuan }}</td>
            <td>{{ ucfirst($peralatan->tabung_udara_kondisi) }}</td>
            <td>{{ ucfirst($peralatan->ups_kondisi) }}</td>
            <td>{{ $peralatan->lampu_emergency_jumlah }}</td>
            <td>{{ ucfirst($peralatan->lampu_emergency_kondisi) }}</td>
            <td>{{ $peralatan->battery_catudaya_jumlah }}</td>
            <td>{{ $peralatan->battery_catudaya_satuan }}</td>
            <td>{{ ucfirst($peralatan->battery_catudaya_kondisi) }}</td>
            <td>{{ $peralatan->battery_blackstart_jumlah }}</td>
            <td>{{ $peralatan->battery_blackstart_satuan }}</td>
            <td>{{ ucfirst($peralatan->battery_blackstart_kondisi) }}</td>
            <td>{{ ucfirst($peralatan->radio_komunikasi_kondisi) }}</td>
            <td>{{ ucfirst($peralatan->radio_kompresor_kondisi) }}</td>
            <td>{{ ucfirst($peralatan->panel_kondisi) }}</td>
            <td>{{ ucfirst($peralatan->simulasi_blackstart) }}</td>
            <td>{{ ucfirst($peralatan->start_kondisi_blackout) }}</td>
            <td>{{ $peralatan->waktu_mulai ? \Carbon\Carbon::parse($peralatan->waktu_mulai)->format('H:i') : '-' }}</td>
            <td>{{ $peralatan->waktu_selesai ? \Carbon\Carbon::parse($peralatan->waktu_selesai)->format('H:i') : '-' }}</td>
            <td>{{ $peralatan->waktu_deadline ? \Carbon\Carbon::parse($peralatan->waktu_deadline)->format('H:i') : '-' }}</td>
            <td>{{ $peralatan->pic }}</td>
            <td>{{ strtoupper($peralatan->status) }}</td>
        </tr>
        @endforeach
    @endforeach
    <tr><td colspan="27"></td></tr>

    <!-- Footer -->
    <tr><td colspan="27"></td></tr>
    <tr class="footer">
        <td colspan="27">Diekspor pada: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
</table> 