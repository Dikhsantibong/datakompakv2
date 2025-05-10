{{-- Sheet Data Bahan Kimia --}}
<div class="sheet">
    <table>
        <thead>
            <tr>
                <th colspan="2" style="text-align: left; width: 15%;">
                    <img src="{{ $navlog_path }}" alt="PLN Logo" width="60" height="20">
                </th>
                <th colspan="5" style="text-align: center; font-size: 14px; font-weight: bold; width: 70%;">
                    Data Bahan Kimia
                </th>
                <th colspan="2" style="text-align: right; width: 15%;">
                    <img src="{{ $k3_path }}" alt="K3 Logo" width="60" height="20">
                </th>
            </tr>
            <tr>
                <th colspan="9" style="text-align: center;">
                    PT PLN Nusantara Power
                </th>
            </tr>
            @if(request()->has('unit_id') || request()->has('jenis_bahan') || request()->has('start_date') || request()->has('end_date'))
                <tr>
                    <th colspan="9">
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
                <th style="font-weight: bold; background-color: #f2f2f2; width: 100px;">Tanggal</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Unit</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Jenis Bahan</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 120px;">Saldo Awal</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 120px;">Penerimaan</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 120px;">Pemakaian</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 120px;">Saldo Akhir</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 200px;">Catatan</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 100px;">Eviden</th>
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
                    <td>{{ $item->catatan_transaksi }}</td>
                    <td>{{ $item->evidence ? 'Ada' : 'Tidak ada' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Sheet Eviden Bahan Kimia --}}
{{-- <div class="sheet">
    <table>
        <thead>
            <tr>
                <th colspan="4" style="text-align: center; font-size: 14px; font-weight: bold;">
                    Eviden Bahan Kimia
                </th>
            </tr>
            <tr>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 100px;">Tanggal</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Unit</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Jenis Bahan</th>
                <th style="font-weight: bold; background-color: #f2f2f2; width: 300px;">Eviden</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bahanKimia->sortBy('tanggal') as $item)
                @if($item->evidence)
                <tr>
                    <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                    <td>{{ $item->unit->name }}</td>
                    <td>{{ $item->jenis_bahan }}</td>
                    <td>
                        <img src="{{ Storage::url($item->evidence) }}" alt="Eviden {{ $item->tanggal->format('Y-m-d') }}" width="300">
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</div>  --}}