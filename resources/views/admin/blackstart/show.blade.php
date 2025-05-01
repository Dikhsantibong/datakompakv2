@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
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

                    <h1 class="text-xl font-semibold text-gray-800">Data Blackstart</h1>
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

        <!-- Main Content -->
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Daftar Data Blackstart</h2>
                        <a href="{{ route('admin.blackstart.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Tambah Data
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Layanan / sentral</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase" colspan="2">collect single line diagram</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">SOP Black start</th>
                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase" colspan="3">status ketersediaan</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">STATUS</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                            <tr>
                                <th class="px-3 py-2"></th>
                                <th class="px-3 py-2"></th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">pembangkit</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">black start</th>
                                <th class="px-3 py-2"></th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">load set</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">line energize</th>
                                <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase">status jaringan</th>
                                <th class="px-3 py-2"></th>
                                <th class="px-3 py-2"></th>
                                <th class="px-3 py-2"></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($powerPlants as $powerPlant)
                            <tr>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $powerPlant->name }}
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Tersedia</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Tersedia</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Tersedia</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Tersedia</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Tersedia</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">Normal</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">SUHARTADI</span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        OPEN
                                    </span>
                                </td>
                                <td class="px-3 py-4 whitespace-nowrap text-sm">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">Hapus</button>
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

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 