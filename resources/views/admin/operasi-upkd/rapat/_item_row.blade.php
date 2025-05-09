@if($item->section->code === 'H')
    <tr>
        <td class="px-6 py-4">{{ $item->uraian }}</td>
        <td class="px-6 py-4">{{ $item->detail }}</td>
        <td class="px-6 py-4">{{ $item->rapatDetail->jadwal->format('d M Y H:i') }}</td>
        <td class="px-6 py-4">{{ ucfirst($item->rapatDetail->mode) }}</td>
        <td class="px-6 py-4">{{ $item->rapatDetail->resume }}</td>
        <td class="px-6 py-4">
            @if($item->rapatDetail->notulen_path)
                <a href="{{ Storage::url($item->rapatDetail->notulen_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-file-alt mr-1"></i> Lihat
                </a>
            @else
                <span class="text-gray-400">Tidak ada</span>
            @endif
        </td>
        <td class="px-6 py-4">
            @if($item->rapatDetail->eviden_path)
                <a href="{{ Storage::url($item->rapatDetail->eviden_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-file mr-1"></i> Lihat
                </a>
            @else
                <span class="text-gray-400">Tidak ada</span>
            @endif
        </td>
        <td class="px-6 py-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.operasi-upkd.rapat.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.operasi-upkd.rapat.destroy', $item->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@else
    <tr>
        <td class="px-6 py-4">{{ $item->order_number }}</td>
        <td class="px-6 py-4">{{ $item->uraian }}</td>
        <td class="px-6 py-4">{{ $item->detail }}</td>
        <td class="px-6 py-4">{{ $item->pic }}</td>
        <td class="px-6 py-4">
            <span class="px-2 py-1 text-xs font-medium rounded-full 
                {{ $item->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                {{ $item->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $item->status === 'pending' ? 'bg-gray-100 text-gray-800' : '' }}">
                {{ ucfirst($item->status) }}
            </span>
        </td>
        <td class="px-6 py-4">{{ $item->keterangan }}</td>
        <td class="px-6 py-4">
            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.operasi-upkd.rapat.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.operasi-upkd.rapat.destroy', $item->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
        </td>
    </tr>
@endif 