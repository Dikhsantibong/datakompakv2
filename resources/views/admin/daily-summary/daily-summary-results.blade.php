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
                    <div class="p-6">
                        <!-- Filter Section -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 mb-6">
                            <h2 class="text-xl font-semibold text-gray-800">Kelola Data Ikhtisar Harian</h2>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <!-- Date Filter -->
                                <div class="w-full sm:w-72">
                                    <label for="dateFilter" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                    <input type="date" 
                                           id="dateFilter"
                                           value="{{ $date }}"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50">
                                </div>

                                <!-- Search Unit -->
                                <div class="w-full sm:w-72">
                                    @if(session('unit') === 'mysql')
                                    <label for="unit-filter" class="block text-sm font-medium text-gray-700 mb-1">Filter Unit Pembangkit</label>
                                    <div class="relative">
                                        <select id="unit-filter" name="unit_source" class="w-full appearance-none rounded-md border border-gray-300 bg-white pl-4 pr-10 py-2 text-gray-700 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer hover:border-gray-400 transition-colors duration-200 select-none">
                                            <option value="">Semua Unit</option>
                                            @foreach($unitSources as $source)
                                                @php
                                                    $sourceName = match($source) {
                                                        'mysql_poasia' => 'PLTD POASIA',
                                                        'mysql_kolaka' => 'PLTD KOLAKA',
                                                        'mysql_bau_bau' => 'PLTD BAU BAU',
                                                        'mysql_wua_wua' => 'PLTD WUA WUA',
                                                        'mysql_winning' => 'PLTD WINNING',
                                                        'mysql_ereke' => 'PLTD EREKE',
                                                        'mysql_ladumpi' => 'PLTD LADUMPI',
                                                        'mysql_langara' => 'PLTD LANGARA',
                                                        'mysql_lanipa_nipa' => 'PLTD LANIPA-NIPA',
                                                        'mysql_pasarwajo' => 'PLTD PASARWAJO',
                                                        'mysql_poasia_containerized' => 'PLTD POASIA CONTAINERIZED',
                                                        'mysql_raha' => 'PLTD RAHA',
                                                        'mysql_wajo' => 'PLTD WAJO',
                                                        'mysql_wangi' => 'PLTD WANGI-WANGI',
                                                        'mysql_rongi' => 'PLTD RONGI',
                                                        'mysql_sabilambo' => 'PLTD SABILAMBO',
                                                        'mysql_pltmg_bau_bau' => 'PLTMG BAU BAU',
                                                        'mysql_pltmg_kendari' => 'PLTD KENDARI',
                                                        'mysql_baruta' => 'PLTD BARUTA',
                                                        'mysql_moramo' => 'PLTD MORAMO',
                                                        'mysql_mikuasi' => 'PLTM MIKUASI',
                                                        default => strtoupper($source)
                                                    };
                                                @endphp
                                                <option value="{{ $source }}" {{ request('unit_source') === $source ? 'selected' : '' }}>
                                                    {{ $sourceName }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Search Input -->
                                <div class="w-full sm:w-72">
                                    <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                                    <div class="relative">
                                        <input type="text" 
                                               id="searchInput"
                                               placeholder="Cari unit atau mesin..."
                                               value="{{ request('search') }}"
                                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 pl-10">
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

                        <!-- Table Content -->
                        <div id="table-content">
                            @foreach($units as $unit)
                                @include('admin.daily-summary._table')
                            @endforeach
                        </div>
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
    const unitFilter = document.getElementById('unit-filter');
    const loading = document.getElementById('loading');
    let searchTimeout;

    function updateContent() {
        // Show loading indicator
        loading.classList.remove('hidden');

        // Get current URL and update the parameters
        const url = new URL(window.location.href);
        
        // Ensure date parameter is properly set
        const selectedDate = dateFilter.value;
        if (selectedDate) {
            url.searchParams.set('date', selectedDate);
        }
        
        // Handle search parameter
        if (searchInput && searchInput.value.trim()) {
            url.searchParams.set('search', searchInput.value.trim());
        } else {
            url.searchParams.delete('search');
        }

        // Handle unit filter parameter
        if (unitFilter) {
            const selectedUnit = unitFilter.value;
            console.log('Selected unit:', selectedUnit); // Debug log
            if (selectedUnit && selectedUnit !== 'all') {
                url.searchParams.set('unit_source', selectedUnit);
            } else {
                url.searchParams.delete('unit_source');
            }
        }

        // Update browser URL without reloading
        window.history.pushState({}, '', url);

        // Add AJAX parameter to indicate this is an AJAX request
        url.searchParams.set('ajax', '1');

        // Log the final URL for debugging
        console.log('Sending request to:', url.toString());

        // Fetch new content
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                document.getElementById('table-content').innerHTML = html;
                loading.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                loading.classList.add('hidden');
                alert('Error loading data. Please try again.');
            });
    }

    // Add event listeners
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            console.log('Date changed to:', this.value);
            updateContent();
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                console.log('Search input:', this.value);
                updateContent();
            }, 500);
        });
    }

    if (unitFilter) {
        unitFilter.addEventListener('change', function() {
            console.log('Unit filter changed to:', this.value);
            updateContent();
        });
    }
});
</script>

<style>
.w-mesin {
    min-width: 100px !important;
}
.w-daya {
    min-width: 500px !important;
}
.w-beban {
    min-width: 250px !important;
}
.w-ratio {
    min-width: 100px !important;
}
.w-produksi {
    min-width: 300px !important;
}
.w-pemakaian-sendiri {
    min-width: 400px !important;
}
.w-jam-operasi {
    min-width: 600px !important;
}
.w-trip {
    min-width: 300px !important;
}
.w-derating {
    min-width: 600px !important;
}
.w-kinerja {
    min-width: 600px !important;
}
.w-capability {
    min-width: 100px !important;
}
.w-nof {
    min-width: 200px !important;
}
.w-jsi {
    min-width: 150px !important;
}
.w-bahan-bakar {
    min-width: 600px !important;
}
.w-pelumas {
    min-width: 800px !important;
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