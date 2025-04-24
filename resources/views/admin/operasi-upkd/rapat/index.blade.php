@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#0A749B] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Buka menu utama</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#0A749B] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Buka menu utama</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-900">Rapat & Link Koordinasi RON</h1>
                </div>

                <!-- Profile and Actions -->
                <div class="flex items-center space-x-4">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Rapat & Link Koordinasi RON', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Rapat & Link Koordinasi RON</h2>
                        <p class="text-blue-100 mb-4">Kelola dan pantau rapat serta koordinasi RON untuk memastikan komunikasi yang efektif antar tim.</p>
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
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Table Header with Controls -->
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Data Rapat</h2>
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
                                <form action="" method="GET" class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                                        <select name="tahun" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                            <option value="">Semua Tahun</option>
                                            @for($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="open">Open</option>
                                            <option value="closed">Closed</option>
                                            <option value="in_progress">In Progress</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Mode Rapat</label>
                                        <select name="mode" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                            <option value="">Semua Mode</option>
                                            <option value="online">Online</option>
                                            <option value="offline">Offline</option>
                                            <option value="hybrid">Hybrid</option>
                                        </select>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-[#009BB9] rounded-md hover:bg-[#009BB9]/80">
                                            <i class="fas fa-search mr-2"></i> Cari
                                        </button>
                                        <a href="" 
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <!-- A. Pekerjaan Tentatif -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            A. PEKERJAAN TENTATIF
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Eksisting</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindak Lanjut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Akhir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Goal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pekerjaan_tentatif as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="pekerjaan_tentatif[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="pekerjaan_tentatif[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- B. ISU MATURITY LEVEL -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            B. ISU MATURITY LEVEL
                                        </th>
                                    </tr>
                                </thead>
                                
                                <!-- B.1 Operation Management -->
                                <thead>
                                    <tr>
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-10">
                                            B.1 OPERATION MANAGEMENT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($operation_management as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="operation_management[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="pekerjaan_tentatif[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="operation_management[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        
                                        <!-- Add other fields similar to pekerjaan_tentatif -->
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- B.2 EFISIENSI MANAGEMENT -->
                                <thead>
                                    <tr>
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-10">
                                            B.2 EFISIENSI MANAGEMENT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($efisiensi_management as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="efisiensi_management[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="efisiensi_management[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="efisiensi_management[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="efisiensi_management[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="efisiensi_management[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">  
                                            <textarea name="efisiensi_management[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="efisiensi_management[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="efisiensi_management[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="efisiensi_management[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        <!-- Add other fields similar to pekerjaan_tentatif -->
                                    </tr>
                                    @endforeach
                                </tbody>



                                <!-- Add sections C through G with similar structure -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            C. PROGRAM KERJA
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($program_kerja as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="program_kerja[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="program_kerja[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>  
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="program_kerja[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="program_kerja[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- D. PROGRAM KERJA -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            D. MONITORING PENGADAAN BARANG DAN JASA
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($monitoring_pengadaan as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_pengadaan[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_pengadaan[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="monitoring_pengadaan[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_pengadaan[{{ $index }}][kondisi_eksisting]" rows="3" 
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_pengadaan[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4"> 
                                            <textarea name="monitoring_pengadaan[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_pengadaan[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="monitoring_pengadaan[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_pengadaan[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        
                                        
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- E. MONITORING APLIKASI -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            E. MONITORING APLIKASI
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($monitoring_aplikasi as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">  
                                            <input type="text" name="monitoring_aplikasi[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="monitoring_aplikasi[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="monitoring_aplikasi[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                            
                                            
                                        </td>

                                        
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- F. PENGWAASAN KONTRAK -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            F. PENGWAASAN KONTRAK
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pengawasan_kontrak as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="pengawasan_kontrak[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pengawasan_kontrak[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="pengawasan_kontrak[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pengawasan_kontrak[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4"> 
                                            <textarea name="pengawasan_kontrak[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pengawasan_kontrak[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pengawasan_kontrak[{{ $index }}][goal]" rows="3"
                                                 class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                            
                                        
                                        <td class="px-6 py-4">
                                            <select name="pengawasan_kontrak[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>

                                        <td class="px-6 py-4">
                                            <textarea name="pengawasan_kontrak[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            G. LAPORAN PEMBANGKIT DAN TRANSAKSI ENERGI
                                        </th>
                                    </tr>
                                </thead>
                                
                                <!-- B.1 Operation Management -->
                                <thead>
                                    <tr>
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-10">
                                            G.1 LAPORAN PEMBANGKIT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($laporan_pembangkit as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_pembangkit[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_pembangkit[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="laporan_pembangkit[{{ $index }}][pic]" value="{{ $item['pic'] }}" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_pembangkit[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">  
                                            <textarea name="laporan_pembangkit[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_pembangkit[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_pembangkit[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="laporan_pembangkit[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_pembangkit[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                        
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            G.2 LAPORAN TRANSAKSI ENERGI
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($laporan_transaksi as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_transaksi[{{ $index }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">  
                                            <textarea name="laporan_transaksi[{{ $index }}][detail]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['detail'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="laporan_transaksi[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_transaksi[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_transaksi[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_transaksi[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_transaksi[{{ $index }}][goal]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $item['goal'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="laporan_pembangkit[{{ $index }}][status]"
                                                class="mt-1 p-2 block w-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="laporan_transaksi[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>

                                        <td class="px-6 py-4">
                                            
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                                
                                
                                
                              <div class="flex justify-end mt-6">
                                <!-- H. RAPAT -->
                                <thead >
                                    <tr class="bg-gray-50">
                                        <th colspan="7" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100 border border-gray-300">
                                            H. RAPAT
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rapat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Online/Offline</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resume</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notulen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-300">Eviden</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(['internal_ron' => 'INTERNAL RON', 'internal_upkd' => 'INTERNAL UPKD', 'eksternal_np1' => 'EKSTERNAL NP', 'eksternal_np2' => 'EKSTERNAL NP'] as $key => $label)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="rapat[{{ $key }}][uraian]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $rapat_data[$key]['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="datetime-local" name="rapat[{{ $key }}][jadwal]" value="{{ $rapat_data[$key]['jadwal'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="rapat[{{ $key }}][online_offline]"
                                                class="mt-1 p-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="online" {{ $rapat_data[$key]['online_offline'] == 'online' ? 'selected' : '' }}>Online</option>
                                                <option value="offline" {{ $rapat_data[$key]['online_offline'] == 'offline' ? 'selected' : '' }}>Offline</option>
                                                <option value="hybrid" {{ $rapat_data[$key]['online_offline'] == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="rapat[{{ $key }}][resume]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $rapat_data[$key]['resume'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="rapat[{{ $key }}][notulen]" rows="3"
                                                class="mt-1 block w-[200px] h-[100px] rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm resize-none">{{ $rapat_data[$key]['notulen'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4 border border-gray-300 w-[300px]">
                                            <input type="file" name="rapat[{{ $key }}][eviden]"
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#009BB9] file:text-white hover:file:bg-[#009BB9]/80">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                </div>

                                <!-- Link Back Up Monitoring -->
                                
                                

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle dropdown
    window.toggleDropdown = function() {
        document.getElementById('dropdown').classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#dropdownToggle')) {
            document.getElementById('dropdown').classList.add('hidden');
        }
    });

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
    const sidebar = document.querySelector('aside');
    
    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
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