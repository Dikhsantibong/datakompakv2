@extends('layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="flex h-screen overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto">
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

                    <h1 class="text-xl font-semibold text-gray-800">Ikhtisar Harian</h1>
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

        {{-- @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif --}}

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'IKHTISAR HARIAN', 'url' => null]]" />
        </div>

        <div class="bg-white p-6">
            <form action="{{ route('daily-summary.store') }}" method="POST" novalidate>
                @csrf
                <!-- Search & Buttons Container -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 px-4 gap-4">
                    <!-- Date Input -->
                    <div class="w-full sm:w-72">
                        <label for="input-date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Input</label>
                        <input type="date" 
                               id="input-date" 
                               name="input_date" 
                               value="{{ request('input_date', now()->format('Y-m-d')) }}"
                               class="w-full appearance-none rounded-md border border-gray-300 bg-white pl-4 pr-10 py-2 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer hover:border-gray-400 transition-colors duration-200">
                    </div>

                    <!-- Search Unit -->
                    <div class="w-full sm:w-72">
                        @if(session('unit') === 'mysql')
                        <label for="unit-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Unit Pembangkit</label>
                        <div class="relative">
                            <select id="unit-filter" name="unit_source" class="w-full appearance-none rounded-md border border-gray-300 bg-white pl-4 pr-10 py-2 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer hover:border-gray-400 transition-colors duration-200 select-none">
                                <option value="all">Semua Unit</option>
                                @foreach($unitSources as $source)
                                    @php
                                        $sourceName = match($source) {
                                            'mysql' => 'UP KENDARI',
                                            'mysql_wua_wua' => 'PLTD WUA-WUA', 
                                            'mysql_poasia' => 'PLTD POASIA',
                                            'mysql_kolaka' => 'PLTD KOLAKA',
                                            'mysql_bau_bau' => 'PLTD BAU-BAU',
                                            default => strtoupper($source)
                                        };
                                    @endphp
                                    <option value="{{ $source }}" {{ $unitSource === $source ? 'selected' : '' }}>
                                        {{ $sourceName }}
                                    </option>
                                @endforeach
                            </select>
                            @endif
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4 justify-end">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Simpan
                        </button>

                        <button type="button" id="refreshButton" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Refresh
                        </button>
                    <button type="button" onclick="window.location.href='{{ route('daily-summary.results') }}'" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-cogs mr-2"></i>
                        Kelola
                    </button>

                        
                    </div>
                </div>

                @foreach($units as $powerPlant) 
                <div class="bg-white rounded shadow-md p-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 text-left mb-4">{{ $powerPlant->name }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 3800px;">
                                <thead class="bg-gray-50">
                                    <tr class="text-center border-b">
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-mesin">Mesin</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-daya">Daya (MW)</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-beban">Beban Puncak (kW)</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-beban">Ratio Daya Kit (%)</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-produksi">Produksi (kWh)</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-pemakaian-sendiri">Pemakaian Sendiri</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-jam-operasi">Jam Periode</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-jam-operasi">Jam Operasi</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-trip">Trip Non OMC</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-derating">Derating</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-kinerja">Kinerja Pembangkit</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-capability">Capability Factor (%)</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-nof">Nett Operating Factor (%)</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-jsi">JSI</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-bahan-bakar">Pemakaian Bahan Bakar/Baku</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-pelumas">Pemakaian Pelumas</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-efisiensi">Effisiensi</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r  w-keterangan">Ket.</th>
                                    </tr>
                                    <tr class="bg-gray-100 text-xs">
                                        <th class="border-r"></th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <span class="subcol-border px-2 mr-4">Terpasang</span>
                                                <span class="subcol-border px-2 mr-4" style="margin-left: 10px;">DMN</span>
                                                <span class="px-2" style="margin-left: 10px;">Mampu</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <span class="subcol-border px-2">Siang</span>
                                                <span class="px-2">Malam</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="text-center px-2">
                                                <span>Kit</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <span class="subcol-border px-2">Bruto</span>
                                                <span class="px-2">Netto</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <span class="subcol-border px-2">Aux (kWh)</span>
                                                <span class="subcol-border px-2">Susut Trafo (kWh)</span>
                                                <span class="px-2">Persentase (%)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="text-center px-2">
                                                <span>Jam</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2">
                                            <div class="grid grid-cols-5 gap-0">
                                                <span class="subcol-border px-2">OPR</span>
                                                <span class="subcol-border px-2">STANDBY</span>
                                                <span class="subcol-border px-2">PO</span>
                                                <span class="subcol-border px-2">MO</span>
                                                <span class="px-2">FO</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <span class="subcol-border px-2">Mesin (kali)</span>
                                                <span class="px-2">Listrik (kali)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2">
                                            <div class="grid grid-cols-4 gap-0">
                                                <span class="subcol-border px-2">EFDH</span>
                                                <span class="subcol-border px-2">EPDH</span>
                                                <span class="subcol-border px-2">EUDH</span>
                                                <span class="px-2">ESDH</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <span class="subcol-border px-2">EAF (%)</span>
                                                <span class="subcol-border px-2">SOF (%)</span>
                                                <span class="subcol-border px-2">EFOR (%)</span>
                                                <span class="px-2">SdOF (Kali)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2">
                                            <div class="text-center">
                                                <span class="px-2">NCF</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="text-center">
                                                <span class="px-2">NOF</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="text-center">
                                                <span class="px-2">Jam</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border border-gray-300">
                                            <div class="grid grid-cols-5 gap-1">
                                                <span class="px-2 text-center border-r border-gray-300">HSD (Liter)</span>
                                                <span class="px-2 text-center border-r border-gray-300">B35 (Liter)</span>
                                                <span class="px-2 text-center border-r border-gray-300">MFO (Liter)</span>
                                                <span class="px-2 text-center border-r border-gray-300">Total BBM (Liter)</span>
                                                <span class="px-2 text-center">Air (M³)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border border-gray-300 w-pelumas">
                                            <div class="grid grid-cols-7 gap-0">
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">Meditran SX 15W/40 CH-4 (LITER)</span>
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">Salyx 420 (LITER)</span>
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">Salyx 430 (LITER)</span>
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">TravoLube A (LITER)</span>
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">Turbolube 46 (LITER)</span>
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">Turbolube 68 (LITER)</span>
                                                <span class="px-1 text-center text-pelumas">TOTAL (LITER)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border border-gray-300 w-efisiensi">
                                            <div class="grid grid-cols-3 gap-0">
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">SFC/SCC (LITER/KWH)</span>
                                                <span class="px-1 text-center border-r border-gray-300 text-pelumas">TARA KALOR/NPHR (KCAL/KWH)</span>
                                                <span class="px-1 text-center text-pelumas">SLC (CC/KWH)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2">
                                            <div class="text-center">
                                                <span class="px-2">Keterangan</span>
                                            </div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($powerPlant->machines as $machine)
                                    <tr>
                                        <td class="px-4 py-3 border-r">
                                            <div class="text-sm font-medium text-gray-900 text-center">{{ $machine->name }}</div>
                                            <input type="hidden" name="data[{{ $machine->id }}][power_plant_id]" value="{{ $powerPlant->id }}">
                                            <input type="hidden" name="data[{{ $machine->id }}][machine_name]" value="{{ $machine->name }}">
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="input-group">
                                                    <input type="number" 
                                                           step="0.001" 
                                                           name="data[{{ $machine->id }}][installed_power]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center"
                                                           value="{{ old('data.'.$machine->id.'.installed_power', '') }}"
                                                           min="0">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" 
                                                           name="data[{{ $machine->id }}][dmn_power]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center"
                                                           value="{{ old('data.'.$machine->id.'.dmn_power') }}">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" 
                                                           name="data[{{ $machine->id }}][capable_power]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center"
                                                           value="{{ old('data.'.$machine->id.'.capable_power') }}">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][peak_load_day]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][peak_load_night]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][kit_ratio]"
                                                       class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][gross_production]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][net_production]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][aux_power]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][transformer_losses]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][usage_percentage]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                @php
                                                    // Get the first day of current month
                                                    $firstDayOfMonth = \Carbon\Carbon::now()->startOfMonth();
                                                    $today = \Carbon\Carbon::now();
                                                    $dayOfMonth = $today->day;
                                                    
                                                    // Get the last record for this machine from previous month
                                                    $lastMonthRecord = \App\Models\DailySummary::where('power_plant_id', $machine->power_plant_id)
                                                        ->where('machine_name', $machine->name)
                                                        ->whereMonth('created_at', $firstDayOfMonth->copy()->subMonth()->month)
                                                        ->orderBy('created_at', 'desc')
                                                        ->first();
                                                    
                                                    // Get all records for this machine in current month
                                                    $currentMonthRecords = \App\Models\DailySummary::where('power_plant_id', $machine->power_plant_id)
                                                        ->where('machine_name', $machine->name)
                                                        ->whereMonth('created_at', $today->month)
                                                        ->orderBy('created_at', 'desc')
                                                        ->get();

                                                    // Calculate period hours - always 24 hours per day
                                                    $periodHours = 24;

                                                    // If there are records this month, calculate cumulative hours
                                                    if ($currentMonthRecords->isNotEmpty()) {
                                                        $periodHours = $dayOfMonth * 24; // Jumlah hari dalam bulan ini * 24
                                                    }
                                                @endphp
                                                <input type="number" 
                                                       step="0.01" 
                                                       name="data[{{ $machine->id }}][period_hours]"
                                                       value="{{ $periodHours }}"
                                                       class="block w-full border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center"
                                                       readonly>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][operating_hours]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][standby_hours]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][planned_outage]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][maintenance_outage]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][forced_outage]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="1" name="data[{{ $machine->id }}][trip_machine]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="1" name="data[{{ $machine->id }}][trip_electrical]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][efdh]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][epdh]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][eudh]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][esdh]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][eaf]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][sof]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][efor]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="1" name="data[{{ $machine->id }}][sdof]"
                                                           class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][ncf]"
                                                       class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][nof]"
                                                       class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][jsi]"
                                                       class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border border-gray-300">
                                            <div class="grid grid-cols-5 gap-1">
                                                <div class="input-group border-r border-gray-300">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][hsd_fuel]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][b35_fuel]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][mfo_fuel]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][total_fuel]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][water_usage]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border border-gray-300">
                                            <div class="grid grid-cols-7 gap-0">
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][meditran_oil]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][salyx_420]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][salyx_430]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][travolube_a]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][turbolube_46]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][turbolube_68]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group px-0.5">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][total_oil]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border border-gray-300">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][sfc_scc]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group border-r border-gray-300 px-0.5">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][nphr]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group px-0.5">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][slc]"
                                                           class="block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="px-2">
                                                <textarea name="data[{{ $machine->id }}][notes]"
                                                       style="width: 200px;"
                                                       class="block w-full  border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                </textarea>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitFilter = document.getElementById('unit-filter');
    const refreshButton = document.getElementById('refreshButton');

    // Handle unit filter change
    unitFilter.addEventListener('change', function() {
        const selectedUnit = this.value;
        
        // Show loading indicator
        const loadingOverlay = document.createElement('div');
        loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        loadingOverlay.innerHTML = `
            <div class="bg-white p-4 rounded-lg shadow-lg">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                <p class="mt-2 text-white">Memuat data...</p>
            </div>
        `;
        document.body.appendChild(loadingOverlay);

        // Send request to update unit source
        fetch('{{ route("set-unit-source") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ unit_source: selectedUnit })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to reflect the changes
                window.location.reload();
            } else {
                console.error('Failed to update unit source');
                alert('Gagal mengubah unit. Silakan coba lagi.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan. Silakan coba lagi.');
        })
        .finally(() => {
            // Remove loading overlay
            loadingOverlay.remove();
        });
    });

    // Handle refresh button click
    refreshButton.addEventListener('click', function() {
        window.location.reload();
    });
});
</script>
<style>
.w-mesin {
    min-width: 100px !important;
}
.w-daya {
    min-width: 300px !important;
}
.w-beban {
    min-width: 200px !important;
}
.w-ratio {
    min-width: 100px !important;
}
.w-produksi {
    min-width: 200px !important;
}
.w-pemakaian-sendiri {
    min-width: 300px !important;
}
.w-jam-operasi {
    min-width: 400px !important;
}
.w-trip {
    min-width: 200px !important;
}
.w-derating {
    min-width: 400px !important;
}
.w-kinerja {
    min-width: 400px !important;
}
.w-capability {
    min-width: 150px !important;
}
.w-nof {
    min-width: 150px !important;
}
.w-jsi {
    min-width: 150px !important;
}
.w-bahan-bakar {
    min-width: 500px !important;
}
.w-pelumas {
    min-width: 700px !important;
}
.w-efisiensi {
    min-width: 350px !important;
}
.w-keterangan {
    min-width: 150px !important;

}
.text-pelumas {
    font-size: 11px !important;
}

/* Remove default arrow in modern browsers */
select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
}

/* Remove default arrow in IE */
select::-ms-expand {
    display: none;
}
</style>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: "{{ session('error') }}",
    });
</script>
@endif

<script>
// Hilangkan validasi default browser
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('invalid', (e) => {
        e.preventDefault();
    });
});

// Modifikasi event handler form submission
document.querySelector('form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Cek apakah ada minimal satu data yang diisi
    const formData = new FormData(this);
    let hasFilledData = false;

    // Cek setiap mesin
    for (let [key, value] of formData.entries()) {
        if (key.includes('[installed_power]') && value) {
            hasFilledData = true;
            break;
        }
    }

    if (!hasFilledData) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Harap isi minimal satu data mesin!'
        });
        return;
    }

    // Jika ada data yang diisi, lanjutkan dengan konfirmasi
    Swal.fire({
        title: 'Konfirmasi',
        text: "Apakah Anda yakin ingin menyimpan data?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Simpan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Menyimpan Data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            this.submit();
        }
    });
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all input fields
        document.querySelectorAll('input[type="number"]').forEach(input => {
            const name = input.getAttribute('name');
            if (!name) return;
            
            // Extract machine ID from input name
            const match = name.match(/data\[(\d+)\]/);
            if (!match) return;
            
            const machineId = match[1];
            
            input.addEventListener('input', function() {
                // Update calculations based on which field was changed
                const fieldName = name.match(/\[([^\]]+)\]$/)[1];
                
                switch(fieldName) {
                    case 'peak_load_day':
                    case 'peak_load_night':
                    case 'capable_power':
                        calculateRatioDaya(machineId);
                        break;
                        
                    case 'gross_production':
                    case 'net_production':
                    case 'aux_power':
                        calculateSusutTrafo(machineId);
                        calculatePersentasePS(machineId);
                        calculateSFC(machineId);
                        calculateSLC(machineId);
                        break;
                        
                    case 'transformer_losses':
                        calculatePersentasePS(machineId);
                        break;
                        
                    case 'operating_hours':
                    case 'standby_hours':
                    case 'efdh':
                    case 'epdh':
                    case 'eudh':
                    case 'esdh':
                        calculateEAF(machineId);
                        calculateEFOR(machineId);
                        calculateNOF(machineId);
                        break;
                        
                    case 'planned_outage':
                    case 'maintenance_outage':
                        calculateSOF(machineId);
                        break;
                        
                    case 'forced_outage':
                        calculateEFOR(machineId);
                        break;
                        
                    case 'trip_machine':
                    case 'trip_electrical':
                        calculateSDOF(machineId);
                        break;
                        
                    case 'period_hours':
                        calculateEAF(machineId);
                        calculateSOF(machineId);
                        calculateNCF(machineId);
                        break;
                        
                    case 'hsd_fuel':
                    case 'b35_fuel':
                    case 'mfo_fuel':
                        calculateTotalFuel(machineId);
                        calculateSFC(machineId);
                        break;
                        
                    case 'meditran_oil':
                    case 'salyx_420':
                    case 'salyx_430':
                    case 'travolube_a':
                    case 'turbolube_46':
                    case 'turbolube_68':
                        calculateTotalOil(machineId);
                        calculateSLC(machineId);
                        break;
                }
                
                // Always update NCF and NOF when any relevant field changes
                if (['net_production', 'capable_power', 'period_hours', 'operating_hours'].includes(fieldName)) {
                    calculateNCF(machineId);
                    calculateNOF(machineId);
                }
            });
        });
    });

    // Helper function to get numeric value from input
    function getNumericValue(machineId, field) {
        const value = document.querySelector(`[name="data[${machineId}][${field}]"]`).value;
        return parseFloat(value) || 0;
    }

    // Ratio Daya = max(peak_load_day, peak_load_night) / capable_power
    function calculateRatioDaya(machineId) {
        const peakDay = getNumericValue(machineId, 'peak_load_day');
        const peakNight = getNumericValue(machineId, 'peak_load_night');
        const capablePower = getNumericValue(machineId, 'capable_power');
        
        if (capablePower > 0) {
            const ratio = (Math.max(peakDay, peakNight) / capablePower) * 100;
            document.querySelector(`[name="data[${machineId}][kit_ratio]"]`).value = ratio.toFixed(2);
        }
    }

    // Susut Trafo = gross_production - net_production - aux_power
    function calculateSusutTrafo(machineId) {
        const grossProd = getNumericValue(machineId, 'gross_production');
        const netProd = getNumericValue(machineId, 'net_production');
        const auxPower = getNumericValue(machineId, 'aux_power');
        
        const susutTrafo = grossProd - netProd - auxPower;
        document.querySelector(`[name="data[${machineId}][transformer_losses]"]`).value = susutTrafo.toFixed(2);
    }

    // Persentase PS = ((aux_power + transformer_losses) / gross_production) * 100
    function calculatePersentasePS(machineId) {
        const auxPower = getNumericValue(machineId, 'aux_power');
        const susutTrafo = getNumericValue(machineId, 'transformer_losses');
        const grossProd = getNumericValue(machineId, 'gross_production');
        
        if (grossProd > 0) {
            const percentage = ((auxPower + susutTrafo) / grossProd) * 100;
            document.querySelector(`[name="data[${machineId}][usage_percentage]"]`).value = percentage.toFixed(2);
        }
    }

    // EAF = ((SH + Standby - EFDH - EPDH - EUDH - ESDH) * capable_power) / (period_hours * capable_power) * 100
    function calculateEAF(machineId) {
        const operatingHours = getNumericValue(machineId, 'operating_hours');
        const standbyHours = getNumericValue(machineId, 'standby_hours');
        const efdh = getNumericValue(machineId, 'efdh');
        const epdh = getNumericValue(machineId, 'epdh');
        const eudh = getNumericValue(machineId, 'eudh');
        const esdh = getNumericValue(machineId, 'esdh');
        const capablePower = getNumericValue(machineId, 'capable_power');
        const periodHours = getNumericValue(machineId, 'period_hours');
        
        if (periodHours > 0 && capablePower > 0) {
            const eaf = ((operatingHours + standbyHours - efdh - epdh - eudh - esdh) * capablePower) / 
                       (periodHours * capablePower) * 100;
            document.querySelector(`[name="data[${machineId}][eaf]"]`).value = eaf.toFixed(2);
        }
    }

    // SOF = ((PO + MO) * capable_power) / (period_hours * capable_power) * 100
    function calculateSOF(machineId) {
        const po = getNumericValue(machineId, 'planned_outage');
        const mo = getNumericValue(machineId, 'maintenance_outage');
        const capablePower = getNumericValue(machineId, 'capable_power');
        const periodHours = getNumericValue(machineId, 'period_hours');
        
        if (periodHours > 0 && capablePower > 0) {
            const sof = ((po + mo) * capablePower) / (periodHours * capablePower) * 100;
            document.querySelector(`[name="data[${machineId}][sof]"]`).value = sof.toFixed(2);
        }
    }

    // EFOR = ((FO + EFDH) * capable_power) / ((operating_hours + FO) * capable_power) * 100
    function calculateEFOR(machineId) {
        const fo = getNumericValue(machineId, 'forced_outage');
        const efdh = getNumericValue(machineId, 'efdh');
        const operatingHours = getNumericValue(machineId, 'operating_hours');
        const capablePower = getNumericValue(machineId, 'capable_power');
        
        if ((operatingHours + fo) > 0 && capablePower > 0) {
            const efor = ((fo + efdh) * capablePower) / ((operatingHours + fo) * capablePower) * 100;
            document.querySelector(`[name="data[${machineId}][efor]"]`).value = efor.toFixed(2);
        }
    }

    // SDOF = trip_machine + trip_electrical
    function calculateSDOF(machineId) {
        const tripMachine = getNumericValue(machineId, 'trip_machine');
        const tripElectrical = getNumericValue(machineId, 'trip_electrical');
        
        const sdof = tripMachine + tripElectrical;
        document.querySelector(`[name="data[${machineId}][sdof]"]`).value = sdof.toFixed(0);
    }

    // NCF = net_production / (capable_power * period_hours) * 100
    function calculateNCF(machineId) {
        const netProduction = getNumericValue(machineId, 'net_production');
        const capablePower = getNumericValue(machineId, 'capable_power');
        const periodHours = getNumericValue(machineId, 'period_hours');
        
        if (capablePower > 0 && periodHours > 0) {
            const ncf = (netProduction / (capablePower * periodHours)) * 100;
            document.querySelector(`[name="data[${machineId}][ncf]"]`).value = ncf.toFixed(2);
        }
    }

    // NOF = (net_production / (capable_power * operating_hours)) * 100
    function calculateNOF(machineId) {
        const netProduction = getNumericValue(machineId, 'net_production');
        const capablePower = getNumericValue(machineId, 'capable_power');
        const operatingHours = getNumericValue(machineId, 'operating_hours');
        
        if (capablePower > 0 && operatingHours > 0) {
            const nof = (netProduction / (capablePower * operatingHours)) * 100;
            document.querySelector(`[name="data[${machineId}][nof]"]`).value = nof.toFixed(2);
        }
    }

    // SFC = total_fuel / gross_production
    function calculateSFC(machineId) {
        const totalFuel = getNumericValue(machineId, 'total_fuel');
        const grossProduction = getNumericValue(machineId, 'gross_production');
        
        if (grossProduction > 0) {
            const sfc = totalFuel / grossProduction;
            document.querySelector(`[name="data[${machineId}][sfc_scc]"]`).value = sfc.toFixed(3);
        }
    }

    // SLC = (total_oil * 1000) / gross_production
    function calculateSLC(machineId) {
        const totalOil = getNumericValue(machineId, 'total_oil');
        const grossProduction = getNumericValue(machineId, 'gross_production');
        
        if (grossProduction > 0) {
            const slc = (totalOil * 1000) / grossProduction;
            document.querySelector(`[name="data[${machineId}][slc]"]`).value = slc.toFixed(3);
        }
    }

    // Calculate total oil consumption
    function calculateTotalOil(machineId) {
        const meditran = getNumericValue(machineId, 'meditran_oil');
        const salyx420 = getNumericValue(machineId, 'salyx_420');
        const salyx430 = getNumericValue(machineId, 'salyx_430');
        const travolube = getNumericValue(machineId, 'travolube_a');
        const turbolube46 = getNumericValue(machineId, 'turbolube_46');
        const turbolube68 = getNumericValue(machineId, 'turbolube_68');
        
        const totalOil = meditran + salyx420 + salyx430 + travolube + turbolube46 + turbolube68;
        document.querySelector(`[name="data[${machineId}][total_oil]"]`).value = totalOil.toFixed(2);
    }

    // Calculate total fuel consumption
    function calculateTotalFuel(machineId) {
        const hsd = getNumericValue(machineId, 'hsd_fuel');
        const b35 = getNumericValue(machineId, 'b35_fuel');
        const mfo = getNumericValue(machineId, 'mfo_fuel');
        
        const totalFuel = hsd + b35 + mfo;
        document.querySelector(`[name="data[${machineId}][total_fuel]"]`).value = totalFuel.toFixed(2);
    }
</script>
@endsection 