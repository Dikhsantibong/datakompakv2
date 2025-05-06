<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="7">Form Pemeriksaan FLM - {{ $flmData->tanggal->format('d F Y') }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="3"></td>
        <td></td>
        <td colspan="3"></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- FLM Data Section -->
    <tr class="section-header">
        <td colspan="7">Data Pemeriksaan FLM</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Tanggal</th>
        <th>Mesin/Peralatan</th>
        <th>Sistem Pembangkit</th>
        <th>Masalah</th>
        <th>Tindakan</th>
        <th>Status</th>
    </tr>
    @php
        $tindakan = [];
        if ($flmData->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
        if ($flmData->tindakan_lumasi) $tindakan[] = 'Lumasi';
        if ($flmData->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
        if ($flmData->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
        if ($flmData->tindakan_lainnya) $tindakan[] = 'Lainnya';
    @endphp
    <tr>
        <td>1</td>
        <td>{{ $flmData->tanggal->format('d/m/Y') }}</td>
        <td>{{ $flmData->mesin }}</td>
        <td>{{ $flmData->sistem }}</td>
        <td>{{ $flmData->masalah }}</td>
        <td>{{ implode(', ', $tindakan) }}</td>
        <td>Selesai</td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- Detail Section -->
    <tr class="section-header">
        <td colspan="7">Detail Pemeriksaan</td>
    </tr>
    <tr class="table-header">
        <th colspan="2">Item</th>
        <th colspan="5">Keterangan</th>
    </tr>
    <tr>
        <td colspan="2">Kondisi Awal</td>
        <td colspan="5">{{ $flmData->kondisi_awal }}</td>
    </tr>
    <tr>
        <td colspan="2">Kondisi Akhir</td>
        <td colspan="5">{{ $flmData->kondisi_akhir }}</td>
    </tr>
    <tr>
        <td colspan="2">Catatan</td>
        <td colspan="5">{{ $flmData->catatan ?? '-' }}</td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- Footer -->
    <tr><td colspan="7"></td></tr>
    <tr class="footer">
        <td colspan="7">Dibuat oleh: {{ $flmData->creator->name ?? 'System' }} | Tanggal: {{ $flmData->created_at->format('d/m/Y H:i') }}</td>
    </tr>
</table> 