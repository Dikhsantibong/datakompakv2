@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <!-- Header -->
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

                    <h1 class="text-xl font-semibold text-gray-800">Data Engine Manage Input</h1>
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
        <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Date Filter -->
            <div class="mb-6">
                <div class="flex items-center gap-4">
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                        <input type="date" 
                               name="date" 
                               id="date"
                               value="{{ $date }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Status Grid -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Status Input per Unit dan Jam</h2>
                    
                    <div class="overflow-x-auto relative" id="tableContainer">
                        <!-- Loading Overlay -->
                        <div id="loadingOverlay" class="hidden absolute inset-0 bg-white bg-opacity-75 z-50 flex items-center justify-center">
                            <div class="flex items-center gap-2">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                <span class="text-gray-600">Loading...</span>
                            </div>
                        </div>

                        <table class="min-w-full divide-y divide-gray-200 border">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-white z-10 border-r">
                                        Unit
                                    </th>
                                    @foreach($hours as $hour)
                                        <th class="px-3 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap border-r">
                                            {{ \Carbon\Carbon::parse($hour)->format('H:i') }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($powerPlants as $powerPlant)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 sticky left-0 bg-white z-10 border-r">
                                            {{ $powerPlant->name }}
                                        </td>
                                        @foreach($hours as $hour)
                                            <td class="px-3 py-4 whitespace-nowrap text-center border-r">
                                                @if($powerPlant->hourlyStatus[$hour])
                                                    <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </span>
                                                @else
                                                    <a href="{{ route('admin.data-engine.edit', ['date' => $date, 'time' => $hour]) }}" 
                                                       class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full hover:bg-red-200">
                                                        <i class="fas fa-times text-xs"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Legend -->
                    <div class="mt-4 flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center size-6 bg-green-100 text-green-800 rounded-full">
                                <i class="fas fa-check text-xs"></i>
                            </span>
                            <span class="text-sm text-gray-600">Data sudah diinput</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center size-6 bg-red-100 text-red-800 rounded-full">
                                <i class="fas fa-times text-xs"></i>
                            </span>
                            <span class="text-sm text-gray-600">Data belum diinput (klik untuk input)</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const loadingOverlay = document.getElementById('loadingOverlay');
    const tableContainer = document.getElementById('tableContainer');

    dateInput.addEventListener('change', function() {
        // Show loading overlay
        loadingOverlay.classList.remove('hidden');

        // Make AJAX request
        fetch(`{{ route('admin.data-engine.daily-list') }}?date=${this.value}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Extract the table content from the response
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTable = doc.querySelector('table').outerHTML;
            
            // Update the table content
            tableContainer.querySelector('table').outerHTML = newTable;
            
            // Update URL without refreshing
            window.history.pushState({}, '', `{{ route('admin.data-engine.daily-list') }}?date=${this.value}`);
        })
        .catch(error => {
            console.error('Error:', error);
            // You might want to show an error message to the user here
        })
        .finally(() => {
            // Hide loading overlay
            loadingOverlay.classList.add('hidden');
        });
    });
});
</script>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 