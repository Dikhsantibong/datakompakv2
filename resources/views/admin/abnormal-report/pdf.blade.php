<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Abnormal/Gangguan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 18px;
        }
        .section-title {
            background-color: #f4f4f4;
            padding: 5px;
            font-weight: bold;
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
        .no-border-top {
            border-top: none;
        }
    </style>
</head>
<body>
    <h1>LAPORAN ABNORMAL/GANGGUAN</h1>

    <table>
        <tr>
            <th width="20%">Tanggal</th>
            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Dibuat Oleh</th>
            <td>{{ $report->creator->name ?? 'N/A' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <th colspan="10" class="section-title">KRONOLOGI KEJADIAN</th>
        </tr>
        <tr>
            <th rowspan="2" width="8%" class="text-center">Pukul (WIB)</th>
            <th rowspan="2" width="22%" class="text-center">Uraian kejadian</th>
            <th colspan="4" class="text-center">Pengamatan</th>
            <th colspan="4" class="text-center">Koordinasi</th>
        </tr>
        <tr>
            <th width="15%" class="text-center">Visual parameter terkait</th>
            <th width="7%" class="text-center">Turun beban</th>
            <th width="7%" class="text-center">Off CBG</th>
            <th width="7%" class="text-center">Stop</th>
            <th width="7%" class="text-center">TL Ophar</th>
            <th width="7%" class="text-center">TL OP</th>
            <th width="7%" class="text-center">TL HAR</th>
            <th width="7%" class="text-center">MUL</th>
        </tr>
        @foreach($report->chronologies as $chronology)
        <tr>
            <td class="text-center">{{ $chronology->waktu->format('H:i') }}</td>
            <td>{{ $chronology->uraian_kejadian }}</td>
            <td>{{ $chronology->visual_parameter }}</td>
            <td class="text-center">{{ $chronology->turun_beban ? '✓' : '' }}</td>
            <td class="text-center">{{ $chronology->off_cbg ? '✓' : '' }}</td>
            <td class="text-center">{{ $chronology->stop ? '✓' : '' }}</td>
            <td class="text-center">{{ $chronology->tl_ophar ? '✓' : '' }}</td>
            <td class="text-center">{{ $chronology->tl_op ? '✓' : '' }}</td>
            <td class="text-center">{{ $chronology->tl_har ? '✓' : '' }}</td>
            <td class="text-center">{{ $chronology->mul ? '✓' : '' }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <th colspan="5" class="section-title">MESIN/PERALATAN TERDAMPAK</th>
        </tr>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th width="35%">Nama Mesin/Peralatan</th>
            <th width="10%" class="text-center">Rusak</th>
            <th width="10%" class="text-center">Abnormal</th>
            <th>Keterangan</th>
        </tr>
        @foreach($report->affectedMachines as $index => $machine)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $machine->nama_mesin }}</td>
            <td class="text-center">{{ $machine->kondisi_rusak ? '✓' : '' }}</td>
            <td class="text-center">{{ $machine->kondisi_abnormal ? '✓' : '' }}</td>
            <td>{{ $machine->keterangan }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <th colspan="3" class="section-title">TINDAK LANJUT</th>
        </tr>
        <tr>
            <th width="10%" class="text-center">FLM</th>
            <th>Usul MO Rutin</th>
            <th width="15%" class="text-center">MO Non Rutin</th>
        </tr>
        @foreach($report->followUpActions as $action)
        <tr>
            <td class="text-center">{{ $action->flm_tindakan ? '✓' : '' }}</td>
            <td>{{ $action->usul_mo_rutin }}</td>
            <td class="text-center">{{ $action->mo_non_rutin ? '✓' : '' }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <th class="section-title">REKOMENDASI</th>
        </tr>
        @foreach($report->recommendations as $index => $recommendation)
        <tr>
            <td>{{ $index + 1 }}. {{ $recommendation->rekomendasi }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <th colspan="5" class="section-title">ADM</th>
        </tr>
        <tr>
            <th width="5%" class="text-center">No</th>
            <th width="20%" class="text-center">FLM</th>
            <th width="20%" class="text-center">PM</th>
            <th width="20%" class="text-center">CM</th>
            <th width="20%" class="text-center">PtW</th>
        </tr>
        @foreach($report->admActions as $index => $adm)
        <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td class="text-center">{{ $adm->flm ? '✓' : '' }}</td>
            <td class="text-center">{{ $adm->pm ? '✓' : '' }}</td>
            <td class="text-center">{{ $adm->cm ? '✓' : '' }}</td>
            <td class="text-center">{{ $adm->ptw ? '✓' : '' }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html> 