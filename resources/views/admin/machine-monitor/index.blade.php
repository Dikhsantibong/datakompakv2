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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <!-- Welcome Card -->
                <div class="rounded-lg shadow-sm p-4 mb-6 text-white relative welcome-card min-h-[200px] md:h-64">
                    <div class="absolute inset-0 bg-blue-500 opacity-50 rounded-lg"></div>
                    <div class="relative z-10">
                        <!-- Text Content -->
                        <div class="space-y-2 md:space-y-4">
                            <div style="overflow: hidden;">
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight typing-animation">
                                    Monitor Status Mesin Pembangkit
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
                </div>

                <!-- Status Indicators -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-blue-600 mb-2">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Total Mesin</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ App\Models\Machine::count() }} unit</p>
                            <a href="{{ route('admin.machine-monitor.show', ['machine' => 1]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-green-600 mb-2">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Total Unit</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ App\Models\PowerPlant::count() }} unit</p>
                            <a href="{{ route('admin.power-plants.index') }}" class="text-green-600 hover:text-green-800 font-medium text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-red-600 mb-2">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Masalah Aktif</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ $machines->sum(function ($machine) {
                                return $machine->issues->where('status', 'open')->count();
                            }) }} masalah</p>
                            <span class="text-red-600 font-medium text-sm">Perlu Perhatian</span>
                        </div>
                    </div>
                </div>

                <!-- Performance Indicators -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">
                    <!-- OPS Status Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-green-600 mb-2">
                                <i class="fas fa-play-circle"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">OPS</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ $machineStatusLogs->where('status', 'OPS')->count() }} kali</p>
                            <span class="text-green-600 text-sm font-medium">Operation</span>
                        </div>
                    </div>

                    <!-- RSH Status Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-blue-600 mb-2">
                                <i class="fas fa-pause-circle"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">RSH</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ $machineStatusLogs->where('status', 'RSH')->count() }} kali</p>
                            <span class="text-blue-600 text-sm font-medium">Reserve Shutdown</span>
                        </div>
                    </div>

                    <!-- FO Status Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-red-600 mb-2">
                                <i class="fas fa-stop-circle"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">FO</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ $machineStatusLogs->where('status', 'FO')->count() }} kali</p>
                            <span class="text-red-600 text-sm font-medium">Forced Outage</span>
                        </div>
                    </div>

                    <!-- MO Status Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-yellow-600 mb-2">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">MO</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ $machineStatusLogs->where('status', 'MO')->count() }} kali</p>
                            <span class="text-yellow-600 text-sm font-medium">Maintenance Outage</span>
                        </div>
                    </div>

                    <!-- Average DMN -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-purple-600 mb-2">
                                <i class="fas fa-chart-area"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">DMN</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($machineStatusLogs->avg('dmn'), 2) }}</p>
                            <span class="text-purple-600 text-sm font-medium">Rata-rata DMN</span>
                        </div>
                    </div>

                    <!-- Average DMP -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-indigo-600 mb-2">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">DMP</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($machineStatusLogs->avg('dmp'), 2) }}</p>
                            <span class="text-indigo-600 text-sm font-medium">Rata-rata DMP</span>
                        </div>
                    </div>
                </div>

                <!-- Operating & Production Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Status Statistics -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium">Statistik Status</h3>
                            <i class="fas fa-chart-pie text-blue-600"></i>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Total Status Log</p>
                                <p class="text-2xl font-semibold text-blue-600">{{ $machineStatusLogs->count() }}</p>
                                <p class="text-sm text-gray-500">records</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Status Aktif</p>
                                <p class="text-2xl font-semibold text-green-600">{{ $machineStatusLogs->where('tanggal', now()->toDateString())->count() }}</p>
                                <p class="text-sm text-gray-500">hari ini</p>
                            </div>
                        </div>
                    </div>

                    <!-- Load Statistics -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium">Statistik Beban</h3>
                            <i class="fas fa-bolt text-yellow-600"></i>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Rata-rata Beban</p>
                                <p class="text-2xl font-semibold text-purple-600">{{ number_format($machineStatusLogs->avg('load_value'), 2) }}</p>
                                <p class="text-sm text-gray-500">MW</p>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Beban Maksimum</p>
                                <p class="text-2xl font-semibold text-indigo-600">{{ number_format($machineStatusLogs->max('load_value'), 2) }}</p>
                                <p class="text-sm text-gray-500">MW</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Monthly Status Frequency -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-800">
                                <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                                Frekuensi Status Mesin (Bulan Ini)
                            </h3>
                            <select id="machineSelect"
                                class="p-2 rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="all">Semua Mesin</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->id }}">{{ $machine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="h-80">
                            <canvas id="statusFrequencyChart"></canvas>
                        </div>
                    </div>

                    <!-- Status Duration -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-800">
                            <i class="fas fa-clock mr-2 text-green-600"></i>
                            Durasi Status (Jam)
                        </h3>
                        <div class="h-80">
                            <canvas id="statusDurationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Status Analysis -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Status Trend -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-800">
                            <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                            Tren Status Bulanan
                        </h3>
                        <div class="h-80">
                            <canvas id="statusTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Status Comparison -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-medium mb-4 text-gray-800">
                            <i class="fas fa-balance-scale mr-2 text-red-600"></i>
                            Perbandingan Status Antar Unit
                        </h3>
                        <div class="h-80">
                            <canvas id="statusComparisonChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                            Keterangan Status
                        </h3>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">OPS (Operation)</dt>
                                <dd class="col-span-2 text-gray-600">Mesin dalam kondisi beroperasi normal.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">RSH (Reserve Shutdown)</dt>
                                <dd class="col-span-2 text-gray-600">Mesin dalam kondisi siap operasi namun tidak beroperasi.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">FO (Forced Outage)</dt>
                                <dd class="col-span-2 text-gray-600">Mesin mengalami gangguan dan harus berhenti beroperasi.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">MO (Maintenance Outage)</dt>
                                <dd class="col-span-2 text-gray-600">Mesin dalam kondisi pemeliharaan terjadwal.</dd>
                            </div>
                        </dl>
                    </div>
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
    // Get current month's data
    const currentDate = new Date();
    const monthlyData = @json($machineStatusLogs->filter(function($log) {
        return Carbon\Carbon::parse($log->tanggal)->month === now()->month;
    })->groupBy('status')->map->count());

    // Status Frequency Chart
    const frequencyCtx = document.getElementById('statusFrequencyChart').getContext('2d');
    new Chart(frequencyCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(monthlyData),
            datasets: [{
                label: 'Frekuensi',
                data: Object.values(monthlyData),
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
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Kejadian'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.parsed.y} kali`;
                        }
                    }
                }
            }
        }
    });

    // Status Duration Chart
    const durationData = @json($machineStatusLogs->filter(function($log) {
        return Carbon\Carbon::parse($log->tanggal)->month === now()->month;
    })->groupBy('status')->map(function($logs) {
        return $logs->count() * 24;
    }));

    const durationCtx = document.getElementById('statusDurationChart').getContext('2d');
    new Chart(durationCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(durationData),
            datasets: [{
                data: Object.values(durationData),
                backgroundColor: [
                    'rgba(52, 211, 153, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(107, 114, 128, 0.8)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed} jam`;
                        }
                    }
                }
            }
        }
    });

    // Monthly Status Trend
    const monthlyTrend = @json($machineStatusLogs
        ->groupBy(function($log) {
            return Carbon\Carbon::parse($log->tanggal)->format('Y-m');
        })
        ->map(function($logs) {
            return $logs->groupBy('status')->map->count();
        })
    );

    const trendCtx = document.getElementById('statusTrendChart').getContext('2d');
    const months = Object.keys(monthlyTrend);
    const statuses = [...new Set(Object.values(monthlyTrend).flatMap(m => Object.keys(m)))];
    
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: statuses.map((status, index) => ({
                label: status,
                data: months.map(month => monthlyTrend[month]?.[status] || 0),
                borderColor: [
                    'rgb(52, 211, 153)',
                    'rgb(59, 130, 246)',
                    'rgb(239, 68, 68)',
                    'rgb(251, 191, 36)',
                    'rgb(107, 114, 128)'
                ][index],
                tension: 0.1
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Kejadian'
                    }
                }
            }
        }
    });

    // Status Comparison by Unit
    const unitComparison = @json($machines->map(function($machine) {
        return [
            'name' => $machine->name,
            'statuses' => $machine->statusLogs()
                ->whereMonth('tanggal', now()->month)
                ->get()
                ->groupBy('status')
                ->map->count()
        ];
    }));

    const comparisonCtx = document.getElementById('statusComparisonChart').getContext('2d');
    const units = unitComparison.map(u => u.name);
    const statusTypes = [...new Set(unitComparison.flatMap(u => Object.keys(u.statuses)))];

    new Chart(comparisonCtx, {
        type: 'bar',
        data: {
            labels: units,
            datasets: statusTypes.map((status, index) => ({
                label: status,
                data: unitComparison.map(u => u.statuses[status] || 0),
                backgroundColor: [
                    'rgba(52, 211, 153, 0.8)',
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(239, 68, 68, 0.8)',
                    'rgba(251, 191, 36, 0.8)',
                    'rgba(107, 114, 128, 0.8)'
                ][index % 5]
            }))
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Kejadian'
                    }
                }
            }
        }
    });

    // Handle machine selection change
    document.getElementById('machineSelect').addEventListener('change', function(e) {
        const machineId = e.target.value;
        if (machineId !== 'all') {
            window.location.href = `?machine_id=${machineId}`;
        } else {
            window.location.href = window.location.pathname;
        }
    });

    // Background image rotation for welcome card
    const backgroundImages = [
        "{{ asset('images/welcome.webp') }}",
        "{{ asset('images/welcome2.jpeg') }}",
        "{{ asset('images/welcome3.jpg') }}"
    ];

    let currentImageIndex = 0;
    const welcomeCard = document.querySelector('.welcome-card');

    function changeBackground() {
        welcomeCard.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;
        currentImageIndex = (currentImageIndex + 1) % backgroundImages.length;
    }

    // Set initial background
    changeBackground();

    // Change background every 5 seconds
    setInterval(changeBackground, 5000);
});
</script>
@endpush
@endsection
