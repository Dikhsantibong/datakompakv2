<?php
?><table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="9">Form Patrol Check - {{ $patrol->created_at->format('d F Y') }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="4"></td>
        <td></td>
        <td colspan="4"></td>
    </tr>
    <tr><td colspan="9"></td></tr>

    <!-- Patrol Check Data Section -->
    <tr class="section-header">
        <td colspan="9">Data Patrol Check</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Tanggal</th>
        <th>Sistem</th>
        <th>Kondisi</th>
        <th>Catatan</th>
        <th>Status</th>
        <th>Dibuat Oleh</th>
        <th>Waktu Dibuat</th>
    </tr>
    @foreach($patrol->condition_systems as $index => $system)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $patrol->created_at->format('d/m/Y') }}</td>
        <td>{{ $system['system'] }}</td>
        <td>{{ ucfirst($system['condition']) }}</td>
        <td>{{ $system['notes'] ?? '-' }}</td>
        <td>{{ ucfirst($patrol->status) }}</td>
        <td>{{ $patrol->creator->name ?? 'System' }}</td>
        <td>{{ $patrol->created_at->format('H:i') }}</td>
    </tr>
    @endforeach
    <tr><td colspan="9"></td></tr>

    @if(count($patrol->abnormal_equipments) > 0)
    <!-- Abnormal Equipment Section -->
    <tr class="section-header">
        <td colspan="9">Data Kondisi Alat Bantu</td>
    </tr>
    <tr class="table-header">
        <th rowspan="2">No</th>
        <th rowspan="2">Peralatan</th>
        <th rowspan="2">Kondisi Awal</th>
        <th colspan="3">Tindak Lanjut</th>
        <th rowspan="2">Kondisi Akhir</th>
        <th rowspan="2">Catatan</th>
    </tr>
    <tr class="sub-header">
        <th>FLM</th>
        <th>SR</th>
        <th>Lainnya</th>
    </tr>
    @foreach($patrol->abnormal_equipments as $index => $equipment)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $equipment['equipment'] }}</td>
        <td>{{ $equipment['condition'] }}</td>
        <td>{{ $equipment['flm'] ? 'Ya' : 'Tidak' }}</td>
        <td>{{ $equipment['sr'] ? 'Ya' : 'Tidak' }}</td>
        <td>{{ $equipment['other'] ? 'Ya' : 'Tidak' }}</td>
        <td>{{ $patrol->condition_after[$index]['condition'] ?? '-' }}</td>
        <td>{{ $patrol->condition_after[$index]['notes'] ?? '-' }}</td>
    </tr>
    @endforeach
    @endif
    <tr><td colspan="9"></td></tr>

    <!-- Footer -->
    <tr><td colspan="9"></td></tr>
    <tr class="footer">
        <td colspan="9">Dibuat oleh: {{ $patrol->creator->name ?? 'System' }} | Tanggal: {{ $patrol->created_at->format('d/m/Y H:i') }}</td>
    </tr>
</table> 