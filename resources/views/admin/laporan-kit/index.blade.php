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
        <div class="container mx-auto px-4 sm:px-6 py-6">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white">
                <div class="max-w-3xl">
                    <h2 class="text-2xl font-bold mb-2">Laporan KIT 00.00</h2>
                    <p class="text-blue-100 mb-4">Selamat datang di halaman Laporan KIT 00.00. Halaman ini digunakan untuk mengelola dan memonitor laporan KIT pada pukul 00.00.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.laporan-kit.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
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

            <!-- Data Tables Container -->
            <div class="space-y-8">
                <!-- DATA PEMERIKSAAN BBM -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
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
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td colspan="18" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DATA PEMERIKSAAN KWH -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">DATA PEMERIKSAAN KWH</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th colspan="10" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b border-r text-center bg-gray-100">KWH PRODUKSI</th>
                                    <th colspan="10" class="px-4 py-3 text-sm font-semibold text-gray-900 border-b text-center bg-gray-100">KWH pemakaian sendiri (PS)</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 1</th>
                                    <th colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 2</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">total prod.</th>
                                    <th colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 1</th>
                                    <th colspan="4" class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center">NAMA PANEL 2</th>
                                    <th colspan="2" class="px-4 py-3 text-sm font-semibold text-gray-900 text-center">total prod.</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">kWH</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AWAL</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 border-r text-center">AKHIR</th>
                                    <th colspan="2" class="px-4 py-2 text-xs font-medium text-gray-500 text-center">kWH</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td colspan="20" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- DATA PEMERIKSAAN PELUMAS -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
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
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td colspan="12" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pemeriksaan Bahan Kimia -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Pemeriksaan Bahan Kimia</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center bg-gray-100">jenis bahan kimia</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center bg-gray-100">stok awal</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 border-r text-center bg-gray-100">terim bahan kimia</th>
                                    <th class="px-4 py-3 text-sm font-semibold text-gray-900 text-center bg-gray-100">total pakai</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td colspan="4" class="px-4 py-4 text-sm text-gray-500 text-center">Tidak ada data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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

    /* Transition effects */
    .transition-colors {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
@endpush
@endsection 