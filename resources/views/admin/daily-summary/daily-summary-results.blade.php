@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
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
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Data Ikhtisar Harian</h1>
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
                            <input type="hidden" name="redirect" value="{{ route('login') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>
       <!-- main content -->
       <main class="flex-1 p-4">
            <div class="max-w-7xl mx-auto">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Data Ikhtisar Harian</h2>
                        <p class="text-blue-100 mb-4">Monitor dan kelola data operasional pembangkit listrik secara harian.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.daily-summary.export-pdf', ['date' => $date]) }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-pdf mr-2 text-sm"></i>Export PDF
                            </a>
                            <a href="{{ route('admin.daily-summary.export-excel', ['date' => $date]) }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2 text-sm"></i>Export Excel
                            </a>
                            <button 
                                onclick="window.location.href='{{ route('admin.daily-summary') }}'" 
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-arrow-left mr-2 text-sm"></i>Kembali
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content Card -->
                <div class="bg-white rounded-lg shadow-md">
        <div class="p-6" id="content-wrapper">
                        <!-- Filter Section -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Kelola Data Ikhtisar Harian</h2>
                        </div>

                        <!-- Horizontal Filters -->
                        <div class="mt-4 border-b border-gray-200 pb-4" id="filters-section">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="relative">
                                    <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                    <input 
                        type="date" 
                        id="dateFilter"
                        value="{{ $date }}"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50"
                    >
                                </div>
                                <div class="relative">
                                    <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="searchInput"
                            placeholder="Cari unit atau mesin..."
                            value="{{ $search ?? '' }}"
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 pl-10"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loading Indicator -->
                        <div id="loading" class="hidden flex justify-center items-center py-4">
                        <svg class="animate-spin h-5 w-5 text-[#009BB9]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                </div>
                
                        <!-- Content Loading Overlay -->
            <div id="content-loading" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white p-4 rounded-lg shadow-lg flex items-center gap-3">
                    <svg class="animate-spin h-5 w-5 text-[#009BB9]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Loading data...</span>
                </div>
            </div>

            @foreach($units as $unit)
                            @include('admin.daily-summary._table')
                                @endforeach
                    </div>
                </div>
        </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateFilter = document.getElementById('dateFilter');
    const searchInput = document.getElementById('searchInput');
    const contentLoading = document.getElementById('content-loading');
    const contentWrapper = document.getElementById('content-wrapper');
    let searchTimeout;

    function updateContent() {
        try {
            // Show loading overlay
            contentLoading.classList.remove('hidden');

            // Get current URL and update the parameters
            const url = new URL(window.location.href);
            url.searchParams.set('date', dateFilter.value);
            if (searchInput.value) {
                url.searchParams.set('search', searchInput.value);
            } else {
                url.searchParams.delete('search');
            }

            // Update browser URL without reloading
            window.history.pushState({}, '', url);

            // Redirect to the new URL
            window.location.href = url.toString();

        } catch (error) {
            console.error('Error:', error);
            alert('Error loading data. Please try again.');
        }
    }

    dateFilter.addEventListener('change', updateContent);

    // Add debounced search functionality
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateContent, 500); // 500ms delay
    });
});
</script>

<style>
.w-mesin {
    min-width: 100px !important;
}
.w-daya {
    min-width: 300px !important;
}
.w-beban {
    min-width: 200px !important;
}
.w-ratio {
    min-width: 100px !important;
}
.w-produksi {
    min-width: 200px !important;
}
.w-pemakaian-sendiri {
    min-width: 300px !important;
}
.w-jam-operasi {
    min-width: 400px !important;
}
.w-trip {
    min-width: 200px !important;
}
.w-derating {
    min-width: 400px !important;
}
.w-kinerja {
    min-width: 400px !important;
}
.w-capability {
    min-width: 100px !important;
}
.w-nof {
    min-width: 100px !important;
}
.w-jsi {
    min-width: 100px !important;
}
.w-bahan-bakar {
    min-width: 500px !important;
}
.w-pelumas {
    min-width: 700px !important;
}
.w-efisiensi {
    min-width: 350px !important;
}
.w-keterangan {
    min-width: 150px !important;
}
.text-pelumas {
    font-size: 11px !important;
}
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
@endsection 