<table>
    <thead>
        <tr>
            <th colspan="2" style="text-align: left;">
                <img src="{{ $navlog_path }}" alt="PLN Logo" style="height: 30px; width: auto;">
            </th>
            <th colspan="3" style="text-align: center; font-size: 16px; font-weight: bold;">
                Data Pelumas
            </th>
            <th colspan="2" style="text-align: right;">
                <img src="{{ $k3_path }}" alt="K3 Logo" style="height: 30px; width: auto;">
            </th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center;">
                PT PLN Nusantara Power
            </th>
        </tr>
        @if(request()->has('unit_id') || request()->has('jenis_pelumas') || request()->has('start_date') || request()->has('end_date'))
            <tr>
                <th colspan="7">
                    Filter:
                    @if(request('unit_id'))
                        Unit: {{ $units->find(request('unit_id'))->name }},
                    @endif
                    @if(request('jenis_pelumas'))
                        Pelumas: {{ request('jenis_pelumas') }},
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
            <th style="font-weight: bold; background-color: #f2f2f2;">Jenis Pelumas</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Saldo Awal</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Penerimaan</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Pemakaian</th>
            <th style="font-weight: bold; background-color: #f2f2f2;">Saldo Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pelumas as $item)
            <tr>
                <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                <td>{{ $item->unit->name }}</td>
                <td>{{ $item->jenis_pelumas }}</td>
                <td>{{ number_format($item->saldo_awal, 2) }}</td>
                <td>{{ number_format($item->penerimaan, 2) }}</td>
                <td>{{ number_format($item->pemakaian, 2) }}</td>
                <td>{{ number_format($item->saldo_akhir, 2) }}</td>
            </tr>
        @endforeach
    </tbody>
</table> 