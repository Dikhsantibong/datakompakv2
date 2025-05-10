<table>
    <!-- Header -->
    <tr class="main-header">
        <td colspan="10">Form Pemeriksaan FLM - {{ $flmData->first()->tanggal->format('d F Y') }}</td>
    </tr>
    
    <!-- Logo spacing row -->
    <tr>
        <td colspan="5"></td>
        <td></td>
        <td colspan="4"></td>
    </tr>
    <tr><td colspan="10"></td></tr>

    <!-- FLM Data Section -->
    <tr class="section-header">
        <td colspan="10">Data Pemeriksaan FLM</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Tanggal</th>
        <th>Operator</th>
        <th>Mesin/Peralatan</th>
        <th>Sistem Pembangkit</th>
        <th>Masalah</th>
        <th>Tindakan</th>
        <th>Status</th>
        <th>Foto Sebelum</th>
        <th>Foto Sesudah</th>
    </tr>
    @foreach($flmData as $index => $item)
        @php
            $tindakan = [];
            if ($item->tindakan_bersihkan) $tindakan[] = 'Bersihkan';
            if ($item->tindakan_lumasi) $tindakan[] = 'Lumasi';
            if ($item->tindakan_kencangkan) $tindakan[] = 'Kencangkan';
            if ($item->tindakan_perbaikan_koneksi) $tindakan[] = 'Perbaikan Koneksi';
            if ($item->tindakan_lainnya) $tindakan[] = 'Lainnya';
        @endphp
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->tanggal->format('d/m/Y') }}</td>
            <td>{{ $item->operator }}</td>
            <td>{{ $item->mesin }}</td>
            <td>{{ $item->sistem }}</td>
            <td>{{ $item->masalah }}</td>
            <td>{{ implode(', ', $tindakan) }}</td>
            <td>{{ ucfirst($item->status) }}</td>
            <td>
                @if($item->eviden_sebelum)
                    <img src="{{ public_path('storage/' . $item->eviden_sebelum) }}" 
                         width="150" 
                         height="100" 
                         alt="Kondisi Sebelum">
                @else
                    -
                @endif
            </td>
            <td>
                @if($item->eviden_sesudah)
                    <img src="{{ public_path('storage/' . $item->eviden_sesudah) }}" 
                         width="150" 
                         height="100" 
                         alt="Kondisi Sesudah">
                @else
                    -
                @endif
            </td>
        </tr>
    @endforeach
    <tr><td colspan="10"></td></tr>

    <!-- Detail Section -->
    <tr class="section-header">
        <td colspan="10">Detail Pemeriksaan</td>
    </tr>
    <tr class="table-header">
        <th colspan="2">Item</th>
        <th colspan="8">Keterangan</th>
    </tr>
    @foreach($flmData as $index => $item)
        <tr>
            <td colspan="2">Kondisi Awal #{{ $index + 1 }}</td>
            <td colspan="8">{{ $item->kondisi_awal }}</td>
        </tr>
        <tr>
            <td colspan="2">Kondisi Akhir #{{ $index + 1 }}</td>
            <td colspan="8">{{ $item->kondisi_akhir }}</td>
        </tr>
        <tr>
            <td colspan="2">Catatan #{{ $index + 1 }}</td>
            <td colspan="8">{{ $item->catatan ?? '-' }}</td>
        </tr>
        @if (!$loop->last)
            <tr><td colspan="10"></td></tr>
        @endif
    @endforeach
    <tr><td colspan="10"></td></tr>

    <!-- Footer -->
    <tr><td colspan="10"></td></tr>
    <tr class="footer">
        <td colspan="10">FLM ID: {{ $flmData->first()->flm_id }} | Dibuat pada: {{ $flmData->first()->created_at->format('d/m/Y H:i') }}</td>
    </tr>
</table> 