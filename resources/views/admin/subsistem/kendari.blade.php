@extends('layouts.app')

@section('content')
@php
$specificTimes = $specificTimes ?? ['11:00:00', '14:00:00', '16:00:00', '18:00:00', '19:00:00'];
@endphp
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

                    <h1 class="text-xl font-semibold text-gray-800">Data Subsistem Kendari</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Data Subsistem Kendari', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <!-- Table Header with Filters -->
                    <div class="mb-4">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex items-center gap-2">
                                <h2 class="text-lg font-semibold text-gray-900">Data PLTU MORAMO</h2>
                                <a href="{{ route('admin.subsistem.kendari.create') }}" 
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                    <i class="fas fa-plus mr-2"></i> Input Data
                                </a>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="mt-4 border-b border-gray-200 pb-4">
                            <form action="{{ route('admin.subsistem.kendari') }}" method="GET" class="flex flex-wrap items-end gap-4">
                                <div class="w-40">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" 
                                           name="date" 
                                           value="{{ request('date', now()->format('Y-m-d')) }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                        <i class="fas fa-search mr-2"></i> Tampilkan Data
                                    </button>
                                    <a href="{{ route('admin.subsistem.kendari') }}" 
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                        <i class="fas fa-undo mr-2"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Unit</th>
                                    @foreach($specificTimes as $time)
                                        <th colspan="3" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">
                                            {{ \Carbon\Carbon::createFromFormat('H:i:s', $time)->format('H:i') }}
                                        </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach($specificTimes as $time)
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">DMP</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Beban</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @if($powerPlant)
                                    @foreach($powerPlant->machines as $machine)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border">
                                                {{ $machine->name }}
                                            </td>
                                            @foreach($specificTimes as $time)
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border text-center">
                                                    <!-- DMP value will be populated here -->
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border text-center">
                                                    <!-- Status will be populated here -->
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border text-center">
                                                    <!-- Beban will be populated here -->
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="{{ count($specificTimes) * 3 + 1 }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data tersedia
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 