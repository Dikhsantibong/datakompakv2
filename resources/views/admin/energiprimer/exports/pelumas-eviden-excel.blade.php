<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center; font-size: 14px; font-weight: bold;">
                Eviden Pelumas
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 100px;">Tanggal</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Unit</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Jenis Pelumas</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 300px;">Link Document</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pelumas->sortBy('tanggal') as $item)
            @if($item->document)
            <tr>
                <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                <td>{{ $item->unit->name }}</td>
                <td>{{ $item->jenis_pelumas }}</td>
                <td>
                    <a href="{{ url(Storage::url('documents/pelumas/' . $item->document)) }}">
                        {{ $item->document }}
                    </a>
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table> 