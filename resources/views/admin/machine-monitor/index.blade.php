@extends('layouts.app')

@section('title', 'Monitor Mesin')

@section('content')
<div class="flex h-screen bg-gray-100">
        @include('components.sidebar')

        <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle"
                            class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <!--  Menu Toggle Sidebar-->
                        <button id="desktop-menu-toggle"
                            class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                    <h1 class="text-xl font-semibold text-gray-900">Monitor Mesin</h1>
                    </div>
                    <div class="relative">
                        <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                            <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                                class="w-7 h-7 rounded-full mr-2">
                            <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                            <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                        </button>
                        <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex items-center pt-2">
                <x-admin-breadcrumb :breadcrumbs="[['name' => 'Monitor Mesin', 'url' => null]]" />
            </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6">
                <!-- Welcome Card -->
                {{-- <div class="rounded-lg shadow-sm p-4 mb-6 text-white relative welcome-card min-h-[200px] md:h-64">
                    <div class="absolute inset-0 bg-blue-500 opacity-50 rounded-lg"></div>
                    <div class="relative z-10">
                        <!-- Text Content -->
                        <div class="space-y-2 md:space-y-4">
                            <div style="overflow: hidden;">
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight typing-animation">
                                    Monitor Data Mesin Pembangkit
                                </h2>
                            </div>
                            <p class="text-sm md:text-lg font-medium fade-in">
                                PLN NUSANTARA POWER UNIT PEMBANGKITAN KENDARI
                            </p>
                            <div class="backdrop-blur-sm bg-white/30 rounded-lg p-3 fade-in">
                                <p class="text-xs md:text-base leading-relaxed">
                                    Platform monitoring status mesin secara real-time untuk analisis dan pengambilan keputusan yang lebih efektif.
                                </p>
                            </div>
            </div>

                        <!-- Logo - Hidden on mobile -->
                        <img src="{{ asset('logo/navlogo.png') }}" alt="Power Plant" class="hidden md:block absolute top-4 right-4 w-32 md:w-48 fade-in">
                    </div>
                </div> --}}

                <!-- Unit Filter -->
                @if(session('unit') === 'mysql')
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-filter mr-2 text-blue-600"></i>
                            Filter Unit
                        </h3>
                        <select id="unitFilter" name="unit_source" class="form-select rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Unit</option>
                            @foreach($powerPlants as $powerPlant)
                                <option value="{{ $powerPlant->unit_source }}" {{ request('unit_source') == $powerPlant->unit_source ? 'selected' : '' }}>
                                    {{ $powerPlant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif

                <!-- Status Indicators -->
                @php
                    $totalOPS = $statusCounts['OPS'] ?? 0;
                    $totalFO = $statusCounts['FO'] ?? 0;
                    $totalMO = $statusCounts['MO'] ?? 0;
                    $maxBeban = $maxBeban ?? 0;
                    $lastUpdate = $lastUpdate ?? null;
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-blue-600 mb-1"><i class="fas fa-cogs"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Total Mesin</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ $machines->count() }}</p>
                            <a href="{{ route('admin.machine-monitor.show') }}" class="text-blue-600 text-xs font-medium hover:underline">Lihat Data</a>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-indigo-600 mb-1"><i class="fas fa-industry"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Unit Pembangkit</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ $powerPlants->count() ?? '-' }}</p>
                            <a href="{{ route('admin.power-plants.index') }}" class="text-indigo-600 text-xs font-medium hover:underline">Lihat Unit</a>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-green-600 mb-1"><i class="fas fa-play-circle"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Mesin Aktif (OPS)</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ $totalOPS }}</p>
                            <span class="text-green-600 text-xs font-medium">Operational</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-red-600 mb-1"><i class="fas fa-exclamation-triangle"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Mesin Gangguan (FO)</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ $totalFO }}</p>
                            <span class="text-red-600 text-xs font-medium">Forced Outage</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-6">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-yellow-600 mb-1"><i class="fas fa-tools"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Maintenance (MO)</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ $totalMO }}</p>
                            <span class="text-yellow-600 text-xs font-medium">Maintenance</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-purple-600 mb-1"><i class="fas fa-bolt"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Beban Maksimum</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ number_format($maxBeban, 2) }} kW</p>
                            <span class="text-purple-600 text-xs font-medium">Peak Load</span>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-3 flex flex-col items-start">
                            <div class="text-2xl text-gray-600 mb-1"><i class="fas fa-clock"></i></div>
                            <h3 class="text-base font-semibold mb-0.5">Update Terakhir</h3>
                            <p class="text-gray-600 mb-1 text-base font-bold">{{ $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->format('d/m/Y') : '-' }}</p>
                            <span class="text-gray-600 text-xs font-medium">Last Update</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Diagram Status Mesin</h3>
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Rata-rata Beban per Mesin</h3>
                        <canvas id="avgBebanChart"></canvas>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">DMN/SLO per Mesin</h3>
                        <canvas id="dmnChart"></canvas>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">DMP/PT per Mesin</h3>
                        <canvas id="dmpChart"></canvas>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Tegangan (V) per Mesin</h3>
                        <canvas id="voltChart"></canvas>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Arus (A) per Mesin</h3>
                        <canvas id="ampChart"></canvas>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Tabel Data Mesin Kesiapan Kit</h3>
                    @foreach($powerPlants as $powerPlant)
                    <div class="bg-white rounded-lg shadow p-6 mb-4 unit-table">
                        <div class="overflow-auto">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex flex-col">
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $powerPlant->name }}</h2>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Update Terakhir:</span>
                                        <span id="last_update_{{ $powerPlant->id }}" class="ml-1">
                                            @php
                                                $lastUpdate = $latestLogs
                                                    ->where('machine.power_plant_id', $powerPlant->id)
                                                    ->max('date');
                                                echo $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->format('d/m/Y H:i') : '-';
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SILM/SLO</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP Test</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (kW)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php $machinesInUnit = $latestLogs->where('machine.power_plant_id', $powerPlant->id); @endphp
                                        @forelse($machinesInUnit as $i => $log)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $i+1 }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->machine->name }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->date ? \Carbon\Carbon::parse($log->date)->format('d/m/Y') : '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->time ? \Carbon\Carbon::parse($log->time)->format('H:i') : '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $log->status === 'OPS' ? 'bg-green-100 text-green-800' :
                                                       ($log->status === 'RSH' ? 'bg-yellow-100 text-yellow-800' :
                                                       ($log->status === 'FO' ? 'bg-red-100 text-red-800' :
                                                       ($log->status === 'MO' ? 'bg-orange-100 text-orange-800' :
                                                       ($log->status === 'P0' ? 'bg-blue-100 text-blue-800' :
                                                       ($log->status === 'MB' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))))) }}">
                                                    {{ $log->status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->silm_slo ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->dmp_performance ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->kw ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $log->keterangan ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="px-4 py-2 text-center text-gray-500">Tidak ada data mesin untuk unit ini</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    .welcome-card {
        background-size: cover;
        background-position: center;
        transition: background-image 1s ease-in-out;
        font-family: 'Poppins', sans-serif;
    }

    .typing-animation {
        overflow: hidden;
        white-space: nowrap;
        border-right: 3px solid white;
        animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
        margin: 0;
        width: 0;
    }

    @media (max-width: 768px) {
        .typing-animation {
            white-space: normal;
            border-right: none;
            width: 100%;
            font-size: 1.5rem;
            line-height: 1.2;
            animation: fadeIn 1s ease-in forwards;
        }
        
        .welcome-card {
            background-position: center;
            padding: 1.5rem;
            min-height: 180px;
        }
    }

    .fade-in {
        opacity: 0;
        animation: fadeIn 1s ease-in forwards;
        animation-delay: 1s;
    }

    @keyframes typing {
        from { width: 0 }
        to { width: 100% }
    }

    @keyframes blink-caret {
        from, to { border-color: transparent }
        50% { border-color: white }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to bottom,
            rgba(0, 0, 0, 0.2),
            rgba(0, 0, 0, 0.4)
        );
        border-radius: 0.5rem;
        z-index: 1;
    }

    .welcome-card > div {
        position: relative;
        z-index: 2;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pie chart status
    const ctx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($statusCounts);
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    'rgba(52, 211, 153, 0.8)',  // OPS - Green
                    'rgba(59, 130, 246, 0.8)',  // RSH - Blue
                    'rgba(239, 68, 68, 0.8)',   // FO - Red
                    'rgba(251, 191, 36, 0.8)',  // MO - Yellow
                    'rgba(107, 114, 128, 0.8)'  // Others - Gray
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Bar chart rata-rata beban per mesin
    const avgBebanCtx = document.getElementById('avgBebanChart').getContext('2d');
    new Chart(avgBebanCtx, {
        type: 'bar',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'Beban (kW)',
                data: @json($processedData['kw']),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'kW' } },
                x: { title: { display: true, text: 'Mesin' } }
            }
        }
    });

    // DMN/SLO per Mesin
    const dmnCtx = document.getElementById('dmnChart').getContext('2d');
    new Chart(dmnCtx, {
        type: 'bar',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'DMN/SLO',
                data: @json($processedData['silm_slo']),
                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true }, x: { title: { display: true, text: 'Mesin' } } }
        }
    });

    // DMP/PT per Mesin
    const dmpCtx = document.getElementById('dmpChart').getContext('2d');
    new Chart(dmpCtx, {
        type: 'bar',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'DMP/PT',
                data: @json($processedData['dmp_performance']),
                backgroundColor: 'rgba(251, 191, 36, 0.7)',
                borderColor: 'rgba(251, 191, 36, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true }, x: { title: { display: true, text: 'Mesin' } } }
        }
    });

    // Tegangan (V) per Mesin
    const voltCtx = document.getElementById('voltChart').getContext('2d');
    new Chart(voltCtx, {
        type: 'bar',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'Tegangan (V)',
                data: @json($processedData['volt']),
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true }, x: { title: { display: true, text: 'Mesin' } } }
        }
    });

    // Arus (A) per Mesin
    const ampCtx = document.getElementById('ampChart').getContext('2d');
    new Chart(ampCtx, {
        type: 'bar',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'Arus (A)',
                data: @json($processedData['amp']),
                backgroundColor: 'rgba(236, 72, 153, 0.7)',
                borderColor: 'rgba(236, 72, 153, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true }, x: { title: { display: true, text: 'Mesin' } } }
        }
    });

    // Handle unit filter change
    document.getElementById('unitFilter').addEventListener('change', function(e) {
        window.location.href = `${window.location.pathname}?unit_source=${e.target.value}`;
    });
});
</script>
@endpush
@endsection
