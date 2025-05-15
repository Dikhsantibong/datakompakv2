@foreach($powerPlants as $powerPlant)
    @if(!str_contains(strtolower($powerPlant->name), 'moramo') && !str_contains(strtolower($powerPlant->name), 'baruta'))
    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
        <div class="p-4 bg-gradient-to-r from-blue-50 to-white border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $powerPlant->name }}</h3>
                </div>
                <div class="flex items-center gap-4">
                    @if(str_starts_with(strtoupper($powerPlant->name), 'PLTM'))
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">Inflow:</span>
                            <span class="text-sm text-gray-900">{{ $powerPlant->inflow ?? '-' }} liter/detik</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">TMA:</span>
                            <span class="text-sm text-gray-900">{{ $powerPlant->tma ?? '-' }} mdpl</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700">HOP:</span>
                            <span class="text-sm text-gray-900">{{ $powerPlant->hop ?? '-' }} hari</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Terpasang (kW)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SILM/SLO</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP Performance Test</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (kW)</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($powerPlant->machines as $index => $machine)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">{{ $index + 1 }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 border-r border-gray-200">{{ $machine->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">
                                {{ $machine->log_time ? \Carbon\Carbon::parse($machine->log_time)->format('H:i') : '-' }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">{{ $machine->daya_terpasang ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">{{ $machine->silm_slo ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">{{ $machine->dmp_performance ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">{{ $machine->kw ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">
                                @if($machine->status)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $machine->status === 'OPS' ? 'bg-green-100 text-green-800' :
                                           ($machine->status === 'RSH' ? 'bg-yellow-100 text-yellow-800' :
                                           ($machine->status === 'FO' ? 'bg-red-100 text-red-800' :
                                           ($machine->status === 'MO' ? 'bg-orange-100 text-orange-800' :
                                           ($machine->status === 'P0' ? 'bg-blue-100 text-blue-800' :
                                           ($machine->status === 'MB' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))))) }}">
                                        {{ $machine->status }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-500">{{ $machine->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-8 text-center text-sm text-gray-500">
                                Tidak ada data mesin untuk unit ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
@endforeach 