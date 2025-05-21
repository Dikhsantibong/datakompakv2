@extends('layouts.app')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }
        .welcome-card {
            background-image: url('{{ asset('images/welcome.webp') }}');
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
        
        .progress-ring {
            transition: all 0.3s ease;
        }
        
        .activity-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        
        .activity-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: currentColor;
        }
    </style>
@endpush

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Monitoring Data Input</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Monitoring Data Input', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto  px-4">
                <!-- Welcome Card -->
                <div class="rounded-lg shadow-sm p-4 mb-6 text-white relative welcome-card min-h-[200px] md:h-64">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-blue-800 opacity-75 rounded-lg"></div>
                    <div class="relative z-10">
                        <!-- Text Content -->
                        <div class="space-y-2 md:space-y-4">
                            <div style="overflow: hidden;">
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight typing-animation">
                                    Monitor Status Input Data
                                </h2>
                            </div>
                            <p class="text-sm md:text-lg font-medium fade-in">
                                PLN NUSANTARA POWER UNIT PEMBANGKITAN KENDARI
                            </p>
                            <div class="backdrop-blur-sm bg-white/30 rounded-lg p-3 fade-in">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $stats['total_units'] }}</div>
                                        <div class="text-sm">Total Unit</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $stats['completed'] }}</div>
                                        <div class="text-sm">Sudah Input</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $stats['pending'] }}</div>
                                        <div class="text-sm">Belum Input</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold">{{ $stats['overdue'] }}</div>
                                        <div class="text-sm">Terlambat</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Logo - Hidden on mobile -->
                        <img src="{{ asset('logo/navlogo.png') }}" alt="Power Plant" class="hidden md:block absolute top-4 right-4 w-32 md:w-48 fade-in">
                    </div>
                </div>

                <!-- Content Sections -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Input Status Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800">
                                <i class="fas fa-clipboard-check mr-2 text-blue-600"></i>
                                Status Input Data
                            </h3>
                            <span class="text-sm text-gray-500">Hari Ini</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Daily Summary</p>
                                    <p class="text-xs text-gray-500">Input data harian</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $stats['completed'] > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $stats['completed'] > 0 ? 'Completed' : 'Pending' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Machine Status</p>
                                    <p class="text-xs text-gray-500">Status mesin terkini</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $stats['completed'] > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $stats['completed'] > 0 ? 'Updated' : 'Pending' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-purple-50 rounded-lg">
                                <div>
                                    <p class="text-sm font-medium text-gray-600">Engine Data</p>
                                    <p class="text-xs text-gray-500">Data performa mesin</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full {{ $stats['completed'] > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $stats['completed'] > 0 ? 'Recorded' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-800">
                                <i class="fas fa-history mr-2 text-blue-600"></i>
                                Aktivitas Terkini
                            </h3>
                            <span class="text-sm text-gray-500">24 Jam Terakhir</span>
                        </div>
                        <div class="relative pl-8 space-y-4 activity-timeline">
                            @foreach($recentActivities->take(5) as $activity)
                                <div class="relative activity-item pl-4 {{ $activity['type'] === 'Daily Summary' ? 'text-blue-600' : ($activity['type'] === 'Machine Status' ? 'text-green-600' : 'text-purple-600') }}">
                                    <div class="bg-white rounded-lg border p-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $activity['unit'] }}</p>
                                                <p class="text-sm text-gray-500">{{ $activity['action'] }}</p>
                                            </div>
                                            <span class="text-xs text-gray-400">{{ Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Unit Status Sections -->
                <div class="space-y-6">
                    <!-- Overdue Units -->
                    @if($overdueUnits->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-red-600">Overdue Units</h3>
                            <span class="text-sm text-gray-500">{{ $overdueUnits->count() }} Units</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($overdueUnits as $unit)
                                <div class="bg-red-50 rounded-lg p-4 unit-card">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $unit['name'] }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                Last Update: {{ $unit['last_update'] ? Carbon\Carbon::parse($unit['last_update'])->diffForHumans() : 'Never' }}
                                            </p>
                                            <div class="mt-3">
                                                <div class="flex flex-wrap gap-2">
                                                    @if(!$unit['daily_summary'])
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Missing Daily Summary
                                                        </span>
                                                    @endif
                                                    @if(!$unit['machine_status'])
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Missing Machine Status
                                                        </span>
                                                    @endif
                                                    @if(!$unit['engine_data'])
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-red-100 text-red-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Missing Engine Data
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Overdue
                                            </span>
                                            <div class="mt-2 text-sm text-gray-500">
                                                {{ $unit['completed_inputs'] }}/3 Complete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Pending Units -->
                    @if($pendingUnits->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-yellow-600">Pending Units</h3>
                            <span class="text-sm text-gray-500">{{ $pendingUnits->count() }} Units</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($pendingUnits as $unit)
                                <div class="bg-yellow-50 rounded-lg p-4 unit-card">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $unit['name'] }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                Last Update: {{ $unit['last_update'] ? Carbon\Carbon::parse($unit['last_update'])->diffForHumans() : 'Never' }}
                                            </p>
                                            <div class="mt-3">
                                                <div class="flex flex-wrap gap-2">
                                                    @if(!$unit['daily_summary'])
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-yellow-100 text-yellow-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Missing Daily Summary
                                                        </span>
                                                    @endif
                                                    @if(!$unit['machine_status'])
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-yellow-100 text-yellow-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Missing Machine Status
                                                        </span>
                                                    @endif
                                                    @if(!$unit['engine_data'])
                                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-yellow-100 text-yellow-700">
                                                            <i class="fas fa-exclamation-circle mr-1"></i> Missing Engine Data
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                            <div class="mt-2 text-sm text-gray-500">
                                                {{ $unit['completed_inputs'] }}/3 Complete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Completed Units -->
                    @if($completedUnits->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-green-600">Completed Units</h3>
                            <span class="text-sm text-gray-500">{{ $completedUnits->count() }} Units</span>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($completedUnits as $unit)
                                <div class="bg-green-50 rounded-lg p-4 unit-card">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">{{ $unit['name'] }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                <i class="fas fa-clock mr-1"></i>
                                                Updated: {{ Carbon\Carbon::parse($unit['last_update'])->diffForHumans() }}
                                            </p>
                                            <div class="mt-3">
                                                <div class="flex flex-wrap gap-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-green-100 text-green-700">
                                                        <i class="fas fa-check-circle mr-1"></i> All Data Complete
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ml-4 flex flex-col items-end">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                            <div class="mt-2 text-sm text-gray-500">
                                                3/3 Complete
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
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

// Set gambar awal
changeBackground();

// Ganti gambar setiap 5 detik
setInterval(changeBackground, 5000);

// Toggle dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdown');
    const dropdownToggle = document.getElementById('dropdownToggle');
    
    if (!dropdown.contains(e.target) && !dropdownToggle.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});
</script>
@endpush

@endsection 