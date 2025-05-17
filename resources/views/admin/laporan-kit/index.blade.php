@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
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

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Laporan KIT 00.00', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 sm:px-6">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white">
                <div class="max-w-3xl">
                    <h2 class="text-2xl font-bold mb-2">Laporan KIT 00.00</h2>
                    <p class="text-blue-100 mb-4">Selamat datang di halaman Laporan KIT 00.00. Halaman ini digunakan untuk mengelola dan memonitor laporan KIT pada pukul 00.00.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.laporan-kit.list') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                            <i class="fas fa-eye mr-2"></i> Lihat Data
                        </a>
                      
                    </div>
                </div>
            </div>

            <!-- Filter Unit & Tanggal -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-800">
                        <i class="fas fa-filter mr-2 text-blue-600"></i>
                        Filter Unit & Tanggal
                    </h3>
                    <form method="GET" action="" class="flex items-center space-x-3">
                        <select id="unitFilter" name="unit_source" onchange="this.form.submit()" class="form-select rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Semua Unit</option>
                            @foreach($powerPlants as $powerPlant)
                                <option value="{{ $powerPlant->unit_source }}" {{ (request('unit_source', $unitSource ?? '') == $powerPlant->unit_source) ? 'selected' : '' }}>
                                    {{ $powerPlant->name }}
                                </option>
                            @endforeach
                        </select>
                        <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-input rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                    </form>
                </div>
            </div>

            <form action="{{ route('admin.laporan-kit.store') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="unit_source" value="{{ $unitSource }}">
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                
                <!-- JAM OPERASI MESIN -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">JAM OPERASI MESIN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">mesin</th>
                                    <th colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">Jam Mesin</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">jam/hari</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">ops</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">har</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">ggn</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">stby/rsh</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($machines as $machine)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r text-center">{{ $machine->name }}</td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="mesin[{{ $machine->id }}][ops]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="mesin[{{ $machine->id }}][har]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="mesin[{{ $machine->id }}][ggn]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="mesin[{{ $machine->id }}][stby]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="mesin[{{ $machine->id }}][jam_hari]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data mesin</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- JENIS GANGGUAN MESIN -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">JENIS GANGGUAN MESIN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">mesin</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">Jenis Gangguan</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">mekanik</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center">elektrik</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($machines as $machine)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r text-center">{{ $machine->name }}</td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" name="gangguan[{{ $machine->id }}][mekanik]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" name="gangguan[{{ $machine->id }}][elektrik]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data mesin</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DATA PEMERIKSAAN BBM -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">DATA PEMERIKSAAN BBM</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th colspan="5" id="bbm-storage-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100 relative">
                                        Storage Tank
                                        <button type="button" onclick="addStorageTankPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th colspan="5" id="bbm-service-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100 relative">
                                        Service Tank
                                        <button type="button" onclick="addServiceTankPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">Total Stok Tangki</th>
                                    <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">Terima BBM</th>
                                    <th colspan="6" id="bbm-flowmeter-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100 relative">
                                        Flowmeter
                                        <button type="button" onclick="addFlowmeterPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">total pakai</th>
                                    <th rowspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle bg-gray-100">aksi</th>
                                </tr>
                                <tr class="bg-gray-50" id="bbm-panel-header-row">
                                    <!-- Panel headers will be added here dynamically -->
                                </tr>
                                <tr class="bg-gray-50" id="bbm-panel-subheader-row">
                                    <!-- Panel subheaders will be added here dynamically -->
                                </tr>
                            </thead>
                            <tbody id="bbm-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="addBBMRow()" class="inline-flex items-center px-3 py-2 border border-blue-600 text-sm leading-4 font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <!-- DATA PEMERIKSAAN KWH -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">DATA PEMERIKSAAN KWH</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 table-fixed">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th id="kwh-produksi-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center relative">
                                        KWH PRODUKSI
                                        <button type="button" onclick="addKWHProduksiPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th id="kwh-ps-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center relative">
                                        KWH pemakaian sendiri (PS)
                                        <button type="button" onclick="addKWHPSPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2" class="w-20 px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">aksi</th>
                                </tr>
                                <tr class="bg-gray-50" id="panel-header-row">
                                    <!-- Panel headers will be added here dynamically -->
                                </tr>
                                <tr class="bg-gray-50" id="panel-subheader-row">
                                    <!-- Panel subheaders will be added here dynamically -->
                                </tr>
                            </thead>
                            <tbody id="kwh-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="addKWHRow()" class="inline-flex items-center px-3 py-2 border border-blue-600 text-sm leading-4 font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <!-- DATA PEMERIKSAAN PELUMAS -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">DATA PEMERIKSAAN PELUMAS</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th colspan="5" id="pelumas-storage-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center relative">
                                        Storage Tank
                                        <button type="button" onclick="addPelumasStorageTankPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th colspan="3" id="pelumas-drum-header" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center relative">
                                        Drum Pelumas
                                        <button type="button" onclick="addPelumasDrumPanel()" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Total Stok Tangki</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Terima pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">total pakai pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle">jenis pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle">aksi</th>
                                </tr>
                                <tr class="bg-gray-50" id="pelumas-panel-header-row">
                                    <!-- Panel headers will be added here dynamically -->
                                </tr>
                                <tr class="bg-gray-50" id="pelumas-panel-subheader-row">
                                    <!-- Panel subheaders will be added here dynamically -->
                                </tr>
                            </thead>
                            <tbody id="pelumas-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="addPelumasRow()" class="inline-flex items-center px-3 py-2 border border-blue-600 text-sm leading-4 font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <!-- Pemeriksaan Bahan Kimia -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2 mb-6">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Pemeriksaan Bahan Kimia</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">jenis bahan kimia</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">stok awal</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">terim bahan kimia</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">total pakai</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="bahan-kimia-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="addBahanKimiaRow()" class="inline-flex items-center px-3 py-2 border border-blue-600 text-sm leading-4 font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </button>
                    </div>
                </div>

                <!-- Beban Tertinggi Harian -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2 mb-6">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Beban Tertinggi Harian</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center bg-gray-100">Mesin</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 text-center bg-gray-100">Beban Tertinggi</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">Siang (07:00 s/d 17:00)</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center">Malam (18:00 s/d 06:0)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($machines as $machine)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r text-center">{{ $machine->name }}</td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="beban[{{ $machine->id }}][siang]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" step="0.1" name="beban[{{ $machine->id }}][malam]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data mesin</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 p-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" @if(!request('tanggal')) disabled style="background:#ccc;cursor:not-allowed;" @endif>
                        Simpan Data
                    </button>
                </div>
                @if(!request('tanggal'))
                    <div class="text-red-600 text-sm text-right pr-6 pb-2">Silakan pilih tanggal terlebih dahulu sebelum mengisi dan menyimpan data.</div>
                @endif
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .main-content {
        overflow-y: auto;
        height: calc(100vh - 64px);
    }
    
    /* Additional table styles */
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid #e5e7eb;
    }
    
    thead tr {
        background-color: #f9fafb;
    }
    
    tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .table-container {
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }

    /* Input styles */
    input[type="number"] {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    input[readonly] {
        background-color: #f3f4f6;
    }

    .delete-row {
        color: #dc2626;
        cursor: pointer;
    }
    .delete-row:hover {
        color: #991b1b;
    }

    /* Tambahan untuk tombol aksi */
    .table-action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 9999px;
        transition: background 0.2s;
    }
    .table-action-btn:hover {
        background: #fee2e2;
    }

    /* Tambahan untuk kolom aksi */
    th.border-l, td.border-l {
        border-left-width: 1px !important;
        border-left-color: #d1d5db !important; /* gray-300 */
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
    // Variables for each table
    let bbmRowCount = 0;
    let kwhRowCount = 0;
    let pelumasRowCount = 0;
    let bahanKimiaRowCount = 0;

    // Panel counts for each table
    let bbmStorageTankCount = 2;
    let bbmServiceTankCount = 2;
    let kwhProduksiPanelCount = 2;
    let kwhPSPanelCount = 2;
    let pelumasStorageTankCount = 2;
    let pelumasDrumCount = 2;
    let bbmFlowmeterCount = 2;

    // Initialize all tables on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize BBM table
        initializeBBMPanels();
        addBBMRow();

        // Initialize KWH table
        initializeKWHPanels();
        addKWHRow();

        // Initialize Pelumas table
        initializePelumasPanels();
        addPelumasRow();

        // Initialize Bahan Kimia table
        addBahanKimiaRow();

        // Add event listeners for machine operation inputs
        setupMachineOperationListeners();
    });

    function setupMachineOperationListeners() {
        const machineInputs = document.querySelectorAll('input[name^="mesin["][name$="][ops]"], input[name^="mesin["][name$="][har]"], input[name^="mesin["][name$="][ggn]"], input[name^="mesin["][name$="][stby]"]');
        machineInputs.forEach(input => {
            input.addEventListener('input', function() {
                const machineId = this.name.match(/mesin\[(\d+)\]/)[1];
                calculateJamHari(machineId);
            });
        });
    }

    // KWH Table Functions
    function initializeKWHPanels() {
        const headerRow = document.getElementById('panel-header-row');
        const subheaderRow = document.getElementById('panel-subheader-row');
        const kwhProduksiHeader = document.getElementById('kwh-produksi-header');
        const kwhPSHeader = document.getElementById('kwh-ps-header');
        
        // Clear existing headers
        headerRow.innerHTML = '';
        subheaderRow.innerHTML = '';

        // Set KWH PRODUKSI width and colspan
        const panelWidth = 160;
        const totalProduksiWidth = kwhProduksiPanelCount * panelWidth;
        kwhProduksiHeader.style.width = `${totalProduksiWidth + 80}px`;
        kwhProduksiHeader.colSpan = (kwhProduksiPanelCount * 2) + 1;

        // Set KWH PS width and colspan
        const totalPSWidth = kwhPSPanelCount * panelWidth;
        kwhPSHeader.style.width = `${totalPSWidth + 80}px`;
        kwhPSHeader.colSpan = (kwhPSPanelCount * 2) + 1;

        // Add Produksi panel headers
        for (let i = 1; i <= kwhProduksiPanelCount; i++) {
            const panelHeader = document.createElement('th');
            panelHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            panelHeader.style.width = '160px';
            panelHeader.colSpan = 2;
                panelHeader.textContent = `PANEL ${i}`;
            headerRow.appendChild(panelHeader);
        }

        // Add total production header
        const totalProdHeader = document.createElement('th');
        totalProdHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center align-middle';
        totalProdHeader.style.width = '80px';
        totalProdHeader.rowSpan = 2;
        totalProdHeader.innerHTML = 'total prod.<br>kWH';
        headerRow.appendChild(totalProdHeader);

        // Add PS panels
        for (let i = 1; i <= kwhPSPanelCount; i++) {
            const panelHeader = document.createElement('th');
            panelHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            panelHeader.style.width = '160px';
            panelHeader.colSpan = 2;
            panelHeader.textContent = `PANEL ${i}`;
            headerRow.appendChild(panelHeader);
        }

        // Add total PS header
        const totalPSHeader = document.createElement('th');
        totalPSHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 text-center align-middle';
        totalPSHeader.style.width = '80px';
        totalPSHeader.rowSpan = 2;
        totalPSHeader.innerHTML = 'total PS<br>kWH';
        headerRow.appendChild(totalPSHeader);

        // Add subheaders for Produksi panels
        for (let i = 0; i < kwhProduksiPanelCount; i++) {
            ['AWAL', 'AKHIR'].forEach(text => {
                const header = document.createElement('th');
                header.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
                header.style.width = '80px';
                header.textContent = text;
                subheaderRow.appendChild(header);
            });
        }

        // Add subheaders for PS panels
        for (let i = 0; i < kwhPSPanelCount; i++) {
            ['AWAL', 'AKHIR'].forEach(text => {
                const header = document.createElement('th');
                header.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
                header.style.width = '80px';
                header.textContent = text;
                subheaderRow.appendChild(header);
            });
        }
    }

    function updateKWHRows() {
        const rows = document.querySelectorAll('#kwh-tbody tr');
        rows.forEach(row => {
        const rowIndex = row.dataset.rowIndex;
        const newInputs = [];

            // Production panels
            for (let i = 1; i <= kwhProduksiPanelCount; i++) {
            newInputs.push(`
                <td class="w-40 px-4 py-2 border-r">
                    <input type="number" step="0.1" name="kwh[${rowIndex}][prod_panel${i}_awal]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </td>
                <td class="w-40 px-4 py-2 border-r">
                    <input type="number" step="0.1" name="kwh[${rowIndex}][prod_panel${i}_akhir]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </td>
            `);
        }

            // Total production
        newInputs.push(`
            <td class="w-20 px-4 py-2 border-r">
                    <input type="number" step="0.1" name="kwh[${rowIndex}][prod_total]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
            </td>
        `);

            // PS panels
            for (let i = 1; i <= kwhPSPanelCount; i++) {
            newInputs.push(`
                <td class="w-40 px-4 py-2 border-r">
                    <input type="number" step="0.1" name="kwh[${rowIndex}][ps_panel${i}_awal]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </td>
                <td class="w-40 px-4 py-2 border-r">
                    <input type="number" step="0.1" name="kwh[${rowIndex}][ps_panel${i}_akhir]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </td>
            `);
        }

        // Total PS
        newInputs.push(`
            <td class="w-20 px-4 py-2 border-r">
                    <input type="number" step="0.1" name="kwh[${rowIndex}][ps_total]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
            </td>
        `);

            // Delete button
        newInputs.push(`
            <td class="w-20 px-4 py-2">
                <button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `);

        row.innerHTML = newInputs.join('');
        setupKWHCalculations(row);
        });
    }

    function setupKWHCalculations(row) {
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateKWHTotals(row));
        });
    }

    function calculateKWHTotals(row) {
        let prodTotal = 0;
        let psTotal = 0;

        // Calculate production total
        for (let i = 1; i <= kwhProduksiPanelCount; i++) {
            const awal = parseFloat(row.querySelector(`input[name$="[prod_panel${i}_awal]"]`).value) || 0;
            const akhir = parseFloat(row.querySelector(`input[name$="[prod_panel${i}_akhir]"]`).value) || 0;
            prodTotal += Math.max(0, akhir - awal);
        }
        row.querySelector('input[name$="[prod_total]"]').value = prodTotal.toFixed(1);

        // Calculate PS total
        for (let i = 1; i <= kwhPSPanelCount; i++) {
            const awal = parseFloat(row.querySelector(`input[name$="[ps_panel${i}_awal]"]`).value) || 0;
            const akhir = parseFloat(row.querySelector(`input[name$="[ps_panel${i}_akhir]"]`).value) || 0;
            psTotal += Math.max(0, akhir - awal);
        }
        row.querySelector('input[name$="[ps_total]"]').value = psTotal.toFixed(1);
    }

    function addKWHProduksiPanel() {
        kwhProduksiPanelCount++;
        initializeKWHPanels();
        updateKWHRows();
    }

    function addKWHPSPanel() {
        kwhPSPanelCount++;
        initializeKWHPanels();
        updateKWHRows();
    }

    function addKWHRow() {
        const tbody = document.getElementById('kwh-tbody');
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.dataset.rowIndex = kwhRowCount++;
        
        // Initialize empty row
        row.innerHTML = '<td></td>';
        tbody.appendChild(row);
        
        // Update row with current panel configuration
        updateKWHRows();
    }

    function calculateJamHari(machineId) {
        const ops = parseFloat(document.querySelector(`input[name="mesin[${machineId}][ops]"]`).value) || 0;
        const har = parseFloat(document.querySelector(`input[name="mesin[${machineId}][har]"]`).value) || 0;
        const ggn = parseFloat(document.querySelector(`input[name="mesin[${machineId}][ggn]"]`).value) || 0;
        const stby = parseFloat(document.querySelector(`input[name="mesin[${machineId}][stby]"]`).value) || 0;
        
        const total = ops + har + ggn + stby;
        document.querySelector(`input[name="mesin[${machineId}][jam_hari]"]`).value = total.toFixed(1);
    }

    function calculateBBMTotals(row) {
        // Calculate Storage Tank total
        let storageTotalLiter = 0;
        for (let i = 1; i <= bbmStorageTankCount; i++) {
            const liter = parseFloat(row.querySelector(`input[name$="[storage_tank_${i}_liter]"]`).value) || 0;
            storageTotalLiter += liter;
        }
        row.querySelector('input[name$="[total_stok]"]').value = storageTotalLiter.toFixed(1);

        // Calculate Service Tank total
        let serviceTotalLiter = 0;
        for (let i = 1; i <= bbmServiceTankCount; i++) {
            const liter = parseFloat(row.querySelector(`input[name$="[service_tank_${i}_liter]"]`).value) || 0;
            serviceTotalLiter += liter;
        }
        row.querySelector('input[name$="[service_total_stok]"]').value = serviceTotalLiter.toFixed(1);

        // Calculate total stok tangki
        const totalStokTangki = storageTotalLiter + serviceTotalLiter;
        row.querySelector('input[name$="[total_stok_tangki]"]').value = totalStokTangki.toFixed(1);

        // Calculate Flowmeter totals
        let totalPakai = 0;
        for (let i = 1; i <= bbmFlowmeterCount; i++) {
            const awal = parseFloat(row.querySelector(`input[name$="[flowmeter_${i}_awal]"]`).value) || 0;
            const akhir = parseFloat(row.querySelector(`input[name$="[flowmeter_${i}_akhir]"]`).value) || 0;
            const pakai = Math.max(0, akhir - awal);
            row.querySelector(`input[name$="[flowmeter_${i}_pakai]"]`).value = pakai.toFixed(1);
            totalPakai += pakai;
        }
        row.querySelector('input[name$="[total_pakai]"]').value = totalPakai.toFixed(1);
    }

    // Functions for BBM panels
    function addStorageTankPanel() {
        bbmStorageTankCount++;
        initializeBBMPanels();
        updateBBMRows();
    }

    function addServiceTankPanel() {
        bbmServiceTankCount++;
        initializeBBMPanels();
        updateBBMRows();
    }

    function addBBMRow() {
        const tbody = document.getElementById('bbm-tbody');
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.dataset.rowIndex = bbmRowCount++;
        
        // Initialize empty row
        row.innerHTML = '<td></td>';
        tbody.appendChild(row);
        
        // Update row with current panel configuration
        updateBBMRows();
    }

    function initializeBBMPanels() {
        const headerRow = document.getElementById('bbm-panel-header-row');
        const subheaderRow = document.getElementById('bbm-panel-subheader-row');
        
        // Clear existing headers
        headerRow.innerHTML = '';
        subheaderRow.innerHTML = '';

        // Update Storage Tank header colspan
        const storageHeader = document.getElementById('bbm-storage-header');
        storageHeader.colSpan = bbmStorageTankCount * 2 + 1;

        // Update Service Tank header colspan
        const serviceHeader = document.getElementById('bbm-service-header');
        serviceHeader.colSpan = bbmServiceTankCount * 2 + 1;

        // Update Flowmeter header colspan
        const flowmeterHeader = document.getElementById('bbm-flowmeter-header');
        flowmeterHeader.colSpan = bbmFlowmeterCount * 3;

        // Add Storage Tank panels
        for (let i = 1; i <= bbmStorageTankCount; i++) {
            const tankHeader = document.createElement('th');
            tankHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            tankHeader.colSpan = 2;
            tankHeader.textContent = `${i}`;
            headerRow.appendChild(tankHeader);

            // Add cm/liter subheaders
            const cmHeader = document.createElement('th');
            cmHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            cmHeader.textContent = 'cm';
            subheaderRow.appendChild(cmHeader);

            const literHeader = document.createElement('th');
            literHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            literHeader.textContent = 'liter';
            subheaderRow.appendChild(literHeader);
        }

        // Add total stok column for Storage Tank
        const totalStokHeader = document.createElement('th');
        totalStokHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
        totalStokHeader.textContent = 'total stok';
        headerRow.appendChild(totalStokHeader);

        const totalStokSubheader = document.createElement('th');
        totalStokSubheader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
        totalStokSubheader.textContent = 'liter';
        subheaderRow.appendChild(totalStokSubheader);

        // Add Service Tank panels
        for (let i = 1; i <= bbmServiceTankCount; i++) {
            const tankHeader = document.createElement('th');
            tankHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            tankHeader.colSpan = 2;
            tankHeader.textContent = `${i}`;
            headerRow.appendChild(tankHeader);

            // Add liter/percentage subheaders
            const literHeader = document.createElement('th');
            literHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            literHeader.textContent = 'liter';
            subheaderRow.appendChild(literHeader);

            const percentageHeader = document.createElement('th');
            percentageHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            percentageHeader.textContent = '%';
            subheaderRow.appendChild(percentageHeader);
        }

        // Add total stok column for Service Tank
        const totalServiceStokHeader = document.createElement('th');
        totalServiceStokHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
        totalServiceStokHeader.textContent = 'total stok';
        headerRow.appendChild(totalServiceStokHeader);

        const totalServiceStokSubheader = document.createElement('th');
        totalServiceStokSubheader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
        totalServiceStokSubheader.textContent = 'liter';
        subheaderRow.appendChild(totalServiceStokSubheader);

        // Add Flowmeter panels
        for (let i = 1; i <= bbmFlowmeterCount; i++) {
            const flowmeterHeader = document.createElement('th');
            flowmeterHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            flowmeterHeader.colSpan = 3;
            flowmeterHeader.textContent = `${i}`;
            headerRow.appendChild(flowmeterHeader);

            // Add awal/akhir/pakai subheaders
            ['awal', 'akhir', `pakai ${i}`].forEach(text => {
                const header = document.createElement('th');
                header.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
                header.textContent = text;
                subheaderRow.appendChild(header);
            });
        }

        // Add total pakai subheader
        const totalPakaiSubheader = document.createElement('th');
        totalPakaiSubheader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
        totalPakaiSubheader.textContent = 'liter';
        subheaderRow.appendChild(totalPakaiSubheader);
    }

    function updateBBMRows() {
        const rows = document.querySelectorAll('#bbm-tbody tr');
        rows.forEach(row => {
            const rowIndex = row.dataset.rowIndex;
            const newInputs = [];

            // Storage Tank inputs
            for (let i = 1; i <= bbmStorageTankCount; i++) {
                newInputs.push(`
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][storage_tank_${i}_cm]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][storage_tank_${i}_liter]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </td>
                `);
            }

            // Total stok for Storage Tank
            newInputs.push(`
                <td class="px-4 py-2 border-r">
                    <input type="number" step="0.1" name="bbm[${rowIndex}][total_stok]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
                </td>
            `);

            // Service Tank inputs
            for (let i = 1; i <= bbmServiceTankCount; i++) {
                newInputs.push(`
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][service_tank_${i}_liter]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][service_tank_${i}_percentage]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </td>
                `);
            }

            // Total stok for Service Tank
            newInputs.push(`
                <td class="px-4 py-2 border-r">
                    <input type="number" step="0.1" name="bbm[${rowIndex}][service_total_stok]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
                </td>
            `);

            // Total Stok Tangki
            newInputs.push(`
                <td class="px-4 py-2 border-r">
                    <input type="number" step="0.1" name="bbm[${rowIndex}][total_stok_tangki]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
                </td>
            `);

            // Terima BBM
            newInputs.push(`
                <td class="px-4 py-2 border-r">
                    <input type="number" step="0.1" name="bbm[${rowIndex}][terima_bbm]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </td>
            `);

            // Dynamic Flowmeter inputs
            for (let i = 1; i <= bbmFlowmeterCount; i++) {
                newInputs.push(`
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_${i}_awal]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_${i}_akhir]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </td>
                    <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_${i}_pakai]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
                    </td>
                `);
            }

            // Total Pakai
            newInputs.push(`
                <td class="px-4 py-2 border-r">
                    <input type="number" step="0.1" name="bbm[${rowIndex}][total_pakai]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
                </td>
            `);

            // Action button
            newInputs.push(`
                <td class="px-4 py-2">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `);

            row.innerHTML = newInputs.join('');
            setupBBMCalculations(row);
        });
    }

    function setupBBMCalculations(row) {
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateBBMTotals(row));
        });
    }

    function calculatePelumasTotals(row) {
        // Calculate Storage Tank total
        let tankTotalLiter = 0;
        for (let i = 1; i <= pelumasStorageTankCount; i++) {
            const liter = parseFloat(row.querySelector(`input[name$="[tank${i}_liter]"]`).value) || 0;
            tankTotalLiter += liter;
        }
        row.querySelector('input[name$="[tank_total_stok]"]').value = tankTotalLiter.toFixed(1);

        // Calculate Drum total
        let drumTotalLiter = 0;
        for (let i = 1; i <= pelumasDrumCount; i++) {
            const liter = parseFloat(row.querySelector(`input[name$="[drum_area${i}]"]`).value) || 0;
            drumTotalLiter += liter;
        }
        row.querySelector('input[name$="[drum_total_stok]"]').value = drumTotalLiter.toFixed(1);

        // Calculate total stok tangki
        const totalStokTangki = tankTotalLiter + drumTotalLiter;
        row.querySelector('input[name$="[total_stok_tangki]"]').value = totalStokTangki.toFixed(1);

        // Calculate total pakai
        const terimaPelumas = parseFloat(row.querySelector('input[name$="[terima_pelumas]"]').value) || 0;
        const totalPakai = totalStokTangki + terimaPelumas;
        row.querySelector('input[name$="[total_pakai]"]').value = totalPakai.toFixed(1);
    }

    function addPelumasRow() {
        const tbody = document.getElementById('pelumas-tbody');
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.dataset.rowIndex = pelumasRowCount++;
        
        // Initialize empty row
        row.innerHTML = '<td></td>';
        tbody.appendChild(row);
        
        // Update row with current panel configuration
        updatePelumasRows();
    }

    function initializePelumasPanels() {
        const headerRow = document.getElementById('pelumas-panel-header-row');
        const subheaderRow = document.getElementById('pelumas-panel-subheader-row');
        
        // Clear existing headers
        headerRow.innerHTML = '';
        subheaderRow.innerHTML = '';

        // Update Storage Tank header colspan
        const storageHeader = document.getElementById('pelumas-storage-header');
        storageHeader.colSpan = pelumasStorageTankCount * 2 + 1;

        // Update Drum header colspan
        const drumHeader = document.getElementById('pelumas-drum-header');
        drumHeader.colSpan = pelumasDrumCount + 1;

        // Add Storage Tank panels
        for (let i = 1; i <= pelumasStorageTankCount; i++) {
            const tankHeader = document.createElement('th');
            tankHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            tankHeader.colSpan = 2;
            tankHeader.textContent = `${i}`;
            headerRow.appendChild(tankHeader);

            // Add cm/liter subheaders
            const cmHeader = document.createElement('th');
            cmHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            cmHeader.textContent = 'cm';
            subheaderRow.appendChild(cmHeader);

            const literHeader = document.createElement('th');
            literHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            literHeader.textContent = 'liter';
            subheaderRow.appendChild(literHeader);
        }

        // Add total stok column
        const totalStokHeader = document.createElement('th');
        totalStokHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
        totalStokHeader.textContent = 'total stok';
        headerRow.appendChild(totalStokHeader);

        const totalStokSubheader = document.createElement('th');
        totalStokSubheader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
        totalStokSubheader.textContent = 'liter';
        subheaderRow.appendChild(totalStokSubheader);

        // Add Drum areas
        for (let i = 1; i <= pelumasDrumCount; i++) {
            const areaHeader = document.createElement('th');
            areaHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
            areaHeader.textContent = `Area ${i}`;
            headerRow.appendChild(areaHeader);

            const literHeader = document.createElement('th');
            literHeader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
            literHeader.textContent = 'liter';
            subheaderRow.appendChild(literHeader);
        }

        // Add total stok drum column
        const totalStokDrumHeader = document.createElement('th');
        totalStokDrumHeader.className = 'px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center';
        totalStokDrumHeader.textContent = 'total stok';
        headerRow.appendChild(totalStokDrumHeader);

        const totalStokDrumSubheader = document.createElement('th');
        totalStokDrumSubheader.className = 'px-4 py-2 text-xs font-medium text-gray-500 border-r text-center';
        totalStokDrumSubheader.textContent = 'liter';
        subheaderRow.appendChild(totalStokDrumSubheader);
    }

    function updatePelumasRows() {
        const rows = document.querySelectorAll('#pelumas-tbody tr');
        rows.forEach(row => {
            const rowIndex = row.dataset.rowIndex;
            const newInputs = [];

            // Storage Tank inputs
            for (let i = 1; i <= pelumasStorageTankCount; i++) {
                newInputs.push(`
            <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="pelumas[${rowIndex}][tank${i}_cm]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="pelumas[${rowIndex}][tank${i}_liter]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
                `);
            }

            // Total stok tank
            newInputs.push(`
            <td class="px-4 py-2 border-r">
                <input type="number" step="0.1" name="pelumas[${rowIndex}][tank_total_stok]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
            </td>
            `);

            // Drum area inputs
            for (let i = 1; i <= pelumasDrumCount; i++) {
                newInputs.push(`
            <td class="px-4 py-2 border-r">
                        <input type="number" step="0.1" name="pelumas[${rowIndex}][drum_area${i}]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
                `);
            }

            // Total stok drum
            newInputs.push(`
            <td class="px-4 py-2 border-r">
                <input type="number" step="0.1" name="pelumas[${rowIndex}][drum_total_stok]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
            </td>
            `);

            // Remaining static inputs
            newInputs.push(`
            <td class="px-4 py-2 border-r">
                <input type="number" step="0.1" name="pelumas[${rowIndex}][total_stok_tangki]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
            </td>
            <td class="px-4 py-2 border-r">
                <input type="number" step="0.1" name="pelumas[${rowIndex}][terima_pelumas]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="px-4 py-2 border-r">
                <input type="number" step="0.1" name="pelumas[${rowIndex}][total_pakai]" class="w-[80px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50" readonly>
            </td>
            <td class="px-4 py-2 border-r">
                <input type="text" name="pelumas[${rowIndex}][jenis]" class="w-[180px] border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="px-4 py-2">
                    <button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                </button>
            </td>
            `);

            row.innerHTML = newInputs.join('');
            setupPelumasCalculations(row);
        });
    }

    function setupPelumasCalculations(row) {
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculatePelumasTotals(row));
        });
    }

    function addPelumasStorageTankPanel() {
        pelumasStorageTankCount++;
        initializePelumasPanels();
        updatePelumasRows();
    }

    function addPelumasDrumPanel() {
        pelumasDrumCount++;
        initializePelumasPanels();
        updatePelumasRows();
    }

    function addBahanKimiaRow() {
        const tbody = document.getElementById('bahan-kimia-tbody');
        const rowIndex = bahanKimiaRowCount++;
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="px-4 py-2 border-r"><input type="text" name="bahan_kimia[${rowIndex}][jenis]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="px-4 py-2 border-r"><input type="number" name="bahan_kimia[${rowIndex}][stok_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="px-4 py-2 border-r"><input type="number" name="bahan_kimia[${rowIndex}][terima]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="px-4 py-2 border-r"><input type="number" name="bahan_kimia[${rowIndex}][total_pakai]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="px-4 py-2"><button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(row);
        setupBahanKimiaCalculations(row);
    }

    function setupBahanKimiaCalculations(row) {
        // Add calculations for Bahan Kimia totals
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateBahanKimiaTotals(row));
        });
    }

    function calculateBahanKimiaTotals(row) {
        // Calculate total pakai
        const stokAwal = parseFloat(row.querySelector('input[name$="[stok_awal]"]').value) || 0;
        const terima = parseFloat(row.querySelector('input[name$="[terima]"]').value) || 0;
        row.querySelector('input[name$="[total_pakai]"]').value = (stokAwal + terima).toFixed(1);
    }

    // Add new function for flowmeter panel
    function addFlowmeterPanel() {
        bbmFlowmeterCount++;
        initializeBBMPanels();
        updateBBMRows();
    }
</script>
@endpush

@endsection