<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Dibuat Oleh</th>
            <th>Mesin/Peralatan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $report->creator->name ?? 'N/A' }}</td>
            <td>
                @foreach($report->affectedMachines as $machine)
                    {{ $machine->nama_mesin }}
                    @if(!$loop->last), @endif
                @endforeach
            </td>
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
    </tbody>
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