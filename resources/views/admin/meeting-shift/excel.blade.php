<table>
    <!-- Logo spacing row -->
    <tr><td colspan="6" style="height:20px;"></td></tr>
    <!-- Baris kosong untuk username -->
    <tr><td colspan="6"></td></tr>
    <tr><td colspan="6"></td></tr>
    <!-- Header utama -->
    <tr class="main-header">
        <td colspan="6" style="font-size:16px; font-weight:bold; text-align:center; background:#D1D5DB; height:30px;">Meeting dan Mutasi Shift Operator - {{ $meetingShift->tanggal->format('d F Y') }} - Shift {{ $meetingShift->current_shift }}</td>
    </tr>
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- Machine Status Section -->
    <tr class="section-header">
        <td colspan="6" style="font-size:13px; font-weight:bold; background:#E2E8F0; border:2px solid #000; height:25px;">Status Mesin</td>
    </tr>
    <tr class="table-header">
        <th style="background:#F8FAFC; border:1px solid #000;">No</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Unit</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Nama Mesin</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Status</th>
        <th colspan="2" style="background:#F8FAFC; border:1px solid #000;">Keterangan</th>
    </tr>
    @foreach($meetingShift->machineStatuses as $index => $machineStatus)
    <tr>
        <td style="border:1px solid #000;">{{ $index + 1 }}</td>
        <td style="border:1px solid #000;">{{ $machineStatus->machine->powerPlant->name ?? '-' }}</td>
        <td style="border:1px solid #000;">{{ $machineStatus->machine->name }}</td>
        <td style="border:1px solid #000;">{{ implode(', ', json_decode($machineStatus->status)) }}</td>
        <td colspan="2" style="border:1px solid #000;">{{ $machineStatus->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- Auxiliary Equipment Section -->
    <tr class="section-header">
        <td colspan="6" style="font-size:13px; font-weight:bold; background:#E2E8F0; border:2px solid #000; height:25px;">Alat Bantu</td>
    </tr>
    <tr class="table-header">
        <th style="background:#F8FAFC; border:1px solid #000;">No</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Nama Alat</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Status</th>
        <th colspan="3" style="background:#F8FAFC; border:1px solid #000;">Keterangan</th>
    </tr>
    @foreach($meetingShift->auxiliaryEquipments as $index => $equipment)
    <tr>
        <td style="border:1px solid #000;">{{ $index + 1 }}</td>
        <td style="border:1px solid #000;">{{ $equipment->name }}</td>
        <td style="border:1px solid #000;">{{ implode(', ', json_decode($equipment->status)) }}</td>
        <td colspan="3" style="border:1px solid #000;">{{ $equipment->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- Resources Section -->
    <tr class="section-header">
        <td colspan="6" style="font-size:13px; font-weight:bold; background:#E2E8F0; border:2px solid #000; height:25px;">Resources</td>
    </tr>
    <tr class="table-header">
        <th style="background:#F8FAFC; border:1px solid #000;">No</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Nama Resource</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Kategori</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Status</th>
        <th colspan="2" style="background:#F8FAFC; border:1px solid #000;">Keterangan</th>
    </tr>
    @foreach($meetingShift->resources as $index => $resource)
    <tr>
        <td style="border:1px solid #000;">{{ $index + 1 }}</td>
        <td style="border:1px solid #000;">{{ $resource->name }}</td>
        <td style="border:1px solid #000;">{{ $resource->category }}</td>
        <td style="border:1px solid #000;">{{ $resource->status }}</td>
        <td colspan="2" style="border:1px solid #000;">{{ $resource->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- K3L Section -->
    <tr class="section-header">
        <td colspan="6" style="font-size:13px; font-weight:bold; background:#E2E8F0; border:2px solid #000; height:25px;">K3L</td>
    </tr>
    <tr class="table-header">
        <th style="background:#F8FAFC; border:1px solid #000;">No</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Tipe</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Uraian</th>
        <th colspan="3" style="background:#F8FAFC; border:1px solid #000;">Saran</th>
    </tr>
    @foreach($meetingShift->k3ls as $index => $k3l)
    <tr>
        <td style="border:1px solid #000;">{{ $index + 1 }}</td>
        <td style="border:1px solid #000;">{{ ucfirst(str_replace('_', ' ', $k3l->type)) }}</td>
        <td style="border:1px solid #000;">{{ $k3l->uraian }}</td>
        <td colspan="3" style="border:1px solid #000;">{{ $k3l->saran }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- Notes Section -->
    <tr class="section-header">
        <td colspan="6" style="font-size:13px; font-weight:bold; background:#E2E8F0; border:2px solid #000; height:25px;">Catatan</td>
    </tr>
    <tr class="table-header">
        <th colspan="2" style="background:#F8FAFC; border:1px solid #000;">Tipe Catatan</th>
        <th colspan="4" style="background:#F8FAFC; border:1px solid #000;">Isi</th>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #000;">Catatan Sistem</td>
        <td colspan="4" style="border:1px solid #000;">{{ $meetingShift->systemNote->content ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #000;">Catatan Umum</td>
        <td colspan="4" style="border:1px solid #000;">{{ $meetingShift->generalNote->content ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2" style="border:1px solid #000;">Resume</td>
        <td colspan="4" style="border:1px solid #000;">{{ $meetingShift->resume->content ?? '-' }}</td>
    </tr>
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- Attendance Section -->
    <tr class="section-header">
        <td colspan="6" style="font-size:13px; font-weight:bold; background:#E2E8F0; border:2px solid #000; height:25px;">Absensi</td>
    </tr>
    <tr class="table-header">
        <th style="background:#F8FAFC; border:1px solid #000;">No</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Nama</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Shift</th>
        <th style="background:#F8FAFC; border:1px solid #000;">Status</th>
        <th colspan="2" style="background:#F8FAFC; border:1px solid #000;">Keterangan</th>
    </tr>
    @foreach($meetingShift->attendances as $index => $attendance)
    <tr>
        <td style="border:1px solid #000;">{{ $index + 1 }}</td>
        <td style="border:1px solid #000;">{{ $attendance->nama }}</td>
        <td style="border:1px solid #000;">{{ $attendance->shift }}</td>
        <td style="border:1px solid #000;">{{ ucfirst($attendance->status) }}</td>
        <td colspan="2" style="border:1px solid #000;">{{ $attendance->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6" style="height:15px;"></td></tr>

    <!-- Signature Section -->
    <tr><td colspan="6" style="height:30px;"></td></tr>
    <tr>
        <td colspan="3" style="text-align:center; font-weight:bold; border:1px solid #000;">Dibuat Oleh</td>
        <td colspan="3" style="text-align:center; font-weight:bold; border:1px solid #000;">Diterima Oleh</td>
    </tr>
    <tr>
        <td colspan="3" style="height:80px; border:1px solid #000;"></td>
        <td colspan="3" style="height:80px; border:1px solid #000;"></td>
    </tr>

    <!-- Footer -->
    <tr><td colspan="6" style="height:15px;"></td></tr>
    <tr class="footer">
        <td colspan="6" style="font-size:11px; text-align:right; color:#555;">Dibuat oleh: {{ $meetingShift->creator->name }} | Tanggal: {{ $meetingShift->created_at->format('d/m/Y H:i') }}</td>
    </tr>
    <tr><td colspan="6"></td></tr>
    <tr><td colspan="6"></td></tr>
    <tr><td colspan="6"></td></tr>
</table> 