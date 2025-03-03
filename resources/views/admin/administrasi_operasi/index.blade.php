@extends('layouts.app')

@push('styles')
    <style>
        /* Pastikan konten utama dapat di-scroll */
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }
    </style>
@endpush

@section('content')
    <div class="flex h-screen bg-gray-50 overflow-auto">
        <!-- Sidebar -->
        @include('components.sidebar')
        
        <!-- Main Content -->
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
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
    
                        <!--  Menu Toggle Sidebar-->
                        <button id="desktop-menu-toggle"
                            class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                            aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
    
                        <h1 class="text-xl font-semibold text-gray-800">Administrasi Operasi</h1>
                    </div>
    
                    <div class="relative">
                        <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                            <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                                class="w-7 h-7 rounded-full mr-2">
                            <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                            <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                        </button>
                        <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                            <a href="{{ route('logout') }}" 
                               class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                               onclick="event.preventDefault(); 
                                        document.getElementById('logout-form').submit();">Logout</a>
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
                    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
                    ['name' => 'Administrasi Operasi', 'url' => null]
                ]" />
            </div>

            <!-- Main Content -->
            <main class="px-6">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <!-- Card Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    <!-- Card 1: Laporan Berita Acara -->
                                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                        <div class="p-6">
                                            <div class="text-4xl text-blue-600 mb-4">
                                                <i class="fas fa-file-alt"></i>
                                            </div>
                                            <h3 class="text-xl font-semibold mb-2">Laporan Berita Acara</h3>
                                            <p class="text-gray-600 mb-4">Kelola laporan berita acara operasional</p>
                                            <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">
                                                Lihat Detail →
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Card 2: Logsheet -->
                                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                        <div class="p-6">
                                            <div class="text-4xl text-green-600 mb-4">
                                                <i class="fas fa-clipboard-list"></i>
                                            </div>
                                            <h3 class="text-xl font-semibold mb-2">Logsheet</h3>
                                            <p class="text-gray-600 mb-4">Pencatatan dan monitoring logsheet harian</p>
                                            <a href="#" class="text-green-600 hover:text-green-800 font-medium">
                                                Lihat Detail →
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Card 3: Dokumen SOP -->
                                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                        <div class="p-6">
                                            <div class="text-4xl text-purple-600 mb-4">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <h3 class="text-xl font-semibold mb-2">Dokumen SOP</h3>
                                            <p class="text-gray-600 mb-4">Akses dokumen Standard Operating Procedure</p>
                                            <a href="#" class="text-purple-600 hover:text-purple-800 font-medium">
                                                Lihat Detail →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/toggle.js') }}"></script>
@endsection