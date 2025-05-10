<table>
    <!-- Logo spacing row -->
    <tr>
        <td colspan="3"></td>
        <td></td>
        <td colspan="3"></td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- Header -->
    <tr class="main-header">
        <td colspan="7">Data Engine Report - {{ date('d F Y', strtotime($date)) }}</td>
    </tr>
    <tr><td colspan="7"></td></tr>

    <!-- Engine Data Section -->
    <tr class="section-header">
        <td colspan="7">Data Operasional Mesin</td>
    </tr>

    @foreach($powerPlants as $powerPlant)
    @if(!str_contains(strtolower($powerPlant->name), 'moramo') && !str_contains(strtolower($powerPlant->name), 'baruta'))
    <!-- Power Plant Header -->
    <tr class="table-header">
        <th colspan="7">{{ $powerPlant->name }}</th>
    </tr>
    <tr class="table-header">
        <th>No</th>
        <th>Mesin</th>
        <th>Status</th>
        <th>Beban (kW)</th>
        <th>kVAR</th>
        <th>Cos φ</th>
        <th>Keterangan</th>
    </tr>
    @php $no = 1; @endphp
    @foreach($powerPlant->machines as $machine)
    <tr>
        <td>{{ $no++ }}</td>
        <td>{{ $machine->name }}</td>
        <td>{{ $machine->status ?? '-' }}</td>
        <td>{{ $machine->kw ?? '-' }}</td>
        <td>{{ $machine->kvar ?? '-' }}</td>
        <td>{{ $machine->cos_phi ?? '-' }}</td>
        <td>{{ $machine->keterangan ?? '-' }}</td>
    </tr>
    @endforeach

    <!-- Power Plant Data -->
    <tr>
        <td colspan="2"><strong>HOP:</strong></td>
        <td colspan="5">{{ $powerPlant->hop ?? '-' }}</td>
    </tr>
    @if(str_contains(strtolower($powerPlant->name), 'pltm'))
    <tr>
        <td colspan="2"><strong>TMA:</strong></td>
        <td colspan="5">{{ $powerPlant->tma ?? '-' }}</td>
    </tr>
    <tr>
        <td colspan="2"><strong>Inflow:</strong></td>
        <td colspan="5">{{ $powerPlant->inflow ?? '-' }}</td>
    </tr>
    @endif
    <tr><td colspan="7"></td></tr>
    @endif
    @endforeach

    <!-- Summary Section -->
    <tr class="section-header">
        <td colspan="7">Ringkasan</td>
    </tr>
    <tr class="table-header">
        <th colspan="3">Parameter</th>
        <th colspan="4">Nilai</th>
    </tr>
    @php
        $totalKw = 0;
        $totalMachines = 0;
        $activeMachines = 0;

        foreach($powerPlants as $powerPlant) {
            foreach($powerPlant->machines as $machine) {
                $totalKw += $machine->kw ?? 0;
                $totalMachines++;
                if($machine->status === 'active') {
                    $activeMachines++;
                }
            }
        }
    @endphp
    <tr>
        <td colspan="3">Total Beban</td>
        <td colspan="4">{{ number_format($totalKw, 2) }} kW</td>
    </tr>
    <tr>
        <td colspan="3">Mesin Aktif</td>
        <td colspan="4">{{ $activeMachines }} dari {{ $totalMachines }} Mesin</td>
    </tr>

    <!-- Footer -->
    <tr><td colspan="7"></td></tr>
    <tr class="footer">
        <td colspan="7">Dibuat oleh: {{ Auth::user()->name }} | Tanggal: {{ now()->format('d/m/Y H:i') }}</td>
    </tr>
</table> 