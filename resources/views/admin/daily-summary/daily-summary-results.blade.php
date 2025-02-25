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

                    <h1 class="text-xl font-semibold text-gray-800">Data Ikhtisar Harian</h1>
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

        <div class="p-6">
            @foreach($units as $unit)
                <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $unit->name }}</h2>
                
                <div class="bg-white rounded shadow-md p-4 mb-6">
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
                                    <th class="px-4 py-2 border border-gray-300">
                                        <div class="grid grid-cols-5 gap-1">
                                            <span class="px-2 text-center border-r border-gray-300">HSD (Liter)</span>
                                            <span class="px-2 text-center border-r border-gray-300">B35 (Liter)</span>
                                            <span class="px-2 text-center border-r border-gray-300">MFO (Liter)</span>
                                            <span class="px-2 text-center border-r border-gray-300">Total BBM (Liter)</span>
                                            <span class="px-2 text-center">Air (M³)</span>
                                        </div>
                                    </th>
                                    <th class="px-4 py-2 border border-gray-300">
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
                                    <th class="px-4 py-2 border border-gray-300">
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
                                @foreach($unit->machines as $machine)
                                    <tr>
                                        <td class="px-4 py-3 border-r">{{ $machine->name }}</td>
                                        
                                        <!-- Daya (MW) -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="text-center border-r">{{ $machine->installed_power ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->dmn_power ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->capable_power ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Beban Puncak -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('peak_load_day') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('peak_load_night') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Ratio Daya Kit -->
                                        <td class="px-4 py-3 border-r text-center">{{ $machine->kit_ratio ?? '-' }}</td>

                                        <!-- Produksi -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('gross_production') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('net_production') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Pemakaian Sendiri -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('aux_power') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('transformer_losses') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('usage_percentage') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Jam Periode -->
                                        <td class="px-4 py-3 border-r text-center">{{ $machine->metrics->sum('period_hours') ?? '-' }}</td>

                                        <!-- Jam Operasi -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('operating_hours') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('standby_hours') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('planned_outage') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('maintenance_outage') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('forced_outage') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Trip Non OMC -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('trip_machine') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('trip_electrical') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Derating -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('efdh') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('epdh') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('eudh') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('esdh') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Kinerja Pembangkit -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('eaf') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('sof') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('efor') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('sdof') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Capability Factor -->
                                        <td class="px-4 py-3 border-r text-center">{{ $machine->metrics->sum('ncf') ?? '-' }}</td>

                                        <!-- Nett Operating Factor -->
                                        <td class="px-4 py-3 border-r text-center">{{ $machine->metrics->sum('nof') ?? '-' }}</td>

                                        <!-- JSI -->
                                        <td class="px-4 py-3 border-r text-center">{{ $machine->metrics->sum('jsi') ?? '-' }}</td>

                                        <!-- Pemakaian Bahan Bakar -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('hsd_fuel') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('b35_fuel') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('mfo_fuel') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('total_fuel') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('water_usage') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Pemakaian Pelumas -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-7 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('meditran_oil') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('salyx_420') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('salyx_430') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('travolube_a') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('turbolube_46') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('turbolube_68') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('total_oil') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Effisiensi -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="text-center border-r">{{ $machine->metrics->sum('sfc_scc') ?? '-' }}</div>
                                                <div class="text-center border-r">{{ $machine->metrics->sum('nphr') ?? '-' }}</div>
                                                <div class="text-center">{{ $machine->metrics->sum('slc') ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Keterangan -->
                                        <td class="px-4 py-3 text-center">{{ $machine->notes ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
<script src="{{ asset('js/toggle.js') }}"></script>
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
    min-width: 100px !important;
}
.w-nof {
    min-width: 100px !important;
}
.w-jsi {
    min-width: 100px !important;
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
</style>
@endsection 