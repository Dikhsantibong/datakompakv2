@extends('layouts.app')

@push('styles')
    <!-- Tambahkan Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Pastikan konten utama dapat di-scroll */
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
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
                <!-- Main Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- Card Total Produksi Netto -->
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow p-4 flex items-center text-white">
                        <i class="fas fa-chart-line text-3xl mr-3 opacity-75"></i>
                        <div>
                            <h3 class="text-sm font-medium opacity-75">Total Produksi Netto</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalNetProduction) }} MW</p>
                        </div>
                    </div>

                    <!-- Card Total Produksi Bruto -->
                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow p-4 flex items-center text-white">
                        <i class="fas fa-chart-bar text-3xl mr-3 opacity-75"></i>
                        <div>
                            <h3 class="text-sm font-medium opacity-75">Total Produksi Bruto</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalGrossProduction) }} MW</p>
                        </div>
                    </div>

                    <!-- Card Beban Puncak -->
                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg shadow p-4 flex items-center text-white">
                        <i class="fas fa-bolt text-3xl mr-3 opacity-75"></i>
                        <div>
                            <h3 class="text-sm font-medium opacity-75">Beban Puncak</h3>
                            <p class="text-2xl font-bold">{{ number_format($peakLoad) }} MW</p>
                        </div>
                    </div>

                    <!-- Card Total Jam Operasi -->
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg shadow p-4 flex items-center text-white">
                        <i class="fas fa-clock text-3xl mr-3 opacity-75"></i>
                        <div>
                            <h3 class="text-sm font-medium opacity-75">Total Jam Operasi</h3>
                            <p class="text-2xl font-bold">{{ number_format($totalOperatingHours) }} Jam</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Access & Recent Activities -->
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <!-- Quick Access -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Akses Cepat</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <a href="{{ route('admin.daily-summary') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-clipboard-list text-2xl text-blue-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Ikhtisar Harian</div>
                                        <div class="text-sm text-gray-500">Input data hari ini</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.laporan.index') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-file-alt text-2xl text-purple-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Laporan</div>
                                        <div class="text-sm text-gray-500">Lihat laporan</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.pembangkit.ready') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-bolt text-2xl text-yellow-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Status Unit</div>
                                        <div class="text-sm text-gray-500">Cek status pembangkit</div>
                                    </div>
                                </a>
                                <a href="{{ route('admin.settings') }}" class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                    <i class="fas fa-cog text-2xl text-gray-500 mr-4"></i>
                                    <div>
                                        <div class="font-medium">Settings</div>
                                        <div class="text-sm text-gray-500">Konfigurasi sistem</div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activities -->
                    <div class="bg-white shadow rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Unit</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">Efisiensi Rata-rata</p>
                                        <p class="text-2xl font-bold text-gray-700">
                                            {{ number_format(($totalNetProduction / ($totalGrossProduction ?: 1)) * 100, 1) }}%
                                        </p>
                                    </div>
                                    <div class="text-3xl text-gray-400">
                                        <i class="fas fa-chart-pie"></i>
                                    </div>
                                </div>
                                <div class="h-px bg-gray-200 my-4"></div>
                                <div class="text-sm text-gray-500">
                                    <p>Rata-rata jam operasi per unit: {{ number_format($totalOperatingHours / 3, 1) }} jam</p>
                                    <p class="mt-2">Beban puncak tertinggi: {{ number_format($peakLoad) }} MW</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Overview -->
                <div class="mt-6 bg-white shadow rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Overview Performa Unit</h3>
                            <select class="text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <option>7 Hari Terakhir</option>
                                <option>30 Hari Terakhir</option>
                                <option>3 Bulan Terakhir</option>
                            </select>
                        </div>
                        <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-chart-area text-4xl mb-2"></i>
                                <p>Grafik performa akan ditampilkan di sini</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/toggle.js') }}"></script>
@endsection
