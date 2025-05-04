@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
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
                        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50 transition-colors duration-150">
                            <i class="fas fa-file-excel mr-2"></i> Export Excel
                        </button>
                        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50 transition-colors duration-150">
                            <i class="fas fa-file-pdf mr-2"></i> Export PDF
                        </button>
                        <button type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white rounded-md hover:bg-gray-50 transition-colors duration-150">
                            <i class="fas fa-print mr-2"></i> Print
                        </button>
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
                                    <th colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">Storage Tank</th>
                                    <th colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">Service Tank</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">Total Stok Tangki</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">Terima BBM</th>
                                    <th colspan="7" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center bg-gray-100">Flowmeter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-l border-gray-300 text-center" rowspan="3">aksi</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">2</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">total stok</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">2</th>
                                    <th colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">1</th>
                                    <th colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">2</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">total pakai</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">cm</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">cm</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">%</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">%</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">awal</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">akhir</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">pakai 1</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">awal</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">akhir</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">pakai 2</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center"></th>
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
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center">KWH PRODUKSI</th>
                                    <th colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center">KWH pemakaian sendiri (PS)</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">aksi </th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 2</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center align-middle">total prod.<br>kWH</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 2</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 text-center align-middle">total prod.<br>kWH</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center"></th>
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
                                    <th colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center">Storage Tank</th>
                                    <th colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center">drum pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Total Stok Tangki</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">Terima pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle">total pakai pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle">jenis pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle">aksi</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">2</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">total stok</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">area 1</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">area 2</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">total stok</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">cm</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">cm</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">liter</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center">text</th>
                                    <td class="px-4 py-2 text-xs font-medium text-gray-500 border-l border-gray-300 text-center">aksi</td>
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
<script>
    let bbmRowCount = 0;
    let kwhRowCount = 0;
    let pelumasRowCount = 0;
    let bahanKimiaRowCount = 0;

    function addBBMRow() {
        const tbody = document.getElementById('bbm-tbody');
        const rowIndex = bbmRowCount++;
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][storage_tank_1_cm]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][storage_tank_1_liter]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][storage_tank_2_cm]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][storage_tank_2_liter]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][total_stok]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][service_tank_1_liter]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][service_tank_1_percentage]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][service_tank_2_liter]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][service_tank_2_percentage]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][total_stok_tangki]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][terima_bbm]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_1_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_1_akhir]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_1_pakai]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_2_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_2_akhir]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </td>
            <td class="w-40 px-4 py-2 border-r">
                <input type="number" step="0.1" name="bbm[${rowIndex}][flowmeter_2_pakai]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
            </td>
            <td class="w-40 px-4 py-2">
                <input type="number" step="0.1" name="bbm[${rowIndex}][total_pakai]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly>
            </td>
            <td class="px-4 py-2 border-l border-gray-300 text-center">
                <button type="button"
                    onclick="this.closest('tr').remove()"
                    class="group flex items-center justify-center mx-auto bg-transparent border-none focus:outline-none"
                    aria-label="Hapus Baris"
                    title="Hapus Baris">
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full group-hover:bg-red-100 transition">
                        <i class="fas fa-trash text-red-600 group-hover:text-red-800 text-lg"></i>
                    </span>
                </button>
            </td>
        `;
        tbody.appendChild(row);
        setupBBMCalculations(row);
    }

    function addKWHRow() {
        const tbody = document.getElementById('kwh-tbody');
        const rowIndex = kwhRowCount++;
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][prod_panel1_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][prod_panel1_akhir]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][prod_panel2_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][prod_panel2_akhir]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][prod_total]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][ps_panel1_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][ps_panel1_akhir]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][ps_panel2_awal]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][ps_panel2_akhir]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="kwh[${rowIndex}][ps_total]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="px-4 py-2"><button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(row);
        setupKWHCalculations(row);
    }

    function addPelumasRow() {
        const tbody = document.getElementById('pelumas-tbody');
        const rowIndex = pelumasRowCount++;
        const row = document.createElement('tr');
        row.className = 'hover:bg-gray-50';
        row.innerHTML = `
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][tank1_cm]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][tank1_liter]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][tank2_cm]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][tank2_liter]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][tank_total_stok]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][drum_area1]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][drum_area2]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][drum_total_stok]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][total_stok_tangki]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][terima_pelumas]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="w-40px-4 py-2 border-r"><input type="number" step="0.1" name="pelumas[${rowIndex}][total_pakai]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" readonly></td>
            <td class="w-40px-4 py-2 border-r"><input type="text" name="pelumas[${rowIndex}][jenis]" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"></td>
            <td class="px-4 py-2"><button type="button" onclick="this.closest('tr').remove()" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(row);
        setupPelumasCalculations(row);
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

    function setupBBMCalculations(row) {
        // Add event listeners for BBM calculations in the new row
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', calculateBBMTotals);
        });
    }

    function setupKWHCalculations(row) {
        // Add calculations for KWH totals
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateKWHTotals(row));
        });
    }

    function setupPelumasCalculations(row) {
        // Add calculations for Pelumas totals
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculatePelumasTotals(row));
        });
    }

    function setupBahanKimiaCalculations(row) {
        // Add calculations for Bahan Kimia totals
        const inputs = row.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            input.addEventListener('input', () => calculateBahanKimiaTotals(row));
        });
    }

    function calculateBBMTotals() {
        // Storage Tank total
        const st1Liter = parseFloat(document.querySelector('input[name="bbm[storage_tank_1_liter]"]').value) || 0;
        const st2Liter = parseFloat(document.querySelector('input[name="bbm[storage_tank_2_liter]"]').value) || 0;
        document.querySelector('input[name="bbm[total_stok]"]').value = (st1Liter + st2Liter).toFixed(1);

        // Flowmeter usage calculations
        const fm1Awal = parseFloat(document.querySelector('input[name="bbm[flowmeter_1_awal]"]').value) || 0;
        const fm1Akhir = parseFloat(document.querySelector('input[name="bbm[flowmeter_1_akhir]"]').value) || 0;
        const fm2Awal = parseFloat(document.querySelector('input[name="bbm[flowmeter_2_awal]"]').value) || 0;
        const fm2Akhir = parseFloat(document.querySelector('input[name="bbm[flowmeter_2_akhir]"]').value) || 0;

        const pakai1 = fm1Akhir - fm1Awal;
        const pakai2 = fm2Akhir - fm2Awal;

        document.querySelector('input[name="bbm[flowmeter_1_pakai]"]').value = pakai1.toFixed(1);
        document.querySelector('input[name="bbm[flowmeter_2_pakai]"]').value = pakai2.toFixed(1);
        document.querySelector('input[name="bbm[total_pakai]"]').value = (pakai1 + pakai2).toFixed(1);
    }

    function calculateKWHTotals(row) {
        // Calculate KWH Production total
        const prodPanel1Awal = parseFloat(row.querySelector('input[name$="[prod_panel1_awal]"]').value) || 0;
        const prodPanel1Akhir = parseFloat(row.querySelector('input[name$="[prod_panel1_akhir]"]').value) || 0;
        const prodPanel2Awal = parseFloat(row.querySelector('input[name$="[prod_panel2_awal]"]').value) || 0;
        const prodPanel2Akhir = parseFloat(row.querySelector('input[name$="[prod_panel2_akhir]"]').value) || 0;
        
        const prodTotal = (prodPanel1Akhir - prodPanel1Awal) + (prodPanel2Akhir - prodPanel2Awal);
        row.querySelector('input[name$="[prod_total]"]').value = prodTotal.toFixed(1);

        // Calculate KWH PS total
        const psPanel1Awal = parseFloat(row.querySelector('input[name$="[ps_panel1_awal]"]').value) || 0;
        const psPanel1Akhir = parseFloat(row.querySelector('input[name$="[ps_panel1_akhir]"]').value) || 0;
        const psPanel2Awal = parseFloat(row.querySelector('input[name$="[ps_panel2_awal]"]').value) || 0;
        const psPanel2Akhir = parseFloat(row.querySelector('input[name$="[ps_panel2_akhir]"]').value) || 0;
        
        const psTotal = (psPanel1Akhir - psPanel1Awal) + (psPanel2Akhir - psPanel2Awal);
        row.querySelector('input[name$="[ps_total]"]').value = psTotal.toFixed(1);
    }

    function calculatePelumasTotals(row) {
        // Calculate Storage Tank total
        const tank1Liter = parseFloat(row.querySelector('input[name$="[tank1_liter]"]').value) || 0;
        const tank2Liter = parseFloat(row.querySelector('input[name$="[tank2_liter]"]').value) || 0;
        row.querySelector('input[name$="[tank_total_stok]"]').value = (tank1Liter + tank2Liter).toFixed(1);

        // Calculate Drum total
        const drumArea1 = parseFloat(row.querySelector('input[name$="[drum_area1]"]').value) || 0;
        const drumArea2 = parseFloat(row.querySelector('input[name$="[drum_area2]"]').value) || 0;
        row.querySelector('input[name$="[drum_total_stok]"]').value = (drumArea1 + drumArea2).toFixed(1);

        // Calculate total stok tangki
        const tankTotal = tank1Liter + tank2Liter;
        const drumTotal = drumArea1 + drumArea2;
        row.querySelector('input[name$="[total_stok_tangki]"]').value = (tankTotal + drumTotal).toFixed(1);
    }

    function calculateBahanKimiaTotals(row) {
        // Calculate total pakai
        const stokAwal = parseFloat(row.querySelector('input[name$="[stok_awal]"]').value) || 0;
        const terima = parseFloat(row.querySelector('input[name$="[terima]"]').value) || 0;
        row.querySelector('input[name$="[total_pakai]"]').value = (stokAwal + terima).toFixed(1);
    }

    // Initialize with one row for each table when the page loads
    document.addEventListener('DOMContentLoaded', function() {
        addBBMRow();
        addKWHRow();
        addPelumasRow();
        addBahanKimiaRow();
    });
</script>
@endpush

@endsection 