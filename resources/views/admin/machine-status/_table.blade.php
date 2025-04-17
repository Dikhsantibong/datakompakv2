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
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-600">
                Data untuk tanggal: 
                <span class="font-medium text-gray-900">
                    {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                </span>
            </p>
        </div>
        
        @foreach($powerPlants as $powerPlant)
            @unless($powerPlant->name === 'UP KENDARI')
                <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden border border-gray-100">
                    <!-- Power Plant Header -->
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <h2 class="text-lg font-semibold text-gray-900">
                                        {{ $powerPlant->name }}
                                    </h2>
                                    @php
                                        $lastUpdate = $logs->whereIn('machine_id', $powerPlant->machines->pluck('id'))
                                            ->max('updated_at');
                                        $formattedLastUpdate = $lastUpdate 
                                            ? \Carbon\Carbon::parse($lastUpdate)->format('d/m/Y H:i:s')
                                            : '-';
                                    @endphp
                                    
                                </div>
                                <div class="text-sm text-gray-500">
                                    <span class="font-medium">Update Terakhir:</span>
                                    <span>{{ $formattedLastUpdate }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Overview -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mt-6">
                            @php
                                $filteredLogs = $logs->filter(function($log) use ($date) {
                                    return $log->tanggal->format('Y-m-d') === $date;
                                });

                                $machineLogs = $filteredLogs->whereIn('machine_id', $powerPlant->machines->pluck('id'));

                                $totalDMP = $machineLogs->sum(fn($log) => (float) $log->dmp);
                                $totalDMN = $machineLogs->sum(fn($log) => (float) $log->dmn);
                                
                                $totalBeban = $machineLogs->sum(function($log) {
                                    if ($log->status === 'Operasi' || $log->status === 'OPS') {
                                        return (float) $log->load_value;
                                    }
                                    return 0;
                                });

                                $hopValue = \App\Models\UnitOperationHour::where('power_plant_id', $powerPlant->id)
                                    ->whereDate('tanggal', $date)
                                    ->value('hop_value') ?? 0;
                                
                                $hopStatus = $hopValue >= 15 ? 'aman' : 'siaga';
                                $hopClass = $hopStatus === 'aman' ? 'text-green-600' : 'text-red-600';
                            @endphp

                            <!-- DMN Card -->
                            <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">DMN</p>
                                    <div class="bg-blue-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalDMN, 2) }} <span class="text-sm font-normal text-gray-500">MW</span></p>
                            </div>

                            <!-- DMP Card -->
                            <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">DMP</p>
                                    <div class="bg-green-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalDMP, 2) }} <span class="text-sm font-normal text-gray-500">MW</span></p>
                            </div>

                            <!-- Total Beban Card -->
                            <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">Total Beban</p>
                                    <div class="bg-purple-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($totalBeban, 2) }} <span class="text-sm font-normal text-gray-500">MW</span></p>
                            </div>

                            <!-- Derating Card -->
                            <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">Derating</p>
                                    <div class="bg-red-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ number_format($totalDMN - $totalDMP, 2) }} 
                                        <span class="text-sm font-normal text-gray-500">MW</span>
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        @if($totalDMN > 0)
                                            ({{ number_format((($totalDMN - $totalDMP) / $totalDMN) * 100, 2) }}%)
                                        @else
                                            (0%)
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- HOP Card -->
                            <div class="bg-white rounded-lg border p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-500">
                                        @if(str_starts_with(trim(strtoupper($powerPlant->name)), 'PLTM '))
                                            Total Inflow
                                        @else
                                            Total HOP
                                        @endif
                                    </p>
                                    <div class="bg-orange-100 rounded-full p-2">
                                        <svg class="w-4 h-4 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                <p class="mt-2 text-2xl font-semibold text-gray-900">
                                    {{ number_format($hopValue, 1) }}
                                    <span class="text-sm font-normal text-gray-500">
                                        @if(str_starts_with(trim(strtoupper($powerPlant->name)), 'PLTM '))
                                            l/s
                                        @else
                                            Hari
                                        @endif
                                    </span>
                                </p>
                                @unless(str_starts_with(trim(strtoupper($powerPlant->name)), 'PLTM '))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $hopStatus === 'aman' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} mt-2">
                                        {{ ucfirst($hopStatus) }}
                                    </span>
                                @endunless
                            </div>
                        </div>

                        <!-- Machine Status Summary -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-4 mt-6">
                            @php
                                $machineCount = $powerPlant->machines->count();
                                
                                $latestLogs = $machineLogs
                                    ->groupBy('machine_id')
                                    ->map(function ($machineLogs) {
                                        return $machineLogs->sortByDesc('input_time')->first();
                                    });
                                
                                $rshCount = $latestLogs->where('status', 'RSH')->count();
                                $foCount = $latestLogs->where('status', 'FO')->count();
                                $moCount = $latestLogs->where('status', 'MO')->count();
                                $p0Count = $latestLogs->where('status', 'P0')->count();
                                $mbCount = $latestLogs->where('status', 'MB')->count();
                                $opsCount = $latestLogs->where('status', 'OPS')->count();
                            @endphp

                            <div class="bg-white rounded-lg border p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-gray-500">Total Mesin</p>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $machineCount }}</p>
                            </div>

                            <div class="bg-emerald-50 rounded-lg border border-emerald-100 p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-emerald-600">RSH</p>
                                <p class="mt-1 text-2xl font-semibold text-emerald-700">{{ $rshCount }}</p>
                            </div>

                            <div class="bg-red-50 rounded-lg border border-red-100 p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-red-600">FO</p>
                                <p class="mt-1 text-2xl font-semibold text-red-700">{{ $foCount }}</p>
                            </div>

                            <div class="bg-amber-50 rounded-lg border border-amber-100 p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-amber-600">P0</p>
                                <p class="mt-1 text-2xl font-semibold text-amber-700">{{ $p0Count }}</p>
                            </div>

                            <div class="bg-sky-50 rounded-lg border border-sky-100 p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-sky-600">MO</p>
                                <p class="mt-1 text-2xl font-semibold text-sky-700">{{ $moCount }}</p>
                            </div>

                            <div class="bg-violet-50 rounded-lg border border-violet-100 p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-violet-600">OPS</p>
                                <p class="mt-1 text-2xl font-semibold text-violet-700">{{ $opsCount }}</p>
                            </div>

                            <div class="bg-gray-50 rounded-lg border border-gray-100 p-3 hover:shadow-md transition-shadow">
                                <p class="text-sm font-medium text-gray-600">MB</p>
                                <p class="mt-1 text-2xl font-semibold text-gray-700">{{ $mbCount }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Machines Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Mampu Slim (MW)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Mampu Pasok (MW)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (MW)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu Input</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($powerPlant->machines as $index => $machine)
                                    @php
                                        $log = $machineLogs->where('machine_id', $machine->id)
                                            ->sortByDesc('input_time')
                                            ->first();
                                        
                                        $status = $log?->status ?? '-';
                                        $statusClass = match($status) {
                                            'RSH' => 'bg-green-100 text-green-800',
                                            'FO' => 'bg-red-100 text-red-800',
                                            'MO' => 'bg-blue-100 text-blue-800',
                                            'P0' => 'bg-orange-100 text-orange-800',
                                            'MB' => 'bg-violet-100 text-violet-800',
                                            'OPS' => 'bg-gray-100 text-gray-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200">{{ $index + 1 }}</td>
                                        <td class="px-4 py-3 border border-gray-200">
                                            <div class="text-sm font-medium text-gray-900 ">{{ $machine->name }}</div>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">{{ $log?->dmn ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">{{ $log?->dmp ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200 text-center">{{ $log?->load_value ?? '-' }}</td>
                                        <td class="px-4 py-3 border border-gray-200">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200">{{ $log?->deskripsi ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200">
                                            @if($log && $log->input_time)
                                                {{ \Carbon\Carbon::parse($log->input_time)->format('H:i:s') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 border border-gray-200">
                                            Tidak ada data mesin untuk unit ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endunless
        @endforeach
    </div>
@endif 

<style>
/* Highest specificity selectors */
table.min-w-full td[data-content-type="equipment"],
table.min-w-full td[data-content-type="description"],
table.min-w-full td[data-content-type="kronologi"],
table.min-w-full td[data-content-type="action-plan"],
table.min-w-full td[data-content-type="progress"],
table.min-w-full td[data-content-type] div,
table.min-w-full td[data-content-type] div div {
    text-align: left !important;
    justify-content: flex-start !important;
}

/* Additional specificity for nested elements */
.table-responsive table.min-w-full td[data-content-type] *,
.table-responsive table.min-w-full td[data-content-type] div *,
.table-responsive table.min-w-full td[data-content-type] div div * {
    text-align: left !important;
    justify-content: flex-start !important;
}

/* Force left alignment with max specificity */
body .table-responsive table.min-w-full td[data-content-type],
body .table-responsive table.min-w-full td[data-content-type] > div,
body .table-responsive table.min-w-full td[data-content-type] > div > div {
    text-align: left !important;
    justify-content: flex-start !important;
}
</style> 