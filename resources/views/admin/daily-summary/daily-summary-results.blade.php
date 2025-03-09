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
       <!-- main content -->
       <main class="flex-1 p-4">
        <div class="bg-white rounded-lg shadow-md p-2">
            <h1 class="text-xl font-semibold text-gray-800"> Kelola Data Ikhtisar Harian</h1>
        <div class="p-6" id="content-wrapper">
            <!-- Add Date Filter -->
            <div class="mb-6 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <input 
                        type="date" 
                        id="dateFilter"
                        value="{{ $date }}"
                        class="rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50"
                    >
                    <div id="loading" class="hidden">
                        <svg class="animate-spin h-5 w-5 text-[#009BB9]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row items-center gap-4">
                    <a href="{{ route('admin.daily-summary.export-pdf', ['date' => $date]) }}" 
                       class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-lg text-base font-medium flex items-center justify-center shadow-sm w-full md:w-auto mb-2 md:mb-0">
                        <i class="fas fa-file-pdf mr-2"></i>Export PDF
                    </a>
                    <a href="{{ route('admin.daily-summary.export-excel', ['date' => $date]) }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-base font-medium flex items-center justify-center shadow-sm w-full md:w-auto mb-2 md:mb-0">
                        <i class="fas fa-file-excel mr-2"></i>Export Excel
                    </a>
                    <button 
                        onclick="window.location.href='{{ route('admin.daily-summary') }}'" 
                        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2.5 rounded-lg text-base font-medium flex items-center justify-center shadow-sm w-full md:w-auto">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </button>
                </div>
            </div>

            <!-- Add Loading Overlay -->
            <div id="content-loading" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-[#009BB9]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Loading data...</span>
                </div>
            </div>

            @foreach($units as $unit)
            
            <div class="bg-white rounded shadow-md p-4 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $unit->name }}</h2>
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
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-bahan-bakar">Pemakaian Bahan Bakar/Baku</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-pelumas">Pemakaian Pelumas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-efisiensi">Effisiensi</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r w-keterangan">Ket.</th>
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
                                @foreach($unit->dailySummaries as $summary)
                                    <tr>
                                        <td class="px-4 py-3 border-r">{{ $summary->machine_name }}</td>
                                        
                                        <!-- Daya (MW) -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->installed_power, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->dmn_power, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->capable_power, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Beban Puncak -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->peak_load_day, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->peak_load_night, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Ratio Daya Kit -->
                                        <td class="px-4 py-3 border-r text-center">{{ number_format($summary->kit_ratio, 2) ?? '-' }}</td>

                                        <!-- Produksi -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->gross_production, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->net_production, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Pemakaian Sendiri -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->aux_power, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->transformer_losses, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->usage_percentage, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Jam Periode -->
                                        <td class="px-4 py-3 border-r text-center">{{ number_format($summary->period_hours, 2) ?? '-' }}</td>

                                        <!-- Jam Operasi -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->operating_hours, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->standby_hours, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->planned_outage, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->maintenance_outage, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->forced_outage, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Trip Non OMC -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-2 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->trip_machine, 0) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->trip_electrical, 0) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Derating -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->efdh, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->epdh, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->eudh, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->esdh, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Kinerja Pembangkit -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-4 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->eaf, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->sof, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->efor, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->sdof, 0) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Capability Factor -->
                                        <td class="px-4 py-3 border-r text-center">{{ number_format($summary->ncf, 2) ?? '-' }}</td>

                                        <!-- Nett Operating Factor -->
                                        <td class="px-4 py-3 border-r text-center">{{ number_format($summary->nof, 2) ?? '-' }}</td>

                                        <!-- JSI -->
                                        <td class="px-4 py-3 border-r text-center">{{ number_format($summary->jsi, 2) ?? '-' }}</td>

                                        <!-- Pemakaian Bahan Bakar -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-5 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->hsd_fuel, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->b35_fuel, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->mfo_fuel, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->total_fuel, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->water_usage, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Pemakaian Pelumas -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-7 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->meditran_oil, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->salyx_420, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->salyx_430, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->travolube_a, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->turbolube_46, 2) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->turbolube_68, 2) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->total_oil, 2) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Effisiensi -->
                                        <td class="px-4 py-3 border-r">
                                            <div class="grid grid-cols-3 gap-0">
                                                <div class="text-center border-r">{{ number_format($summary->sfc_scc, 3) ?? '-' }}</div>
                                                <div class="text-center border-r">{{ number_format($summary->nphr, 3) ?? '-' }}</div>
                                                <div class="text-center">{{ number_format($summary->slc, 3) ?? '-' }}</div>
                                            </div>
                                        </td>

                                        <!-- Keterangan -->
                                        <td class="px-4 py-3 text-center">{{ $summary->notes ?? '-' }}</td>
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
</div>
</main>
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateFilter = document.getElementById('dateFilter');
    const contentLoading = document.getElementById('content-loading');
    const contentWrapper = document.getElementById('content-wrapper');

    dateFilter.addEventListener('change', async function() {
        try {
            // Show loading overlay
            contentLoading.classList.remove('hidden');

            // Get current URL and update the date parameter
            const url = new URL(window.location.href);
            url.searchParams.set('date', this.value);

            // Update browser URL without reloading
            window.history.pushState({}, '', url);

            // Redirect to the new URL
            window.location.href = url.toString();

        } catch (error) {
            console.error('Error:', error);
            alert('Error loading data. Please try again.');
        }
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
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
@endsection 