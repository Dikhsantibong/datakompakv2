@if($powerPlants->isEmpty())
    <div class="flex items-center justify-center py-12">
        <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 14h.01M5.05 4.05A9 9 0 1119.95 4.05 9 9 0 015.05 4.05zM12 9a1 1 0 110-2 1 1 0 010 2zm0 0v1"/>
            </svg>
            <p class="mt-4 text-gray-500">Tidak ada data untuk ditampilkan</p>
        </div>
    </div>
@else
    <div class="space-y-8">
        @foreach($powerPlants as $powerPlant)
            @if(!str_contains(strtolower($powerPlant->name), 'moramo') && !str_contains(strtolower($powerPlant->name), 'baruta'))
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
                <!-- Power Plant Header -->
                <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-90   0">{{ $powerPlant->name }}</h2>
                            <p class="text-sm text-gray-500 mt-1">
                                Data untuk tanggal: {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                            </p>
                        </div>
                        
                        <!-- Display power plant logs -->
                        <div class="flex items-center gap-4">
                            @if(str_starts_with(strtoupper($powerPlant->name), 'PLTM'))
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700">Inflow:</span>
                                    <span class="text-sm text-gray-900">{{ $powerPlant->inflow ?? '-' }}</span>
                                    <span class="text-sm text-gray-600">liter/detik</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700">TMA:</span>
                                    <span class="text-sm text-gray-900">{{ $powerPlant->tma ?? '-' }}</span>
                                    <span class="text-sm text-gray-600">mdpl</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-700">HOP:</span>
                                    <span class="text-sm text-gray-900">{{ $powerPlant->hop ?? '-' }}</span>
                                    <span class="text-sm text-gray-600">hari</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Machines Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider ">No</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Terpasang (kW)</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">DMN SILM/SLO</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">DMP Performance Test</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Beban Aktual (kW)</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">kVAR</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Cos φ</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                               
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($powerPlant->machines as $index => $machine)
                                @php
                                    $latestLog = $machine->getLatestLog($date);
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 border border-gray-200 text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        @if($latestLog)
                                            @if(isset($time))
                                                {{ \Carbon\Carbon::parse($time)->format('H:i') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($latestLog->time)->format('H:i') }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                  
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->daya_terpasang ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->silm_slo ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->dmp_performance ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->kw ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->kvar ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->cos_phi ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">
                                        {{ $machine->status ?? '-' }}
                                    </td>
                                    
                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200">
                                        {{ $machine->keterangan ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="px-4 py-8 text-center text-sm text-gray-500 border border-gray-200">
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
    </div>
@endif 