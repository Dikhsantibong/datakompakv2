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

                    <h1 class="text-xl font-semibold text-gray-800">Detail Meeting Shift</h1>
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
                ['name' => 'Meeting Shift', 'url' => route('admin.meeting-shift.list')],
                ['name' => 'Detail Meeting Shift', 'url' => null]
            ]" />
        </div>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Info Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="flex-1 min-w-0">
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                                Detail Meeting Shift
                            </h2>
                            <div class="flex flex-wrap gap-4 items-center text-sm text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                   Tanggal: {{ $meetingShift->tanggal ? \Carbon\Carbon::parse($meetingShift->tanggal)->format('d F Y') : '-' }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2 text-green-500"></i>
                                    Shift : {{ $meetingShift->current_shift }}
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-sign-in-alt mr-2 text-yellow-500"></i>
                                    Jam Masuk: {{ $meetingShift->created_at->format('d F Y, H:i') }}
                                </div>
                                
                                <div class="flex items-center">
                                    <i class="fas fa-user mr-2 text-purple-500"></i>
                                    Unit: {{ $meetingShift->machineStatuses->first()->machine->powerPlant->name }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex md:mt-0 md:ml-4">
                            <a href="{{ route('admin.meeting-shift.list') }}" 
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Kondisi Mesin -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-cogs mr-2 text-gray-500"></i>
                                Kondisi Mesin
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Unit
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Nama Mesin
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meetingShift->machineStatuses as $status)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 border-b border-gray-200 border">
                                            {{ $status->machine->powerPlant->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 border-b border-gray-200 border">
                                            {{ $status->machine->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm border-b border-gray-200 border">
                                            <div class="flex flex-wrap gap-1">
                                                @php
                                                    $statuses = is_array($status->status) ? $status->status : json_decode($status->status);
                                                @endphp
                                                @foreach($statuses as $stat)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $stat }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 border">
                                            {{ $status->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Alat Bantu -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-tools mr-2 text-gray-500"></i>
                                Kondisi Alat Bantu
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Nama Alat
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meetingShift->auxiliaryEquipments as $equipment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 border-b border border-gray-200">
                                            {{ $equipment->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm border-b border border-gray-200">
                                            <div class="flex flex-wrap gap-1">
                                                @php
                                                    $statuses = is_array($equipment->status) ? $equipment->status : json_decode($equipment->status);
                                                @endphp
                                                @foreach($statuses as $stat)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $stat }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200">
                                            {{ $equipment->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi Resource -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-cube mr-2 text-gray-500"></i>
                                Kondisi Resource
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Nama Resource
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Kategori
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meetingShift->resources as $resource)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 border-b border-gray-200 border ">
                                            {{ $resource->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 border">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $resource->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm border-b border-gray-200 border">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $resource->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 border">
                                            {{ $resource->keterangan ?? '-' }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Kondisi K3L -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-hard-hat mr-2 text-gray-500"></i>
                                Kondisi K3L
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Tipe
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Uraian
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Saran
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Eviden
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meetingShift->k3ls as $k3l)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm border-b border-gray-200 border">
                                            @if($k3l->type == 'positif')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Positif (Tidak Ada K3L)</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $k3l->type == 'unsafe_action' ? 'bg-red-100 text-red-800' : 'bg-orange-100 text-orange-800' }}">
                                                    {{ str_replace('_', ' ', ucfirst($k3l->type)) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 border">
                                            {{ ($k3l->type == 'positif' || $k3l->uraian == '-' || empty($k3l->uraian)) ? 'Tidak Ada K3L' : $k3l->uraian }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 border">
                                            {{ ($k3l->type == 'positif' || $k3l->saran == '-' || empty($k3l->saran)) ? '-' : $k3l->saran }}
                                        </td>
                                        <td class="px-6 py-4 text-sm border-b border-gray-200 border">
                                            @if($k3l->eviden_path)
                                            <a href="{{ asset('storage/' . $k3l->eviden_path) }}" 
                                               target="_blank"
                                               class="inline-flex items-center text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-image mr-1"></i>
                                                Lihat Eviden
                                            </a>
                                            @else
                                            <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Catatan Sistem -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-clipboard-list mr-2 text-gray-500"></i>
                                    Catatan Sistem
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 whitespace-pre-line">
                                    {{ $meetingShift->systemNote->content ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Catatan Umum -->
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    <i class="fas fa-clipboard-check mr-2 text-gray-500"></i>
                                    Catatan Umum
                                </h3>
                            </div>
                            <div class="p-6">
                                <p class="text-sm text-gray-600 whitespace-pre-line">
                                    {{ $meetingShift->generalNote->content ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Uraian Shift -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-align-left mr-2 text-gray-500"></i>
                                Uraian Shift
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 whitespace-pre-line">
                                {{ $meetingShift->resume->uraian_shift ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Resume Rapat -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-file-alt mr-2 text-gray-500"></i>
                                Resume Rapat
                            </h3>
                        </div>
                        <div class="p-6">
                            <p class="text-sm text-gray-600 whitespace-pre-line">
                                {{ $meetingShift->resume->content ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <!-- Absensi -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <i class="fas fa-users mr-2 text-gray-500"></i>
                                Absensi
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            No
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Nama
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Shift
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                            Keterangan
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($meetingShift->attendances as $index => $attendance)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200 border-r border-gray-200">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900 border-r border-gray-200">
                                            {{ $attendance->nama }}
                                        </td>
                                        <td class="px-6 py-4 text-center border-r border-gray-200">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($attendance->shift == 'A') bg-blue-100 text-blue-800
                                                @elseif($attendance->shift == 'B') bg-green-100 text-green-800
                                                @elseif($attendance->shift == 'C') bg-yellow-100 text-yellow-800
                                                @else bg-purple-100 text-purple-800 @endif">
                                                Shift {{ $attendance->shift }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 border-r border-gray-200">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($attendance->status == 'hadir') bg-green-100 text-green-800
                                                @elseif($attendance->status == 'izin') bg-yellow-100 text-yellow-800
                                                @elseif($attendance->status == 'sakit') bg-orange-100 text-orange-800
                                                @elseif($attendance->status == 'cuti') bg-blue-100 text-blue-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($attendance->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border-b border-gray-200">
                                            {{ $attendance->keterangan ?? '-' }}
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

@push('styles')
<style>
    .main-content {
        overflow-y: auto;
        height: calc(100vh - 64px);
    }
    
    /* Add smooth hover transitions */
    .hover\:bg-gray-50 {
        transition: background-color 0.2s ease-in-out;
    }
    
    /* Add subtle box shadow to cards */
    .shadow-sm {
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }
    
    /* Improve table responsiveness */
    .overflow-x-auto {
        -webkit-overflow-scrolling: touch;
    }
</style>
@endpush

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 