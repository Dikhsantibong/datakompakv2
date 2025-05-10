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

                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
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

        <div class="pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Operasi UL/Sentral', 'url' => '#'],
                ['name' => 'Blackstart', 'url' => null]
            ]" />
        </div>

        <!-- Main Content -->
        <div class="px-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-2">Form Input Data Blackstart</h2>
                        <p class="text-sm text-gray-600">Silakan isi form berikut untuk menambahkan data blackstart baru.</p>
                    </div>

                    <form id="blackstartForm" action="{{ route('admin.blackstart.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Tanggal -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Periode Bulan</label>
                                <input type="month" name="tanggal" 
                                       class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200" 
                                       required>
                            </div>
                        </div>

                        <!-- Flash Messages -->
                        <div id="alertMessages">
                            @if(session('success'))
                                <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6" role="alert">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6" role="alert">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6" role="alert">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <ul class="list-disc list-inside text-sm text-red-700">
                                                @foreach($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end items-center gap-4 mb-6">
                            <a href="{{ route('admin.blackstart.show') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Data
                            </a>
                            <button type="submit" id="submitForm"
                                    class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Data
                            </button>
                        </div>

                        <!-- Tables Section -->
                        <div class="space-y-8">
                            <!-- Tabel Input -->
                            <div class="overflow-x-auto rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">No</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Unit Layanan / sentral</th>
                                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200" colspan="2">collect single line diagram</th>
                                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Evidence Diagram</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">SOP Black start</th>
                                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">Evidence SOP</th>
                                            <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200" colspan="3">status ketersediaan</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">PIC</th>
                                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th class="px-3 py-2"></th>
                                            <th class="px-3 py-2"></th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">pembangkit</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">black start</th>
                                            <th class="px-3 py-2"></th>
                                            <th class="px-3 py-2"></th>
                                            <th class="px-3 py-2"></th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">load set</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">line energize</th>
                                            <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">status jaringan</th>
                                            <th class="px-3 py-2"></th>
                                            <th class="px-3 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="blackstart-tbody" class="bg-white divide-y divide-gray-200">
                                        <!-- Rows will be added here dynamically -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Add Row Button -->
                            <div class="flex justify-start">
                                <button type="button" id="add-row" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Tambah Baris
                                </button>
                            </div>

                            <!-- Tabel Peralatan Blackstart -->
                            <div class="mt-8">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Peralatan Blackstart</h2>
                                <div class="overflow-x-auto rounded-lg border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">No</th>
                                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Unit Layanan / sentral</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Kompresor diesel</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Tabung udara</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">UPS</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Lampu emergency</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Battery catudaya</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Battery black start</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Radio komunikasi</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Simulasi black start</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Start kondisi black out</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">TARGET WAKTU Mulai</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Selesai</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Deadline</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">PIC</th>
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase">STATUS</th>
                                            </tr>
                                            <tr>
                                                <th class="px-3 py-2"></th>
                                                <th class="px-3 py-2"></th>
                                                <!-- Kompresor diesel -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Jumlah</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Eviden</th>
                                                <!-- Tabung udara -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Jumlah</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Eviden</th>
                                                <!-- UPS -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <!-- Lampu emergency -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Jumlah</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Eviden</th>
                                                <!-- Battery catudaya -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Jumlah</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Eviden</th>
                                                <!-- Battery black start -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Jumlah</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Eviden</th>
                                                <!-- Radio komunikasi -->
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Jumlah</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Kondisi</th>
                                                <th class="px-1 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">Eviden</th>
                                                <th class="px-3 py-2"></th>
                                                <th class="px-3 py-2"></th>
                                                <th class="px-3 py-2"></th>
                                                <th class="px-3 py-2"></th>
                                                <th class="px-3 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="peralatan-tbody" class="bg-white divide-y divide-gray-200">
                                            <!-- Template row will be added here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Add Peralatan Row Button -->
                            <div class="mt-4">
                                <button type="button" id="add-peralatan-row" 
                                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                    Tambah Peralatan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Row Templates -->
<template id="row-template">
    <tr>
        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="row-number"></span>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="unit_id[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Unit</option>
                @foreach($powerPlants as $powerPlant)
                    <option value="{{ $powerPlant->id }}">{{ $powerPlant->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="pembangkit_status[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="tidak_tersedia">Tidak Tersedia</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="black_start_status[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="tidak_tersedia">Tidak Tersedia</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="diagram_evidence[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 evidence-input" data-preview="diagram-preview">
            <div class="diagram-preview mt-1 text-xs text-gray-600"></div>
            <div class="diagram-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="sop_status[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="tidak_tersedia">Tidak Tersedia</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="sop_evidence[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 evidence-input" data-preview="sop-preview">
            <div class="sop-preview mt-1 text-xs text-gray-600"></div>
            <div class="sop-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="load_set_status[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="tidak_tersedia">Tidak Tersedia</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="line_energize_status[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Status</option>
                <option value="tersedia">Tersedia</option>
                <option value="tidak_tersedia">Tidak Tersedia</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="status_jaringan[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                <option value="">Pilih Status</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="text" name="pic[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Nama PIC" required>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <button type="button" class="delete-row text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </td>
    </tr>
</template>

<template id="peralatan-row-template">
    <tr>
        <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
            <span class="row-number"></span>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="unit_layanan[]" class="p-2 w-[150px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih Unit</option>
                @foreach($powerPlants as $powerPlant)
                    <option value="{{ $powerPlant->id }}">{{ $powerPlant->name }}</option>
                @endforeach
            </select>
        </td>
        <!-- Kompresor diesel -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="number" name="kompresor_jumlah[]" class="p-2 w-[80px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="kompresor_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="kompresor_eviden[]" class="evidence-input p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" data-preview="kompresor-preview">
            <div class="kompresor-preview mt-1 text-xs text-gray-600"></div>
            <div class="kompresor-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <!-- Tabung udara -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="number" name="tabung_jumlah[]" class="p-2 w-[80px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="tabung_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="tabung_eviden[]" class="evidence-input p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" data-preview="tabung-preview">
            <div class="tabung-preview mt-1 text-xs text-gray-600"></div>
            <div class="tabung-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <!-- UPS -->
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="ups_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <!-- Lampu Emergency -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="number" name="lampu_jumlah[]" class="p-2 w-[80px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="lampu_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="lampu_eviden[]" class="evidence-input p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" data-preview="lampu-preview">
            <div class="lampu-preview mt-1 text-xs text-gray-600"></div>
            <div class="lampu-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <!-- Battery Catudaya -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="number" name="battery_catudaya_jumlah[]" class="p-2 w-[80px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="battery_catudaya_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="catudaya_eviden[]" class="evidence-input p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" data-preview="catudaya-preview">
            <div class="catudaya-preview mt-1 text-xs text-gray-600"></div>
            <div class="catudaya-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <!-- Battery Black Start -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="number" name="battery_blackstart_jumlah[]" class="p-2 w-[80px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="battery_blackstart_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="blackstart_eviden[]" class="evidence-input p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" data-preview="blackstart-preview">
            <div class="blackstart-preview mt-1 text-xs text-gray-600"></div>
            <div class="blackstart-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <!-- Radio Komunikasi -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="number" name="radio_jumlah[]" class="p-2 w-[80px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="radio_komunikasi_kondisi[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="normal">Normal</option>
                <option value="tidak_normal">Tidak Normal</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="file" name="radio_eviden[]" class="evidence-input p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" data-preview="radio-preview">
            <div class="radio-preview mt-1 text-xs text-gray-600"></div>
            <div class="radio-filename mt-1 text-xs text-gray-600"></div>
        </td>
        <!-- Simulasi Black Start -->
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="simulasi_blackstart[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="pernah">Pernah</option>
                <option value="belum_pernah">Belum Pernah</option>
            </select>
        </td>
        <!-- Start Kondisi Black Out -->
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="start_kondisi_blackout[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="pernah">Pernah</option>
                <option value="belum_pernah">Belum Pernah</option>
            </select>
        </td>
        <!-- Target Waktu -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="time" name="waktu_mulai[]" class="p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="time" name="waktu_selesai[]" class="p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="time" name="waktu_deadline[]" class="p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
        </td>
        <!-- PIC -->
        <td class="px-3 py-4 whitespace-nowrap">
            <input type="text" name="pic[]" class="p-2 w-[120px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Nama PIC">
        </td>
        <!-- Status -->
        <td class="px-3 py-4 whitespace-nowrap">
            <select name="status[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih</option>
                <option value="open">OPEN</option>
                <option value="close">CLOSE</option>
            </select>
        </td>
        <td class="px-3 py-4 whitespace-nowrap">
            <button type="button" class="delete-row text-red-500 hover:text-red-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </td>
    </tr>
</template>

<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Existing table functionality
    const tbody = document.getElementById('blackstart-tbody');
    const addRowButton = document.getElementById('add-row');
    const rowTemplate = document.getElementById('row-template');

    // New peralatan table functionality
    const peralatanTbody = document.getElementById('peralatan-tbody');
    const addPeralatanRowButton = document.getElementById('add-peralatan-row');
    const peralatanRowTemplate = document.getElementById('peralatan-row-template');

    function updateRowNumbers(tableBody) {
        const rows = tableBody.getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            const numberCell = rows[i].querySelector('.row-number');
            if (numberCell) {
                numberCell.textContent = i + 1;
            }
        }
    }

    function addRow(tbody, template) {
        const clone = document.importNode(template.content, true);
        
        // Add delete functionality to the new row
        const deleteButton = clone.querySelector('.delete-row');
        deleteButton.addEventListener('click', function() {
            this.closest('tr').remove();
            updateRowNumbers(tbody);
        });

        tbody.appendChild(clone);
        updateRowNumbers(tbody);
    }

    if (addRowButton && rowTemplate) {
        addRowButton.addEventListener('click', () => addRow(tbody, rowTemplate));
        // Add initial row if tbody is empty
        if (tbody.children.length === 0) {
            addRow(tbody, rowTemplate);
        }
    }

    if (addPeralatanRowButton && peralatanRowTemplate) {
        addPeralatanRowButton.addEventListener('click', () => addRow(peralatanTbody, peralatanRowTemplate));
        // Add initial row if peralatanTbody is empty
        if (peralatanTbody.children.length === 0) {
            addRow(peralatanTbody, peralatanRowTemplate);
        }
    }

    // Form submission handling
    const form = document.getElementById('blackstartForm');
    const submitButton = document.getElementById('submitForm');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Menyimpan...
        `;

        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok) {
                // Show success message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6';
                alertDiv.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">${result.message || 'Data berhasil disimpan'}</p>
                        </div>
                    </div>
                `;

                const alertMessages = document.getElementById('alertMessages');
                alertMessages.innerHTML = '';
                alertMessages.appendChild(alertDiv);

                // Redirect after success
                setTimeout(() => {
                    window.location.href = "{{ route('admin.blackstart.show') }}";
                }, 1500);
            } else {
                // Show error message
                const errors = result.errors || {'error': [result.message || 'Terjadi kesalahan saat menyimpan data']};
                let errorHtml = '';
                
                for (const field in errors) {
                    errors[field].forEach(error => {
                        errorHtml += `<li>${error}</li>`;
                    });
                }

                const alertDiv = document.createElement('div');
                alertDiv.className = 'bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6';
                alertDiv.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <ul class="list-disc list-inside text-sm text-red-700">
                                ${errorHtml}
                            </ul>
                        </div>
                    </div>
                `;

                const alertMessages = document.getElementById('alertMessages');
                alertMessages.innerHTML = '';
                alertMessages.appendChild(alertDiv);
            }
        } catch (error) {
            console.error('Error:', error);
            // Show error message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6';
            alertDiv.innerHTML = `
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">Terjadi kesalahan saat menyimpan data</p>
                    </div>
                </div>
            `;

            const alertMessages = document.getElementById('alertMessages');
            alertMessages.innerHTML = '';
            alertMessages.appendChild(alertDiv);
        } finally {
            submitButton.disabled = false;
            submitButton.innerHTML = `
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Simpan Data
            `;
        }
    });

    // File input handling (bahasa Indonesia)
    function handleEvidenceInputChange(e) {
        if (e.target.classList.contains('evidence-input')) {
            const file = e.target.files[0];
            const previewDiv = e.target.parentElement.querySelector('.' + e.target.dataset.preview);
            const filenameDiv = e.target.parentElement.querySelector('.' + e.target.dataset.preview.replace('preview', 'filename'));
            
            if (file) {
                // Tampilkan nama file
                filenameDiv.textContent = `Berkas: ${file.name}`;
                // Preview untuk gambar
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(ev) {
                        previewDiv.innerHTML = `
                            <img src="${ev.target.result}" class="max-w-[100px] max-h-[100px] object-contain mt-1 rounded" alt="Pratinjau Gambar">
                        `;
                    }
                    reader.readAsDataURL(file);
                } else if (file.type === 'application/pdf') {
                    previewDiv.innerHTML = `
                        <div class="flex items-center text-blue-600 mt-1">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span>Berkas PDF</span>
                        </div>
                    `;
                } else {
                    previewDiv.innerHTML = '';
                }
            } else {
                previewDiv.innerHTML = '';
                filenameDiv.textContent = '';
            }
        }
    }

    // Event listener untuk file input, termasuk baris dinamis
    document.addEventListener('change', handleEvidenceInputChange);

    // Jika baris baru ditambahkan, pastikan event tetap berjalan (delegasi sudah cukup)
});
</script>
@endsection 