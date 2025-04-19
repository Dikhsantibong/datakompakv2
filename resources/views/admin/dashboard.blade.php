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
            transition: background-image 1s ease-in-out;
            font-family: 'Poppins', sans-serif;
            min-height: 200px;
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

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20
        ">
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

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Dashboard', 'url' => null]]" />
        </div>
        
        <!-- Main Content -->
        
        <div class="p-6">
            <!-- Welcome Card -->
            
            <div class="rounded-lg shadow-sm p-4 mb-6 text-white relative welcome-card min-h-[200px] md:h-64">
                <div class="absolute inset-0 bg-blue-500 opacity-50 rounded-lg"></div>
                <div class="relative z-10">
                    <!-- Text Content -->
                    <div class="space-y-2 md:space-y-4">
                        <div style="overflow: hidden;">
                            <h2 class="text-2xl md:text-3xl font-bold tracking-tight typing-animation">
                                Selamat Datang di Datakompak
                            </h2>
                        </div>
                        <p class="text-sm md:text-lg font-medium fade-in">
                            Data Komunitas Operasi Mantap Unit Pembangkit Kendari
                        </p>
                        <div class="backdrop-blur-sm bg-white/30 rounded-lg p-3 fade-in w-50">
                            <p class="text-xs md:text-base leading-relaxed">
                                Platform terintegrasi untuk monitoring dan analisis kinerja pembangkit listrik secara real-time. 
                                Dapatkan insight mendalam untuk pengambilan keputusan yang lebih efektif dan efisien.
                            </p>
                        </div>
                    </div>
                    
                    <!-- Logo - Hidden on mobile -->
                    <img src="{{ asset('logo/navlogo.png') }}" alt="Power Plant" class="hidden md:block absolute top-4 right-4 w-32 md:w-48 fade-in">
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
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalNetProduction, 2) }} kWh</p>
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
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalGrossProduction, 2) }} kWh</p>
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
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format(max($dailySummaries->pluck('peak_load_day')->max(), $dailySummaries->pluck('peak_load_night')->max()), 2) }} MW</p>
                        <a href="{{ route('admin.daily-summary.results', ['date' => date('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>

                <!-- Card 4: Total Jam Periode -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-purple-600 mb-2">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Jam Periode</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalPeriodHours, 1) }} Jam</p>
                        <a href="{{ route('admin.daily-summary.results', ['date' => date('Y-m-d')]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                            Lihat Detail →
                        </a>
                    </div>
                </div>
            </div>

             <!-- Operation Schedule Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium">Jadwal Operasi Hari Ini</h3>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Lihat Semua Jadwal →</a>
                </div>
                @if($operationSchedules->count() > 0)
                    <div class="space-y-4">
                        @foreach($operationSchedules as $schedule)
                            <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium text-gray-800">{{ $schedule->title }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">{{ $schedule->description }}</p>
                                        <div class="flex items-center mt-2 text-sm text-gray-500">
                                            <i class="fas fa-map-marker-alt mr-2"></i>
                                            <span>{{ $schedule->location }}</span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-800">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @if($schedule->status == 'completed') bg-green-100 text-green-800
                                            @elseif($schedule->status == 'in_progress') bg-blue-100 text-blue-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($schedule->status) }}
                                        </span>
                                    </div>
                                </div>
                                @if($schedule->participants)
                                    <div class="mt-3 flex items-center text-sm text-gray-500">
                                        <i class="fas fa-users mr-2"></i>
                                        <span>{{ count($schedule->participants) }} Peserta</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-3"></i>
                        <p>Tidak ada jadwal operasi untuk hari ini</p>
                    </div>
                @endif
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
                        <span class="text-sm text-gray-500">Total: {{ $machineStats['total'] }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- OPS Machines -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Operasi (OPS)</p>
                                    <p class="text-2xl font-semibold text-green-600">
                                        {{ $machineStats['ops'] }}
                                    </p>
                                </div>
                                <div class="text-green-500">
                                    <i class="fas fa-play-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- RSH Machines -->
                        <div class="bg-red-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Reserve Shutdown (RSH)</p>
                                    <p class="text-2xl font-semibold text-red-600">
                                        {{ $machineStats['rsh'] }}
                                    </p>
                                </div>
                                <div class="text-red-500">
                                    <i class="fas fa-stop-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- MO Machines -->
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Maintenance Outage (MO)</p>
                                    <p class="text-2xl font-semibold text-yellow-600">
                                        {{ $machineStats['mo'] }}
                                    </p>
                                </div>
                                <div class="text-yellow-500">
                                    <i class="fas fa-tools text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- FO Machines -->
                        <div class="bg-blue-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Forced Outage (FO)</p>
                                    <p class="text-2xl font-semibold text-blue-600">
                                        {{ $machineStats['fo'] }}
                                    </p>
                                </div>
                                <div class="text-blue-500">
                                    <i class="fas fa-exclamation-circle text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- PO Machines -->
                        <div class="bg-purple-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Planned Outage (PO)</p>
                                    <p class="text-2xl font-semibold text-purple-600">
                                        {{ $machineStats['po'] }}
                                    </p>
                                </div>
                                <div class="text-purple-500">
                                    <i class="fas fa-calendar-check text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- MB Machines -->
                        <div class="bg-orange-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Mothballed (MB)</p>
                                    <p class="text-2xl font-semibold text-orange-600">
                                        {{ $machineStats['mb'] }}
                                    </p>
                                </div>
                                <div class="text-orange-500">
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

@push('scripts')
<script>
    const backgroundImages = [
        "{{ asset('images/welcome.webp') }}",
        "{{ asset('images/welcome2.jpeg') }}",
        "{{ asset('images/welcome3.jpg') }}",
        // Tambahkan path gambar lainnya sesuai kebutuhan
    ];

    let currentImageIndex = 0;
    const welcomeCard = document.querySelector('.welcome-card');

    function changeBackground() {
        welcomeCard.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;
        currentImageIndex = (currentImageIndex + 1) % backgroundImages.length;
    }

    // Set gambar awal
    changeBackground();

    // Ganti gambar setiap 5 detik
    setInterval(changeBackground, 5000);
</script>
@endpush
