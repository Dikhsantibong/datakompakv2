<table style="border-collapse: collapse;">
    <!-- Logo spacing row - no border -->
    <tr>
        <td colspan="11" style="height:20px; border:none;"></td>
    </tr>
    <!-- Logo row - no border -->
    <tr>
        <td colspan="3" style="border:none; height:60px;">
            <!-- PLN Logo will be placed here by export class -->
        </td>
        <td colspan="5" style="border:none; text-align:center;">
            <span style="font-size:22px; color:#00AEEF; font-family:Arial;">{{ Auth::user()->name ?? 'UP KENDARI' }}</span>
        </td>
        <td colspan="3" style="border:none; height:60px;">
            <!-- Unit Logo will be placed here by export class -->
        </td>
    </tr>
    <!-- Spacing before header - no border -->
    <tr>
        <td colspan="11" style="height:20px; border:none;"></td>
    </tr>
    <!-- Header utama -->
    <tr class="main-header">
        <td colspan="11" style="font-size:16px; font-weight:bold; text-align:center; background:#D1D5DB; height:30px;">Laporan Abnormal/Gangguan - {{ $report->created_at->format('d F Y') }}</td>
    </tr>
    <tr>
        <td colspan="11" style="height:15px;"></td>
    </tr>

    <!-- Kronologi Kejadian Section -->
    <tr class="section-header">
        <td colspan="11">Kronologi Kejadian</td>
    </tr>
    <tr class="table-header">
        <th rowspan="2">Pukul (WIB)</th>
        <th rowspan="2">Uraian kejadian</th>
        <th colspan="2">Pengamatan</th>
        <th colspan="3">Tindakan Isolasi</th>
        <th colspan="4">Koordinasi</th>
    </tr>
    <tr class="table-header">
        <th>Visual</th>
        <th>Parameter</th>
        <th>Turun beban</th>
        <th>CBG OFF</th>
        <th>Stop</th>
        <th>TL Ophar</th>
        <th>TL OP</th>
        <th>TL HAR</th>
        <th>MUL</th>
    </tr>

    @foreach($report->chronologies as $index => $chronology)
    <tr>
        <td>{{ $chronology->waktu->format('H:i') }}</td>
        <td>{{ $chronology->uraian_kejadian }}</td>
        <td>{{ $chronology->visual }}</td>
        <td>{{ $chronology->parameter }}</td>
        <td>{{ $chronology->turun_beban ? '✓' : '-' }}</td>
        <td>{{ $chronology->off_cbg ? '✓' : '-' }}</td>
        <td>{{ $chronology->stop ? '✓' : '-' }}</td>
        <td>{{ $chronology->tl_ophar ? '✓' : '-' }}</td>
        <td>{{ $chronology->tl_op ? '✓' : '-' }}</td>
        <td>{{ $chronology->tl_har ? '✓' : '-' }}</td>
        <td>{{ $chronology->mul ? '✓' : '-' }}</td>
    </tr>
    @endforeach

    <!-- Spacing row -->
    <tr><td colspan="11"></td></tr>

    <!-- Mesin/Peralatan Terdampak Section -->
    <tr class="section-header">
        <td colspan="11">Mesin/Peralatan Terdampak</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th colspan="5">Nama Mesin/Peralatan/Material</th>
        <th>Rusak</th>
        <th>Abnormal</th>
        <th colspan="2">Keterangan</th>
    </tr>
    @foreach($report->affectedMachines as $index => $machine)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td colspan="5">{{ $machine->nama_mesin }}</td>
        <td>{{ $machine->kondisi_rusak ? '✓' : '-' }}</td>
        <td>{{ $machine->kondisi_abnormal ? '✓' : '-' }}</td>
        <td colspan="2">{{ $machine->keterangan }}</td>
    </tr>
    @endforeach

    <!-- Spacing row -->
    <tr><td colspan="11"></td></tr>

    <!-- Tindak Lanjut Section -->
    <tr class="section-header">
        <td colspan="11">Tindak Lanjut Tindakan</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>FLM</th>
        <th colspan="4">Usul MO rutin (PO-PS)</th>
        <th>MO non rutin</th>
        <th colspan="3">Lainnya</th>
    </tr>
    @foreach($report->followUpActions as $index => $action)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $action->flm_tindakan ? '✓' : '-' }}</td>
        <td colspan="4">{{ $action->usul_mo_rutin }}</td>
        <td>{{ $action->mo_non_rutin ? '✓' : '-' }}</td>
        <td colspan="3">{{ $action->lainnya ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Spacing row -->
    <tr><td colspan="11"></td></tr>

    <!-- Rekomendasi Section -->
    <tr class="section-header">
        <td colspan="11">Rekomendasi</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th colspan="9">Uraian</th>
    </tr>
    @foreach($report->recommendations as $index => $recommendation)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td colspan="9">{{ $recommendation->rekomendasi }}</td>
    </tr>
    @endforeach

    <!-- Spacing row -->
    <tr><td colspan="11"></td></tr>

    <!-- ADM Section -->
    <tr class="section-header">
        <td colspan="11">Tindak Lanjut Administrasi</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>FLM</th>
        <th>PM</th>
        <th>CM</th>
        <th>PtW</th>
        <th>SR</th>
        <th colspan="4"></th>
    </tr>
    @foreach($report->admActions as $index => $adm)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $adm->flm ? '✓' : '-' }}</td>
        <td>{{ $adm->pm ? '✓' : '-' }}</td>
        <td>{{ $adm->cm ? '✓' : '-' }}</td>
        <td>{{ $adm->ptw ? '✓' : '-' }}</td>
        <td>{{ $adm->sr ? '✓' : '-' }}</td>
        <td colspan="4"></td>
    </tr>
    @endforeach

    <!-- Spacing rows -->
    <tr><td colspan="11"></td></tr>
    <tr><td colspan="11"></td></tr>

    <!-- Validation Section -->
    <tr>
        <td colspan="5" style="text-align: center;">
            <strong>Diperiksa oleh:</strong>
            <br><br><br><br>
            <strong>.................................</strong>
            <br>
            <strong>Supervisor</strong>
            <br>
            <strong>Tanggal: ....../....../......</strong>
        </td>
        <td colspan="1"></td>
        <td colspan="5" style="text-align: center;">
            <strong>Dibuat oleh:</strong>
            <br><br><br><br>
            <strong>{{ $report->creator->name }}</strong>
            <br>
            <strong>{{ $report->creator->position ?? 'Staff' }}</strong>
            <br>
            <strong>Tanggal: {{ $report->created_at->format('d/m/Y') }}</strong>
        </td>
    </tr>

    <!-- Footer -->
    <tr><td colspan="11"></td></tr>
    <tr class="footer">
        <td colspan="11">Diekspor pada: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
</table> 