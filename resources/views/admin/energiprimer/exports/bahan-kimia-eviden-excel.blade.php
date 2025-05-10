<table>
    <thead>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 14px; font-weight: bold;">
                Eviden Bahan Kimia
            </th>
        </tr>
        <tr>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 100px;">Tanggal</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Unit</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Jenis Bahan</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 150px;">Tipe File</th>
            <th style="font-weight: bold; background-color: #f2f2f2; width: 300px;">Link Eviden</th>
        </tr>
    </thead>
    <tbody>
        @foreach($bahanKimia->sortBy('tanggal') as $item)
            @if($item->evidence)
            @php
                $extension = pathinfo(Storage::path($item->evidence), PATHINFO_EXTENSION);
                $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'bmp']);
            @endphp
            <tr>
                <td>{{ $item->tanggal->format('Y-m-d') }}</td>
                <td>{{ $item->unit->name }}</td>
                <td>{{ $item->jenis_bahan }}</td>
                <td>{{ strtoupper($extension) }}</td>
                <td>
                    @if($isImage)
                        <img src="{{ Storage::url($item->evidence) }}" alt="Eviden {{ $item->tanggal->format('Y-m-d') }}" style="max-width: 300px; max-height: 200px;">
                    @else
                        <a href="{{ Storage::url($item->evidence) }}" target="_blank">
                            Lihat Dokumen
                        </a>
                    @endif
                </td>
            </tr>
            @endif
        @endforeach
    </tbody>
</table> 