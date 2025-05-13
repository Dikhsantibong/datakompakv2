<table>
    <!-- Logo spacing row -->
    <tr>
        <td colspan="3"></td>
        <td></td>
        <td colspan="2"></td>
    </tr>
    <tr><td colspan="6"></td></tr>

    <!-- Header -->
    <tr class="main-header">
        <td colspan="6">Meeting dan Mutasi Shift Operator - {{ $meetingShift->tanggal->format('d F Y') }} - Shift {{ $meetingShift->current_shift }}</td>
    </tr>
    <tr><td colspan="6"></td></tr>

    <!-- Machine Status Section -->
    <tr class="section-header">
        <td colspan="6">Status Mesin</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Unit</th>
        <th>Nama Mesin</th>
        <th>Status</th>
        <th colspan="2">Keterangan</th>
    </tr>
    @foreach($meetingShift->machineStatuses as $index => $machineStatus)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $machineStatus->machine->powerPlant->name ?? '-' }}</td>
        <td>{{ $machineStatus->machine->name }}</td>
        <td>{{ implode(', ', json_decode($machineStatus->status)) }}</td>
        <td colspan="2">{{ $machineStatus->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6"></td></tr>

    <!-- Auxiliary Equipment Section -->
    <tr class="section-header">
        <td colspan="6">Alat Bantu</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Nama Alat</th>
        <th>Status</th>
        <th colspan="3">Keterangan</th>
    </tr>
    @foreach($meetingShift->auxiliaryEquipments as $index => $equipment)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $equipment->name }}</td>
        <td>{{ implode(', ', json_decode($equipment->status)) }}</td>
        <td colspan="3">{{ $equipment->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6"></td></tr>

    <!-- Resources Section -->
    <tr class="section-header">
        <td colspan="6">Resources</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Nama Resource</th>
        <th>Kategori</th>
        <th>Status</th>
        <th colspan="2">Keterangan</th>
    </tr>
    @foreach($meetingShift->resources as $index => $resource)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $resource->name }}</td>
        <td>{{ $resource->category }}</td>
        <td>{{ $resource->status }}</td>
        <td colspan="2">{{ $resource->keterangan ?? '-' }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6"></td></tr>

    <!-- K3L Section -->
    <tr class="section-header">
        <td colspan="6">K3L</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Tipe</th>
        <th>Uraian</th>
        <th colspan="3">Saran</th>
    </tr>
    @foreach($meetingShift->k3ls as $index => $k3l)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ ucfirst(str_replace('_', ' ', $k3l->type)) }}</td>
        <td>{{ $k3l->uraian }}</td>
        <td colspan="3">{{ $k3l->saran }}</td>
    </tr>
    @endforeach
    <tr><td colspan="6"></td></tr>

    <!-- Notes Section -->
    <tr class="section-header">
        <td colspan="6">Catatan</td>
    </tr>
    <tr class="table-header">
        <th colspan="2">Tipe Catatan</th>
        <th colspan="4">Isi</th>
    </tr>
    <tr>
        <td colspan="2">Catatan Sistem</td>
        <td colspan="4">{{ $meetingShift->systemNote->content ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2">Catatan Umum</td>
        <td colspan="4">{{ $meetingShift->generalNote->content ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2">Resume</td>
        <td colspan="4">{{ $meetingShift->resume->content ?? '-' }}</td>
    </tr>
    <tr><td colspan="6"></td></tr>

    <!-- Attendance Section -->
    <tr class="section-header">
        <td colspan="6">Absensi</td>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Nama</th>
        <th>Shift</th>
        <th>Status</th>
        <th colspan="2">Keterangan</th>
    </tr>
    @foreach($meetingShift->attendances as $index => $attendance)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $attendance->nama }}</td>
        <td>{{ $attendance->shift }}</td>
        <td>{{ ucfirst($attendance->status) }}</td>
        <td colspan="2">{{ $attendance->keterangan ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Footer -->
    <tr><td colspan="6"></td></tr>
    <tr class="footer">
        <td colspan="6">Dibuat oleh: {{ $meetingShift->creator->name }} | Tanggal: {{ $meetingShift->created_at->format('d/m/Y H:i') }}</td>
    </tr>
</table> 