@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Edit Data K3 Keamanan dan Lingkungan</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Data K3 Keamanan dan Lingkungan', 'url' => route('admin.k3-kamp.view')], ['name' => 'Edit', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
                @endif

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <form action="{{ route('admin.k3-kamp.update', $report->id) }}" method="POST" class="p-6">
                        @csrf
                        @method('PUT')

                        <!-- K3 & Keamanan Section -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">K3 & Keamanan</h2>
                            <div class="space-y-6">
                                @php
                                    $k3Items = $report->items->where('item_type', 'k3_keamanan');
                                @endphp
                                @foreach($k3Items as $item)
                                <div class="border rounded-lg p-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">{{ $item->item_name }}</label>
                                        <div class="mt-2 space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status_{{ $item->id }}" value="ada" class="form-radio" {{ $item->status === 'ada' ? 'checked' : '' }}>
                                                <span class="ml-2">Ada</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status_{{ $item->id }}" value="tidak_ada" class="form-radio" {{ $item->status === 'tidak_ada' ? 'checked' : '' }}>
                                                <span class="ml-2">Tidak Ada</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                                        <div class="mt-2 space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="kondisi_{{ $item->id }}" value="normal" class="form-radio" {{ $item->kondisi === 'normal' ? 'checked' : '' }}>
                                                <span class="ml-2">Normal</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="kondisi_{{ $item->id }}" value="abnormal" class="form-radio" {{ $item->kondisi === 'abnormal' ? 'checked' : '' }}>
                                                <span class="ml-2">Abnormal</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <textarea name="keterangan_{{ $item->id }}" rows="2" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $item->keterangan }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Lingkungan Section -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-800 mb-4">Lingkungan</h2>
                            <div class="space-y-6">
                                @php
                                    $lingkunganItems = $report->items->where('item_type', 'lingkungan');
                                @endphp
                                @foreach($lingkunganItems as $item)
                                <div class="border rounded-lg p-4">
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">{{ $item->item_name }}</label>
                                        <div class="mt-2 space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status_{{ $item->id }}" value="ada" class="form-radio" {{ $item->status === 'ada' ? 'checked' : '' }}>
                                                <span class="ml-2">Ada</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="status_{{ $item->id }}" value="tidak_ada" class="form-radio" {{ $item->status === 'tidak_ada' ? 'checked' : '' }}>
                                                <span class="ml-2">Tidak Ada</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700">Kondisi</label>
                                        <div class="mt-2 space-x-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="kondisi_{{ $item->id }}" value="normal" class="form-radio" {{ $item->kondisi === 'normal' ? 'checked' : '' }}>
                                                <span class="ml-2">Normal</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="kondisi_{{ $item->id }}" value="abnormal" class="form-radio" {{ $item->kondisi === 'abnormal' ? 'checked' : '' }}>
                                                <span class="ml-2">Abnormal</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                        <textarea name="keterangan_{{ $item->id }}" rows="2" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $item->keterangan }}</textarea>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.k3-kamp.view') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 