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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Edit Laporan KIT 00.00', 'url' => null]]" />
        </div>

        <div class="container mx-auto px-4 sm:px-6">
            <form action="{{ route('admin.laporan-kit.update', $laporan->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $laporan->tanggal) }}" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium">Unit</label>
                            <select name="unit_source" class="form-select w-full">
                                @foreach($powerPlants as $plant)
                                    <option value="{{ $plant->unit_source }}" {{ $laporan->unit_source == $plant->unit_source ? 'selected' : '' }}>
                                        {{ $plant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Jam Operasi Mesin -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">JAM OPERASI MESIN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th>Mesin</th>
                                    <th>Ops</th>
                                    <th>Har</th>
                                    <th>Ggn</th>
                                    <th>Stby/Rsh</th>
                                    <th>Jam/Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machines as $machine)
                                @php
                                    $data = optional($laporan->jamOperasi)->firstWhere('machine_id', $machine->id);
                                @endphp
                                <tr>
                                    <td>{{ $machine->name }}</td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][ops]" value="{{ optional($data)->ops }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][har]" value="{{ optional($data)->har }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][ggn]" value="{{ optional($data)->ggn }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][stby]" value="{{ optional($data)->stby }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="mesin[{{ $machine->id }}][jam_hari]" value="{{ optional($data)->jam_hari }}" class="w-full border-gray-300 rounded-md"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Jenis Gangguan Mesin -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">JENIS GANGGUAN MESIN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th>Mesin</th>
                                    <th>Mekanik</th>
                                    <th>Elektrik</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machines as $machine)
                                @php
                                    $data = optional($laporan->gangguan)->firstWhere('machine_id', $machine->id);
                                @endphp
                                <tr>
                                    <td>{{ $machine->name }}</td>
                                    <td><input type="number" name="gangguan[{{ $machine->id }}][mekanik]" value="{{ optional($data)->mekanik }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" name="gangguan[{{ $machine->id }}][elektrik]" value="{{ optional($data)->elektrik }}" class="w-full border-gray-300 rounded-md"></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data Pemeriksaan BBM -->
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
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center">liter</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($laporan->bbm as $i => $row)
                                <tr>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][storage_tank_1_cm]" value="{{ $row->storage_tank_1_cm }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][storage_tank_1_liter]" value="{{ $row->storage_tank_1_liter }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][storage_tank_2_cm]" value="{{ $row->storage_tank_2_cm }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][storage_tank_2_liter]" value="{{ $row->storage_tank_2_liter }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][total_stok]" value="{{ $row->total_stok }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][service_tank_1_liter]" value="{{ $row->service_tank_1_liter }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][service_tank_1_percentage]" value="{{ $row->service_tank_1_percentage }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][service_tank_2_liter]" value="{{ $row->service_tank_2_liter }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][service_tank_2_percentage]" value="{{ $row->service_tank_2_percentage }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][total_stok_tangki]" value="{{ $row->total_stok_tangki }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][terima_bbm]" value="{{ $row->terima_bbm }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][flowmeter_1_awal]" value="{{ $row->flowmeter_1_awal }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][flowmeter_1_akhir]" value="{{ $row->flowmeter_1_akhir }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][flowmeter_1_pakai]" value="{{ $row->flowmeter_1_pakai }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][flowmeter_2_awal]" value="{{ $row->flowmeter_2_awal }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][flowmeter_2_akhir]" value="{{ $row->flowmeter_2_akhir }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" name="bbm[{{ $i }}][flowmeter_2_pakai]" value="{{ $row->flowmeter_2_pakai }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td><input type="number" name="bbm[{{ $i }}][total_pakai]" value="{{ $row->total_pakai }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data Pemeriksaan KWH -->
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
                                </tr>
                                <tr class="bg-gray-50">
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">PANEL 1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">PANEL 2</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center align-middle">total prod.<br>kWH</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">PANEL 1</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">PANEL 2</th>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporan->kwh ?? [] as $i => $row)
                                <tr>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][prod_panel1_awal]" value="{{ optional($row)->prod_panel1_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][prod_panel1_akhir]" value="{{ optional($row)->prod_panel1_akhir }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][prod_panel2_awal]" value="{{ optional($row)->prod_panel2_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][prod_panel2_akhir]" value="{{ optional($row)->prod_panel2_akhir }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][prod_total]" value="{{ optional($row)->prod_total }}" class="w-full border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][ps_panel1_awal]" value="{{ optional($row)->ps_panel1_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][ps_panel1_akhir]" value="{{ optional($row)->ps_panel1_akhir }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][ps_panel2_awal]" value="{{ optional($row)->ps_panel2_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][ps_panel2_akhir]" value="{{ optional($row)->ps_panel2_akhir }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="kwh[{{ $i }}][ps_total]" value="{{ optional($row)->ps_total }}" class="w-full border-gray-300 rounded-md bg-gray-50" readonly></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data KWH</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Data Pemeriksaan Pelumas -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">DATA PEMERIKSAAN PELUMAS</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th colspan="5" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">Storage Tank</th>
                                    <th colspan="3" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">drum pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">Total Stok Tangki</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">Terima pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center align-middle bg-gray-100">total pakai pelumas</th>
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center align-middle bg-gray-100">jenis pelumas</th>
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
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporan->pelumas ?? [] as $i => $row)
                                <tr>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][tank1_cm]" value="{{ optional($row)->tank1_cm }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][tank1_liter]" value="{{ optional($row)->tank1_liter }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][tank2_cm]" value="{{ optional($row)->tank2_cm }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][tank2_liter]" value="{{ optional($row)->tank2_liter }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][tank_total_stok]" value="{{ optional($row)->tank_total_stok }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][drum_area1]" value="{{ optional($row)->drum_area1 }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][drum_area2]" value="{{ optional($row)->drum_area2 }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][drum_total_stok]" value="{{ optional($row)->drum_total_stok }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][total_stok_tangki]" value="{{ optional($row)->total_stok_tangki }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][terima_pelumas]" value="{{ optional($row)->terima_pelumas }}" class="w-[80px] border-gray-300 rounded-md"></td>
                                    <td class="border-r"><input type="number" step="0.1" name="pelumas[{{ $i }}][total_pakai]" value="{{ optional($row)->total_pakai }}" class="w-[80px] border-gray-300 rounded-md bg-gray-50" readonly></td>
                                    <td><input type="text" name="pelumas[{{ $i }}][jenis]" value="{{ optional($row)->jenis }}" class="w-[180px] border-gray-300 rounded-md"></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data pelumas</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pemeriksaan Bahan Kimia -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">PEMERIKSAAN BAHAN KIMIA</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th>Jenis Bahan Kimia</th>
                                    <th>Stok Awal</th>
                                    <th>Terima Bahan Kimia</th>
                                    <th>Total Pakai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($laporan->bahanKimia ?? [] as $i => $row)
                                <tr>
                                    <td><input type="text" name="bahan_kimia[{{ $i }}][jenis]" value="{{ optional($row)->jenis }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="bahan_kimia[{{ $i }}][stok_awal]" value="{{ optional($row)->stok_awal }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="bahan_kimia[{{ $i }}][terima]" value="{{ optional($row)->terima }}" class="w-full border-gray-300 rounded-md"></td>
                                    <td><input type="number" step="0.1" name="bahan_kimia[{{ $i }}][total_pakai]" value="{{ optional($row)->total_pakai }}" class="w-full border-gray-300 rounded-md bg-gray-50" readonly></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data bahan kimia</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Beban Tertinggi Harian -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden p-6 m-2">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">BEBAN TERTINGGI HARIAN</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th rowspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">Mesin</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">Beban Tertinggi</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">Siang (07:00 s/d 17:00)</th>
                                    <th class="px-4 py-2 text-xs font-medium text-gray-500 text-center">Malam (18:00 s/d 06:00)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($machines as $machine)
                                @php
                                    $data = optional($laporan->bebanTertinggi)->firstWhere('machine_id', $machine->id);
                                @endphp
                                <tr>
                                    <td class="px-4 py-2 text-sm text-gray-900 border-r text-center">{{ $machine->name }}</td>
                                    <td class="px-4 py-2 border-r">
                                        <input type="number" step="0.1" name="beban[{{ $machine->id }}][siang]" value="{{ optional($data)->siang }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                    <td class="px-4 py-2">
                                        <input type="number" step="0.1" name="beban[{{ $machine->id }}][malam]" value="{{ optional($data)->malam }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 p-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<script src="{{ asset('js/toggle.js') }}"></script>
@endsection
