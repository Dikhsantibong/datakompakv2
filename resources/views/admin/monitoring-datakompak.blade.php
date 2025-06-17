@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }
        .tab-active {
            border-bottom: 2px solid #2563eb;
            color: #2563eb;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(0.9); opacity: 0.5; }
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .loading-dots {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .loading-dots div {
            width: 10px;
            height: 10px;
            background-color: #2563eb;
            border-radius: 50%;
            animation: pulse 1.4s ease-in-out infinite;
        }
        .loading-dots div:nth-child(2) {
            animation-delay: 0.2s;
        }
        .loading-dots div:nth-child(3) {
            animation-delay: 0.4s;
        }
        .loading-text {
            background: linear-gradient(90deg, #2563eb 25%, #60a5fa 50%, #2563eb 75%);
            background-size: 200% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: shimmer 2s infinite;
            font-weight: 500;
        }
        .loading-overlay {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
            transition: all 0.3s ease;
        }
    </style>
@endpush

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
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

                    <h1 class="text-xl font-semibold text-gray-800">Monitoring Data Input</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Monitoring Data Input', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="px-2">
                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-industry text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Total Unit</p>
                                <p class="text-lg font-semibold">{{ $stats['total_units'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-check-circle text-xl"></i>
                </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Sudah Input</p>
                                <p class="text-lg font-semibold">{{ $stats['completed'] }}</p>
                            </div>
                        </div>
                                </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Pending</p>
                                <p class="text-lg font-semibold">{{ $stats['pending'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-red-100 text-red-600">
                                <i class="fas fa-exclamation-circle text-xl"></i>
                        </div>
                            <div class="ml-4">
                                <p class="text-sm text-gray-500">Terlambat</p>
                                <p class="text-lg font-semibold">{{ $stats['overdue'] }}</p>
                                </div>
                        </div>
                    </div>
                </div>


                <!-- Tabs -->
                <div class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8">
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'data-engine' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="data-engine">
                                Data Engine 24 Jam
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'daily-summary' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="daily-summary">
                                Ikhtisar Harian
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'meeting-shift' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="meeting-shift">
                                Meeting Shift
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'bahan-bakar' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="bahan-bakar">
                                Bahan Bakar
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'pelumas' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="pelumas">
                                Pelumas
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'laporan-kit' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="laporan-kit">
                                Laporan KIT 00.00
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'flm-inspection' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="flm-inspection">
                                FLM Inspection
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'bahan-kimia' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="bahan-kimia">
                                Bahan Kimia
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'five-s5r' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="five-s5r">
                                FiveS5R
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'patrol-check' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="patrol-check">
                                Patrol Check
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Filters -->
                <div class="mb-6">
                    <div class="flex items-center gap-4">
                        <!-- Date Filter for Data Engine -->
                        <div class="{{ $activeTab === 'data-engine' ? '' : 'hidden' }}" id="date-filter">
                            <label for="date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date"
                                   name="date"
                                   id="date"
                                   value="{{ $date }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <!-- Month Filter for other tabs -->
                        <div class="{{ $activeTab === 'data-engine' ? 'hidden' : '' }}" id="month-filter">
                            <label for="month" class="block text-sm font-medium text-gray-700">Bulan</label>
                            <input type="month"
                                   name="month"
                                   id="month"
                                   value="{{ $month }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="overflow-x-auto relative" id="tableContainer">
                            @include('admin.monitoring-datakompak._table')
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
                                <span class="text-sm text-gray-600">Data belum diinput</span>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div id="excel-loading-overlay" class="loading-overlay" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100vw; height:100vh; align-items:center; justify-content:center;">
    <div style="text-align:center;">
        <div class="loading-dots">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="loading-text mt-4 text-lg">Mempersiapkan file Excel</div>
    </div>
</div>

<div id="tab-loading-overlay" class="loading-overlay" style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100vw; height:100vh; align-items:center; justify-content:center;">
    <div style="text-align:center;">
        <div class="loading-dots">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <div class="loading-text mt-4 text-lg">Memuat data</div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
// Toggle dropdown
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
window.addEventListener('click', function(e) {
    const dropdown = document.getElementById('dropdown');
    const dropdownToggle = document.getElementById('dropdownToggle');

    if (!dropdown.contains(e.target) && !dropdownToggle.contains(e.target)) {
        dropdown.classList.add('hidden');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('date');
    const monthInput = document.getElementById('month');
    const dateFilter = document.getElementById('date-filter');
    const monthFilter = document.getElementById('month-filter');
    const tableContainer = document.getElementById('tableContainer');
    const tabLinks = document.querySelectorAll('.tab-link');
    const loadingOverlay = document.getElementById('tab-loading-overlay');
    let currentTab = '{{ $activeTab }}';

    function showLoading() {
        loadingOverlay.style.display = 'flex';
    }

    function hideLoading() {
        loadingOverlay.style.display = 'none';
    }

    function updateContent(tab, params) {
        showLoading();
        const queryString = new URLSearchParams(params).toString();
        fetch(`{{ route('admin.monitoring-datakompak') }}?${queryString}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            tableContainer.innerHTML = html;
            window.history.pushState({}, '', `{{ route('admin.monitoring-datakompak') }}?${queryString}`);
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            hideLoading();
        });
    }

    dateInput?.addEventListener('change', function() {
        updateContent(currentTab, { date: this.value, tab: currentTab });
    });

    monthInput?.addEventListener('change', function() {
        updateContent(currentTab, { month: this.value, tab: currentTab });
    });

    tabLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();

            tabLinks.forEach(l => {
                l.classList.remove('tab-active');
                l.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            this.classList.add('tab-active');
            this.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

            currentTab = this.dataset.tab;
            if (currentTab === 'data-engine') {
                dateFilter.classList.remove('hidden');
                monthFilter.classList.add('hidden');
                updateContent(currentTab, { date: dateInput.value, tab: currentTab });
            } else {
                dateFilter.classList.add('hidden');
                monthFilter.classList.remove('hidden');
                updateContent(currentTab, { month: monthInput.value, tab: currentTab });
            }
        });
    });

    document.querySelectorAll('form[action*="export-excel"]').forEach(form => {
        form.addEventListener('submit', function() {
            document.getElementById('excel-loading-overlay').style.display = 'flex';
            setTimeout(() => {
                document.getElementById('excel-loading-overlay').style.display = 'none';
            }, 10000); // fallback hide after 10s
        });
    });
});
</script>
@endpush

@endsection
