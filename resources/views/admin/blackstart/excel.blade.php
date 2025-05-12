<table>
    <!-- Row for logo (leave empty, logo will be injected by WithDrawings) -->
    <tr><td colspan="27"></td></tr>
    <!-- Title -->
    <tr>
        <td colspan="27" style="text-align: center; font-size: 16px; font-weight: bold; padding: 10px 0;">
            Data Blackstart - {{ $blackstarts->first()->tanggal->format('F Y') }}
        </td>
    </tr>
    <tr><td colspan="27"></td></tr>

    <!-- Blackstart Data Section -->
    <tr class="section-header">
        <td colspan="12">Komitmen dan Pembahasan</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Unit Layanan / Sentral</th>
        <th>Pembangkit</th>
        <th>Black Start</th>
        <th>Evidence Diagram</th>
        <th>SOP Black Start</th>
        <th>Evidence SOP</th>
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
        <td>
            @if($blackstart->diagram_evidence)
                <a href="{{ asset('storage/' . $blackstart->diagram_evidence) }}" target="_blank">
                    {{ basename($blackstart->diagram_evidence) }}
                </a>
            @else
                -
            @endif
        </td>
        <td>{{ ucfirst($blackstart->sop_status) }}</td>
        <td>
            @if($blackstart->sop_evidence)
                <a href="{{ asset('storage/' . $blackstart->sop_evidence) }}" target="_blank">
                    {{ basename($blackstart->sop_evidence) }}
                </a>
            @else
                -
            @endif
        </td>
        <td>{{ ucfirst($blackstart->load_set_status) }}</td>
        <td>{{ ucfirst($blackstart->line_energize_status) }}</td>
        <td>{{ ucfirst($blackstart->status_jaringan) }}</td>
        <td>{{ $blackstart->pic }}</td>
        <td>{{ strtoupper($blackstart->status) }}</td>
    </tr>
    @endforeach
    <tr><td colspan="12"></td></tr>

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
        <th colspan="3">Lampu Emergency</th>
        <th colspan="3">Battery Catudaya</th>
        <th colspan="3">Battery Black Start</th>
        <th colspan="3">Radio Komunikasi</th>
        <th>Simulasi Black Start</th>
        <th>Start Kondisi Black Out</th>
        <th colspan="3">Target Waktu</th>
        <th>PIC</th>
        <th>Status</th>
    </tr>
    <tr class="table-subheader">
        <th></th>
        <th></th>
        <!-- Kompresor Diesel -->
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Eviden</th>
        <!-- Tabung Udara -->
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Eviden</th>
        <!-- UPS -->
        <th>Kondisi</th>
        <!-- Lampu Emergency -->
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Eviden</th>
        <!-- Battery Catudaya -->
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Eviden</th>
        <!-- Battery Black Start -->
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Eviden</th>
        <!-- Radio Komunikasi -->
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Eviden</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    @foreach($blackstarts as $blackstart)
        @foreach($blackstart->peralatanBlackstarts as $peralatan)
        <tr>
            <td>{{ $loop->parent->iteration }}.{{ $loop->iteration }}</td>
            <td>{{ $blackstart->powerPlant->name }}</td>
            <!-- Kompresor Diesel -->
            <td>{{ $peralatan->kompresor_diesel_jumlah }}</td>
            <td>{{ ucfirst($peralatan->kompresor_diesel_kondisi) }}</td>
            <td>
                @if($peralatan->kompresor_eviden)
                    <a href="{{ asset('storage/' . $peralatan->kompresor_eviden) }}" target="_blank">
                        {{ basename($peralatan->kompresor_eviden) }}
                    </a>
                @else
                    -
                @endif
            </td>
            <!-- Tabung Udara -->
            <td>{{ $peralatan->tabung_udara_jumlah }}</td>
            <td>{{ ucfirst($peralatan->tabung_udara_kondisi) }}</td>
            <td>
                @if($peralatan->tabung_eviden)
                    <a href="{{ asset('storage/' . $peralatan->tabung_eviden) }}" target="_blank">
                        {{ basename($peralatan->tabung_eviden) }}
                    </a>
                @else
                    -
                @endif
            </td>
            <!-- UPS -->
            <td>{{ ucfirst($peralatan->ups_kondisi) }}</td>
            <!-- Lampu Emergency -->
            <td>{{ $peralatan->lampu_emergency_jumlah }}</td>
            <td>{{ ucfirst($peralatan->lampu_emergency_kondisi) }}</td>
            <td>
                @if($peralatan->lampu_eviden)
                    <a href="{{ asset('storage/' . $peralatan->lampu_eviden) }}" target="_blank">
                        {{ basename($peralatan->lampu_eviden) }}
                    </a>
                @else
                    -
                @endif
            </td>
            <!-- Battery Catudaya -->
            <td>{{ $peralatan->battery_catudaya_jumlah }}</td>
            <td>{{ ucfirst($peralatan->battery_catudaya_kondisi) }}</td>
            <td>
                @if($peralatan->catudaya_eviden)
                    <a href="{{ asset('storage/' . $peralatan->catudaya_eviden) }}" target="_blank">
                        {{ basename($peralatan->catudaya_eviden) }}
                    </a>
                @else
                    -
                @endif
            </td>
            <!-- Battery Black Start -->
            <td>{{ $peralatan->battery_blackstart_jumlah }}</td>
            <td>{{ ucfirst($peralatan->battery_blackstart_kondisi) }}</td>
            <td>
                @if($peralatan->blackstart_eviden)
                    <a href="{{ asset('storage/' . $peralatan->blackstart_eviden) }}" target="_blank">
                        {{ basename($peralatan->blackstart_eviden) }}
                    </a>
                @else
                    -
                @endif
            </td>
            <!-- Radio Komunikasi -->
            <td>{{ $peralatan->radio_jumlah }}</td>
            <td>{{ ucfirst($peralatan->radio_komunikasi_kondisi) }}</td>
            <td>
                @if($peralatan->radio_eviden)
                    <a href="{{ asset('storage/' . $peralatan->radio_eviden) }}" target="_blank">
                        {{ basename($peralatan->radio_eviden) }}
                    </a>
                @else
                    -
                @endif
            </td>
            <!-- Simulasi Black Start -->
            <td>{{ ucfirst($peralatan->simulasi_blackstart) }}</td>
            <!-- Start Kondisi Black Out -->
            <td>{{ ucfirst($peralatan->start_kondisi_blackout) }}</td>
            <!-- Target Waktu -->
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