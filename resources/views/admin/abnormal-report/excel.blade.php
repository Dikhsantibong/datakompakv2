<table>
    <tr>
        <th colspan="2">LAPORAN ABNORMAL/GANGGUAN</th>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
    </tr>
    <tr>
        <th>Dibuat Oleh</th>
        <td>{{ $report->creator->name ?? 'N/A' }}</td>
    </tr>
</table>

<table style="margin-top: 20px;">
    <tr>
        <th colspan="10">KRONOLOGI KEJADIAN</th>
    </tr>
    <tr>
        <th rowspan="2">Pukul (WIB)</th>
        <th rowspan="2">Uraian kejadian</th>
        <th colspan="4">Pengamatan</th>
        <th colspan="4">Koordinasi</th>
    </tr>
    <tr>
        <th>Visual parameter terkait</th>
        <th>Turun beban</th>
        <th>Off CBG</th>
        <th>Stop</th>
        <th>TL Ophar</th>
        <th>TL OP</th>
        <th>TL HAR</th>
        <th>MUL</th>
    </tr>
    @foreach($report->chronologies as $chronology)
    <tr>
        <td>{{ $chronology->waktu->format('H:i') }}</td>
        <td>{{ $chronology->uraian_kejadian }}</td>
        <td>{{ $chronology->visual_parameter }}</td>
        <td>{{ $chronology->turun_beban ? 'Ya' : '' }}</td>
        <td>{{ $chronology->off_cbg ? 'Ya' : '' }}</td>
        <td>{{ $chronology->stop ? 'Ya' : '' }}</td>
        <td>{{ $chronology->tl_ophar ? 'Ya' : '' }}</td>
        <td>{{ $chronology->tl_op ? 'Ya' : '' }}</td>
        <td>{{ $chronology->tl_har ? 'Ya' : '' }}</td>
        <td>{{ $chronology->mul ? 'Ya' : '' }}</td>
    </tr>
    @endforeach
</table>

<table style="margin-top: 20px;">
    <tr>
        <th colspan="5">MESIN/PERALATAN TERDAMPAK</th>
    </tr>
    <tr>
        <th>No</th>
        <th>Nama Mesin/Peralatan</th>
        <th>Rusak</th>
        <th>Abnormal</th>
        <th>Keterangan</th>
    </tr>
    @foreach($report->affectedMachines as $index => $machine)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $machine->nama_mesin }}</td>
        <td>{{ $machine->kondisi_rusak ? 'Ya' : '' }}</td>
        <td>{{ $machine->kondisi_abnormal ? 'Ya' : '' }}</td>
        <td>{{ $machine->keterangan }}</td>
    </tr>
    @endforeach
</table>

<table style="margin-top: 20px;">
    <tr>
        <th colspan="3">TINDAK LANJUT</th>
    </tr>
    <tr>
        <th>FLM</th>
        <th>Usul MO Rutin</th>
        <th>MO Non Rutin</th>
    </tr>
    @foreach($report->followUpActions as $action)
    <tr>
        <td>{{ $action->flm_tindakan ? 'Ya' : '' }}</td>
        <td>{{ $action->usul_mo_rutin }}</td>
        <td>{{ $action->mo_non_rutin ? 'Ya' : '' }}</td>
    </tr>
    @endforeach
</table>

<table style="margin-top: 20px;">
    <tr>
        <th>REKOMENDASI</th>
    </tr>
    @foreach($report->recommendations as $recommendation)
    <tr>
        <td>{{ $recommendation->rekomendasi }}</td>
    </tr>
    @endforeach
</table>

<table style="margin-top: 20px;">
    <tr>
        <th colspan="5">ADM</th>
    </tr>
    <tr>
        <th>No</th>
        <th>FLM</th>
        <th>PM</th>
        <th>CM</th>
        <th>PtW</th>
    </tr>
    @foreach($report->admActions as $index => $adm)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $adm->flm ? 'Ya' : '' }}</td>
        <td>{{ $adm->pm ? 'Ya' : '' }}</td>
        <td>{{ $adm->cm ? 'Ya' : '' }}</td>
        <td>{{ $adm->ptw ? 'Ya' : '' }}</td>
    </tr>
    @endforeach
</table> 