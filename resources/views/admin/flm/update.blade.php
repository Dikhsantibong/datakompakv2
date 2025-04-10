@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Update Form Pemeriksaan FLM</h1>
                </div>

                <div class="flex items-center gap-x-4">
                    <a href="{{ route('admin.flm.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                </div>
            </div>
        </header>

        <main class="p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
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
                <div class="tab-contents space-y-6">
                    <!-- Pemeriksaan Visual Tab -->
                    <div id="pemeriksaan-visual-content" class="tab-content">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komponen</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3">Mesin Utama</td>
                                        <td class="px-4 py-3 text-green-600">Baik</td>
                                        <td class="px-4 py-3">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Lumasi Tab -->
                    <div id="lumasi-content" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fungsi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3">Fuel Pump</td>
                                        <td class="px-4 py-3 text-green-600">Baik</td>
                                        <td class="px-4 py-3">Sudah dilumasi</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Kencangkan Tab -->
                    <div id="kencangkan-content" class="tab-content hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Komponen</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-4 py-3">Baut Pompa JW</td>
                                        <td class="px-4 py-3 text-green-600">Normal</td>
                                        <td class="px-4 py-3">Sudah dikencangkan</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Permasalahan Tab -->
                    <div id="permasalahan-content" class="tab-content hidden">
                        <div class="space-y-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Masalah Ditemukan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tindakan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu Pelaksanaan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-4 py-3">Kebocoran oli</td>
                                            <td class="px-4 py-3">Penggantian seal</td>
                                            <td class="px-4 py-3">2024-03-15 09:30</td>
                                            <td class="px-4 py-3">Sudah diperbaiki</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional Information -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi kondisi akhir</label>
                                    <p class="mt-1 text-gray-900">Semua komponen dalam kondisi baik setelah perbaikan dan pemeriksaan</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status Dokumentasi</label>
                                    <p class="mt-1 text-green-600">Ada</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                                    <p class="mt-1 text-gray-900">Perlu pemeriksaan rutin setiap 2 minggu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end gap-x-4">
                    <button type="button" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Edit
                    </button>
                    <button type="button" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        Delete
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

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
    });
</script>

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
@endsection 