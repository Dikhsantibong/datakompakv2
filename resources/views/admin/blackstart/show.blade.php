@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
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
                    <h1 class="text-xl font-semibold text-gray-900">Data Blackstart</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Operasi UL/Sentral', 'url' => '#'],
                ['name' => 'Blackstart', 'url' => route('admin.blackstart.index')],
                ['name' => 'Data', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Manajemen Data Blackstart</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor status blackstart untuk optimasi operasional pembangkit listrik.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.blackstart.export-excel', request()->query()) }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </a>
                            <a href="{{ route('admin.blackstart.export-pdf', request()->query()) }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </a>
                            <a href="{{ route('admin.blackstart.index') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header with Filters -->
                        <div class="mb-4" id="table-controls">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-semibold text-gray-900">Data Blackstart</h2>
                                    <button id="toggleFullTable" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                            onclick="toggleFullTableView()">
                                        <i class="fas fa-expand mr-1"></i> Full Table
                                    </button>
                                    @if(request()->has('unit_id') || request()->has('status') || request()->has('pembangkit_status') || request()->has('black_start_status') || request()->has('pic') || request()->has('start_date') || request()->has('end_date'))
                                        <div class="flex flex-wrap gap-2" id="active-filters">
                                            @if(request('unit_id'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Unit: {{ $powerPlants->find(request('unit_id'))->name }}
                                                    <button onclick="removeFilter('unit_id')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('status'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Status: {{ ucfirst(request('status')) }}
                                                    <button onclick="removeFilter('status')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('pembangkit_status'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Status Pembangkit: {{ ucfirst(str_replace('_', ' ', request('pembangkit_status'))) }}
                                                    <button onclick="removeFilter('pembangkit_status')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('black_start_status'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Status Black Start: {{ ucfirst(str_replace('_', ' ', request('black_start_status'))) }}
                                                    <button onclick="removeFilter('black_start_status')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('pic'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    PIC: {{ request('pic') }}
                                                    <button onclick="removeFilter('pic')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('start_date'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('M Y') }}
                                                    @if(request('end_date'))
                                                        - {{ \Carbon\Carbon::parse(request('end_date'))->format('M Y') }}
                                                    @endif
                                                    <button onclick="removeFilter('start_date'); removeFilter('end_date')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Horizontal Filters -->
                            <div class="mt-2 border-b border-gray-200 pb-4" id="filters-section">
                                <form action="{{ route('admin.blackstart.show') }}" method="GET" 
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                        <select name="unit_id" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Unit</option>
                                            @foreach($powerPlants as $unit)
                                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="close" {{ request('status') == 'close' ? 'selected' : '' }}>Close</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status Pembangkit</label>
                                        <select name="pembangkit_status" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="tersedia" {{ request('pembangkit_status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="tidak_tersedia" {{ request('pembangkit_status') == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status Black Start</label>
                                        <select name="black_start_status" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="tersedia" {{ request('black_start_status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="tidak_tersedia" {{ request('black_start_status') == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">PIC</label>
                                        <input type="text" name="pic" 
                                               class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('pic') }}" 
                                               placeholder="Cari PIC...">
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Periode Awal</label>
                                        <input type="month" name="start_date" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('start_date') }}">
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Periode Akhir</label>
                                        <input type="month" name="end_date" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('end_date') }}">
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            <i class="fas fa-search mr-2"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.blackstart.show') }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Flash Messages -->
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        <!-- Main Blackstart Table -->
                        <div class="overflow-x-auto mb-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Komitmen dan Pembahasan</h2>
                            <table class="min-w-full text-xs border border-gray-200 rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2">No</th>
                                        <th class="px-3 py-2">Unit Layanan / Sentral</th>
                                        <th class="px-3 py-2">Pembangkit</th>
                                        <th class="px-3 py-2">Black Start</th>
                                        <th class="px-3 py-2">Diagram Evidence</th>
                                        <th class="px-3 py-2">SOP</th>
                                        <th class="px-3 py-2">SOP Evidence</th>
                                        <th class="px-3 py-2">Load Set</th>
                                        <th class="px-3 py-2">Line Energize</th>
                                        <th class="px-3 py-2">Status Jaringan</th>
                                        <th class="px-3 py-2">PIC</th>
                                        <th class="px-3 py-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($blackstarts as $blackstart)
                                    <tr class="hover:bg-gray-50 border-b border-gray-200">
                                        <td class="px-3 py-2">{{ $loop->iteration }}</td>
                                        <td class="px-3 py-2">{{ $blackstart->powerPlant->name }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($blackstart->pembangkit_status) }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($blackstart->black_start_status) }}</td>
                                        <td class="px-3 py-2">
                                            @if($blackstart->diagram_evidence)
                                                <a href="{{ asset('storage/'.$blackstart->diagram_evidence) }}" target="_blank" class="text-blue-600 hover:underline flex items-center gap-1">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">{{ ucfirst($blackstart->sop_status) }}</td>
                                        <td class="px-3 py-2">
                                            @if($blackstart->sop_evidence)
                                                <a href="{{ asset('storage/'.$blackstart->sop_evidence) }}" target="_blank" class="text-green-600 hover:underline flex items-center gap-1">
                                                    <i class="fas fa-file-alt"></i> Lihat
                                                </a>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-2">{{ ucfirst($blackstart->load_set_status) }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($blackstart->line_energize_status) }}</td>
                                        <td class="px-3 py-2">{{ ucfirst($blackstart->status_jaringan) }}</td>
                                        <td class="px-3 py-2">{{ $blackstart->pic }}</td>
                                        <td class="px-3 py-2">
                                            <form action="{{ route('admin.blackstart.destroy', $blackstart->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Peralatan Blackstart Table -->
                        <div class="overflow-x-auto mt-8 border-t border-gray-200 pt-8">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">Data Peralatan Blackstart</h2>
                            <table class="min-w-full text-xs border border-gray-200 rounded-lg overflow-hidden">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">No</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Unit Layanan / sentral</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Kompresor diesel</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Tabung udara</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">UPS</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Lampu emergency</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Battery catudaya</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Battery black start</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">Radio komunikasi</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Simulasi black start</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Start kondisi black out</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">TARGET WAKTU Mulai</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Selesai</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Deadline</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">PIC</th>
                                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200">STATUS</th>
                                    </tr>
                                    <tr>
                                        <th class="px-3 py-2"></th>
                                        <th class="px-3 py-2"></th>
                                        <!-- Kompresor diesel -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">jumlah</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">eviden</th>
                                        <!-- Tabung udara -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">jumlah</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">eviden</th>
                                        <!-- UPS -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <!-- Lampu Emergency -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">jumlah</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">eviden</th>
                                        <!-- Battery Catudaya -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">jumlah</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">eviden</th>
                                        <!-- Battery Black Start -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">jumlah</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">eviden</th>
                                        <!-- Radio Komunikasi -->
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">jumlah</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">kondisi</th>
                                        <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">eviden</th>
                                        <th class="px-3 py-2"></th>
                                        <th class="px-3 py-2"></th>
                                        <th class="px-3 py-2"></th>
                                        <th class="px-3 py-2"></th>
                                        <th class="px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($blackstarts as $blackstart)
                                        @foreach($blackstart->peralatanBlackstarts as $peralatan)
                                        <tr>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                                            </td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                {{ $blackstart->powerPlant->name }}
                                            </td>
                                            <!-- Kompresor diesel -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->kompresor_diesel_jumlah }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->kompresor_diesel_kondisi) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                @if($peralatan->kompresor_eviden)
                                                    <a href="{{ asset('storage/'.$peralatan->kompresor_eviden) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline">
                                                        @php $ext = pathinfo($peralatan->kompresor_eviden, PATHINFO_EXTENSION); @endphp
                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']))
                                                            <img src="{{ asset('storage/'.$peralatan->kompresor_eviden) }}" alt="Eviden" class="w-8 h-8 object-cover rounded border" />
                                                        @elseif(strtolower($ext) == 'pdf')
                                                            <i class="fas fa-file-pdf text-red-600"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        <span class="truncate max-w-[100px]">{{ basename($peralatan->kompresor_eviden) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <!-- Tabung udara -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->tabung_udara_jumlah }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->tabung_udara_kondisi) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                @if($peralatan->tabung_eviden)
                                                    <a href="{{ asset('storage/'.$peralatan->tabung_eviden) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline">
                                                        @php $ext = pathinfo($peralatan->tabung_eviden, PATHINFO_EXTENSION); @endphp
                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']))
                                                            <img src="{{ asset('storage/'.$peralatan->tabung_eviden) }}" alt="Eviden" class="w-8 h-8 object-cover rounded border" />
                                                        @elseif(strtolower($ext) == 'pdf')
                                                            <i class="fas fa-file-pdf text-red-600"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        <span class="truncate max-w-[100px]">{{ basename($peralatan->tabung_eviden) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <!-- UPS -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->ups_kondisi) }}</td>
                                            <!-- Lampu Emergency -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->lampu_emergency_jumlah }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->lampu_emergency_kondisi) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                @if($peralatan->lampu_eviden)
                                                    <a href="{{ asset('storage/'.$peralatan->lampu_eviden) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline">
                                                        @php $ext = pathinfo($peralatan->lampu_eviden, PATHINFO_EXTENSION); @endphp
                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']))
                                                            <img src="{{ asset('storage/'.$peralatan->lampu_eviden) }}" alt="Eviden" class="w-8 h-8 object-cover rounded border" />
                                                        @elseif(strtolower($ext) == 'pdf')
                                                            <i class="fas fa-file-pdf text-red-600"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        <span class="truncate max-w-[100px]">{{ basename($peralatan->lampu_eviden) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <!-- Battery Catudaya -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->battery_catudaya_jumlah }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->battery_catudaya_kondisi) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                @if($peralatan->catudaya_eviden)
                                                    <a href="{{ asset('storage/'.$peralatan->catudaya_eviden) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline">
                                                        @php $ext = pathinfo($peralatan->catudaya_eviden, PATHINFO_EXTENSION); @endphp
                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']))
                                                            <img src="{{ asset('storage/'.$peralatan->catudaya_eviden) }}" alt="Eviden" class="w-8 h-8 object-cover rounded border" />
                                                        @elseif(strtolower($ext) == 'pdf')
                                                            <i class="fas fa-file-pdf text-red-600"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        <span class="truncate max-w-[100px]">{{ basename($peralatan->catudaya_eviden) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <!-- Battery Black Start -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->battery_blackstart_jumlah }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->battery_blackstart_kondisi) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                @if($peralatan->blackstart_eviden)
                                                    <a href="{{ asset('storage/'.$peralatan->blackstart_eviden) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline">
                                                        @php $ext = pathinfo($peralatan->blackstart_eviden, PATHINFO_EXTENSION); @endphp
                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']))
                                                            <img src="{{ asset('storage/'.$peralatan->blackstart_eviden) }}" alt="Eviden" class="w-8 h-8 object-cover rounded border" />
                                                        @elseif(strtolower($ext) == 'pdf')
                                                            <i class="fas fa-file-pdf text-red-600"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        <span class="truncate max-w-[100px]">{{ basename($peralatan->blackstart_eviden) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <!-- Radio Komunikasi -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->radio_jumlah }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->radio_komunikasi_kondisi) }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                @if($peralatan->radio_eviden)
                                                    <a href="{{ asset('storage/'.$peralatan->radio_eviden) }}" target="_blank" class="flex items-center gap-1 text-blue-600 hover:underline">
                                                        @php $ext = pathinfo($peralatan->radio_eviden, PATHINFO_EXTENSION); @endphp
                                                        @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','bmp','webp']))
                                                            <img src="{{ asset('storage/'.$peralatan->radio_eviden) }}" alt="Eviden" class="w-8 h-8 object-cover rounded border" />
                                                        @elseif(strtolower($ext) == 'pdf')
                                                            <i class="fas fa-file-pdf text-red-600"></i>
                                                        @else
                                                            <i class="fas fa-file-alt"></i>
                                                        @endif
                                                        <span class="truncate max-w-[100px]">{{ basename($peralatan->radio_eviden) }}</span>
                                                    </a>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                            <!-- Simulasi Black Start -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->simulasi_blackstart) }}</td>
                                            <!-- Start Kondisi Black Out -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ ucfirst($peralatan->start_kondisi_blackout) }}</td>
                                            <!-- Target Waktu -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->waktu_mulai ? \Carbon\Carbon::parse($peralatan->waktu_mulai)->format('H:i') : '-' }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->waktu_selesai ? \Carbon\Carbon::parse($peralatan->waktu_selesai)->format('H:i') : '-' }}</td>
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->waktu_deadline ? \Carbon\Carbon::parse($peralatan->waktu_deadline)->format('H:i') : '-' }}</td>
                                            <!-- PIC -->
                                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">{{ $peralatan->pic }}</td>
                                            <!-- Status -->
                                            <td class="px-3 py-4 whitespace-nowrap border-r border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $peralatan->status === 'normal' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($peralatan->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
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
    // Auto-submit form when changing filters
    const filterForm = document.querySelector('form');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"]');

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
    const filtersSection = document.getElementById('filters-section');
    const activeFilters = document.getElementById('active-filters');
    const welcomeCard = document.querySelector('.welcome-card')?.parentElement;
    const mainContent = document.querySelector('main');
    
    // Toggle full table mode
    const isFullTable = button.classList.contains('bg-blue-600');
    
    if (isFullTable) {
        // Restore normal view
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';
        
        if (filtersSection) filtersSection.style.display = '';
        if (activeFilters) activeFilters.style.display = '';
        if (welcomeCard) welcomeCard.style.display = '';
        if (mainContent) mainContent.classList.remove('pt-0');
        
    } else {
        // Enable full table view
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';
        
        if (filtersSection) filtersSection.style.display = 'none';
        if (activeFilters) activeFilters.style.display = 'none';
        if (welcomeCard) welcomeCard.style.display = 'none';
        if (mainContent) mainContent.classList.add('pt-0');
    }
}
</script>
@endpush

@endsection 