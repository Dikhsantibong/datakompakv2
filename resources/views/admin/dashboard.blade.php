@extends('layouts.app')

@push('styles')
    <!-- Tambahkan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Pastikan konten utama dapat di-scroll */
        .main-content {
            overflow-y: auto;
            /* Izinkan scroll vertikal */
            height: calc(100vh - 64px);
            /* Sesuaikan tinggi dengan mengurangi tinggi header */
        }

        /* Styling untuk container grafik */
        .chart-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 500px !important; /* Tinggi container */
            margin: 0 auto;
            padding: 20px;
        }

        /* Styling untuk canvas grafik */
        #srChart, #woChart, #woBacklogChart {
            max-width: 400px !important;
            max-height: 400px !important;
            margin: 0 auto;
        }
    </style>
    
@endpush

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
    
                        <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
                    </div>
    
                    <div class="relative">
                        <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                            <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                                class="w-7 h-7 rounded-full mr-2">
                            <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                            <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                        </button>
                        <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                            <a href="{{ route('logout') }}" 
                               class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                               onclick="event.preventDefault(); 
                                        document.getElementById('logout-form').submit();">Logout</a>
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

            <!-- Dashboard Content -->
            <main class="px-6">
                <!-- Charts -->
                 <!-- Card Total Produksi -->
                      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                        <!-- Card Total Produksi Netto -->
                        <div class="bg-blue-100 rounded-lg shadow p-4 flex items-center" style="height: 150px;">
                            <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Total Produksi Netto</h3>
                                <p class="text-xl font-bold text-gray-900">{{ $totalNetProduction }}</p>
                            </div>
                        </div>
<!-- Card Total Produksi Bruto -->
                        <div class="bg-green-100 rounded-lg shadow p-4 flex items-center" style="height: 150px;">
                            <i class="fas fa-chart-bar text-green-600 mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Total Produksi Bruto</h3>
                                <p class="text-xl font-bold text-gray-900">{{ $totalGrossProduction }}</p>
                            </div>
                        </div>
    
                        <div class="bg-yellow-100 rounded-lg shadow p-4 flex items-center" style="height: 150px;">
                            <i class="fas fa-bolt text-yellow-600 mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Beban Puncak</h3>
                                <p class="text-xl font-bold text-gray-900">{{ $peakLoad }}</p>
                            </div>
                        </div>
    
                        <div class="bg-purple-100 rounded-lg shadow p-4 flex items-center" style="height: 150px;">
                            <i class="fas fa-clock text-purple-600 mr-3"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">Total Jam Operasi</h3>
                                <p class="text-xl font-bold text-gray-900">{{ $totalOperatingHours }}</p>
                            </div>
                        </div>
                    </div>
               
            

                
            </main>
        </div>
    </div>

    <script src="{{ asset('js/toggle.js') }}"></script>

   
    @push('scripts')
    @endpush
@endsection
