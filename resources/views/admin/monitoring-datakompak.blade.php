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
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'flm' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="flm">
                                FLM
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === '5s5r' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="5s5r">
                                5S5R
                            </a>
                            <a href="#"
                               class="tab-link whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'bahan-kimia' ? 'tab-active' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}"
                               data-tab="bahan-kimia">
                                Bahan Kimia
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

                <!-- Monitoring Summary Section -->
                <div class="mt-8 bg-white rounded-lg shadow">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Monitoring Summary</h2>
                            <div class="flex gap-4">
                                <div>
                                    <label for="summary_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" id="summary_start_date" value="{{ $startDate }}" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="summary_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" id="summary_end_date" value="{{ $endDate }}" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <button id="copyReport" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <i class="fas fa-copy mr-2"></i>Copy Report
                                </button>
                            </div>
                        </div>

                        <!-- Charts Container -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <canvas id="overallChart"></canvas>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <canvas id="categoryChart"></canvas>
                            </div>
                        </div>

                        <!-- Unit Details -->
                        <div id="unitDetails" class="space-y-8">
                            @foreach($monitoringSummary as $unitName => $data)
                                <div class="border-t pt-6">
                                    <h3 class="text-lg font-semibold mb-4">{{ $unitName }}</h3>

                                    <!-- Operator KIT Section -->
                                    <div class="mb-6">
                                        <h4 class="text-md font-medium mb-3">OPERATOR KIT:</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @foreach($data['operator'] as $key => $stat)
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                                        <span class="text-sm font-semibold">{{ $stat['percentage'] }}%</span>
                                                    </div>
                                                    <div class="mt-2 text-xs text-gray-500">
                                                        {{ $stat['missing_inputs'] }} kali tidak menginput
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Operasi UL/Central Section -->
                                    <div>
                                        <h4 class="text-md font-medium mb-3">OPERASI UL/CENTRAL:</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @foreach($data['operasi'] as $key => $stat)
                                                <div class="bg-gray-50 p-4 rounded-lg">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                                        <span class="text-sm font-semibold">{{ $stat['percentage'] }}%</span>
                                                    </div>
                                                    <div class="mt-2 text-xs text-gray-500">
                                                        {{ $stat['missing_inputs'] }} kali tidak menginput
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Initialize charts
    const summaryData = @json($monitoringSummary);
    initializeCharts(summaryData);

    // Handle date range changes
    document.getElementById('summary_start_date').addEventListener('change', updateSummary);
    document.getElementById('summary_end_date').addEventListener('change', updateSummary);

    // Handle copy report button
    document.getElementById('copyReport').addEventListener('click', function() {
        const startDate = document.getElementById('summary_start_date').value;
        const endDate = document.getElementById('summary_end_date').value;
        const formattedStartDate = new Date(startDate).toLocaleDateString('id-ID', { day: 'numeric' });
        const formattedEndDate = new Date(endDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

        let report = `MONITORING PENGISIAN DATAKOMPAK.COM TANGGAL ${formattedStartDate} S/D ${formattedEndDate}\n\n`;

        Object.entries(summaryData).forEach(([unitName, data]) => {
            report += `${unitName}\n\n`;

            report += `OPERATOR KIT:\n`;
            Object.entries(data.operator).forEach(([key, stat], index) => {
                report += `${index + 1}. ${key.toUpperCase().replace('_', ' ')}: ${stat.percentage}%, ${stat.missing_inputs} kali tidak menginput\n`;
            });

            report += `\nOPERASI UL/CENTRAL:\n`;
            Object.entries(data.operasi).forEach(([key, stat], index) => {
                report += `${index + 1}. ${key.toUpperCase().replace('_', ' ')}: ${stat.percentage}%, ${stat.missing_inputs} kali tidak menginput\n`;
            });

            report += '\n';
        });

        report += `\nRANKING (MENGINPUT TERTINGGI KE RENDAH)\n`;
        Object.entries(summaryData)
            .sort((a, b) => b[1].average_score - a[1].average_score)
            .forEach(([unitName, data], index) => {
                report += `${index + 1}. ${unitName}: ${data.average_score}%\n`;
            });

        navigator.clipboard.writeText(report).then(() => {
            alert('Report copied to clipboard!');
        });
    });

    function updateSummary() {
        const startDate = document.getElementById('summary_start_date').value;
        const endDate = document.getElementById('summary_end_date').value;

        fetch(`{{ route('admin.monitoring-datakompak') }}?get_summary=1&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                initializeCharts(data.summary);
                // Update the unit details section
                // This would require additional implementation
            });
    }

    function initializeCharts(data) {
        // Overall Performance Chart
        const overallCtx = document.getElementById('overallChart').getContext('2d');
        new Chart(overallCtx, {
            type: 'bar',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    label: 'Overall Performance',
                    data: Object.values(data).map(unit => unit.average_score),
                    backgroundColor: 'rgba(37, 99, 235, 0.5)',
                    borderColor: 'rgba(37, 99, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Unit Performance'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Category Performance Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categories = ['Operator KIT', 'Operasi UL/Central'];
        const categoryData = Object.values(data).map(unit => [
            Object.values(unit.operator).reduce((acc, curr) => acc + curr.percentage, 0) / Object.keys(unit.operator).length,
            Object.values(unit.operasi).reduce((acc, curr) => acc + curr.percentage, 0) / Object.keys(unit.operasi).length
        ]);

        new Chart(categoryCtx, {
            type: 'radar',
            data: {
                labels: categories,
                datasets: Object.keys(data).map((unitName, index) => ({
                    label: unitName,
                    data: categoryData[index],
                    fill: true,
                    backgroundColor: `rgba(37, 99, 235, ${0.2 + (index * 0.1)})`,
                    borderColor: `rgba(37, 99, 235, ${0.7 + (index * 0.1)})`,
                    pointBackgroundColor: `rgba(37, 99, 235, ${0.7 + (index * 0.1)})`,
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: `rgba(37, 99, 235, ${0.7 + (index * 0.1)})`
                }))
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Category Performance by Unit'
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }
});
</script>
@endpush

@endsection
