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

        
        <main class="px-6 py-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
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
                                <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                <td class="border px-4 py-2">
                                    <input type="file" class="hidden" id="eviden-{{ $i }}">
                                    <label for="eviden-{{ $i }}" class="cursor-pointer bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600">
                                        Upload
                                    </label>
                                </td>
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </div>
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