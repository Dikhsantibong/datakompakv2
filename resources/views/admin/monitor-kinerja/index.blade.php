@extends('layouts.app')

@section('title', 'Kinerja Pembangkit')

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
                    <h1 class="text-xl font-semibold text-gray-900">Kinerja Pembangkit</h1>
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
                            <input type="hidden" name="redirect" value="{{ route('login') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Kinerja Pembangkit', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class=" px-2">
                <!-- Welcome Card -->
                {{-- <div class="rounded-lg shadow-sm p-4 mb-6 text-white relative welcome-card min-h-[200px] md:h-64">
                    <div class="absolute inset-0 bg-blue-500 opacity-50 rounded-lg"></div>
                    <div class="relative z-10">
                        <!-- Text Content -->
                        <div class="space-y-2 md:space-y-4">
                            <div style="overflow: hidden;">
                                <h2 class="text-2xl md:text-3xl font-bold tracking-tight typing-animation">
                                    Monitor Data Kinerja Pembangkit
                                </h2>
                            </div>
                            <p class="text-sm md:text-lg font-medium fade-in">
                                PLN NUSANTARA POWER UNIT PEMBANGKITAN KENDARI
                            </p>
                            <div class="backdrop-blur-sm bg-white/30 rounded-lg p-3 fade-in">
                                <p class="text-xs md:text-base leading-relaxed">
                                    Platform monitoring kinerja pembangkit secara real-time untuk analisis dan pengambilan keputusan yang lebih efektif.
                                </p>
                            </div>
                        </div>

                        <!-- Logo - Hidden on mobile -->
                        <img src="{{ asset('logo/navlogo.png') }}" alt="Power Plant" class="hidden md:block absolute top-4 right-4 w-32 md:w-48 fade-in">
                    </div>
                </div> --}}

                <!-- Unit Filter -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-filter mr-2 text-blue-600"></i>
                            Filter Unit
                        </h3>
                        <select id="unitFilter" name="unit_source" class="form-select rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Unit</option>
                            @foreach($powerPlants as $powerPlant)
                                <option value="{{ $powerPlant->unit_source }}" {{ $selectedUnitSource == $powerPlant->unit_source ? 'selected' : '' }}>
                                    {{ $powerPlant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Performance Indicators -->
                <div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-6">
                    <!-- EAF Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-blue-600 mb-2">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">EAF</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['eaf'], 1) }}%</p>
                            <span class="text-blue-600 text-sm font-medium">Equivalent Availability Factor</span>
                        </div>
                    </div>

                    <!-- SOF Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-red-600 mb-2">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">SOF</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['sof'], 1) }}%</p>
                            <span class="text-red-600 text-sm font-medium">Scheduled Outage Factor</span>
                        </div>
                    </div>

                    <!-- EFOR Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-yellow-600 mb-2">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">EFOR</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['efor'], 1) }}%</p>
                            <span class="text-yellow-600 text-sm font-medium">Equivalent Forced Outage Rate</span>
                        </div>
                    </div>

                    <!-- SdOF Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-indigo-600 mb-2">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">SdOF</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['sdof'], 1) }}%</p>
                            <span class="text-indigo-600 text-sm font-medium">Sudden Outage Factor</span>
                        </div>
                    </div>

                    <!-- NCF Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-emerald-600 mb-2">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">NCF</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['ncf'], 1) }}%</p>
                            <span class="text-emerald-600 text-sm font-medium">Net Capacity Factor</span>
                        </div>
                    </div>

                    <!-- Transformer Losses Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-purple-600 mb-2">
                                <i class="fas fa-plug"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">PS Susut Trafo</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($transformerLosses['current'], 2) }}</p>
                            <span class="text-purple-600 text-sm font-medium">{{ $transformerLosses['unit'] }}</span>
                        </div>
                    </div>

                    <!-- Kit Ratio Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-pink-600 mb-2">
                                <i class="fas fa-percentage"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Kit Ratio</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['kit_ratio'], 1) }}%</p>
                            <span class="text-pink-600 text-sm font-medium">Rasio Daya KIT</span>
                        </div>
                    </div>

                    <!-- Usage Percentage Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-orange-600 mb-2">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Usage (%)</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['usage_percentage'], 1) }}%</p>
                            <span class="text-orange-600 text-sm font-medium">Persentase Pemakaian Sendiri</span>
                        </div>
                    </div>

                    <!-- Water Usage Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-cyan-600 mb-2">
                                <i class="fas fa-tint"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Air (M³)</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($fuelUsage['water'], 1) }}</p>
                            <span class="text-cyan-600 text-sm font-medium">Pemakaian Air</span>
                        </div>
                    </div>

                    <!-- Trip Machine Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-gray-700 mb-2">
                                <i class="fas fa-tools"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Trip Mesin</h3>
                            <p class="text-gray-700 mb-2 text-sm">{{ number_format($performance['trip_machine'] ?? ($latestSummary->trip_machine ?? 0), 0) }}</p>
                            <span class="text-gray-700 text-sm font-medium">Jumlah Trip Mesin</span>
                        </div>
                    </div>

                    <!-- Trip Electrical Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-blue-700 mb-2">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Trip Listrik</h3>
                            <p class="text-blue-700 mb-2 text-sm">{{ number_format($performance['trip_electrical'] ?? ($latestSummary->trip_electrical ?? 0), 0) }}</p>
                            <span class="text-blue-700 text-sm font-medium">Jumlah Trip Listrik</span>
                        </div>
                    </div>

                    <!-- EFDH Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-green-600 mb-2">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">EFDH</h3>
                            <p class="text-green-600 mb-2 text-sm">{{ number_format($performance['efdh'] ?? ($latestSummary->efdh ?? 0), 1) }}</p>
                            <span class="text-green-600 text-sm font-medium">Equivalent Forced Derating Hours</span>
                        </div>
                    </div>

                    <!-- EPDH Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-orange-500 mb-2">
                                <i class="fas fa-hourglass-end"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">EPDH</h3>
                            <p class="text-orange-500 mb-2 text-sm">{{ number_format($performance['epdh'] ?? ($latestSummary->epdh ?? 0), 1) }}</p>
                            <span class="text-orange-500 text-sm font-medium">Equivalent Planned Derating Hours</span>
                        </div>
                    </div>

                    <!-- EUDH Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-purple-600 mb-2">
                                <i class="fas fa-hourglass-start"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">EUDH</h3>
                            <p class="text-purple-600 mb-2 text-sm">{{ number_format($performance['eudh'] ?? ($latestSummary->eudh ?? 0), 1) }}</p>
                            <span class="text-purple-600 text-sm font-medium">Equivalent Unplanned Derating Hours</span>
                        </div>
                    </div>

                    <!-- ESDH Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-pink-600 mb-2">
                                <i class="fas fa-hourglass"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">ESDH</h3>
                            <p class="text-pink-600 mb-2 text-sm">{{ number_format($performance['esdh'] ?? ($latestSummary->esdh ?? 0), 1) }}</p>
                            <span class="text-pink-600 text-sm font-medium">Equivalent Sudden Derating Hours</span>
                        </div>
                    </div>

                    <!-- JSI Card -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-fuchsia-600 mb-2">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">JSI</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ number_format($performance['jsi'], 1) }}</p>
                            <span class="text-fuchsia-600 text-sm font-medium">Jadwal Shift Indeks</span>
                        </div>
                    </div>
                </div>

                <!-- Operating & Production Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Operating Statistics -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium">Statistik Operasi</h3>
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Jam Operasi</p>
                                <p class="text-2xl font-semibold text-blue-600">{{ $operatingStats['operating_hours'] }}</p>
                                <p class="text-sm text-gray-500">jam</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Jam Standby</p>
                                <p class="text-2xl font-semibold text-green-600">{{ $operatingStats['standby_hours'] }}</p>
                                <p class="text-sm text-gray-500">jam</p>
                            </div>
                        </div>
                    </div>

                    <!-- Production Statistics -->
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium">Statistik Produksi</h3>
                            <i class="fas fa-bolt text-yellow-600"></i>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Produksi Bruto</p>
                                <p class="text-2xl font-semibold text-purple-600">{{ $productionStats['gross_production'] }}</p>
                                <p class="text-sm text-gray-500">MW</p>
                            </div>
                            <div class="bg-indigo-50 rounded-lg p-4">
                                <p class="text-sm text-gray-600">Produksi Netto</p>
                                <p class="text-2xl font-semibold text-indigo-600">{{ $productionStats['net_production'] }}</p>
                                <p class="text-sm text-gray-500">MW</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Fuel Usage -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-gas-pump mr-2 text-gray-600"></i>
                            Penggunaan Bahan Bakar
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">HSD</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['hsd'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">B35</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['b35'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">B40</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['b40'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">MFO</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['mfo'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['total'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Oil Usage -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-oil-can mr-2 text-gray-600"></i>
                            Penggunaan Pelumas
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Meditran</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['meditran'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Salyx 420</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['salyx_420'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Salyx 430</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['salyx_430'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['total'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Parameters -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-cogs mr-2 text-gray-600"></i>
                            Parameter Teknis
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">SFC/SCC</span>
                                <span class="font-semibold text-gray-800">{{ $technicalParams['sfc_scc'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">NPHR</span>
                                <span class="font-semibold text-gray-800">{{ $technicalParams['nphr'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">SLC</span>
                                <span class="font-semibold text-gray-800">{{ $technicalParams['slc'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-medium mb-4">Grafik Kinerja Pembangkit</h3>
                    <div style="height: 300px;">
                        <canvas id="performanceChart"></canvas>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Production Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium mb-4">Grafik Produksi Netto</h3>
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
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Kit Ratio Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium mb-4">Grafik Kit Ratio 7 Hari Terakhir</h3>
                        <div style="height: 300px;">
                            <canvas id="kitRatioChart"></canvas>
                        </div>
                    </div>
                    <!-- Usage Percentage Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium mb-4">Grafik Usage Percentage 7 Hari Terakhir</h3>
                        <div style="height: 300px;">
                            <canvas id="usageChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Trip Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium mb-4">Grafik Trip Mesin & Trip Listrik</h3>
                        <div style="height: 300px;">
                            <canvas id="tripChart"></canvas>
                        </div>
                    </div>
                    <!-- Derating Hours Chart -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-medium mb-4">Grafik Derating Hours (EFDH, EPDH, EUDH, ESDH)</h3>
                        <div style="height: 300px;">
                            <canvas id="deratingChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h3 class="text-lg font-medium mb-4">Grafik JSI 7 Hari Terakhir</h3>
                    <div style="height: 300px;">
                        <canvas id="jsiChart"></canvas>
                    </div>
                </div>

                <!-- Tabel Rekap Kinerja Mesin per Unit -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800">Tabel Rekap Kinerja Mesin per Unit</h3>
                    @foreach($powerPlants as $powerPlant)
                    <div class="bg-white rounded-lg shadow p-6 mb-4 unit-table">
                        <div class="overflow-auto">
                            <div class="flex justify-between items-center mb-4">
                                <div class="flex flex-col">
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $powerPlant->name }}</h2>
                                    <div class="text-sm text-gray-600">
                                        <span class="font-medium">Update Terakhir:</span>
                                        <span id="last_update_{{ $powerPlant->id }}" class="ml-1">
                                            @php
                                                $lastUpdate = $dailySummaries
                                                    ->where('power_plant_id', $powerPlant->id)
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
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Operasi</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam Standby</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produksi Netto (MW)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kit Ratio (%)</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @php $summariesInUnit = $dailySummaries->where('power_plant_id', $powerPlant->id); @endphp
                                        @forelse($summariesInUnit as $i => $summary)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $i+1 }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->machine_name }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->date ? \Carbon\Carbon::parse($summary->date)->format('d/m/Y') : '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->operating_hours ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->standby_hours ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->net_production ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->kit_ratio ?? '-' }}</td>
                                            <td class="px-4 py-2 border-r border-gray-200">{{ $summary->notes ?? '-' }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="px-4 py-2 text-center text-gray-500">Tidak ada data summary untuk unit ini</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Keterangan Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                            Keterangan Indikator
                        </h3>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">EAF (Equivalent Availability Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor kesiapan pembangkit untuk beroperasi.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">SOF (Scheduled Outage Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor pemeliharaan terjadwal pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">EFOR (Equivalent Forced Outage Rate)</dt>
                                <dd class="col-span-2 text-gray-600">Tingkat gangguan paksa pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">SdOF (Sudden Outage Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor gangguan mendadak pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">NCF (Net Capacity Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor kapasitas bersih pembangkit.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

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
                font-size: 1.5rem; /* Ukuran font yang lebih kecil di mobile */
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

        /* Tambahan untuk memastikan text tetap terbaca */
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
    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                { label: 'EAF (%)', data: {!! json_encode($chartData['eaf']) !!}, borderColor: 'rgb(59, 130, 246)', backgroundColor: 'rgba(59, 130, 246, 0.1)', borderWidth: 2, pointRadius: 4, pointHoverRadius: 6, tension: 0.3 },
                { label: 'SOF (%)', data: {!! json_encode($chartData['sof']) !!}, borderColor: 'rgb(239, 68, 68)', backgroundColor: 'rgba(239, 68, 68, 0.1)', borderWidth: 2, pointRadius: 4, pointHoverRadius: 6, tension: 0.3 },
                { label: 'EFOR (%)', data: {!! json_encode($chartData['efor']) !!}, borderColor: 'rgb(234, 179, 8)', backgroundColor: 'rgba(234, 179, 8, 0.1)', borderWidth: 2, pointRadius: 4, pointHoverRadius: 6, tension: 0.3 },
                { label: 'NCF (%)', data: {!! json_encode($chartData['ncf']) !!}, borderColor: 'rgb(34, 197, 94)', backgroundColor: 'rgba(34, 197, 94, 0.1)', borderWidth: 2, pointRadius: 4, pointHoverRadius: 6, tension: 0.3 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'top', labels: { usePointStyle: true, padding: 20, font: { size: 12 } } }, tooltip: { backgroundColor: 'rgba(255, 255, 255, 0.9)', titleColor: '#1f2937', bodyColor: '#1f2937', borderColor: '#e5e7eb', borderWidth: 1, padding: 12, displayColors: true, callbacks: { label: function(context) { return context.dataset.label + ': ' + context.parsed.y.toFixed(1) + '%'; } } } },
            scales: { y: { beginAtZero: true, max: 100, grid: { drawBorder: false, color: 'rgba(0, 0, 0, 0.05)' }, ticks: { callback: function(value) { return value + '%'; }, font: { size: 11 } } }, x: { grid: { drawBorder: false, color: 'rgba(0, 0, 0, 0.05)' }, ticks: { font: { size: 11 } } } }
        }
    });
    // Production Chart
    const productionCtx = document.getElementById('productionChart').getContext('2d');
    new Chart(productionCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{ label: 'Produksi Netto (MW)', data: {!! json_encode($chartData['production']) !!}, borderColor: 'rgb(59, 130, 246)', backgroundColor: 'rgba(59, 130, 246, 0.1)', fill: true, tension: 0.4 }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return value + ' MW'; } } } }
        }
    });
    // Fuel Consumption Chart
    const fuelCtx = document.getElementById('fuelChart').getContext('2d');
    new Chart(fuelCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                { label: 'HSD', data: {!! json_encode($chartData['fuel']['hsd']) !!}, backgroundColor: 'rgba(34, 197, 94, 0.5)', borderColor: 'rgb(34, 197, 94)', borderWidth: 1 },
                { label: 'MFO', data: {!! json_encode($chartData['fuel']['mfo']) !!}, backgroundColor: 'rgba(234, 179, 8, 0.5)', borderColor: 'rgb(234, 179, 8)', borderWidth: 1 },
                { label: 'B35', data: {!! json_encode($chartData['fuel']['b35']) !!}, backgroundColor: 'rgba(59, 130, 246, 0.5)', borderColor: 'rgb(59, 130, 246)', borderWidth: 1 },
                { label: 'B40', data: {!! json_encode($chartData['fuel']['b40']) !!}, backgroundColor: 'rgba(147, 51, 234, 0.5)', borderColor: 'rgb(147, 51, 234)', borderWidth: 1 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return value + ' L'; } } } }
        }
    });
    // Kit Ratio Chart
    const kitRatioCtx = document.getElementById('kitRatioChart').getContext('2d');
    new Chart(kitRatioCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{ label: 'Kit Ratio (%)', data: {!! json_encode($chartData['kit_ratio']) !!}, backgroundColor: 'rgba(236, 72, 153, 0.5)', borderColor: 'rgb(236, 72, 153)', borderWidth: 1 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } } }
    });
    // Usage Percentage Chart
    const usageCtx = document.getElementById('usageChart').getContext('2d');
    new Chart(usageCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{ label: 'Usage (%)', data: {!! json_encode($chartData['usage_percentage']) !!}, backgroundColor: 'rgba(251, 191, 36, 0.5)', borderColor: 'rgb(251, 191, 36)', borderWidth: 1 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' } } } }
    });
    // Trip Chart
    const tripCtx = document.getElementById('tripChart').getContext('2d');
    new Chart(tripCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                { label: 'Trip Mesin', data: {!! json_encode($chartData['trip_machine']) !!}, borderColor: 'rgb(75, 85, 99)', backgroundColor: 'rgba(75, 85, 99, 0.1)', fill: true, tension: 0.3 },
                { label: 'Trip Listrik', data: {!! json_encode($chartData['trip_electrical']) !!}, borderColor: 'rgb(156, 163, 175)', backgroundColor: 'rgba(156, 163, 175, 0.1)', fill: true, tension: 0.3 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });
    // Derating Hours Chart
    const deratingCtx = document.getElementById('deratingChart').getContext('2d');
    new Chart(deratingCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                { label: 'EFDH', data: {!! json_encode($chartData['efdh']) !!}, backgroundColor: 'rgba(132, 204, 22, 0.5)', borderColor: 'rgb(132, 204, 22)', borderWidth: 1 },
                { label: 'EPDH', data: {!! json_encode($chartData['epdh']) !!}, backgroundColor: 'rgba(163, 230, 53, 0.5)', borderColor: 'rgb(163, 230, 53)', borderWidth: 1 },
                { label: 'EUDH', data: {!! json_encode($chartData['eudh']) !!}, backgroundColor: 'rgba(190, 242, 100, 0.5)', borderColor: 'rgb(190, 242, 100)', borderWidth: 1 },
                { label: 'ESDH', data: {!! json_encode($chartData['esdh']) !!}, backgroundColor: 'rgba(217, 249, 157, 0.5)', borderColor: 'rgb(217, 249, 157)', borderWidth: 1 }
            ]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
    });
    // JSI Chart
    const jsiCtx = document.getElementById('jsiChart').getContext('2d');
    new Chart(jsiCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [{ label: 'JSI', data: {!! json_encode($chartData['jsi']) !!}, backgroundColor: 'rgba(232, 121, 249, 0.5)', borderColor: 'rgb(232, 121, 249)', borderWidth: 1 }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
    // Event handler filter unit
    const unitFilter = document.getElementById('unitFilter');
    if (unitFilter) {
        unitFilter.addEventListener('change', function(e) {
            window.location.href = `${window.location.pathname}?unit_source=${e.target.value}`;
        });
    }
});
</script>
@endpush
