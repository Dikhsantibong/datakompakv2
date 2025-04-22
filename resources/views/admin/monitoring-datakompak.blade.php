@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }
        .welcome-card {
            background-size: cover;
            background-position: center;
            transition: all 0.3s ease;
        }
        .status-badge {
            transition: all 0.3s ease;
        }
        .status-badge:hover {
            transform: scale(1.05);
        }
        .unit-card {
            transition: all 0.3s ease;
        }
        .unit-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
    </style>
@endpush

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Monitoring Unit Pembangkit</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Monitoring Unit Pembangkit', 'url' => null]]" />
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <!-- Overview Card -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-lg p-6 mb-6 text-white relative welcome-card">
                <div class="relative z-10">
                    <div class="flex justify-between items-start">
                        <div class="space-y-2">
                            <h2 class="text-2xl font-bold">Status Unit Pembangkit</h2>
                            <p class="text-blue-100">Monitoring status input data unit pembangkit</p>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4">
                                <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                    <div class="text-2xl font-bold">{{ $stats['total_units'] }}</div>
                                    <div class="text-sm text-blue-100">Total Unit</div>
                                </div>
                                <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                    <div class="text-2xl font-bold">{{ $stats['completed'] }}</div>
                                    <div class="text-sm text-blue-100">Sudah Input</div>
                                </div>
                                <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                    <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
                                    <div class="text-sm text-blue-100">Belum Input</div>
                                </div>
                                <div class="bg-white/10 rounded-lg p-3 backdrop-blur-sm">
                                    <div class="text-2xl font-bold">{{ $stats['overdue'] }}</div>
                                    <div class="text-sm text-blue-100">Terlambat</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unit Type Distribution -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Chart -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium mb-4">Distribusi Jenis Unit</h3>
                    <div style="height: 300px;">
                        <canvas id="unitTypeChart"></canvas>
                    </div>
                </div>

                <!-- System Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-medium mb-4">Statistik Sistem</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">System Uptime</p>
                                <p class="text-2xl font-semibold text-blue-600">{{ $systemStats['uptime'] }}</p>
                            </div>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Response Time</p>
                                <p class="text-2xl font-semibold text-green-600">{{ $systemStats['avg_response'] }}</p>
                            </div>
                        </div>
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Total Input Hari Ini</p>
                                <p class="text-2xl font-semibold text-purple-600">{{ $systemStats['total_inputs_today'] }}</p>
                            </div>
                        </div>
                        <div class="bg-orange-50 rounded-lg p-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-600">Active Users</p>
                                <p class="text-2xl font-semibold text-orange-600">{{ $systemStats['active_users'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Unit Status Sections -->
            <div class="space-y-6">
                <!-- Pending Units -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Unit Yang Belum Input Data</h3>
                        <span class="text-sm text-gray-500">{{ count($pendingUnits) }} Unit</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($pendingUnits as $unit)
                            <div class="bg-yellow-50 rounded-lg p-4 unit-card">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $unit['name'] }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Last Update: {{ $unit['last_update']->diffForHumans() }}
                                        </p>
                                        <div class="mt-3 space-y-1">
                                            <p class="text-sm font-medium text-gray-700">Data yang belum diinput:</p>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-yellow-100 text-yellow-700">
                                                    <i class="fas fa-chart-line mr-1"></i> Data Operasi
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-yellow-100 text-yellow-700">
                                                    <i class="fas fa-file-alt mr-1"></i> Laporan Harian
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-2">
                                                <p><i class="fas fa-exclamation-circle mr-1"></i> Data Operasi: KW, KVAR, Cos Phi</p>
                                                <p><i class="fas fa-exclamation-circle mr-1"></i> Laporan: Produksi, Jam Operasi, BBM</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                                <p class="text-gray-600">Semua unit sudah melakukan input data hari ini!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Overdue Units -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Unit Yang Terlambat Input</h3>
                        <span class="text-sm text-gray-500">{{ count($overdueUnits) }} Unit</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($overdueUnits as $unit)
                            <div class="bg-red-50 rounded-lg p-4 unit-card">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $unit['name'] }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Last Update: {{ $unit['last_update']->diffForHumans() }}
                                        </p>
                                        <div class="mt-3 space-y-1">
                                            <p class="text-sm font-medium text-gray-700">Data yang belum diinput:</p>
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">
                                                    <i class="fas fa-chart-line mr-1"></i> Data Operasi
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">
                                                    <i class="fas fa-file-alt mr-1"></i> Laporan Harian
                                                </span>
                                            </div>
                                            <div class="text-xs text-gray-500 mt-2">
                                                <p><i class="fas fa-exclamation-circle mr-1"></i> Data Operasi: KW, KVAR, Cos Phi</p>
                                                <p><i class="fas fa-exclamation-circle mr-1"></i> Laporan: Produksi, Jam Operasi, BBM</p>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        Terlambat
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                                <p class="text-gray-600">Tidak ada unit yang terlambat input data!</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Completed Units -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Unit Yang Sudah Input Data</h3>
                        <span class="text-sm text-gray-500">{{ count($completedUnits) }} Unit</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($completedUnits as $unit)
                            <div class="bg-green-50 rounded-lg p-4 unit-card">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $unit['name'] }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            Updated: {{ $unit['last_update']->diffForHumans() }}
                                        </p>
                                        <div class="mt-3">
                                            <div class="flex flex-wrap gap-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-green-100 text-green-700">
                                                    <i class="fas fa-check-circle mr-1"></i> Semua Data Lengkap
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Completed
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8">
                                <i class="fas fa-exclamation-circle text-yellow-500 text-4xl mb-3"></i>
                                <p class="text-gray-600">Belum ada unit yang melakukan input data hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Unit Type Distribution Chart
    const unitTypeCtx = document.getElementById('unitTypeChart').getContext('2d');
    new Chart(unitTypeCtx, {
        type: 'doughnut',
        data: {
            labels: ['PLTD', 'PLTM', 'PLTU', 'PLTMG', 'Other'],
            datasets: [{
                data: [
                    {{ $unitTypes['PLTD'] }},
                    {{ $unitTypes['PLTM'] }},
                    {{ $unitTypes['PLTU'] }},
                    {{ $unitTypes['PLTMG'] }},
                    {{ $unitTypes['Other'] }}
                ],
                backgroundColor: [
                    'rgba(59, 130, 246, 0.8)',
                    'rgba(16, 185, 129, 0.8)',
                    'rgba(245, 158, 11, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(107, 114, 128, 0.8)'
                ],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            cutout: '70%'
        }
    });
</script>
@endpush

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 