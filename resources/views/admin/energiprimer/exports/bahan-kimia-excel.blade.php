<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center; font-size: 16px; font-weight: bold;">
                Data Bahan Kimia
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">
                PT PLN Nusantara Power
            </th>
        </tr>
        @if(request()->has('unit_id') || request()->has('jenis_bahan') || request()->has('start_date') || request()->has('end_date'))
            <tr>
                <th colspan="7">
                    Filter:
                    @if(request('unit_id'))
                        Unit: {{ $units->find(request('unit_id'))->name }},
                    @endif
                    @if(request('jenis_bahan'))
                        Bahan: {{ request('jenis_bahan') }},
                    @endif
                    @if(request('start_date'))
                        Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                        @if(request('end_date'))
                            - {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                        @endif
                    @endif
                </th>
            </tr>
        @endif
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2;">Tanggal</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Unit</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Jenis Bahan</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Saldo Awal</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Penerimaan</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Pemakaian</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bahanKimia as $item)
            <tr>
                <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                <td>{{ $item->unit->name }}</td>
                <td>{{ $item->jenis_bahan }}</td>
                <td>{{ number_format($item->saldo_awal, 2) }}</td>
                <td>{{ number_format($item->penerimaan, 2) }}</td>
                <td>{{ number_format($item->pemakaian, 2) }}</td>
                <td>{{ number_format($item->saldo_akhir, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table> 