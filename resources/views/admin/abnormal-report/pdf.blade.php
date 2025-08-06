<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Abnormal/Gangguan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
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
    <div class="header" style="padding:0; background-color:#D1D5DB; margin-bottom: 20px;">
        <table width="100%" style="border:none; background:none;">
            <tr>
                <td style="width:80px; text-align:left; border:none;">
                    <img src="{{ public_path('logo/navlog1.png') }}" alt="PLN Logo" style="height:50px;">
                </td>
                <td style="text-align:center; border:none;">
                    <h1 style="margin:0; font-size:18px; color:#000;">
                        LAPORAN ABNORMAL/GANGGUAN<br>
                        @php
                            $unitMapping = [
                                'mysql_poasia' => 'PLTD POASIA',
                                'mysql_kolaka' => 'PLTD KOLAKA',
                                'mysql_bau_bau' => 'PLTD BAU BAU',
                                'mysql_wua_wua' => 'PLTD WUA WUA',
                                'mysql_winning' => 'PLTD WINNING',
                                'mysql_erkee' => 'PLTD ERKEE',
                                'mysql_ladumpi' => 'PLTD LADUMPI',
                                'mysql_langara' => 'PLTD LANGARA',
                                'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                                'mysql_pasarwajo' => 'PLTD PASARWAJO',
                                'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                                'mysql_raha' => 'PLTD RAHA',
                                'mysql_wajo' => 'PLTD WAJO',
                                'mysql_wangi_wangi' => 'PLTD WANGI-WANGI',
                                'mysql_rongi' => 'PLTM RONGI',
                                'mysql_sabilambo' => 'PLTM SABILAMBO',
                                'mysql_pltmg_bau_bau' => 'PLTD BAU BAU',
                                'mysql_pltmg_kendari' => 'PLTD KENDARI',
                                'mysql_baruta' => 'PLTD BARUTA',
                                'mysql_moramo' => 'PLTD MORAMO',
                                'mysql_mikuasi' => 'PLTM MIKUASI',
                            ];
                            $unitCode = $report->sync_unit_origin ?? null;
                            $unitName = $unitCode ? ($unitMapping[$unitCode] ?? $unitCode) : 'UP Kendari';
                        @endphp
                        Unit: {{ $unitName }}
                    </h1>
                </td>
                @php
                    $unitLogoMap = [
                        'PLTD POASIA' => 'logo/PLTD_POASIA.png',
                        'PLTD KOLAKA' => 'logo/PLTD_KOLAKA.png',
                        'PLTD BAU BAU' => 'logo/PLTD_BAU_BAU.png',
                        'PLTD WUA WUA' => 'logo/PLTD_WUA_WUA.png',
                        'PLTD WINNING' => 'logo/PLTM_WINNING.png',
                        'PLTD ERKEE' => 'logo/PLTD_EREKE.png',
                        'PLTD LADUMPI' => 'logo/PLTD_LADUMPI.png',
                        'PLTD LANGARA' => 'logo/PLTD_LANGARA.png',
                        'PLTD LANIPA-NIPA' => 'logo/PLTD_LANIPA_NIPA.png',
                        'PLTD PASARWAJO' => 'logo/PLTD_PASARWAJO.png',
                        'PLTD POASIA CONTAINERIZED' => 'logo/PLTD_POASIA_CONTAINERIZED.png',
                        'PLTD RAHA' => 'logo/PLTD_RAHA.png',
                        'PLTD WAJO' => 'logo/PLTD_WAJO.png',
                        'PLTD WANGI-WANGI' => 'logo/PLTD_WANGI_WANGI.png',
                        'PLTM RONGI' => 'logo/PLTM_RONGI.png',
                        'PLTM SABILAMBO' => 'logo/PLTM_SABILAMBO.png',
                        'PLTD KENDARI' => 'logo/PLTMG_KENDARI.png',
                        'PLTD BARUTA' => 'logo/PLTU_BARUTA.png',
                        'PLTD MORAMO' => 'logo/PLTU_MORAMO.png',
                        'PLTM MIKUASI' => 'logo/PLTM_MIKUASI.png',
                    ];
                    $logoPath = $unitLogoMap[$unitName] ?? 'logo/UP_KENDARI.png';
                @endphp
                <td style="width:80px; text-align:right; border:none;">
                    <img src="{{ public_path($logoPath) }}" alt="Unit Logo" style="height:50px;">
                </td>
            </tr>
        </table>
    </div>

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
            <td class="text-center">{!! $chronology->turun_beban ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $chronology->off_cbg ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $chronology->stop ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $chronology->tl_ophar ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $chronology->tl_op ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $chronology->tl_har ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $chronology->mul ? '&#10003;' : '' !!}</td>
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
            <td class="text-center">{!! $machine->kondisi_rusak ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $machine->kondisi_abnormal ? '&#10003;' : '' !!}</td>
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
            <td class="text-center">{!! $action->flm_tindakan ? '&#10003;' : '' !!}</td>
            <td>{{ $action->usul_mo_rutin }}</td>
            <td class="text-center">{!! $action->mo_non_rutin ? '&#10003;' : '' !!}</td>
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
            <td class="text-center">{!! $adm->flm ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $adm->pm ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $adm->cm ? '&#10003;' : '' !!}</td>
            <td class="text-center">{!! $adm->ptw ? '&#10003;' : '' !!}</td>
        </tr>
        @endforeach
    </table>
</body>
</html> 