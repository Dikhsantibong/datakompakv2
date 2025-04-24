@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button id="mobile-menu-toggle" class="md:hidden inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Program Kerja</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.operasi-upkd.program-kerja.export-excel') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export Excel
                    </a>
                    <a href="{{ route('admin.operasi-upkd.program-kerja.export-pdf') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </a>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Operasi UPKD', 'url' => null], ['name' => 'Program Kerja', 'url' => null]]" />
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="py-6">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
                    <!-- Welcome Card -->
                    <div class="bg-gradient-to-r from-[#0A749B] to-[#0A749B]/80 rounded-lg shadow-lg p-6 mb-6">
                        <div class="max-w-7xl mx-auto">
                            <h2 class="text-2xl font-bold text-white mb-2">Program Kerja</h2>
                            <p class="text-blue-100 mb-4">Kelola dan monitor program kerja untuk memastikan pencapaian target dan efisiensi operasional.</p>
                        </div>
                    </div>

                    <!-- Table Section -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
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
                                            <td colspan="21" class="px-3 py-2 text-sm font-semibold text-gray-900 bg-gray-100">I. RUTIN</td>
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
                                            <td class="px-3 py-2 text-sm text-gray-500 border">{{ $item[0] }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-500 border {{ isset($item[2]) ? 'pl-6' : '' }}">{{ $item[1] }}</td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][goal]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][no_prk]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][satuan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][volume]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="rutin[{{ str_replace('.', '_', $item[0]) }}][sifat_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="rutin[{{ str_replace('.', '_', $item[0]) }}][sifat_non_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][bentuk_prj]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][bentuk_kegiatan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_1]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_2]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_3]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_4]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tahap_5]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][harga]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][pic]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                                <select name="rutin[{{ str_replace('.', '_', $item[0]) }}][status]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Pilih Status</option>
                                                    <option value="on_track">On Track</option>
                                                    <option value="delayed">Delayed</option>
                                                    <option value="completed">Completed</option>
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][keterangan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][tindak_lanjut]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="rutin[{{ str_replace('.', '_', $item[0]) }}][monitoring]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][goal]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][no_prk]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][satuan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][volume]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][sifat_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][sifat_non_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][bentuk_prj]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][bentuk_kegiatan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_1]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_2]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_3]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_4]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tahap_5]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][harga]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][pic]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][keterangan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][tindak_lanjut]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="bertahap[{{ str_replace('.', '_', $item[0]) }}][monitoring]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][goal]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][no_prk]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][satuan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][volume]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="tentative[{{ str_replace('.', '_', $item[0]) }}][sifat_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 text-center border">
                                                <input type="checkbox" name="tentative[{{ str_replace('.', '_', $item[0]) }}][sifat_non_rutin]" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][bentuk_prj]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][bentuk_kegiatan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_1]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_2]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_3]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_4]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tahap_5]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][harga]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][pic]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][keterangan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][tindak_lanjut]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border">
                                                <input type="text" name="tentative[{{ str_replace('.', '_', $item[0]) }}][monitoring]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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
});
</script>
@endpush

@endsection
                