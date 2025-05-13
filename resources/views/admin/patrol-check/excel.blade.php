<?php
?><table style="width:100%; border-collapse:collapse; font-family:Arial, sans-serif; font-size:12px;">
    <!-- Header -->
    <tr>
        <!-- Logo kiri atas -->
        <td rowspan="4" style="width:80px; text-align:left; vertical-align:top; border:none;"></td>
        <!-- Judul di tengah atas -->
        <td colspan="6" style="text-align:center; font-size:20px; font-weight:bold; color:#333; border:none;">Patrol Check KIT</td>
        <!-- Logo kanan atas -->
        <td rowspan="4" style="width:120px; text-align:right; vertical-align:top; border:none;"></td>
    </tr>
    <tr>
        <!-- Nama user di kanan atas -->
        <td colspan="6" style="text-align:right; font-size:14px; color:#406a7d; border:none; font-family:Arial, sans-serif; font-weight:normal; white-space:nowrap;">
            {{ optional($patrol->creator)->name }}
        </td>
    </tr>
    <tr>
        <!-- Shift dan waktu di tengah -->
        <td colspan="6" style="text-align:center; font-size:14px; border:none;">
            Shift: {{ $patrol->shift }} | Waktu: {{ $patrol->time }}
        </td>
    </tr>
    <tr>
        <!-- Tanggal di tengah -->
        <td colspan="6" style="text-align:center; font-size:13px; color:#666; border:none;">
            Tanggal: {{ optional($patrol->created_at)->format('d F Y') }}
        </td>
    </tr>
    <tr><td colspan="8" style="border:none;"></td></tr>

    <!-- Section Title: Kondisi Umum Peralatan Bantu -->
    <tr>
        <td colspan="8" style="font-size:16px; font-weight:bold; color:#333; border-bottom:2px solid #009BB9; padding:8px 0 5px 0; background:#fff;">Kondisi Umum Peralatan Bantu</td>
    </tr>
    <tr style="background-color:#009BB9; color:#fff; font-weight:bold;">
        <th style="border:1px solid #ddd; padding:8px;">No</th>
        <th style="border:1px solid #ddd; padding:8px;">Sistem</th>
        <th style="border:1px solid #ddd; padding:8px;">Kondisi</th>
        <th style="border:1px solid #ddd; padding:8px;">Keterangan</th>
        <th style="border:1px solid #ddd; padding:8px;">Status</th>
        <th style="border:1px solid #ddd; padding:8px;">Dibuat Oleh</th>
        <th style="border:1px solid #ddd; padding:8px;">Waktu Dibuat</th>
        <th style="border:1px solid #ddd; padding:8px;"></th>
    </tr>
    @foreach($patrol->condition_systems as $i => $row)
    <tr>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $i+1 }}</td>
        <td style="border:1px solid #ddd; padding:8px;">{{ $row['system'] }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center; color:{{ $row['condition'] === 'normal' ? '#28a745' : '#dc3545' }}; font-weight:bold;">{{ ucfirst($row['condition']) }}</td>
        <td style="border:1px solid #ddd; padding:8px;">{{ $row['notes'] ?? '-' }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ ucfirst($patrol->status) }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $patrol->creator->name ?? 'System' }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $patrol->created_at->format('H:i') }}</td>
        <td style="border:1px solid #ddd; padding:8px;"></td>
    </tr>
    @endforeach

    <!-- Section Title: Data Kondisi Alat Bantu -->
    @if(count($patrol->abnormal_equipments) > 0)
    <tr>
        <td colspan="8" style="font-size:16px; font-weight:bold; color:#333; border-bottom:2px solid #009BB9; padding:8px 0 5px 0; background:#fff;">Data Kondisi Alat Bantu</td>
    </tr>
    <tr style="background-color:#009BB9; color:#fff; font-weight:bold;">
        <th style="border:1px solid #ddd; padding:8px;">No</th>
        <th style="border:1px solid #ddd; padding:8px;">Peralatan</th>
        <th style="border:1px solid #ddd; padding:8px;">Kondisi Awal</th>
        <th style="border:1px solid #ddd; padding:8px;">FLM</th>
        <th style="border:1px solid #ddd; padding:8px;">SR</th>
        <th style="border:1px solid #ddd; padding:8px;">Lainnya</th>
        <th style="border:1px solid #ddd; padding:8px;">Kondisi Akhir</th>
        <th style="border:1px solid #ddd; padding:8px;">Keterangan</th>
    </tr>
    @foreach($patrol->abnormal_equipments as $i => $row)
    <tr>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $i+1 }}</td>
        <td style="border:1px solid #ddd; padding:8px;">{{ $row['equipment'] }}</td>
        <td style="border:1px solid #ddd; padding:8px;">{{ $row['condition'] }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $row['flm'] ? 'Ya' : '-' }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $row['sr'] ? 'Ya' : '-' }}</td>
        <td style="border:1px solid #ddd; padding:8px; text-align:center;">{{ $row['other'] ? 'Ya' : '-' }}</td>
        <td style="border:1px solid #ddd; padding:8px;">{{ $patrol->condition_after[$i]['condition'] ?? '-' }}</td>
        <td style="border:1px solid #ddd; padding:8px;">{{ $patrol->condition_after[$i]['notes'] ?? '-' }}</td>
    </tr>
    @endforeach
    @endif
</table> 