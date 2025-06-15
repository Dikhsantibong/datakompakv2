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

        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        <!-- Koordinasi Data Menu Button -->
        <div class="px-6 mb-4 justify-end flex">
            <div class="relative group">
                <button id="koordinasiDataBtn" class="w-full md:w-auto flex items-center justify-between px-4 py-2.5 text-sm font-medium text-white bg-[#009BB9] rounded-lg hover:bg-[#007A94] focus:ring-4 focus:ring-[#009BB9]/50 transition-all duration-300 shadow-md hover:shadow-lg">
                    <div class="flex items-center">
                        <i class="fas fa-database mr-2"></i>
                        <span>Koordinasi Data UPB/UP3 - KIT</span>
                    </div>
                    <i class="fas fa-chevron-down ml-2 transition-transform duration-300 group-hover:rotate-180"></i>
                </button>
                <!-- Dropdown menu -->
                <div id="koordinasiDataDropdown" class="hidden absolute left-0 mt-2 w-full md:w-64 bg-white rounded-lg shadow-xl z-50 animate-fade-in-down">
                    <div class="p-2 space-y-1">
                        <div class="font-medium text-sm text-gray-500 px-3 py-2">Menu Koordinasi Data</div>
                        <a href="{{ route('admin.subsistem.bau-bau') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-server mr-2 text-[#009BB9]"></i>
                            Data Subsistem Bau-Bau
                        </a>
                        <a href="{{ route('admin.subsistem.kendari') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-industry mr-2 text-[#009BB9]"></i>
                            Data Subsistem Kendari
                        </a>
                        <a href="{{ route('admin.kit-up-kendari') }}" class="flex items-center px-3 py-2 text-sm text-gray-700 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                            <i class="fas fa-bolt mr-2 text-[#009BB9]"></i>
                            Data KIT UP Kendari
                        </a>
                    </div>
                </div>
            </div>
        </div>

        
        
        <!-- Main Content -->
        
        <div class="px-6">
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


            
                
            
                @if($operationSchedules->count() > 0)
                    <div class="space-y-4 ">
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
                    <div class="text-center py-8 text-gray-500 bg-white rounded-lg shadow-md p-6 mb-6">
                        <i class="fas fa-calendar-times text-4xl mb-3"></i>
                        <p>Tidak ada jadwal operasi untuk hari ini</p>
                    </div>
                @endif
            </div>

            <!-- Unit & Machine Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 ">
                <!-- Power Plants Overview -->
                <div class="bg-white rounded-lg shadow-md p-6 ml-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Unit Pembangkit</h3>
                        <span class="text-sm text-gray-500">Total: {{ $powerPlants->count() }}</span>
                    </div>
                    <div class="space-y-4 max-h-80 overflow-y-auto pr-2">
                        @foreach($powerPlants as $plant)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                <div>
                                    <p class="text-sm font-medium">{{ $plant->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $plant->machines->count() }} Mesin</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">
                                    {{ \App\Models\MachineOperation::whereIn('machine_id', $plant->machines->pluck('id'))
                                        ->sum('dmp') }} MW
                                </p>
                                <p class="text-xs text-gray-500">Kapasitas Total (DMP)</p>
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
                <div class="bg-white rounded-lg shadow-md p-6 mr-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium">Status Mesin</h3>
                        <span class="text-sm text-gray-500">Bulan: {{ \Carbon\Carbon::now()->isoFormat('MMMM Y') }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <!-- OPS Machines -->
                        <div class="bg-green-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm text-gray-600">Operasi (OPS)</p>
                                    <p class="text-2xl font-semibold text-green-600">
                                        {{ $machineStats['ops']['count'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($machineStats['ops']['hours']) }} jam
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
                                        {{ $machineStats['rsh']['count'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($machineStats['rsh']['hours']) }} jam
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
                                        {{ $machineStats['mo']['count'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($machineStats['mo']['hours']) }} jam
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
                                        {{ $machineStats['fo']['count'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($machineStats['fo']['hours']) }} jam
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
                                        {{ $machineStats['po']['count'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($machineStats['po']['hours']) }} jam
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
                                        {{ $machineStats['mb']['count'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ number_format($machineStats['mb']['hours']) }} jam
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const koordinasiDataBtn = document.getElementById('koordinasiDataBtn');
        const koordinasiDataDropdown = document.getElementById('koordinasiDataDropdown');
        
        koordinasiDataBtn.addEventListener('click', function() {
            koordinasiDataDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!koordinasiDataBtn.contains(event.target) && !koordinasiDataDropdown.contains(event.target)) {
                koordinasiDataDropdown.classList.add('hidden');
            }
        });
    });
</script>
@endpush
