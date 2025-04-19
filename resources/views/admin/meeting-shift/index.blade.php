@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
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
                    <h1 class="text-xl font-semibold text-gray-900">Meeting dan Mutasi Shift Operator</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Meeting dan Mutasi Shift Operator', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Meeting dan Mutasi Shift Operator</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor aktivitas meeting dan mutasi shift operator untuk memastikan operasional yang optimal.</p>
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
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Tab Navigation -->
                        <div x-data="{ activeTab: 'kondisi-mesin' }" class="space-y-6">
                            <nav class="flex space-x-6 border-b border-gray-200 overflow-x-auto" aria-label="Tabs">
                                <button @click="activeTab = 'kondisi-mesin'" 
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'kondisi-mesin',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-mesin'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-cogs mr-2"></i>
                                    Kondisi Mesin
                                </button>
                                
                                <button @click="activeTab = 'kondisi-alat-bantu'"
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'kondisi-alat-bantu',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-alat-bantu'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-tools mr-2"></i>
                                    Kondisi Alat Bantu
                                </button>
                                
                                <button @click="activeTab = 'kondisi-resource'"
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'kondisi-resource',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-resource'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-boxes mr-2"></i>
                                    Kondisi Resource
                                </button>
                                
                                <button @click="activeTab = 'kondisi-k3l'"
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'kondisi-k3l',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-k3l'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-hard-hat mr-2"></i>
                                    Kondisi K3L
                                </button>
                                
                                <button @click="activeTab = 'catatan-sistem'"
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'catatan-sistem',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'catatan-sistem'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-clipboard-list mr-2"></i>
                                    Catatan Kondisi Sistem
                                </button>
                                
                                <button @click="activeTab = 'catatan-validasi'"
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'catatan-validasi',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'catatan-validasi'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-clipboard-check mr-2"></i>
                                    Catatan Umum
                                </button>
                                
                                <button @click="activeTab = 'absensi-resume'"
                                        :class="{'border-[#009BB9] text-[#009BB9]': activeTab === 'absensi-resume',
                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'absensi-resume'}"
                                        class="py-4 px-1 border-b-2 font-medium text-sm whitespace-nowrap transition-all duration-200">
                                    <i class="fas fa-users mr-2"></i>
                                    Absensi dan Resume Rapat
                                </button>
                            </nav>

                            <!-- Tab Panels -->
                            <div class="mt-6">
                                <!-- Kondisi Mesin -->
                                <div x-show="activeTab === 'kondisi-mesin'" 
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 transform scale-95"
                                     x-transition:enter-end="opacity-100 transform scale-100"
                                     class="space-y-6">
                                    <div class="flex justify-between items-center mb-6">
                                        <h2 class="text-lg font-semibold text-gray-800">Status Kondisi Mesin</h2>
                                        <div class="flex gap-2">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                                <i class="fas fa-download mr-2"></i>
                                                Export
                                            </button>
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                                <i class="fas fa-print mr-2"></i>
                                                Print
                                            </button>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.meeting-shift.store') }}" method="POST" class="space-y-6">
                                        @csrf
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                                                <thead>
                                                    <tr class="bg-gray-50">
                                                        <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle border-r">
                                                            Mesin
                                                        </th>
                                                        <th colspan="5" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                                            Status
                                                        </th>
                                                        <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider align-middle">
                                                            Keterangan
                                                        </th>
                                                    </tr>
                                                    <tr class="bg-gray-50">
                                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span>Operasi</span>
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                    Normal
                                                                </span>
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span>Stand By</span>
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                    Siap
                                                                </span>
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span>HAR Rutin</span>
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                    Maintenance
                                                                </span>
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span>HAR Non Rutin</span>
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                                    Perbaikan
                                                                </span>
                                                            </div>
                                                        </th>
                                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            <div class="flex flex-col items-center gap-1">
                                                                <span>Gangguan</span>
                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    Masalah
                                                                </span>
                                                            </div>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($machines as $index => $machine)
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">
                                                            {{ $machine->name }}
                                                            <input type="hidden" name="machine_status[{{ $index }}][machine_id]" value="{{ $machine->id }}">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="machine_status[{{ $index }}][status][]" value="operasi" 
                                                                       class="form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 focus:ring-green-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="machine_status[{{ $index }}][status][]" value="standby" 
                                                                       class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="machine_status[{{ $index }}][status][]" value="har_rutin" 
                                                                       class="form-checkbox h-5 w-5 text-yellow-600 rounded border-gray-300 focus:ring-yellow-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="machine_status[{{ $index }}][status][]" value="har_nonrutin" 
                                                                       class="form-checkbox h-5 w-5 text-orange-600 rounded border-gray-300 focus:ring-orange-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="machine_status[{{ $index }}][status][]" value="gangguan" 
                                                                       class="form-checkbox h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="text" name="machine_status[{{ $index }}][keterangan]" 
                                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm" 
                                                                   placeholder="Masukkan keterangan...">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Tombol Submit -->
                                        <div class="flex justify-end mt-6">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9] transition-all duration-200">
                                                <i class="fas fa-save mr-2"></i>
                                                Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Kondisi Alat Bantu -->
                                <div x-show="activeTab === 'kondisi-alat-bantu'" class="space-y-4">
                                    <form action="{{ route('admin.meeting-shift.store-alat-bantu') }}" method="POST">
                                        @csrf
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white border border-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Alat Bantu</th>
                                                        <th colspan="4" class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Status</th>
                                                        <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Keterangan</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Normal</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Abnormal</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Gangguan</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">FLM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $alatBantu = [
                                                        'system pelumas',
                                                        'system bbm',
                                                        'system jcw/HT',
                                                        'system cw/LT',
                                                        'system start'
                                                    ];
                                                    @endphp

                                                    @foreach($alatBantu as $index => $alat)
                                                    <tr class="hover:bg-gray-50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r">
                                                            {{ $alat }}
                                                            <input type="hidden" name="alat_bantu[{{ $index }}][name]" value="{{ $alat }}">
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="alat_bantu[{{ $index }}][status][]" value="normal" 
                                                                       class="form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 focus:ring-green-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="alat_bantu[{{ $index }}][status][]" value="abnormal" 
                                                                       class="form-checkbox h-5 w-5 text-yellow-600 rounded border-gray-300 focus:ring-yellow-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="alat_bantu[{{ $index }}][status][]" value="gangguan" 
                                                                       class="form-checkbox h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                                            <label class="inline-flex items-center justify-center">
                                                                <input type="checkbox" name="alat_bantu[{{ $index }}][status][]" value="flm" 
                                                                       class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition-all cursor-pointer">
                                                            </label>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <input type="text" name="alat_bantu[{{ $index }}][keterangan]" 
                                                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm" 
                                                                   placeholder="Masukkan keterangan...">
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Tombol Submit -->
                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                <i class="fas fa-save mr-2"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Kondisi Resource -->
                                <div x-show="activeTab === 'kondisi-resource'" class="space-y-4">
                                    <form action="{{ route('admin.meeting-shift.store-resource') }}" method="POST">
                                        @csrf
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white border border-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Stok</th>
                                                        <th colspan="5" class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Status</th>
                                                        <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Keterangan</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">0%-20%</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">21%-40%</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">41%-61%</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">61%-80%</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">up to 80%</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                    $resources = [
                                                        ['name' => 'PELUMAS', 'is_category' => true],
                                                        ['name' => 'stok pelumas', 'is_category' => false],
                                                        ['name' => 'BBM', 'is_category' => true],
                                                        ['name' => 'storage tank', 'is_category' => false],
                                                        ['name' => 'service tank', 'is_category' => false],
                                                        ['name' => 'AIR PENDINGIN', 'is_category' => true],
                                                        ['name' => 'storage/bak air', 'is_category' => false],
                                                        ['name' => 'service tank', 'is_category' => false],
                                                        ['name' => 'UDARA START', 'is_category' => true],
                                                        ['name' => 'storage tank', 'is_category' => false],
                                                        ['name' => 'service tank', 'is_category' => false],
                                                    ];
                                                    @endphp

                                                    @foreach($resources as $index => $resource)
                                                    <tr class="{{ $resource['is_category'] ? 'bg-gray-50 font-semibold' : '' }} hover:bg-gray-50 transition-colors">
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r {{ $resource['is_category'] ? 'text-lg' : 'pl-8' }}">
                                                            {{ $resource['name'] }}
                                                            <input type="hidden" name="resources[{{ $index }}][name]" value="{{ $resource['name'] }}">
                                                        </td>
                                                        @if(!$resource['is_category'])
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <label class="inline-flex items-center justify-center">
                                                                    <input type="checkbox" name="resources[{{ $index }}][status][]" value="0-20" 
                                                                           class="form-checkbox h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500 transition-all cursor-pointer">
                                                                </label>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <label class="inline-flex items-center justify-center">
                                                                    <input type="checkbox" name="resources[{{ $index }}][status][]" value="21-40" 
                                                                           class="form-checkbox h-5 w-5 text-orange-600 rounded border-gray-300 focus:ring-orange-500 transition-all cursor-pointer">
                                                                </label>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <label class="inline-flex items-center justify-center">
                                                                    <input type="checkbox" name="resources[{{ $index }}][status][]" value="41-61" 
                                                                           class="form-checkbox h-5 w-5 text-yellow-600 rounded border-gray-300 focus:ring-yellow-500 transition-all cursor-pointer">
                                                                </label>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <label class="inline-flex items-center justify-center">
                                                                    <input type="checkbox" name="resources[{{ $index }}][status][]" value="61-80" 
                                                                           class="form-checkbox h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 transition-all cursor-pointer">
                                                                </label>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                                <label class="inline-flex items-center justify-center">
                                                                    <input type="checkbox" name="resources[{{ $index }}][status][]" value="up-80" 
                                                                           class="form-checkbox h-5 w-5 text-green-600 rounded border-gray-300 focus:ring-green-500 transition-all cursor-pointer">
                                                                </label>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap">
                                                                <input type="text" name="resources[{{ $index }}][keterangan]" 
                                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm" 
                                                                       placeholder="Masukkan keterangan...">
                                                            </td>
                                                        @else
                                                            <td colspan="6" class="border border-gray-300"></td>
                                                        @endif
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Tombol Submit -->
                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                <i class="fas fa-save mr-2"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Kondisi K3L -->
                                <div x-show="activeTab === 'kondisi-k3l'" class="space-y-4">
                                    <form action="{{ route('admin.meeting-shift.store-k3l') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white border border-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Potensi Bahaya</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Uraian</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Saran & Tindak Lanjut</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Eviden</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Unsafe Action -->
                                                    <tr>
                                                        <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">
                                                            Unsafe Action
                                                            <input type="hidden" name="k3l[0][type]" value="unsafe_action">
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <textarea name="k3l[0][uraian]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan uraian unsafe action..."></textarea>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <textarea name="k3l[0][saran]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan saran dan tindak lanjut..."></textarea>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <div class="flex flex-col space-y-2">
                                                                <input type="file" name="k3l[0][eviden]" class="p-1 border rounded" accept="image/*,.pdf">
                                                                <span class="text-sm text-gray-500">Format: JPG, PNG, PDF (Max 2MB)</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!-- Unsafe Condition -->
                                                    <tr>
                                                        <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">
                                                            Unsafe Condition
                                                            <input type="hidden" name="k3l[1][type]" value="unsafe_condition">
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <textarea name="k3l[1][uraian]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan uraian unsafe condition..."></textarea>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <textarea name="k3l[1][saran]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan saran dan tindak lanjut..."></textarea>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <div class="flex flex-col space-y-2">
                                                                <input type="file" name="k3l[1][eviden]" class="p-1 border rounded" accept="image/*,.pdf">
                                                                <span class="text-sm text-gray-500">Format: JPG, PNG, PDF (Max 2MB)</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        
                                        <!-- Tombol Submit -->
                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                <i class="fas fa-save mr-2"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Catatan Kondisi Sistem -->
                                <div x-show="activeTab === 'catatan-sistem'" class="space-y-4">
                                    <form action="{{ route('admin.meeting-shift.store-sistem') }}" method="POST">
                                        @csrf
                                        <div class="bg-white p-6 rounded-lg shadow-sm">
                                            <label for="catatan_sistem" class="block text-sm font-medium text-gray-700 mb-2">
                                                Catatan Kondisi Sistem
                                            </label>
                                            <textarea 
                                                id="catatan_sistem"
                                                name="catatan_sistem" 
                                                rows="10" 
                                                class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Masukkan catatan kondisi sistem..."
                                            ></textarea>
                                            
                                            <!-- Tombol Submit -->
                                            <div class="flex justify-end mt-4">
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                    <i class="fas fa-save mr-2"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Catatan Umum -->
                                <div x-show="activeTab === 'catatan-validasi'" class="space-y-4">
                                    <form action="{{ route('admin.meeting-shift.store-catatan-umum') }}" method="POST">
                                        @csrf
                                        <div class="bg-white p-6 rounded-lg shadow-sm">
                                            <label for="catatan_umum" class="block text-sm font-medium text-gray-700 mb-2">
                                                Catatan Umum
                                            </label>
                                            <textarea 
                                                id="catatan_umum"
                                                name="catatan_umum" 
                                                rows="10" 
                                                class="w-full p-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Masukkan catatan umum..."
                                            ></textarea>
                                            
                                            <!-- Tombol Submit -->
                                            <div class="flex justify-end mt-4">
                                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                    <i class="fas fa-save mr-2"></i> Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <!-- Absensi dan Resume -->
                                <div x-show="activeTab === 'absensi-resume'" class="space-y-4">
                                    <form action="{{ route('admin.meeting-shift.store-absensi') }}" method="POST">
                                        @csrf
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full bg-white border border-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">No</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Nama</th>
                                                        <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Shift</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                    <tr>
                                                        <td class="border border-gray-300 px-4 py-2 text-center">{{ $i }}</td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <input type="text" 
                                                                name="absensi[{{ $i }}][nama]" 
                                                                class="w-full p-1 border rounded" 
                                                                placeholder="Masukkan nama..."
                                                                required>
                                                        </td>
                                                        <td class="border border-gray-300 px-4 py-2">
                                                            <select name="absensi[{{ $i }}][shift]" 
                                                                class="w-full p-1 border rounded"
                                                                required>
                                                                <option value="">Pilih Shift</option>
                                                                <option value="pagi">Pagi</option>
                                                                <option value="sore">Sore</option>
                                                                <option value="malam">Malam</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    @endfor
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Tombol untuk menambah baris -->
                                        <div class="flex justify-start mt-4">
                                            <button type="button" 
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                                <i class="fas fa-plus mr-2"></i> Tambah Baris
                                            </button>
                                        </div>
                                        
                                        <!-- Tombol Submit -->
                                        <div class="flex justify-end mt-4">
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                <i class="fas fa-save mr-2"></i> Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 