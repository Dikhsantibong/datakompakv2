<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="14">Data Pengadaan Barang dan Jasa - {{ now()->format('d F Y') }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="4"></td>
        <td colspan="6"></td>
        <td colspan="4"></td>
    </tr>
    <tr><td colspan="14"></td></tr>

    <!-- Filter Info -->
    @if(request()->has('tahun') || request()->has('jenis') || request()->has('status'))
    <tr>
        <td colspan="14">
            Filter: 
            @if(request('tahun'))
                Tahun: {{ request('tahun') }},
            @endif
            @if(request('jenis'))
                Jenis: {{ request('jenis') }},
            @endif
            @foreach(['pengusulan', 'proses_kontrak', 'pengadaan', 'pekerjaan_fisik', 'pemberkasan', 'pembayaran'] as $status)
                @if(request($status))
                    {{ ucfirst(str_replace('_', ' ', $status)) }}: {{ request($status) }},
                @endif
            @endforeach
        </td>
    </tr>
    <tr><td colspan="14"></td></tr>
    @endif

    <!-- Table Header -->
    <tr class="table-header">
        <th>No</th>
        <th>Item Pekerjaan</th>
        <th>Tahun</th>
        <th>Nilai Kontrak</th>
        <th>No. PRK</th>
        <th>Jenis</th>
        <th>Intensitas</th>
        <th>Pengusulan</th>
        <th>Proses Kontrak</th>
        <th>Pengadaan</th>
        <th>Pekerjaan Fisik</th>
        <th>Pemberkasan</th>
        <th>Pembayaran</th>
        <th>Keterangan</th>
    </tr>

    <!-- Table Body -->
    @foreach($pengadaan as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->judul }}</td>
        <td>{{ $item->tahun }}</td>
        <td>{{ number_format($item->nilai_kontrak, 2) }}</td>
        <td>{{ $item->no_prk }}</td>
        <td>{{ $item->jenis }}</td>
        <td>{{ $item->intensitas }}</td>
        <td>{{ $item->pengusulan }}</td>
        <td>{{ $item->proses_kontrak }}</td>
        <td>{{ $item->pengadaan }}</td>
        <td>{{ $item->pekerjaan_fisik }}</td>
        <td>{{ $item->pemberkasan }}</td>
        <td>{{ $item->pembayaran }}</td>
        <td>{{ $item->keterangan }}</td>
    </tr>
    @endforeach

    <!-- Footer -->
    <tr><td colspan="14"></td></tr>
    <tr class="footer">
        <td colspan="14">Diekspor pada: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
</table> 