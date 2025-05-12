<table>
    <tr>
        <td colspan="7">LAPORAN K3 KAMP DAN LINGKUNGAN</td>
    </tr>
    <tr>
        <td colspan="7">Tanggal: {{ $report->date->format('d/m/Y') }}</td>
    </tr>
    <tr>
        <td colspan="7">Dibuat oleh: {{ $report->creator->name }}</td>
    </tr>
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="7">K3 & Keamanan</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Item</th>
        <th>Status</th>
        <th>Kondisi</th>
        <th>Keterangan</th>
        <th>Eviden</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($report->items->where('item_type', 'k3_keamanan') as $item)
    <tr>
        <td>{{ $no++ }}</td>
        <td>K3 & Keamanan</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ ucfirst($item->status) }}</td>
        <td>{{ ucfirst($item->kondisi) }}</td>
        <td>{{ $item->keterangan }}</td>
        <td>{{ $item->media->isNotEmpty() ? 'Ada' : '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>
    <tr>
        <td colspan="7">Lingkungan</td>
    </tr>
    <tr>
        <th>No</th>
        <th>Kategori</th>
        <th>Item</th>
        <th>Status</th>
        <th>Kondisi</th>
        <th>Keterangan</th>
        <th>Eviden</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($report->items->where('item_type', 'lingkungan') as $item)
    <tr>
        <td>{{ $no++ }}</td>
        <td>Lingkungan</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ ucfirst($item->status) }}</td>
        <td>{{ ucfirst($item->kondisi) }}</td>
        <td>{{ $item->keterangan }}</td>
        <td>{{ $item->media->isNotEmpty() ? 'Ada' : '-' }}</td>
    </tr>
    @endforeach
</table>