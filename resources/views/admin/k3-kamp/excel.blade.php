<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="7">Laporan K3 KAMP dan Lingkungan - {{ date('d F Y', strtotime($report->date)) }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="3"></td>
        <td></td>
        <td colspan="3"></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- K3 & Keamanan Section -->
    <tr class="section-header">
        <td colspan="7">K3 & Keamanan</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Item</th>
        <th>Status</th>
        <th>Kondisi</th>
        <th colspan="3">Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($report->items->where('item_type', 'k3_keamanan') as $item)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ ucfirst($item->status) }}</td>
        <td>{{ ucfirst($item->kondisi) }}</td>
        <td colspan="3">{{ $item->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Lingkungan Section -->
    <tr class="section-header">
        <td colspan="7">Lingkungan</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Item</th>
        <th>Status</th>
        <th>Kondisi</th>
        <th colspan="3">Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($report->items->where('item_type', 'lingkungan') as $item)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ ucfirst($item->status) }}</td>
        <td>{{ ucfirst($item->kondisi) }}</td>
        <td colspan="3">{{ $item->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="7"></td></tr>

    <!-- Footer -->
    <tr><td colspan="7"></td></tr>
    <tr class="footer">
        <td colspan="7">Dibuat oleh: {{ $report->creator->name ?? 'System' }} | Tanggal: {{ date('d/m/Y H:i', strtotime($report->created_at)) }}</td>
    </tr>
</table> 