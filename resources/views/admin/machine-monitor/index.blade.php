@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-gray-50 overflow-auto">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div id="main-content" class="flex-1 main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-3">
                    <div class="flex items-center gap-x-3">
                        <!-- Mobile Menu Toggle -->
                        <button id="mobile-menu-toggle"
                            class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
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
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">Dasbor Pemantauan Mesin</h1>
                    </div>

                    @include('components.timer')
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

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Indikator Kinerja -->
                <div class="bg-white grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 rounded-lg shadow-md" style="padding:20px">
                    <div class="bg-blue-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
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

                    <div class="bg-green-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
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

                    <div class="bg-red-50 rounded-lg shadow-md hover:shadow-lg transition-shadow">
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

                <!-- Chart Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Monthly Status Frequency -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">
                                <i class="fas fa-chart-bar mr-2 text-blue-600"></i>
                                Frekuensi Status Mesin (Bulan Ini)
                            </h2>
                            <select id="machineSelect"
                                    class="p-2 rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500 ">
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
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-clock mr-2 text-green-600"></i>
                            Durasi Status (Jam)
                        </h2>
                        <div class="h-80">
                            <canvas id="statusDurationChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Monthly Trends -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Status Trend -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-chart-line mr-2 text-purple-600"></i>
                            Tren Status Bulanan
                        </h2>
                        <div class="h-80">
                            <canvas id="statusTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Status Comparison -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">
                            <i class="fas fa-balance-scale mr-2 text-red-600"></i>
                            Perbandingan Status Antar Unit
                        </h2>
                        <div class="h-80">
                            <canvas id="statusComparisonChart"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Chart.js -->
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

            // Status Duration Chart (Hours per Status)
            const durationData = @json($machineStatusLogs->filter(function($log) {
                return Carbon\Carbon::parse($log->tanggal)->month === now()->month;
            })->groupBy('status')->map(function($logs) {
                return $logs->count() * 24; // Assuming each status log represents 24 hours
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
                // You can implement AJAX call here to update the charts based on selected machine
                // For now, we'll just reload the page with the machine ID as a parameter
                if (machineId !== 'all') {
                    window.location.href = `?machine_id=${machineId}`;
                } else {
                    window.location.href = window.location.pathname;
                }
            });
        });
    </script>

    <style>
        .main-content {
            min-height: 100vh;
        }
        
        canvas {
            max-height: 100%;
        }
    </style>
@endsection
