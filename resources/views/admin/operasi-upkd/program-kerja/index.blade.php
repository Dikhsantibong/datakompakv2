@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Program Kerja</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Operasi UPKD', 'url' => null], ['name' => 'Program Kerja', 'url' => null]]" />
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Program Kerja</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor program kerja untuk memastikan pencapaian target dan efisiensi operasional.</p>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                            <a href="{{ route('admin.operasi-upkd.program-kerja.create') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Data Program Kerja</h2>
                            <button id="toggleFullTable" 
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                    onclick="toggleFullTableView()">
                                <i class="fas fa-expand mr-1"></i> Full Table
                            </button>
                        </div>

                        <!-- Filter Controls -->
                        <div id="table-controls">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex flex-wrap gap-2" id="active-filters">
                                        <!-- Active filters will be displayed here -->
                                    </div>
                                </div>
                            </div>

                            <!-- Horizontal Filters -->
                            <div class="mt-2 border-b border-gray-200 pb-4" id="filters-section">
                                <form action="{{ route('admin.operasi-upkd.program-kerja.index') }}" method="GET" 
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                                        <select name="tahun" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Tahun</option>
                                            @for($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="on_track">On Track</option>
                                            <option value="delayed">Delayed</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            <i class="fas fa-search mr-2"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.operasi-upkd.program-kerja.index') }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <form action="{{ route('admin.operasi-upkd.program-kerja.store') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NO</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">PROGRAM KERJA</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">GOAL</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NO. PRK</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">SATUAN</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">VOLUME</th>
                                            <th colspan="2" class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">SIFAT</th>
                                            <th colspan="2" class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">BENTUK</th>
                                            <th colspan="5" class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TAHAP</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">HARGA</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">PIC</th>
                                            <th colspan="3" class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">WAKTU</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">STATUS</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KETERANGAN</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">TINDAK LANJUT</th>
                                            <th rowspan="2" class="px-3 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">LINK MONITORING</th>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">RUTIN</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NON RUTIN</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">PRJ</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KEGIATAN</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KE 1 2025</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KE 2 2026</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KE 3 2027</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KE 4 2028</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">KE 5 2029</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">MULAI</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">SELESAI</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">DEADLINE TARGET</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- I. RUTIN Section -->
                                        <tr class="bg-gray-50">
                                            <td colspan="21" class="px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100 w-[500px]">I. RUTIN</td>
                                        </tr>
                                        @php
                                        $rutinItems = [
                                            ['1', 'PERFORMANCE TEST'],
                                            ['2', 'ANTI BLACK OUT - BLACKSTART'],
                                            ['3', 'PLM'],
                                            ['4', 'PTW'],
                                            ['5', 'MATURITY LEVEL OPERASI'],
                                            ['5.1', 'OPERATION', true],
                                            ['5.2', 'EFICIENCY', true],
                                            ['6', 'WORKSHOP OPERASI'],
                                            ['6.1', 'ADMINISTRASI OPERASI', true],
                                            ['6.2', 'MATURITY LEVEL', true],
                                            ['6.3', 'REFRESHMENT APLIKASI', true],
                                            ['6.4', 'PROGRAM KERJA', true],
                                            ['7', 'PELAYANAN PELANGGAN'],
                                            ['7.1', 'REKONSOLIASI DATA SISTEM & KIT', true],
                                            ['8', 'TOOLS PLM'],
                                            ['9', 'MATERIAL CONSUMABLE OPERASI'],
                                            ['10', 'MATERIAL 5S5R AREA PRODUKSI'],
                                            ['11', 'BIAYA SDM OPERASI'],
                                            ['12', 'PEMBUATAN DAN REVIEW SOP']
                                        ];
                                        @endphp

                                        @foreach($rutinItems as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-sm text-gray-500 border w-[600px] ">{{ $item[0] }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-500 border {{ isset($item[2]) ? 'pl-6' : '' }}">{{ $item[1] }}</td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="rutin[{{ str_replace('.', '_', $item[0]) }}][goal]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][no_prk]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][satuan]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][volume]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="rutin[{{ str_replace('.', '_', $item[0]) }}][sifat_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="rutin[{{ str_replace('.', '_', $item[0]) }}][sifat_non_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][bentuk_prj]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][bentuk_kegiatan]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_1]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_2]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_3]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_4]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_5]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][harga]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][pic]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="rutin[{{ str_replace('.', '_', $item[0]) }}][waktu_mulai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="rutin[{{ str_replace('.', '_', $item[0]) }}][waktu_selesai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="rutin[{{ str_replace('.', '_', $item[0]) }}][deadline]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <select name="rutin[{{ str_replace('.', '_', $item[0]) }}][status]" class=" p-2 w-[120px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Pilih Status</option>
                                                    <option value="on_track">On Track</option>
                                                    <option value="delayed">Delayed</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="rutin[{{ str_replace('.', '_', $item[0]) }}][keterangan]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="rutin[{{ str_replace('.', '_', $item[0]) }}][tindak_lanjut]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][monitoring]" class="w-full min-w-[250px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                        </tr>
                                        @endforeach

                                        <!-- II. BERTAHAP Section -->
                                        <tr class="bg-gray-50">
                                            <td colspan="21" class="px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100">II. BERTAHAP</td>
                                        </tr>
                                        @php
                                        $bertahapItems = [
                                            ['1', 'PEMAKAIAN SENDIRI - SUSUT TRAFO'],
                                            ['1.1', 'AUTO HEATER GENERATOR', true],
                                            ['1.2', 'PISAH KWH PRODUKSI & LANGGAN', true],
                                            ['1.3', 'PISAH TRAFO KIT', true],
                                            ['1.4', 'REKAYASA PS KE LINE 20KV', true],
                                            ['2', 'JUSTIFIKASI KONDISI SARANA DAN PRASARANA OPERASI'],
                                            ['3', 'RADIO KOMUNIKASI'],
                                            ['3.1', 'RIG DAN HT', true],
                                            ['3.2', 'ANTENE RADIO KOMUNIKASI', true],
                                            ['3.3', 'SSB RADIO', true],
                                            ['4', 'ACC']
                                        ];
                                        @endphp

                                        @foreach($bertahapItems as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-sm text-gray-500 border">{{ $item[0] }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-500 border {{ isset($item[2]) ? 'pl-6' : '' }}">{{ $item[1] }}</td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="bertahap[{{ str_replace('.', '_', $item[0]) }}][goal]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][no_prk]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][satuan]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][volume]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][sifat_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][sifat_non_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][bentuk_prj]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][bentuk_kegiatan]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_1]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_2]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_3]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_4]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_5]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][harga]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][pic]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][waktu_mulai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][waktu_selesai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][deadline]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <select name="bertahap[{{ str_replace('.', '_', $item[0]) }}][status]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Pilih Status</option>
                                                    <option value="on_track">On Track</option>
                                                    <option value="delayed">Delayed</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="bertahap[{{ str_replace('.', '_', $item[0]) }}][keterangan]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tindak_lanjut]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][monitoring]" class="w-full min-w-[250px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                        </tr>
                                        @endforeach

                                        <!-- III. TENTATIVE Section -->
                                        <tr class="bg-gray-50">
                                            <td colspan="21" class="px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100">III. TENTATIVE</td>
                                        </tr>
                                        @php
                                        $tentativeItems = [
                                            ['1', 'SLO - reSLO'],
                                            ['2', 'TERA KWH']
                                        ];
                                        @endphp

                                        @foreach($tentativeItems as $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-3 py-2 text-sm text-gray-500 border">{{ $item[0] }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-500 border">{{ $item[1] }}</td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="tentative[{{ str_replace('.', '_', $item[0]) }}][goal]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][no_prk]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][satuan]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][volume]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="tentative[{{ str_replace('.', '_', $item[0]) }}][sifat_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="tentative[{{ str_replace('.', '_', $item[0]) }}][sifat_non_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][bentuk_prj]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][bentuk_kegiatan]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_1]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_2]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_3]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_4]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_5]" class="w-full min-w-[150px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][harga]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][pic]" class="w-full min-w-[200px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="tentative[{{ str_replace('.', '_', $item[0]) }}][waktu_mulai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="tentative[{{ str_replace('.', '_', $item[0]) }}][waktu_selesai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="date" name="tentative[{{ str_replace('.', '_', $item[0]) }}][deadline]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <select name="tentative[{{ str_replace('.', '_', $item[0]) }}][status]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Pilih Status</option>
                                                    <option value="on_track">On Track</option>
                                                    <option value="delayed">Delayed</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="tentative[{{ str_replace('.', '_', $item[0]) }}][keterangan]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <textarea name="tentative[{{ str_replace('.', '_', $item[0]) }}][tindak_lanjut]" class="w-full min-w-[300px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="2"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][monitoring]" class="w-full min-w-[250px] text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                        </tr>
                                        @endforeach

                                        <!-- Note Section -->
                                        <tr>
                                            <td colspan="21" class="px-3 py-4 border">
                                                <div class="font-medium text-gray-900">NOTE:</div>
                                                <div class="mt-1">Link Monitoring:</div>
                                                <input type="text" name="note[link_monitoring]" class="mt-1 w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan link monitoring...">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                    <i class="fas fa-save mr-2"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
    const sidebar = document.querySelector('aside');
    
    mobileMenuBtn?.addEventListener('click', () => {
        sidebar?.classList.toggle('hidden');
    });

    // Auto-submit form when changing filters
    const filterForm = document.querySelector('form');
    const filterInputs = filterForm.querySelectorAll('select');

    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            filterForm.submit();
        });
    });
});

function removeFilter(filterName) {
    const form = document.querySelector('form');
    const input = form.querySelector(`[name="${filterName}"]`);
    if (input) {
        input.value = '';
        form.submit();
    }
}

// Add full table view toggle functionality
function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const tableControls = document.getElementById('table-controls');
    const welcomeCard = document.querySelector('.bg-gradient-to-r').closest('.mb-6');
    const successAlert = document.querySelector('.bg-green-100');
    
    // Toggle full table mode
    const isFullTable = button.classList.contains('bg-blue-600');
    
    if (isFullTable) {
        // Restore normal view
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';
        
        if (tableControls) tableControls.style.display = '';
        if (welcomeCard) welcomeCard.style.display = '';
        if (successAlert) successAlert.style.display = '';
        
    } else {
        // Enable full table view
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';
        
        if (tableControls) tableControls.style.display = 'none';
        if (welcomeCard) welcomeCard.style.display = 'none';
        if (successAlert) successAlert.style.display = 'none';
    }
}
</script>
@endpush

@endsection
                