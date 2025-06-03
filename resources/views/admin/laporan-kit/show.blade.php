@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    <div id="main-content" class="flex-1 main-content">
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

                    <h1 class="text-xl font-semibold text-gray-800">Laporan KIT 00.00</h1>
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

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Info Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Laporan KIT 00.00</h2>
                    <div class="flex flex-wrap gap-4 items-center text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-industry mr-2 text-green-500"></i>
                            Unit: {{ $laporan->powerPlant->name ?? '-' }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user mr-2 text-purple-500"></i>
                            {{ $laporan->creator->name ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Jam Operasi Mesin -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Jam Operasi Mesin</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ops</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Har</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ggn</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stby/Rsh</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam/Hari</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->jamOperasi as $row)
                                    <tr>
                                        <td class="px-6 py-4 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->ops }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->har }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->ggn }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->stby }}</td>
                                        <td class="px-6 py-4">{{ $row->jam_hari }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Jenis Gangguan Mesin -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Jenis Gangguan Mesin</h3>
                        </div>
                        <div class="overflow-x-auto">
                            @php
                            $filteredGangguan = $laporan->gangguan->filter(function($g) {
                                return !is_null($g->mekanik) || !is_null($g->elektrik);
                            });
                            @endphp
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mekanik</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elektrik</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($filteredGangguan as $row)
                                    <tr>
                                        <td class="px-6 py-4 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->mekanik }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->elektrik }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->keterangan ?? '-' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- BBM Inspection Data -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan BBM</h3>
                        </div>
                        
                        @if($laporan->bbm && $laporan->bbm->isNotEmpty())
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center align-middle">Mesin</th>
                                                <th colspan="10" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">
                                                    Storage Tank
                                                </th>
                                                <th colspan="10" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">
                                                    Service Tank
                                                </th>
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Total Stok Tangki</th>
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Terima BBM</th>
                                                <th colspan="15" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">
                                                    Flowmeter
                                                </th>
                                                <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">total pakai</th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">{{ $i }}</th>
                                                @endfor
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">{{ $i }}</th>
                                                @endfor
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">{{ $i }}</th>
                                                @endfor
                                            </tr>
                                            <tr class="bg-gray-50">
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">cm</th>
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                                @endfor
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">%</th>
                                                @endfor
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">awal</th>
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">akhir</th>
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">pakai {{ $i }}</th>
                                                @endfor
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($laporan->bbm as $bbm)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900 border-r text-center">{{ $bbm->machine->name ?? '-' }}</td>
                                                
                                                <!-- Storage Tank values -->
                                                @foreach($bbm->storageTanks->sortBy('tank_number') as $tank)
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($tank->cm, 2) }}</td>
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($tank->liter, 2) }}</td>
                                                @endforeach
                                                
                                                <!-- Service Tank values -->
                                                @foreach($bbm->serviceTanks->sortBy('tank_number') as $tank)
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($tank->liter, 2) }}</td>
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($tank->percentage, 2) }}</td>
                                                @endforeach
                                                
                                                <!-- Total Stok Tangki -->
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($bbm->total_stok_tangki, 2) }}</td>
                                                
                                                <!-- Terima BBM -->
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($bbm->terima_bbm, 2) }}</td>
                                                
                                                <!-- Flowmeter values -->
                                                @foreach($bbm->flowmeters->sortBy('flowmeter_number') as $flowmeter)
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($flowmeter->awal, 2) }}</td>
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($flowmeter->akhir, 2) }}</td>
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($flowmeter->pakai, 2) }}</td>
                                                @endforeach
                                                
                                                <!-- Total Pakai -->
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($bbm->total_pakai, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="p-6">
                                <div class="text-center text-gray-500 bg-gray-50 rounded-lg p-4">
                                    <i class="fas fa-info-circle text-blue-500 text-xl mb-2"></i>
                                    <p>Tidak ada data BBM tersedia</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Data Pemeriksaan KWH -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan KWH</h3>
                        </div>
                        
                        @if($laporan->kwh->isNotEmpty())
                            @foreach($laporan->kwh as $kwh)
                                <div class="p-6">
                                    <!-- KWH Information Summary -->
                                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Production Panel Summary -->
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Production Panel</h4>
                                            <div class="text-sm">
                                                <p class="text-gray-600">Jumlah Panel: <span class="font-medium">{{ $kwh->productionPanels->count() }}</span></p>
                                                <p class="text-gray-600">Total Produksi: <span class="font-medium">{{ number_format($kwh->prod_total, 2) }} KWH</span></p>
                                            </div>
                                        </div>
                                        
                                        <!-- PS Panel Summary -->
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">PS Panel</h4>
                                            <div class="text-sm">
                                                <p class="text-gray-600">Jumlah Panel: <span class="font-medium">{{ $kwh->psPanels->count() }}</span></p>
                                                <p class="text-gray-600">Total PS: <span class="font-medium">{{ number_format($kwh->ps_total, 2) }} KWH</span></p>
                                            </div>
                                        </div>

                                        <!-- Net Production Summary -->
                                        <div class="bg-gray-50 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Net Production</h4>
                                            <div class="text-sm">
                                                <p class="text-gray-600">Total Net: <span class="font-medium">{{ number_format($kwh->prod_total - $kwh->ps_total, 2) }} KWH</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detailed Panel Information -->
                                    <div class="space-y-8">
                                        <!-- Production Panels Detail -->
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                                <h3 class="text-sm font-semibold text-gray-700">Production Panels Detail</h3>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panel Number</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Awal</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @forelse($kwh->productionPanels->sortBy('panel_number') as $panel)
                                                        <tr>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">Panel {{ $panel->panel_number }}</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->awal, 2) }}</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir, 2) }}</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir - $panel->awal, 2) }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data panel produksi</td>
                                                        </tr>
                                                        @endforelse
                                                        <tr class="bg-gray-50">
                                                            <td colspan="3" class="px-4 py-2 border-r whitespace-nowrap text-right font-medium">Total Production:</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwh->prod_total, 2) }} KWH</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- PS Panels Detail -->
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                                <h3 class="text-sm font-semibold text-gray-700">PS Panels Detail</h3>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panel Number</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Awal</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @forelse($kwh->psPanels->sortBy('panel_number') as $panel)
                                                        <tr>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">Panel {{ $panel->panel_number }}</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->awal, 2) }}</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir, 2) }}</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir - $panel->awal, 2) }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data panel PS</td>
                                                        </tr>
                                                        @endforelse
                                                        <tr class="bg-gray-50">
                                                            <td colspan="3" class="px-4 py-2 border-r whitespace-nowrap text-right font-medium">Total PS:</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwh->ps_total, 2) }} KWH</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Summary Section -->
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                                <h3 class="text-sm font-semibold text-gray-700">Summary KWH</h3>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Production</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total PS</th>
                                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Production</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        <tr>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwh->prod_total, 2) }} KWH</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwh->ps_total, 2) }} KWH</td>
                                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwh->prod_total - $kwh->ps_total, 2) }} KWH</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-6">
                                <div class="text-center text-gray-500">
                                    <p>No KWH data available</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Data Pemeriksaan Pelumas -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan Pelumas</h3>
                        </div>
                        
                        @if($laporan->pelumas && $laporan->pelumas->isNotEmpty())
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center align-middle">Mesin</th>
                                                <th colspan="10" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">
                                                    Storage Tank
                                                </th>
                                                <th colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">
                                                    Drum Area
                                                </th>
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Total Stok Tangki</th>
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Terima Pelumas</th>
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Total Pakai</th>
                                                <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle border-r">Jenis Pelumas</th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">{{ $i }}</th>
                                                @endfor
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">Area {{ $i }}</th>
                                                @endfor
                                            </tr>
                                            <tr class="bg-gray-50">
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">cm</th>
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                                @endfor
                                                @for ($i = 1; $i <= 5; $i++)
                                                <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                                @endfor
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($laporan->pelumas as $pelumas)
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900 border-r text-center">{{ $pelumas->machine->name ?? '-' }}</td>
                                                
                                                <!-- Storage Tank values -->
                                                @foreach($pelumas->storageTanks->sortBy('tank_number') as $tank)
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($tank->cm, 2) }}</td>
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($tank->liter, 2) }}</td>
                                                @endforeach
                                                
                                                <!-- Drum values -->
                                                @foreach($pelumas->drums->sortBy('area_number') as $drum)
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($drum->jumlah, 2) }}</td>
                                                @endforeach
                                                
                                                <!-- Total Stok Tangki -->
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($pelumas->total_stok_tangki, 2) }}</td>
                                                
                                                <!-- Terima Pelumas -->
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($pelumas->terima_pelumas, 2) }}</td>
                                                
                                                <!-- Total Pakai -->
                                                <td class="px-4 py-2 border-r text-center">{{ number_format($pelumas->total_pakai, 2) }}</td>
                                                
                                                <!-- Jenis Pelumas -->
                                                <td class="px-4 py-2 border-r text-center">{{ $pelumas->jenis }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="p-6">
                                <div class="text-center text-gray-500 bg-gray-50 rounded-lg p-4">
                                    <i class="fas fa-info-circle text-blue-500 text-xl mb-2"></i>
                                    <p>Tidak ada data Pelumas tersedia</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Pemeriksaan Bahan Kimia -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Pemeriksaan Bahan Kimia</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Bahan Kimia</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Terima</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pakai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->bahanKimia as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->jenis }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->stok_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->terima }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_pakai }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Beban Tertinggi Harian -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Beban Tertinggi Harian</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Siang (07:00-17:00)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Malam (18:00-06:00)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->bebanTertinggi as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->siang }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->malam }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection

@push('styles')
<style>
    .main-content {
        overflow-y: auto;
        height: calc(100vh - 64px);
    }
</style>
@endpush
