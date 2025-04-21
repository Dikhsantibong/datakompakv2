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
                    <h1 class="text-xl font-semibold text-gray-900">Laporan Keuangan</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Laporan Keuangan', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Laporan Keuangan</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor laporan keuangan UPKD untuk memastikan transparansi dan akuntabilitas.</p>
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
                        <form action="{{ route('admin.operasi-upkd.laporan-keuangan.store') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- Periode Laporan -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Periode Awal
                                    </label>
                                    <input type="date" name="periode_awal" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]"
                                           required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Periode Akhir
                                    </label>
                                    <input type="date" name="periode_akhir" 
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]"
                                           required>
                                </div>
                            </div>

                            <!-- Tabel Laporan Keuangan -->
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                                                Keterangan
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                                                Debit (Rp)
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-r">
                                                Kredit (Rp)
                                            </th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Saldo (Rp)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200" id="transaksi-container">
                                        <tr class="transaksi-row">
                                            <td class="px-6 py-4 border-r">
                                                <input type="text" name="transaksi[0][keterangan]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]"
                                                       placeholder="Masukkan keterangan transaksi">
                                            </td>
                                            <td class="px-6 py-4 border-r">
                                                <input type="number" name="transaksi[0][debit]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]"
                                                       placeholder="0" min="0">
                                            </td>
                                            <td class="px-6 py-4 border-r">
                                                <input type="number" name="transaksi[0][kredit]" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]"
                                                       placeholder="0" min="0">
                                            </td>
                                            <td class="px-6 py-4 text-right font-medium">
                                                0
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="flex justify-between">
                                <button type="button" id="tambah-transaksi"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Transaksi
                                </button>

                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Laporan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 