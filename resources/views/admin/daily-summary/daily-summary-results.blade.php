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
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Mesin</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Daya (MW)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Beban Puncak (kW)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Ratio Daya Kit (%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Produksi (kWh)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Pemakaian Sendiri</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Jam Periode</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Jam Operasi</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Trip Non OMC</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Derating</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Kinerja Pembangkit</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Capability Factor (%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Nett Operating Factor (%)</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">JSI</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Pemakaian Bahan Bakar/Baku</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Pemakaian Pelumas</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($unit->machines as $machine)
                                    <tr>
                                        <td class="px-4 py-3 border-r">{{ $machine->name }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->capacity }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('peak_load') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->kit_ratio ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('production') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('self_usage') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('period_hours') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('operating_hours') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('trip') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('derating') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('performance') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('capability_factor') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('nett_operating_factor') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('jsi') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('fuel_usage') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->metrics->sum('lubricant_usage') ?? '-' }}</td>
                                        <td class="px-4 py-3 border-r">{{ $machine->notes ?? '-' }}</td>
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
@endsection 