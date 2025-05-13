@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100 overflow-hidden">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 overflow-auto">
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

                    <h1 class="text-xl font-semibold text-gray-800">Status Mesin</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Status Mesin', 'url' => null]]" />
        </div>
        
        <!-- Loading Overlay -->
        <div id="loading" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300 hidden">
            <div class="bg-white rounded-lg p-8 flex flex-col items-center">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div>
                <p class="mt-4 text-gray-600 font-medium">Memuat data...</p>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 ">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                <div class="max-w-3xl">
                    <h2 class="text-2xl font-bold mb-2">Laporan Kesiapan Pembangkit</h2>
                    <p class="text-blue-100 mb-4">Monitor dan kelola status operasional mesin pembangkit listrik secara real-time.</p>
                    <div class="flex flex-wrap gap-3">
                        <button id="copyFormattedData" 
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            Salin Laporan
                        </button>

                        <a href="{{ route('admin.machine-status.export-pdf') }}?date={{ request('date', date('Y-m-d')) }}&input_time={{ request('input_time') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Export PDF
                        </a>

                        <button id="exportExcelBtn"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Export Excel
                        </button>

                        <a href="{{ route('admin.pembangkit.ready') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Update Mesin
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <!-- Filter Section -->
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                        <h2 class="text-xl font-semibold text-gray-800">Laporan Kesiapan Pembangkit</h2>
                        <div class="flex items-center space-x-4">
                            <button id="toggleFullTable" 
                                class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                onclick="toggleFullTableView()">
                                <i class="fas fa-expand mr-1"></i> Full Table
                            </button>
                        </div>
                    </div>

                    <!-- Horizontal Filters -->
                    <div class="mt-4 border-b border-gray-200 pb-4" id="filters-section">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @if(session('unit') === 'mysql')
                            <div class="relative">
                                <label for="unit-source" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                                <select id="unit-source" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg"
                                    onchange="updateTable()">
                                    <option value="">Semua Unit</option>
                                    <option value="mysql">UP Kendari</option>
                                    <option value="mysql_wua_wua">Wua Wua</option>
                                    <option value="mysql_poasia">Poasia</option>
                                    <option value="mysql_kolaka">Kolaka</option>
                                    <option value="mysql_bau_bau">Bau Bau</option>
                                </select>
                            </div>
                            @endif

                            <div class="relative">
                                <label for="date-picker" class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                                <input type="date" id="date-picker" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg"
                                    value="{{ $date }}"
                                    onchange="updateTable()">
                            </div>

                            <div class="relative">
                                <label for="input-time" class="block text-sm font-medium text-gray-700 mb-1">Waktu</label>
                                <select id="input-time" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg"
                                    onchange="updateTable()">
                                    <option value="">Semua Waktu</option>
                                    <option value="06:00">06:00 (Pagi)</option>
                                    <option value="11:00">11:00 (Siang)</option>
                                    <option value="14:00">14:00 (Siang)</option>
                                    <option value="18:00">18:00 (Malam)</option>
                                    <option value="19:00">19:00 (Malam)</option>
                                </select>
                            </div>

                            <div class="relative">
                                <label for="searchInput" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                                <input type="text" id="searchInput" 
                                    placeholder="Cari unit/mesin/status..." 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-lg"
                                    value="{{ request('search') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="overflow-x-auto">
                    @include('admin.machine-status._table')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Excel Modal -->
<div id="exportExcelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-8 border w-[480px] shadow-xl rounded-lg bg-white">
        <div class="absolute top-4 right-4">
            <button id="closeExcelModal" class="text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <h3 class="text-xl font-semibold text-gray-900 mb-6">Export Excel</h3>

        <form id="exportExcelForm" action="{{ route('admin.machine-status.export-excel') }}" method="GET">
            <div class="space-y-6">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                    <input type="date" id="end_date" name="end_date" 
                           class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="export_input_time" class="block text-sm font-medium text-gray-700 mb-2">Waktu Input</label>
                    <select id="export_input_time" name="input_time" 
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Waktu</option>
                        <option value="06:00">06:00 (Pagi)</option>
                        <option value="11:00">11:00 (Siang)</option>
                        <option value="14:00">14:00 (Siang)</option>
                        <option value="18:00">18:00 (Malam)</option>
                        <option value="19:00">19:00 (Malam)</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelExcelExport"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                        Batal
                    </button>
                    <button type="submit" id="confirmExcelExport"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Export Excel
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* Base Styles */
.loading {
    transition: opacity 0.3s ease-in-out;
}

.loading.hidden {
    opacity: 0;
    pointer-events: none;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideIn {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Component Styles */
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}

.animate-slide-in {
    animation: slideIn 0.3s ease-out;
}

/* Layout Transitions */
.sidebar {
    transition: all 0.3s ease-in-out;
}

#main-content {
    transition: all 0.3s ease-in-out;
}

/* Interactive Elements */
button, a {
    transition: all 0.2s ease-in-out;
}

.hover-scale:hover {
    transform: scale(1.02);
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #666;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .responsive-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function toggleDropdown() {
    document.getElementById('dropdown').classList.toggle('hidden');
}

document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('dropdown');
    const dropdownToggle = document.getElementById('dropdownToggle');
    
    if (!dropdown.contains(event.target) && !dropdownToggle.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

let searchTimeout;

document.getElementById('searchInput').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        updateTable();
    }, 500); // Debounce 500ms
});

function updateTable() {
    const date = document.getElementById('date-picker').value;
    const unitSource = @json(session('unit')) === 'mysql' ? document.getElementById('unit-source')?.value : null;
    const inputTime = document.getElementById('input-time').value;
    const searchText = document.getElementById('searchInput').value;
    
    showLoading();
    
    const params = new URLSearchParams({
        date: date,
        search: searchText,
        ...(unitSource && { unit_source: unitSource }),
        ...(inputTime && { input_time: inputTime })
    });
    
    fetch(`{{ route('admin.machine-status.view') }}?${params.toString()}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const container = document.querySelector('.overflow-x-auto');
            if (container) {
                container.innerHTML = data.html;
            }
        } else {
            throw new Error(data.message || 'Terjadi kesalahan saat memuat data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const container = document.querySelector('.overflow-x-auto');
        if (container) {
            container.innerHTML = `
                <div class="text-center py-4 text-red-500">
                    Terjadi kesalahan saat memuat data: ${error.message}
                </div>
            `;
        }
    })
    .finally(() => {
        hideLoading();
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Desktop menu toggle
    const desktopMenuToggle = document.getElementById('desktop-menu-toggle');
    const sidebar = document.querySelector('.sidebar'); // Sesuaikan dengan class sidebar Anda
    
    desktopMenuToggle.addEventListener('click', function() {
        sidebar.classList.toggle('collapsed');
        document.getElementById('main-content').classList.toggle('sidebar-collapsed');
    });

    // Sembunyikan loading saat halaman selesai dimuat
    hideLoading();
});

// Tambahkan event listener untuk window load
window.addEventListener('load', function() {
    // Sembunyikan loading saat semua resource (gambar, dll) selesai dimuat
    hideLoading();
});

function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
}

// Tambahkan timeout sebagai fallback
setTimeout(hideLoading, 5000); // Sembunyikan loading setelah 5 detik jika masih belum hilang

// Tambahkan event listener untuk AJAX requests jika ada
document.addEventListener('ajax:start', showLoading);
document.addEventListener('ajax:complete', hideLoading);

// Jika menggunakan jQuery AJAX
$(document).ajaxStart(function() {
    showLoading();
});

$(document).ajaxComplete(function() {
    hideLoading();
});

// Jika menggunakan Fetch API, buat wrapper function
function fetchWithLoading(url, options = {}) {
    showLoading();
    return fetch(url, options)
        .finally(() => {
            hideLoading();
        });
}

function formatDate(date) {
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const d = new Date(date);
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

function getFormattedReport() {
    const date = document.getElementById('date-picker').value;
    const time = document.getElementById('input-time').value || '11:00';
    
    let report = `Assalamu Alaikum Wr.Wb\n`;
    report += `Laporan Kesiapan Pembangkit PLN Nusantara Power\n`;
    report += `Unit Pembangkitan Kendari, ${formatDate(date)}\n`;
    report += `Pukul : ${time} Wita\n\n`;

    // Get all power plant sections
    const powerPlantSections = document.querySelectorAll('.bg-white.rounded-xl.shadow-sm.mb-8');
    
    powerPlantSections.forEach(section => {
        const title = section.querySelector('h2').textContent.trim();
        const stats = section.querySelectorAll('.grid.grid-cols-1.md\\:grid-cols-2.lg\\:grid-cols-5 .bg-white.rounded-lg.border');
        
        report += `\n${title}\n`;
        
        // Add DMN, DMP, Beban, HOP stats
        let totalDMP = 0;
        let totalLoad = 0;

        stats.forEach(stat => {
            const label = stat.querySelector('p:first-child').textContent.trim();
            const valueElement = stat.querySelector('p:nth-child(2)') || stat.querySelector('.mt-2');
            let value = valueElement ? valueElement.textContent.trim().split(' ')[0] : '0';
            
            if (label === 'DMP') {
                totalDMP = parseFloat(value);
            } else if (label === 'Total Beban') {
                totalLoad = parseFloat(value);
            }
            
            report += `${label}: ${value} MW\n`;
        });

        // Calculate reserve power
        const reservePower = totalDMP - totalLoad;
        report += `Cadangan Daya: ${reservePower.toFixed(2)} MW\n\n`;

        // Add machine details
        const machines = section.querySelectorAll('tbody tr');
        machines.forEach(machine => {
            const cells = machine.querySelectorAll('td');
            if (cells.length >= 6) {
                const name = cells[1].querySelector('div').textContent.trim();
                const dmn = cells[2].textContent.trim();
                const dmp = cells[3].textContent.trim();
                const load = cells[4].textContent.trim();
                const status = cells[5].querySelector('span').textContent.trim();
                const description = cells[6].textContent.trim();
                
                report += `- ${name} : ${dmn}/${dmp}/${load} MW /${status} (${description})`;
                if (load && load !== '-') {
                    report += ` `;
                }
                report += '\n';
            }
        });
        
        report += '\n';
    });

    report += '\nBarakallahu Fikhum dan Terima Kasih';
    return report;
}

document.getElementById('copyFormattedData').addEventListener('click', function() {
    const formattedReport = getFormattedReport();
    
    navigator.clipboard.writeText(formattedReport).then(() => {
        // Show success message using SweetAlert2
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Laporan telah disalin ke clipboard',
            timer: 2000,
            showConfirmButton: false
        });
    }).catch(err => {
        console.error('Failed to copy text: ', err);
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Gagal menyalin laporan ke clipboard'
        });
    });
});

// Excel Export Modal Functions
document.getElementById('exportExcelBtn').addEventListener('click', function() {
    document.getElementById('exportExcelModal').classList.remove('hidden');
    
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('start_date').value = today;
    document.getElementById('end_date').value = today;
});

document.getElementById('closeExcelModal').addEventListener('click', function() {
    document.getElementById('exportExcelModal').classList.add('hidden');
});

document.getElementById('cancelExcelExport').addEventListener('click', function() {
    document.getElementById('exportExcelModal').classList.add('hidden');
});

document.getElementById('exportExcelForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    const inputTime = document.getElementById('export_input_time').value;
    
    showLoading();
    
    const params = new URLSearchParams({
        start_date: startDate,
        end_date: endDate,
        input_time: inputTime
    });

    window.location.href = `{{ route('admin.machine-status.export-excel') }}?${params.toString()}`;
    
    setTimeout(() => {
        document.getElementById('exportExcelModal').classList.add('hidden');
        hideLoading();
    }, 2000);
});
</script>
@endsection 