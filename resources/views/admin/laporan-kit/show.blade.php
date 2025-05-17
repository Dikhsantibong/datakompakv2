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

                    <!-- Data Pemeriksaan BBM -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan BBM</h3>
                        </div>
                        
                        @php
                            $bbmData = $laporan->bbm->first();
                        @endphp

                        <!-- Debug Info -->
                        @if(config('app.debug'))
                        <div class="p-4 bg-gray-100 text-sm">
                            <p>BBM Data Available: {{ $bbmData ? 'Yes' : 'No' }}</p>
                            @if($bbmData)
                                <p>BBM ID: {{ $bbmData->id }}</p>
                                <p>Storage Tanks Count: {{ $bbmData->storageTanks->count() }}</p>
                                <p>Service Tanks Count: {{ $bbmData->serviceTanks->count() }}</p>
                                <p>Flowmeters Count: {{ $bbmData->flowmeters->count() }}</p>
                            @endif
                        </div>
                        @endif
                        
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
                                    @if($bbmData && $bbmData->productionPanels && $bbmData->productionPanels->isNotEmpty())
                                        @php
                                            $panelsPerRow = 2; // Assuming 2 panels per row based on your form
                                            $totalPanels = $bbmData->productionPanels->count();
                                            $rowCount = ceil($totalPanels / $panelsPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $panelsPerRow; $i++)
                                                @php
                                                    $panelIndex = ($row * $panelsPerRow) + $i;
                                                    $panel = $bbmData->productionPanels->get($panelIndex);
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
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($bbmData->prod_total, 2) }}</td>
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
                                    @if($bbmData && $bbmData->psPanels && $bbmData->psPanels->isNotEmpty())
                                        @php
                                            $panelsPerRow = 2; // Assuming 2 panels per row based on your form
                                            $totalPanels = $bbmData->psPanels->count();
                                            $rowCount = ceil($totalPanels / $panelsPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $panelsPerRow; $i++)
                                                @php
                                                    $panelIndex = ($row * $panelsPerRow) + $i;
                                                    $panel = $bbmData->psPanels->get($panelIndex);
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
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($bbmData->ps_total, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- BBM Storage Tanks -->
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
                                    @if($bbmData && $bbmData->storageTanks && $bbmData->storageTanks->isNotEmpty())
                                        @php
                                            $tanksPerRow = 2; // Assuming 2 tanks per row based on your form
                                            $totalTanks = $bbmData->storageTanks->count();
                                            $rowCount = ceil($totalTanks / $tanksPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $tanksPerRow; $i++)
                                                @php
                                                    $tankIndex = ($row * $tanksPerRow) + $i;
                                                    $tank = $bbmData->storageTanks->get($tankIndex);
                                                @endphp
                                                @if($tank)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $tanksPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Tank {{ $tank->tank_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->cm, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->liter, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="4" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="3" class="px-4 py-2 border-r whitespace-nowrap text-right">Total Storage:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($bbmData->total_stok, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- BBM Service Tanks -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Service Tanks</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tank Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liter</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($bbmData && $bbmData->serviceTanks && $bbmData->serviceTanks->isNotEmpty())
                                        @php
                                            $tanksPerRow = 2; // Assuming 2 tanks per row based on your form
                                            $totalTanks = $bbmData->serviceTanks->count();
                                            $rowCount = ceil($totalTanks / $tanksPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $tanksPerRow; $i++)
                                                @php
                                                    $tankIndex = ($row * $tanksPerRow) + $i;
                                                    $tank = $bbmData->serviceTanks->get($tankIndex);
                                                @endphp
                                                @if($tank)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $tanksPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Tank {{ $tank->tank_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->liter, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->percentage, 2) }}%</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="4" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="2" class="px-4 py-2 border-r whitespace-nowrap text-right">Total Service:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($bbmData->service_total_stok, 2) }}</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">-</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- BBM Flowmeters -->
                        <div class="overflow-x-auto">
                            <div class="px-6 py-3 border-b border-gray-200 bg-gray-50">
                                <h4 class="text-sm font-semibold text-gray-700">Flowmeters</h4>
                            </div>
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Row</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter Number</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Awal</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Akhir</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pakai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @if($bbmData && $bbmData->flowmeters && $bbmData->flowmeters->isNotEmpty())
                                        @php
                                            $metersPerRow = 2; // Assuming 2 flowmeters per row based on your form
                                            $totalMeters = $bbmData->flowmeters->count();
                                            $rowCount = ceil($totalMeters / $metersPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $metersPerRow; $i++)
                                                @php
                                                    $meterIndex = ($row * $metersPerRow) + $i;
                                                    $meter = $bbmData->flowmeters->get($meterIndex);
                                                @endphp
                                                @if($meter)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $metersPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Flowmeter {{ $meter->flowmeter_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($meter->awal, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($meter->akhir, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($meter->pakai, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="5" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

                                        <tr class="bg-gray-50 font-semibold">
                                            <td colspan="4" class="px-4 py-2 border-r whitespace-nowrap text-right">Total Pakai:</td>
                                            <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($bbmData->total_pakai, 2) }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="5" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
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
                                            $tanksPerRow = 3; // Assuming 3 tanks per row based on your form
                                            $totalTanks = $pelumasData->storageTanks->count();
                                            $rowCount = ceil($totalTanks / $tanksPerRow);
                                        @endphp

                                        @for($row = 0; $row < $rowCount; $row++)
                                            @for($i = 0; $i < $tanksPerRow; $i++)
                                                @php
                                                    $tankIndex = ($row * $tanksPerRow) + $i;
                                                    $tank = $pelumasData->storageTanks->get($tankIndex);
                                                @endphp
                                                @if($tank)
                                                <tr>
                                                    @if($i === 0)
                                                        <td class="px-4 py-2 border-r whitespace-nowrap" rowspan="{{ $tanksPerRow }}">Row {{ $row + 1 }}</td>
                                                    @endif
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">Tank {{ $tank->tank_number }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->cm, 2) }}</td>
                                                    <td class="px-4 py-2 border-r whitespace-nowrap">{{ number_format($tank->liter, 2) }}</td>
                                                </tr>
                                                @endif
                                            @endfor
                                            @if($row < $rowCount - 1)
                                                <tr><td colspan="4" class="border-b border-gray-200"></td></tr>
                                            @endif
                                        @endfor

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
