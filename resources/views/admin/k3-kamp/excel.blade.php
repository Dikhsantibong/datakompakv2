<?php
// Ensure no whitespace or newlines before the table
?><table>
    <tr>
        <td colspan="5">Laporan K3 KAMP dan Lingkungan - {{ $report->date->format('d F Y') }}</td>
    </tr>

    <tr>
        <td colspan="5">K3 & Keamanan</td>
    </tr>
    <tr>
        <td>No</td>
        <td>Item</td>
        <td>Status</td>
        <td>Kondisi</td>
        <td>Keterangan</td>
        
    </tr>
    @foreach($report->items->where('item_type', 'k3_keamanan') as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ ucfirst($item->status) }}</td>
        <td>{{ ucfirst($item->kondisi) }}</td>
        <td>{{ $item->keterangan ?? '-' }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="5">Lingkungan</td>
    </tr>
    <tr>
        <td>No</td>
        <td>Item</td>
        <td>Status</td>
        <td>Kondisi</td>
        <td>Keterangan</td>
    </tr>
    @foreach($report->items->where('item_type', 'lingkungan') as $index => $item)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item->item_name }}</td>
        <td>{{ ucfirst($item->status) }}</td>
        <td>{{ ucfirst($item->kondisi) }}</td>
        <td>{{ $item->keterangan ?? '-' }}</td>
    </tr>
    @endforeach

    <tr>
        <td colspan="5">Dibuat oleh: {{ $report->creator->name ?? 'System' }} | {{ $report->created_at->format('d/m/Y H:i') }}</td>
    </tr>
</table><?php
// Ensure no whitespace or newlines after the table
?> 