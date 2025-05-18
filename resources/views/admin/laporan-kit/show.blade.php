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
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($filteredGangguan as $row)
                                    <tr>
                                        <td class="px-6 py-4 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->mekanik }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->elektrik }}</td>
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
                    <div class="bg-white rounded-lg shadow-sm mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-white">
                            <h2 class="text-lg font-medium text-gray-800">Data Pemeriksaan BBM</h2>
                        </div>
                        
                        @if(!$laporan->bbm->isEmpty())
                            @foreach($laporan->bbm as $bbm)
                                <div class="p-6">
                                    <!-- Tank Information Summary -->
                                    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <!-- Storage Tanks Summary -->
                                        <div class="bg-blue-50 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Storage Tanks</h4>
                                            <div class="text-sm">
                                                <p class="text-gray-600">Jumlah Tangki: <span class="font-medium">{{ $bbm->storageTanks->count() }}</span></p>
                                                <p class="text-gray-600">Total Stok: <span class="font-medium">{{ number_format($bbm->total_stok, 2) }} L</span></p>
                                            </div>
                                        </div>
                                        
                                        <!-- Service Tanks Summary -->
                                        <div class="bg-green-50 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Service Tanks</h4>
                                            <div class="text-sm">
                                                <p class="text-gray-600">Jumlah Tangki: <span class="font-medium">{{ $bbm->serviceTanks->count() }}</span></p>
                                                <p class="text-gray-600">Total Stok: <span class="font-medium">{{ number_format($bbm->service_total_stok, 2) }} L</span></p>
                                            </div>
                                        </div>

                                        <!-- Flowmeters Summary -->
                                        <div class="bg-yellow-50 rounded-lg p-4">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Flowmeters</h4>
                                            <div class="text-sm">
                                                <p class="text-gray-600">Jumlah Flowmeter: <span class="font-medium">{{ $bbm->flowmeters->count() }}</span></p>
                                                <p class="text-gray-600">Total Pakai: <span class="font-medium">{{ number_format($bbm->total_pakai, 2) }} L</span></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detailed Tank Information -->
                                    <div class="space-y-8">
                                        <!-- Storage Tanks Detail -->
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                            <div class="px-6 py-3 border-b border-gray-200 bg-blue-50">
                                                <h3 class="text-sm font-semibold text-gray-700">Storage Tanks Detail</h3>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tank Number</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CM</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liter</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @forelse($bbm->storageTanks->sortBy('tank_number') as $tank)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Tank {{ $tank->tank_number }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($tank->cm, 2) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($tank->liter, 2) }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data storage tank</td>
                                                        </tr>
                                                        @endforelse
                                                        <tr class="bg-blue-50">
                                                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Storage</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{{ number_format($bbm->total_stok, 2) }} L</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <!-- Service Tanks Detail -->
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                            <div class="px-6 py-3 border-b border-gray-200 bg-green-50">
                                                <h3 class="text-sm font-semibold text-gray-700">Service Tanks Detail</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tank Number</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liter</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                                        @forelse($bbm->serviceTanks->sortBy('tank_number') as $tank)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Tank {{ $tank->tank_number }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($tank->liter, 2) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($tank->percentage, 2) }}%</td>
                                    </tr>
                                    @empty
                                    <tr>
                                                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">Tidak ada data service tank</td>
                                    </tr>
                                    @endforelse
                                                        <tr class="bg-green-50">
                                                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Service</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">{{ number_format($bbm->service_total_stok, 2) }} L</td>
                                                        </tr>
                                </tbody>
                            </table>
                        </div>
                                        </div>

                                        <!-- Flowmeters Detail -->
                                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                            <div class="px-6 py-3 border-b border-gray-200 bg-yellow-50">
                                                <h3 class="text-sm font-semibold text-gray-700">Flowmeters Detail</h3>
                                            </div>
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200">
                                                    <thead class="bg-gray-50">
                                                        <tr>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter Number</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Awal</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakai</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200">
                                                        @forelse($bbm->flowmeters->sortBy('flowmeter_number') as $flowmeter)
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Flowmeter {{ $flowmeter->flowmeter_number }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($flowmeter->awal, 2) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($flowmeter->akhir, 2) }}</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($flowmeter->pakai, 2) }}</td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data flowmeter</td>
                                                        </tr>
                                                        @endforelse
                                                        <tr class="bg-yellow-50">
                                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Total Pakai</td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-yellow-600">{{ number_format($bbm->total_pakai, 2) }} L</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Summary Section -->
                                    <div class="mt-8 bg-gray-50 rounded-lg p-6">
                                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Summary BBM</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <p class="text-sm text-gray-600">Total Stok Tangki</p>
                                                <p class="mt-1 text-lg font-semibold text-blue-600">{{ number_format($bbm->total_stok_tangki, 2) }} L</p>
                                                <div class="mt-2 grid grid-cols-2 gap-2">
                                                    <div>
                                                        <p class="text-xs text-gray-500">Storage</p>
                                                        <p class="text-sm font-medium text-blue-600">{{ number_format($bbm->total_stok, 2) }} L</p>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs text-gray-500">Service</p>
                                                        <p class="text-sm font-medium text-blue-600">{{ number_format($bbm->service_total_stok, 2) }} L</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <p class="text-sm text-gray-600">Terima BBM</p>
                                                <p class="mt-1 text-lg font-semibold text-green-600">{{ number_format($bbm->terima_bbm, 2) }} L</p>
                                            </div>
                                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                                <p class="text-sm text-gray-600">Total Pakai</p>
                                                <p class="mt-1 text-lg font-semibold text-red-600">{{ number_format($bbm->total_pakai, 2) }} L</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="p-6">
                                <div class="text-center text-gray-500">
                                    <p>No BBM data available</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Data Pemeriksaan KWH -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan KWH</h3>
                        </div>

                        @php
                            $kwhData = $laporan->kwh->first();
                        @endphp

                        <!-- Production Panel -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Production Panel</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panel</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Awal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($kwhData && $kwhData->productionPanels && $kwhData->productionPanels->isNotEmpty())
                                        @php
                                            $panelsPerRow = 2; // Assuming 2 panels per row based on your form
                                            $totalPanels = $kwhData->productionPanels->count();
                                            $rowCount = ceil($totalPanels / $panelsPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $panelsPerRow; $i++)
                                                @php
                                                    $panelIndex = ($row * $panelsPerRow) + $i;
                                                    $panel = $kwhData->productionPanels->get($panelIndex);
                                                @endphp
                                                @if($panel)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $panelsPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Panel {{ $panel->panel_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->awal, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir - $panel->awal, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="5" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="4" class="px-4 py-2 border-r whitespace-nowrap text-right">Total Production:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwhData->prod_total, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- PS Panel -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">PS Panel</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Panel</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Awal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($kwhData && $kwhData->psPanels && $kwhData->psPanels->isNotEmpty())
                                        @php
                                            $panelsPerRow = 2; // Assuming 2 panels per row based on your form
                                            $totalPanels = $kwhData->psPanels->count();
                                            $rowCount = ceil($totalPanels / $panelsPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $panelsPerRow; $i++)
                                                @php
                                                    $panelIndex = ($row * $panelsPerRow) + $i;
                                                    $panel = $kwhData->psPanels->get($panelIndex);
                                                @endphp
                                                @if($panel)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $panelsPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Panel {{ $panel->panel_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->awal, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($panel->akhir - $panel->awal, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="5" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="4" class="px-4 py-2 border-r whitespace-nowrap text-right">Total PS:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwhData->ps_total, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- KWH Summary -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Summary KWH</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Production</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total PS</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Production</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($kwhData)
                                        <tr>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwhData->prod_total, 2) }}</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwhData->ps_total, 2) }}</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($kwhData->prod_total - $kwhData->ps_total, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Data Pemeriksaan Pelumas -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan Pelumas</h3>
                        </div>

                        @php
                            $pelumasData = $laporan->pelumas->first();
                        @endphp

                        <!-- Storage Tanks -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Storage Tanks</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tank Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CM</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liter</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($pelumasData && $pelumasData->storageTanks && $pelumasData->storageTanks->isNotEmpty())
                                        @php
                                            $tanksPerRow = 3;
                                            $totalTanks = $pelumasData->storageTanks->count();
                                            $rowCount = ceil($totalTanks / $tanksPerRow);
                                            $tanks = $pelumasData->storageTanks->groupBy(function($tank) use ($tanksPerRow) {
                                                return floor(($tank->id - 1) / $tanksPerRow);
                                            });
                                        @endphp

                                        @foreach($tanks as $rowIndex => $rowTanks)
                                            @foreach($rowTanks as $tank)
                                                <tr>
                                                    @if($loop->first)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $tanksPerRow }}">Row {{ $rowIndex + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Tank {{ $tank->tank_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->cm, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->liter, 2) }}</td>
                                                </tr>
                                            @endforeach
                                            @if(!$loop->last)
                                                <tr><td colspan="4" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endforeach

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="3" class="px-4 py-2 border-r whitespace-nowrap text-right">Total Storage:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($pelumasData->tank_total_stok, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Drums -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Drums</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($pelumasData && $pelumasData->drums && $pelumasData->drums->isNotEmpty())
                                        @php
                                            $drumsPerRow = 4; // Assuming 4 drums per row based on your form
                                            $totalDrums = $pelumasData->drums->count();
                                            $rowCount = ceil($totalDrums / $drumsPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $drumsPerRow; $i++)
                                                @php
                                                    $drumIndex = ($row * $drumsPerRow) + $i;
                                                    $drum = $pelumasData->drums->get($drumIndex);
                                                @endphp
                                                @if($drum)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $drumsPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Area {{ $drum->area_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($drum->jumlah, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="3" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="2" class="px-4 py-2 border-r whitespace-nowrap text-right">Total Drums:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($pelumasData->drum_total_stok, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pelumas Summary -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Summary Pelumas</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok Tangki</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terima Pelumas</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pakai</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($pelumasData)
                                        <tr>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($pelumasData->total_stok_tangki, 2) }}</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($pelumasData->terima_pelumas, 2) }}</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($pelumasData->total_pakai, 2) }}</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ $pelumasData->jenis }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
