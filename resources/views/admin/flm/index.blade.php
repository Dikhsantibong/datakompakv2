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

                    <h1 class="text-xl font-semibold text-gray-900">Form Pemeriksaan FLM</h1>
                </div>

                <!-- Add View List Button -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.flm.list') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-list mr-2"></i>
                        Lihat Data
                    </a>

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
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Form Pemeriksaan FLM', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
               
                <!-- Main Content -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6">
                        <!-- Add success message display -->
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.flm.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border px-4 py-2 text-sm">No.</th>
                                            <th class="border px-4 py-2 text-sm">Mesin/peralatan</th>
                                            <th class="border px-4 py-2 text-sm">Sistem pembangkit</th>
                                            <th class="border px-4 py-2 text-sm">Masalah awal yang ditemukan</th>
                                            <th class="border px-4 py-2 text-sm">kondisi awal</th>
                                            <th colspan="5" class="border px-4 py-2 text-sm text-center">Tindakan FLM</th>
                                            <th class="border px-4 py-2 text-sm">kondisi akhir</th>
                                            <th class="border px-4 py-2 text-sm">Catatan FLM</th>
                                            <th class="border px-4 py-2 text-sm">eviden</th>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2 text-sm">bersihkan</th>
                                            <th class="border px-4 py-2 text-sm">lumasi</th>
                                            <th class="border px-4 py-2 text-sm">kencangkan</th>
                                            <th class="border px-4 py-2 text-sm">perbaikan koneksi</th>
                                            <th class="border px-4 py-2 text-sm">lainnya</th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i <= 4; $i++)
                                        <tr>
                                            <td class="border px-4 py-2 text-center">{{ $i }}</td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded" name="mesin_{{ $i }}"></td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded" name="sistem_{{ $i }}"></td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded" name="masalah_{{ $i }}"></td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded" name="kondisi_awal_{{ $i }}"></td>
                                            <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox" name="tindakan_{{ $i }}[]" value="bersihkan"></td>
                                            <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox" name="tindakan_{{ $i }}[]" value="lumasi"></td>
                                            <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox" name="tindakan_{{ $i }}[]" value="kencangkan"></td>
                                            <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox" name="tindakan_{{ $i }}[]" value="perbaikan_koneksi"></td>
                                            <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox" name="tindakan_{{ $i }}[]" value="lainnya"></td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded" name="kondisi_akhir_{{ $i }}"></td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded" name="catatan_{{ $i }}"></td>
                                            <td class="border px-4 py-2">
                                                <input type="file" class="hidden" id="eviden-{{ $i }}" name="eviden_{{ $i }}">
                                                <label for="eviden-{{ $i }}" class="cursor-pointer bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600">
                                                    Upload
                                                </label>
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan
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