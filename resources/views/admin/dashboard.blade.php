@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }
    </style>
@endpush

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
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
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                </div>

                <div class="relative">
                    <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                        <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}" class="w-7 h-7 rounded-full mr-2">
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                    </button>
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                        <a href="{{ route('logout') }}" 
                           class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Main Content -->
        
        <div class="p-6">
            <!-- Welcome Card -->
            
            <div class="rounded-lg shadow-sm p-6 mb-6 text-white h-64 relative" style="background-image: url('{{ asset('images/welcome.webp') }}'); background-size: cover;">
                <div class="absolute inset-0 bg-blue-500 opacity-50 rounded-lg"></div>
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <h2 class="text-3xl font-bold mb-3 tracking-tight">Selamat Datang di Datakompak</h2>
                        <p class="text-xl font-semibold mb-2">Data Komunitas Operasi Mantap Unit Pembangkit Kendari</p>
                        <div class="backdrop-blur-sm bg-white/30 rounded-lg p-3 mt-4 max-w-xl">
                            <p class="text-base leading-relaxed">
                                Platform terintegrasi untuk monitoring dan analisis kinerja pembangkit listrik secara real-time. 
                                Dapatkan insight mendalam untuk pengambilan keputusan yang lebih efektif dan efisien.
                            </p>
                        </div>
                    </div>
                    <img src="{{ asset('logo/navlogo.png') }}" alt="Power Plant" class="w-48">
                </div>
            </div>


            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Card 1: Total Produksi Netto -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-blue-600 mb-2">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Produksi Netto</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalNetProduction) }} MW</p>
                        <a href="{{ route('admin.daily-summary.results', ['date' => date('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>

                <!-- Card 2: Total Produksi Bruto -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-green-600 mb-2">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Produksi Bruto</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalGrossProduction) }} MW</p>
                        <a href="{{ route('admin.daily-summary.results', ['date' => date('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>

                <!-- Card 3: Beban Puncak -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-yellow-600 mb-2">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Beban Puncak</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($peakLoad) }} MW</p>
                        <a href="{{ route('admin.daily-summary.results', ['date' => date('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>

                <!-- Card 4: Total Jam Operasi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-purple-600 mb-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Jam Operasi</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalOperatingHours) }} Jam</p>
                        <a href="{{ route('admin.daily-summary.results', ['date' => date('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Unit & Machine Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Power Plants Overview -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Unit Pembangkit</h3>
                        <span class="text-sm text-gray-500">Total: {{ $powerPlants->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($powerPlants->take(4) as $plant)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium">{{ $plant->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $plant->machines->count() }} Mesin</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ $plant->machines->sum('capacity') }} MW</p>
                                <p class="text-xs text-gray-500">Kapasitas Total</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.power-plants.index') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Semua Unit →
                        </a>
                    </div>
                </div>

                <!-- Machines Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Status Mesin</h3>
                        <span class="text-sm text-gray-500">Total: {{ $machines->count() }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- Operating Machines -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Beroperasi</p>
                                    <p class="text-2xl font-semibold text-green-600">
                                        {{ $machines->where('status', 'RUNNING')->count() }}
                                    </p>
                                </div>
                                <div class="text-green-500">
                                    <i class="fas fa-play-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Stopped Machines -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Berhenti</p>
                                    <p class="text-2xl font-semibold text-red-600">
                                        {{ $machines->where('status', 'STOP')->count() }}
                                    </p>
                                </div>
                                <div class="text-red-500">
                                    <i class="fas fa-stop-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Machines -->
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Maintenance</p>
                                    <p class="text-2xl font-semibold text-yellow-600">
                                        {{ $machines->whereIn('status', ['MAINTENANCE', 'OVERHAUL'])->count() }}
                                    </p>
                                </div>
                                <div class="text-yellow-500">
                                    <i class="fas fa-tools text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Standby Machines -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Standby</p>
                                    <p class="text-2xl font-semibold text-blue-600">
                                        {{ $machines->where('status', 'STANDBY')->count() }}
                                    </p>
                                </div>
                                <div class="text-blue-500">
                                    <i class="fas fa-pause-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.machine-monitor.show') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Lihat Detail Mesin →
                        </a>
                    </div>
                </div>
            </div>

            <!-- Progress Trackers -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Progress Tracker Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium mb-4">Status Pembangkit</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium">Kapasitas Terpasang</span>
                                <span class="text-sm font-medium">85%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium">Efisiensi Pembangkit</span>
                                <span class="text-sm font-medium">78%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-1">
                                <span class="text-sm font-medium">Ketersediaan Unit</span>
                                <span class="text-sm font-medium">92%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-yellow-500 h-2 rounded-full" style="width: 92%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium mb-4">Aktivitas Terkini</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                            <p class="text-sm">Unit PLTD Bau-Bau beroperasi normal</p>
                            <span class="text-xs text-gray-500 ml-auto">2 jam yang lalu</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-3"></div>
                            <p class="text-sm">Pemeliharaan rutin PLTU Kendari</p>
                            <span class="text-xs text-gray-500 ml-auto">4 jam yang lalu</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                            <p class="text-sm">Update data operasional PLTD Wangi-Wangi</p>
                            <span class="text-xs text-gray-500 ml-auto">5 jam yang lalu</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-red-500 rounded-full mr-3"></div>
                            <p class="text-sm">Peringatan bahan bakar PLTD Raha</p>
                            <span class="text-xs text-gray-500 ml-auto">6 jam yang lalu</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Production Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium mb-4">Grafik Produksi Harian</h3>
                    <div style="height: 300px;">
                        <canvas id="productionChart"></canvas>
                    </div>
                </div>

                <!-- Fuel Consumption Chart -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-medium mb-4">Konsumsi Bahan Bakar</h3>
                    <div style="height: 300px;">
                        <canvas id="fuelChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Fuel Monitoring Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Monitoring Bahan Bakar</h3>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Detail →</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- HSD Fuel -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium">HSD</span>
                            <span class="text-xs text-gray-500">Stok: 75%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                        </div>
                        <div class="text-sm text-gray-600">150,000 Liter</div>
                    </div>
                    <!-- MFO Fuel -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium">MFO</span>
                            <span class="text-xs text-gray-500">Stok: 45%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-yellow-500 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                        <div class="text-sm text-gray-600">90,000 Liter</div>
                    </div>
                    <!-- Batubara -->
                    <div class="border rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium">B35</span>
                            <span class="text-xs text-gray-500">Stok: 60%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                            <div class="bg-blue-500 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                        <div class="text-sm text-gray-600">1,200 liter</div>
                    </div>
                </div>
            </div>

            <!-- Chart Initialization Scripts -->
            <script>
                // Production Chart
                const productionCtx = document.getElementById('productionChart').getContext('2d');
                new Chart(productionCtx, {
                    type: 'line',
                    data: {
                        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                        datasets: [{
                            label: 'Produksi Netto (MW)',
                            data: [65, 59, 80, 81, 56, 55, 70],
                            borderColor: 'rgb(59, 130, 246)',
                            tension: 0.1,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 100,
                                ticks: {
                                    stepSize: 20
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });

                // Fuel Consumption Chart
                const fuelCtx = document.getElementById('fuelChart').getContext('2d');
                new Chart(fuelCtx, {
                    type: 'bar',
                    data: {
                        labels: ['HSD', 'MFO', 'B35'],
                        datasets: [{
                            label: 'Konsumsi Harian',
                            data: [12000, 19000, 3000],
                            backgroundColor: [
                                'rgba(34, 197, 94, 0.5)',
                                'rgba(234, 179, 8, 0.5)',
                                'rgba(59, 130, 246, 0.5)'
                            ],
                            borderColor: [
                                'rgb(34, 197, 94)',
                                'rgb(234, 179, 8)',
                                'rgb(59, 130, 246)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                min: 0,
                                max: 25000,
                                ticks: {
                                    stepSize: 5000
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection
