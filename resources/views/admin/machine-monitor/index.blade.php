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

                    <!-- Menu Toggle Sidebar-->
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
                    <h1 class="text-xl font-semibold text-gray-900">Dashboard Monitor Mesin</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Dashboard Monitor Mesin', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="px-4 py-4">
                <!-- Status Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Status Mesin</h2>
                    <div class="text-sm text-gray-600">
                        Bulan: {{ now()->format('F Y') }}
                    </div>
                </div>

                <!-- Status Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-4 mb-6">
                    <!-- Operasi (OPS) -->
                    <div class="bg-green-50 rounded-lg p-4 border border-green-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-700 font-medium">Operasi (OPS)</h3>
                                <p class="text-3xl font-bold text-green-600 mt-2">{{ $statusCounts['OPS'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ isset($statusDurations['OPS']) ? number_format($statusDurations['OPS'], 1) : 0 }} jam</p>
                            </div>
                            <div class="p-2 bg-green-100 rounded-full">
                                <i class="fas fa-play-circle text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Reserve Shutdown (RSH) -->
                    <div class="bg-red-50 rounded-lg p-4 border border-red-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-700 font-medium">Reserve Shutdown (RSH)</h3>
                                <p class="text-3xl font-bold text-red-600 mt-2">{{ $statusCounts['RSH'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ isset($statusDurations['RSH']) ? number_format($statusDurations['RSH'], 1) : 0 }} jam</p>
                            </div>
                            <div class="p-2 bg-red-100 rounded-full">
                                <i class="fas fa-stop-circle text-red-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Outage (MO) -->
                    <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-700 font-medium">Maintenance Outage (MO)</h3>
                                <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $statusCounts['MO'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ isset($statusDurations['MO']) ? number_format($statusDurations['MO'], 1) : 0 }} jam</p>
                            </div>
                            <div class="p-2 bg-yellow-100 rounded-full">
                                <i class="fas fa-tools text-yellow-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Forced Outage (FO) -->
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-700 font-medium">Forced Outage (FO)</h3>
                                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $statusCounts['FO'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ isset($statusDurations['FO']) ? number_format($statusDurations['FO'], 1) : 0 }} jam</p>
                            </div>
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="fas fa-exclamation-circle text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Planned Outage (PO) -->
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-700 font-medium">Planned Outage (PO)</h3>
                                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $statusCounts['PO'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ isset($statusDurations['PO']) ? number_format($statusDurations['PO'], 1) : 0 }} jam</p>
                            </div>
                            <div class="p-2 bg-purple-100 rounded-full">
                                <i class="fas fa-calendar-check text-purple-500 text-xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Mothballed (MB) -->
                    <div class="bg-orange-50 rounded-lg p-4 border border-orange-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-gray-700 font-medium">Mothballed (MB)</h3>
                                <p class="text-3xl font-bold text-orange-600 mt-2">{{ $statusCounts['MB'] ?? 0 }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ isset($statusDurations['MB']) ? number_format($statusDurations['MB'], 1) : 0 }} jam</p>
                            </div>
                            <div class="p-2 bg-orange-100 rounded-full">
                                <i class="fas fa-pause-circle text-orange-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Welcome Banner -->


                <!-- Quick Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <!-- Total Machines -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-cogs text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 font-medium">Total Mesin</p>
                                <p class="text-xl font-bold text-gray-800">{{ $machines->count() }}</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.machine-monitor.show') }}" class="text-blue-600 text-sm font-medium hover:underline">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Operational Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-play-circle text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 font-medium">Mesin Operasional</p>
                                <p class="text-xl font-bold text-gray-800">{{ $statusCounts['OPS'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <span class="text-green-500 text-sm">
                                    {{ number_format(($statusCounts['OPS'] ?? 0) / ($machines->count() ?: 1) * 100, 1) }}%
                                </span>
                                <span class="text-gray-500 text-sm ml-1">Operational Rate</span>
                            </div>
                        </div>
                    </div>

                    <!-- Maintenance Status -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-tools text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 font-medium">Dalam Maintenance</p>
                                <p class="text-xl font-bold text-gray-800">{{ $statusCounts['MO'] ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex items-center">
                                <span class="text-yellow-500 text-sm">
                                    {{ number_format(($statusCounts['MO'] ?? 0) / ($machines->count() ?: 1) * 100, 1) }}%
                                </span>
                                <span class="text-gray-500 text-sm ml-1">Maintenance Rate</span>
                            </div>
                        </div>
                    </div>

                    <!-- Peak Load -->
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-bolt text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500 font-medium">Beban Puncak</p>
                                <p class="text-xl font-bold text-gray-800">{{ number_format($maxBeban, 2) }} kW</p>
                            </div>
                        </div>
                        <div class="mt-2">
                            <span class="text-purple-500 text-sm">Peak Load Status</span>
                        </div>
                    </div>
                </div>

                <!-- Charts Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Machine Status Distribution -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Distribusi Status Mesin</h3>
                        <div class="h-[300px]">
                            <canvas id="statusChart" class="w-full"></canvas>
                        </div>
                    </div>

                    <!-- Load Distribution -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">Distribusi Beban per Mesin</h3>
                        <div class="h-[300px]">
                            <canvas id="loadChart" class="w-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- DMN/SLO Performance -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">DMN/SLO Performance</h3>
                        <div class="h-[300px]">
                            <canvas id="dmnChart" class="w-full"></canvas>
                        </div>
                    </div>

                    <!-- DMP Performance -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold mb-4 text-gray-800">DMP Performance</h3>
                        <div class="h-[300px]">
                            <canvas id="dmpChart" class="w-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Machine Status Table -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Status Mesin Terkini</h3>
                        <div class="flex space-x-2">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                <i class="fas fa-download mr-2"></i>Export
                            </button>
                            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>

                    @foreach($powerPlants as $powerPlant)
                    <div class="bg-white rounded-lg shadow p-6 mb-4">
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
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SILM/SLO</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP Test</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (kW)</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @php
                                        $machinesInUnit = $latestLogs->where('machine.power_plant_id', $powerPlant->id);
                                    @endphp
                                    @forelse($machinesInUnit as $i => $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $i+1 }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->machine->name }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->date ? \Carbon\Carbon::parse($log->date)->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->time ? \Carbon\Carbon::parse($log->time)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $log->status === 'OPS' ? 'bg-green-100 text-green-800' :
                                                   ($log->status === 'RSH' ? 'bg-yellow-100 text-yellow-800' :
                                                   ($log->status === 'FO' ? 'bg-red-100 text-red-800' :
                                                   ($log->status === 'MO' ? 'bg-orange-100 text-orange-800' :
                                                   ($log->status === 'PO' ? 'bg-blue-100 text-blue-800' :
                                                   ($log->status === 'MB' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))))) }}">
                                                {{ $log->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $log->silm_slo ?? '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $log->dmp_performance ?? '-' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ number_format($log->kw ?? 0, 2) }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">{{ $log->keterangan ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="9" class="px-4 py-3 text-center text-gray-500">
                                            Tidak ada data mesin untuk unit ini
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .bg-gradient-overlay {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 100%);
    }

    .chart-container {
        position: relative;
        height: 300px !important;
        width: 100% !important;
    }

    canvas {
        width: 100% !important;
        height: 100% !important;
    }

    .status-badge {
        transition: all 0.3s ease;
    }

    .status-badge:hover {
        transform: translateY(-1px);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Konfigurasi umum untuk semua grafik
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        animation: {
            duration: 0 // Menonaktifkan animasi untuk performa lebih baik
        },
        layout: {
            padding: {
                left: 10,
                right: 10,
                top: 10,
                bottom: 20
            }
        },
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    boxWidth: 8,
                    font: {
                        size: 11
                    }
                }
            }
        }
    };

    // Status Distribution Chart
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(@json($statusCounts)),
            datasets: [{
                data: Object.values(@json($statusCounts)),
                backgroundColor: [
                    '#10B981', // OPS - Green
                    '#3B82F6', // RSH - Blue
                    '#EF4444', // FO - Red
                    '#F59E0B', // MO - Yellow
                    '#6B7280'  // Others - Gray
                ],
                borderWidth: 2
            }]
        },
        options: {
            ...commonOptions,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        boxWidth: 8
                    }
                }
            }
        }
    });

    // Load Distribution Chart
    new Chart(document.getElementById('loadChart'), {
        type: 'bar',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'Beban (kW)',
                data: @json($processedData['kw']),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                barPercentage: 0.7,
                categoryPercentage: 0.8
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'kW',
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // DMN/SLO Chart
    new Chart(document.getElementById('dmnChart'), {
        type: 'line',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'DMN/SLO',
                data: @json($processedData['silm_slo']),
                borderColor: 'rgba(16, 185, 129, 1)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });

    // DMP Performance Chart
    new Chart(document.getElementById('dmpChart'), {
        type: 'line',
        data: {
            labels: @json($processedData['labels']),
            datasets: [{
                label: 'DMP Performance',
                data: @json($processedData['dmp_performance']),
                borderColor: 'rgba(245, 158, 11, 1)',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 10
                        }
                    }
                }
            }
        }
    });
});

// Toggle dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const dropdownToggle = document.getElementById('dropdownToggle');

    if (!dropdown.contains(event.target) && !dropdownToggle.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
@endpush
@endsection
