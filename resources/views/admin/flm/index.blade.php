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

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Form Pemeriksaan FLM</h1>
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

        <div class="mt-6 flex justify-end gap-x-4 mb-6">
            <a href="{{ route('admin.flm.update-view') }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-cog mr-2"></i>Kelola Data
            </a>
            <button type="button" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">
                <i class="fas fa-sync-alt mr-2"></i>Refresh Data
            </button>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <i class="fas fa-save mr-2"></i>Simpan
            </button>
        </div>
        
        <main class="px-6 py-8 bg-white rounded-lg shadow-md ">
            <!-- Tab Navigation -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button type="button" 
                            class="tab-btn flex items-center whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600" 
                            data-target="pemeriksaan-visual">
                            Pemeriksaan Visual
                        </button>
                        <button type="button" 
                            class="tab-btn flex items-center whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-target="lumasi">
                            Lumasi
                        </button>
                        <button type="button" 
                            class="tab-btn flex items-center whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-target="kencangkan">
                            Kencangkan
                        </button>
                        <button type="button" 
                            class="tab-btn flex items-center whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" 
                            data-target="permasalahan">
                            Permasalahan
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="tab-contents">
                <!-- Pemeriksaan Visual Tab -->
                <div id="pemeriksaan-visual-content" class="tab-content">
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#0A749B]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Komponen</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Kondisi (Baik/Rusak)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">Mesin Utama</td>
                                    <td class="px-4 py-3 text-sm">
                                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                                            <option value="baik">Baik</option>
                                            <option value="rusak">Rusak</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lumasi Tab -->
                <div id="lumasi-content" class="tab-content hidden">
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#0A749B]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Fungsi</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Kondisi (Baik/Rusak)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">Fuel Pump</td>
                                    <td class="px-4 py-3 text-sm">
                                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                                            <option value="baik">Baik</option>
                                            <option value="rusak">Rusak</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kencangkan Tab -->
                <div id="kencangkan-content" class="tab-content hidden">
                    <div class="overflow-x-auto shadow-md rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#0A749B]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Komponen</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Kondisi (Normal/Tidak Normal)</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">1</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">Baut Pompa JW</td>
                                    <td class="px-4 py-3 text-sm">
                                        <select class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 p-2">
                                            <option value="normal">Normal</option>
                                            <option value="tidak_normal">Tidak Normal</option>
                                        </select>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <input type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                </tr>
                                <!-- Add more rows as needed -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Permasalahan Tab -->
                <div id="permasalahan-content" class="tab-content hidden">
                    <div class="overflow-x-auto shadow-md rounded-lg mb-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-[#0A749B]">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">No</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Masalah Ditemukan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tindakan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Waktu Pelaksanaan</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900">1</td>
                                    <td class="px-4 py-3 text-sm">
                                        <textarea type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <textarea type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <input type="datetime-local" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <textarea type="text" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"></textarea>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Deskripsi kondisi akhir</label>
                            <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" rows="3"></textarea>
                        </div>
                        
                        <div class="flex items-center gap-x-4">
                            <label class="text-sm font-medium text-gray-700">Checklist dokumentasi:</label>
                            <div class="flex items-center gap-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="dokumentasi" value="ada" class="form-radio text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Ada</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="dokumentasi" value="tidak_ada" class="form-radio text-blue-600">
                                    <span class="ml-2 text-sm text-gray-700">Tidak Ada</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                            <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" rows="3"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
           
        </main>
    </div>
</div>

<style>
    .tab-btn {
        transition: all 0.3s ease-in-out;
    }

    .tab-btn:hover {
        color: #1a56db;
    }

    .tab-content {
        transition: opacity 0.3s ease-in-out;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching functionality
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        function switchTab(tabName) {
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active classes from all tabs
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            const selectedContent = document.getElementById(`${tabName}-content`);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }

            // Activate selected tab button
            const selectedTab = document.querySelector(`[data-target="${tabName}"]`);
            if (selectedTab) {
                selectedTab.classList.remove('border-transparent', 'text-gray-500');
                selectedTab.classList.add('border-blue-500', 'text-blue-600');
            }
        }

        // Add click event listeners to tab buttons
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-target');
                switchTab(tabName);
            });
        });

        // Initialize dropdown toggle
        window.toggleDropdown = function() {
            const dropdown = document.getElementById('dropdown');
            dropdown.classList.toggle('hidden');
        };

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dropdown');
            const dropdownToggle = document.getElementById('dropdownToggle');
            
            if (!dropdown.contains(event.target) && !dropdownToggle.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
@endsection