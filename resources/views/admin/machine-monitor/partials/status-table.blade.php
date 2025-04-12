<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Input</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMN-SLO</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP-realisasi-kesiapan KIT</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">{{ $log->machine->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $log->tanggal->format('d M Y') }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">
                        <div class="font-medium text-gray-900">
                            {{ $log->input_time ? $log->input_time->format('H:i:s') : 'N/A' }}
                        </div>
                        <div class="text-gray-500">
                            {{ $log->created_at->diffForHumans() }}
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        {{ $log->status === 'START' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $log->status === 'STOP' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $log->status === 'PARALLEL' ? 'bg-blue-100 text-blue-800' : '' }}">
                        {{ $log->status }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->dmn, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->dmp, 2) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->load_value, 2) }}%</td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                    Tidak ada data untuk waktu yang dipilih
                </td>
            </tr>
        @endforelse
    </tbody>
</table> 