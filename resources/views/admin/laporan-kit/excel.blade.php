<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="7">Laporan KIT 00.00 - {{ date('d F Y', strtotime($laporan->tanggal)) }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="3"></td>
        <td></td>
        <td colspan="3"></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- BBM Section -->
    <tr class="section-header">
        <td colspan="7">Data BBM</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Jenis BBM</th>
        <th>Stock Awal</th>
        <th>Penerimaan</th>
        <th>Pemakaian</th>
        <th>Stock Akhir</th>
        <th>Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->bbm as $bbm)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $bbm->jenis_bbm }}</td>
        <td>{{ $bbm->stock_awal }}</td>
        <td>{{ $bbm->penerimaan }}</td>
        <td>{{ $bbm->pemakaian }}</td>
        <td>{{ $bbm->stock_akhir }}</td>
        <td>{{ $bbm->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- KWH Section -->
    <tr class="section-header">
        <td colspan="7">Data KWH</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Stand Awal</th>
        <th>Stand Akhir</th>
        <th>Selisih</th>
        <th>Faktor Kali</th>
        <th>Total KWH</th>
        <th>Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->kwh as $kwh)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $kwh->stand_awal }}</td>
        <td>{{ $kwh->stand_akhir }}</td>
        <td>{{ $kwh->selisih }}</td>
        <td>{{ $kwh->faktor_kali }}</td>
        <td>{{ $kwh->total_kwh }}</td>
        <td>{{ $kwh->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Pelumas Section -->
    <tr class="section-header">
        <td colspan="7">Data Pelumas</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Jenis Pelumas</th>
        <th>Stock Awal</th>
        <th>Penerimaan</th>
        <th>Pemakaian</th>
        <th>Stock Akhir</th>
        <th>Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->pelumas as $pelumas)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $pelumas->jenis_pelumas }}</td>
        <td>{{ $pelumas->stock_awal }}</td>
        <td>{{ $pelumas->penerimaan }}</td>
        <td>{{ $pelumas->pemakaian }}</td>
        <td>{{ $pelumas->stock_akhir }}</td>
        <td>{{ $pelumas->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Bahan Kimia Section -->
    <tr class="section-header">
        <td colspan="7">Data Bahan Kimia</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Jenis Bahan</th>
        <th>Stock Awal</th>
        <th>Penerimaan</th>
        <th>Pemakaian</th>
        <th>Stock Akhir</th>
        <th>Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->bahanKimia as $bahan)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $bahan->jenis_bahan }}</td>
        <td>{{ $bahan->stock_awal }}</td>
        <td>{{ $bahan->penerimaan }}</td>
        <td>{{ $bahan->pemakaian }}</td>
        <td>{{ $bahan->stock_akhir }}</td>
        <td>{{ $bahan->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Jam Operasi Section -->
    <tr class="section-header">
        <td colspan="7">Jam Operasi Mesin</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Mesin</th>
        <th>Jam Start</th>
        <th>Jam Stop</th>
        <th>Total Jam</th>
        <th colspan="2">Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->jamOperasi as $operasi)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $operasi->machine->name }}</td>
        <td>{{ $operasi->jam_start }}</td>
        <td>{{ $operasi->jam_stop }}</td>
        <td>{{ $operasi->total_jam }}</td>
        <td colspan="2">{{ $operasi->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Beban Tertinggi Section -->
    <tr class="section-header">
        <td colspan="7">Beban Tertinggi</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Mesin</th>
        <th>Beban (KW)</th>
        <th>Arus (A)</th>
        <th>Tegangan (V)</th>
        <th>Cos φ</th>
        <th>Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->bebanTertinggi as $beban)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $beban->machine->name }}</td>
        <td>{{ $beban->beban_kw }}</td>
        <td>{{ $beban->arus }}</td>
        <td>{{ $beban->tegangan }}</td>
        <td>{{ $beban->cos_phi }}</td>
        <td>{{ $beban->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Gangguan Section -->
    <tr class="section-header">
        <td colspan="7">Gangguan</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Mesin</th>
        <th colspan="2">Gangguan Mekanik</th>
        <th colspan="3">Gangguan Elektrik</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($laporan->gangguan as $gangguan)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $gangguan->machine->name }}</td>
        <td colspan="2">{{ $gangguan->mekanik ?? '-' }}</td>
        <td colspan="3">{{ $gangguan->elektrik ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Footer -->
    <tr><td colspan="7"></td></tr>
    <tr class="footer">
        <td colspan="7">Dibuat oleh: {{ $laporan->creator->name ?? 'System' }} | Tanggal: {{ date('d/m/Y H:i', strtotime($laporan->created_at)) }}</td>
    </tr>
</table> 