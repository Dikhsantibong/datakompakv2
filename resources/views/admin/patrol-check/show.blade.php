@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Patrol Check KIT</h1>
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
                ['name' => 'Patrol Check KIT', 'url' => route('admin.patrol-check.index')],
                ['name' => 'Daftar', 'url' => route('admin.patrol-check.list')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Report Info -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Informasi Patrol Check</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $patrol->creator->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $patrol->created_at->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Shift</dt>
                                    <dd class="mt-1 text-sm text-gray-900">Shift {{ $patrol->shift }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Waktu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $patrol->time }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Equipment Conditions -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Kondisi Umum Peralatan Bantu</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NO</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">SISTEM</th>
                                            <th colspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi Umum</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Normal</th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Abnormal</th>
                                            <th class="border px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(($patrol->condition_systems ?? []) as $index => $condition)
                                        <tr>
                                            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="border px-4 py-2">{{ $condition['system'] ?? '' }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(($condition['condition'] ?? '') === 'normal')
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(($condition['condition'] ?? '') === 'abnormal')
                                                    <i class="fas fa-check text-red-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">{{ $condition['notes'] ?? '' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Abnormal Equipment Data -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Data Kondisi Alat Bantu</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">NO</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">ALAT BANTU</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">Kondisi Awal</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border" colspan="3">Tindak Lanjut</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">Kondisi Akhir</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">FLM</th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">SR</th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Lainnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($patrol->abnormal_equipments ?? []) as $index => $abnormal)
                                        <tr>
                                            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="border px-4 py-2">{{ $abnormal['equipment'] ?? '' }}</td>
                                            <td class="border px-4 py-2">{{ $abnormal['condition'] ?? '' }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(!empty($abnormal['flm']))
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(!empty($abnormal['sr']))
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(!empty($abnormal['other']))
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">
                                                {{ $patrol->condition_after[$index]['condition'] ?? '' }}
                                            </td>
                                            <td class="border px-4 py-2">
                                                {{ $patrol->condition_after[$index]['notes'] ?? '' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="border px-4 py-2 text-center text-gray-500">
                                                Tidak ada data kondisi alat bantu
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.patrol-check.list') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="button" 
                                onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                            <i class="fas fa-print mr-2"></i>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 