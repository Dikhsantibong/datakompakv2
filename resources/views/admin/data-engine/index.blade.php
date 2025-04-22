@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 overflow-x-hidden overflow-y-auto">
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

                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
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
        

        <!-- Main Content -->
        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Date Picker and Actions -->
                <div class="mb-6 flex flex-wrap gap-4">
                    <form id="dateFilterForm" action="{{ route('admin.data-engine.index') }}" method="GET" class="flex-grow flex gap-4">
                        <input type="date" 
                               id="datePicker"
                               name="date" 
                               value="{{ request('date', now()->format('Y-m-d')) }}"
                               class="rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <button type="submit" 
                                id="submitBtn"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            <span class="inline-flex items-center">
                                <svg id="loadingIcon" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <i class="fas fa-eye mr-2"></i>Tampilkan Data
                            </span>
                        </button>
                        <a href="{{ route('admin.data-engine.edit', ['date' => request('date', now()->format('Y-m-d'))]) }}"
                           class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            <i class="fas fa-edit mr-2"></i>Update Data
                        </a>
                    </form>

                    <!-- Export Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.data-engine.export-excel', ['date' => request('date', now()->format('Y-m-d'))]) }}"
                           class="px-4 py-2 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 inline-flex items-center">
                            <i class="fas fa-file-excel mr-2"></i>Export Excel
                        </a>
                        <a href="{{ route('admin.data-engine.export-pdf', ['date' => request('date', now()->format('Y-m-d'))]) }}"
                           class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 inline-flex items-center">
                            <i class="fas fa-file-pdf mr-2"></i>Export PDF
                        </a>
                    </div>
                </div>

                <div id="tableContainer">
                    @include('admin.data-engine._table')
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('dateFilterForm');
    const datePicker = document.getElementById('datePicker');
    const submitBtn = document.getElementById('submitBtn');
    const loadingIcon = document.getElementById('loadingIcon');
    const tableContainer = document.getElementById('tableContainer');

    // Function to show loading state
    function showLoading() {
        loadingIcon.classList.remove('hidden');
        submitBtn.disabled = true;
        tableContainer.classList.add('opacity-50');
    }

    // Function to hide loading state
    function hideLoading() {
        loadingIcon.classList.add('hidden');
        submitBtn.disabled = false;
        tableContainer.classList.remove('opacity-50');
    }

    // Handle date change
    datePicker.addEventListener('change', function() {
        showLoading();
        form.submit();
    });

    // Handle form submission
    form.addEventListener('submit', function() {
        showLoading();
    });
});
</script>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection