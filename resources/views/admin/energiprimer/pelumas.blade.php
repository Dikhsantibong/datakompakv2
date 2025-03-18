@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto">
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

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Data Pelumas</h1>
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
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-semibold text-gray-900">Data Pelumas</h2>
                    <div class="flex gap-3">
                        <!-- Export Buttons -->
                        <a href="{{ route('admin.energiprimer.pelumas.export-excel', request()->query()) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md">
                            <i class="fas fa-file-excel mr-2"></i> Export Excel
                        </a>
                        <a href="{{ route('admin.energiprimer.pelumas.export-pdf', request()->query()) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md">
                            <i class="fas fa-file-pdf mr-2"></i> Export PDF
                        </a>
                        <a href="{{ route('admin.energiprimer.pelumas.create') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </a>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="mt-4 bg-white rounded-lg shadow-sm flex justify-end">
                    <div class="p-4">
                        <form action="{{ route('admin.energiprimer.pelumas') }}" method="GET" class="flex flex-wrap items-end gap-3">
                            <!-- Unit Filter -->
                            <div class="w-48">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                <select name="unit_id" class="w-full h-8 px-2 text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Semua Unit</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Jenis Pelumas Filter -->
                            <div class="w-36">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Pelumas</label>
                                <input type="text" name="jenis_pelumas" 
                                       class="w-full h-8 text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ request('jenis_pelumas') }}"
                                       placeholder="Cari jenis...">
                            </div>

                            <!-- Date Range Filters -->
                            <div class="w-36">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" 
                                       class="w-full h-8 text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ request('start_date') }}">
                            </div>

                            <div class="w-36">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                <input type="date" name="end_date" 
                                       class="w-full h-8 text-sm rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       value="{{ request('end_date') }}">
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button type="submit"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                    <i class="fas fa-search mr-2"></i> Cari
                                </button>
                                <a href="{{ route('admin.energiprimer.pelumas') }}" 
                                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                                    <i class="fas fa-undo mr-2"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Active Filters -->
                @if(request()->has('unit_id') || request()->has('jenis_pelumas') || request()->has('start_date') || request()->has('end_date'))
                    <div class="mt-2 text-xs text-gray-500 italic">
                        Filter aktif:
                        @if(request('unit_id'))
                            <span class="mr-2">Unit: {{ $units->find(request('unit_id'))->name }}</span>
                        @endif
                        @if(request('jenis_pelumas'))
                            <span class="mr-2">Pelumas: {{ request('jenis_pelumas') }}</span>
                        @endif
                        @if(request('start_date'))
                            <span>Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                            @if(request('end_date'))
                                - {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                            @endif
                            </span>
                        @endif
                    </div>
                @endif

                <!-- Table -->
                <div class="mt-6 bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Pelumas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pemakaian</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pelumas as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $item->tanggal->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $item->unit->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $item->jenis_pelumas }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->saldo_awal, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->penerimaan, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->pemakaian, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->saldo_akhir, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-3">
                                                <a href="{{ route('admin.energiprimer.pelumas.edit', $item->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.energiprimer.pelumas.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
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