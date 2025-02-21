@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
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
                            <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'IKHTISAR HARIAN', 'url' => null]]" />
        </div>
        <div class="flex justify-end mt-4 space-x-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                <i class="fas fa-save mr-2"></i>Simpan Data
            </button>
            <button type="button" onclick="location.reload();" class="bg-gray-500 text-white px-4 py-2 rounded">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Data
            </button>
            <button type="button" onclick="window.location.href='{{ route('admin.daily-summary.results') }}'" class="bg-green-500 text-white px-4 py-2 rounded">
                <i class="fas fa-eye mr-2"></i>Lihat Data
            </button>
        </div>

        
        <div class="relative">
            <form action="{{ route('daily-summary.store') }}" method="POST">
                @csrf
                @foreach($units as $powerPlant) 
                    <h3 class="text-lg font-semibold text-gray-800 text-left mb-4">{{ $powerPlant->name }}</h3>
                    <div class="bg-white rounded shadow-md p-4">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border table-fixed" style="min-width: 3800px;">
                                <thead class="bg-gray-50">
                                    <tr>
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
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-bahan-bakar">Pemakaian Bahan Bakar/Baku</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-pelumas">Pemakaian Pelumas</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-efisiensi">Effisiensi</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider w-keterangan">Ket.</th>
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
                                        <th class="px-4 py-2">
                                            <div class="grid grid-cols-5 gap-0">
                                                <span class="subcol-border px-2">HSD (Liter)</span>
                                                <span class="subcol-border px-2">B35 (Liter)</span>
                                                <span class="subcol-border px-2">MFO (Liter)</span>
                                                <span class="subcol-border px-2">Total BBM (Liter)</span>
                                                <span class="px-2">Air (M³)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-7 gap-0">
                                                <span class="subcol-border border px-2">Meditran SX 15W/40 CH-4<br>(LITER)</span>
                                                <span class="subcol-border border px-2">Salyx 420<br>(LITER)</span>
                                                <span class="subcol-border border px-2">Salyx 430<br>(LITER)</span>
                                                <span class="subcol-border border px-2">TravoLube A<br>(LITER)</span>
                                                <span class="subcol-border border px-2">Turbolube 46<br>(LITER)</span>
                                                <span class="subcol-border border px-2">Turbolube 68<br>(LITER)</span>
                                                <span class="border px-2">TOTAL<br>(LITER)</span>
                                            </div>
                                        </th>
                                        <th class="px-4 py-2 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <span class="subcol-border px-2">SFC/SCC (LITER/KWH)</span>
                                                <span class="subcol-border px-2">TARA KALOR/NPHR (KCAL/KWH)</span>
                                                <span class="px-2">SLC (CC/KWH)</span>
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
                                            <input type="hidden" name="data[{{ $machine->id }}][unit_id]" value="{{ $powerPlant->id }}">
                                            <input type="hidden" name="data[{{ $machine->id }}][machine_id]" value="{{ $machine->id }}">
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][installed_power]" 
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center"
                                                           required>
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][dmn_power]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][capable_power]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][peak_load_day]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][peak_load_night]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][kit_ratio]"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][gross_production]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][net_production]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][aux_power]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][transformer_losses]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][usage_percentage]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][period_hours]"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][operating_hours]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][standby_hours]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][planned_outage]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][maintenance_outage]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][forced_outage]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="1" name="data[{{ $machine->id }}][trip_machine]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="1" name="data[{{ $machine->id }}][trip_electrical]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][efdh]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][epdh]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][eudh]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][esdh]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][eaf]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][sof]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][efor]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="1" name="data[{{ $machine->id }}][sdof]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][ncf]"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][nof]"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="px-2">
                                                <input type="number" step="0.01" name="data[{{ $machine->id }}][jsi]"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][hsd_fuel]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][b35_fuel]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][mfo_fuel]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][total_fuel]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][water_usage]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-7 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][meditran_oil]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][salyx_420]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][salyx_430]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][travolube_a]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][turbolube_46]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][turbolube_68]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" name="data[{{ $machine->id }}][total_oil]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][sfc_scc]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][nphr]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                                <div class="input-group">
                                                    <input type="number" step="0.001" name="data[{{ $machine->id }}][slc]"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm text-center">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="px-2">
                                                <input type="text" name="data[{{ $machine->id }}][notes]"
                                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
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

</script>
@endsection 