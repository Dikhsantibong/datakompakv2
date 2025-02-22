@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 overflow-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!-- Desktop Menu Toggle -->
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

                    <h1 class="text-xl font-semibold text-gray-800">Status Mesin</h1>
                </div>

                @include('components.timer')
                
                <!-- User Dropdown -->
                <div class="relative">
                    <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                        <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                            class="w-7 h-7 rounded-full mr-2">
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                    </button>
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                        <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Loading indicator -->
        <div id="loading" class="loading fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 opacity-75 flex flex-col items-center justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-blue-500"></div>
            <h2 class="text-center text-white text-xl font-semibold mt-4">Loading...</h2>
            <p class="w-1/3 text-center text-white">Mohon tunggu sebentar...</p>
        </div>

        <!-- Main Content -->
        <div class="container mx-auto px-4 py-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Status Mesin</h2>
                    
                    <!-- Add Copy Button -->
                    <button id="copyFormattedData" 
                        class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 mr-2">
                        <i class="fas fa-copy mr-2"></i>
                        Salin Laporan
                    </button>
                    
                    <!-- Filter Area -->
                    <div class="flex flex-col gap-4">
                        <!-- Row 1: Essential Filters -->
                        <div class="flex flex-wrap items-center gap-3">
                            @if(session('unit') === 'mysql')
                            <div class="flex items-center">
                                <label for="unit-source" class="text-sm text-gray-700 font-medium mr-2">Unit:</label>
                                <select id="unit-source" 
                                    class="border rounded px-3 py-2 text-sm w-40"
                                    onchange="updateTable()">
                                    <option value="">Semua Unit</option>
                                    <option value="mysql" {{ request('unit_source') == 'mysql' ? 'selected' : '' }}>UP Kendari</option>
                                    <option value="mysql_wua_wua" {{ request('unit_source') == 'mysql_wua_wua' ? 'selected' : '' }}>Wua Wua</option>
                                    <option value="mysql_poasia" {{ request('unit_source') == 'mysql_poasia' ? 'selected' : '' }}>Poasia</option>
                                    <option value="mysql_kolaka" {{ request('unit_source') == 'mysql_kolaka' ? 'selected' : '' }}>Kolaka</option>
                                    <option value="mysql_bau_bau" {{ request('unit_source') == 'mysql_bau_bau' ? 'selected' : '' }}>Bau Bau</option>
                                </select>
                            </div>
                            @endif

                            <div class="flex items-center">
                                <label for="date-picker" class="text-sm text-gray-700 font-medium mr-2">Tanggal:</label>
                                <input type="date" id="date-picker" 
                                    class="border rounded px-3 py-2 text-sm w-auto"
                                    value="{{ $date }}"
                                    onchange="updateTable()">
                            </div>

                            <div class="flex items-center">
                                <label for="input-time" class="text-sm text-gray-700 font-medium mr-2">Waktu:</label>
                                <select id="input-time" 
                                    class="border rounded px-3 py-2 text-sm w-40"
                                    onchange="updateTable()">
                                    <option value="">Semua Waktu</option>
                                    <option value="06:00" {{ request('input_time') == '06:00' ? 'selected' : '' }}>06:00 (Pagi)</option>
                                    <option value="11:00" {{ request('input_time') == '11:00' ? 'selected' : '' }}>11:00 (Siang)</option>
                                    <option value="14:00" {{ request('input_time') == '14:00' ? 'selected' : '' }}>14:00 (Siang)</option>
                                    <option value="18:00" {{ request('input_time') == '18:00' ? 'selected' : '' }}>18:00 (Malam)</option>
                                    <option value="19:00" {{ request('input_time') == '19:00' ? 'selected' : '' }}>19:00 (Malam)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Search and Action -->
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="flex-grow">
                                <input type="text" id="searchInput" 
                                    placeholder="Cari unit/mesin/status..." 
                                    class="border rounded px-3 py-2 text-sm w-full"
                                    value="{{ request('search') }}">
                            </div>

                            <div>
                                <a href="{{ route('admin.pembangkit.ready') }}" 
                                   class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-sync-alt mr-2"></i>
                                    Update Mesin
                                </a>
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

<script src="{{ asset('js/toggle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    const powerPlantSections = document.querySelectorAll('.bg-white.rounded-lg.shadow.p-6.mb-4');
    
    powerPlantSections.forEach(section => {
        const title = section.querySelector('h1').textContent.replace('STATUS MESIN - ', '');
        const stats = section.querySelectorAll('.grid.grid-cols-5 .bg-blue-50, .bg-green-50, .bg-purple-50, .bg-orange-50');
        
        report += `\n${title}\n`;
        
        // Add DMN, DMP, Beban, HOP stats
        let totalDMP = 0;
        let totalLoad = 0;

        stats.forEach(stat => {
            const label = stat.querySelector('p:first-child').textContent.trim();
            const value = stat.querySelector('p:last-child').textContent.trim();
            report += `${label} ${value}\n`;

            if (label === 'DMP:') {
                totalDMP = parseFloat(value);
            } else if (label === 'Total Beban:') {
                totalLoad = parseFloat(value);
            }
        });

        // Calculate reserve power
        const reservePower = totalDMP - totalLoad;
        report += `Cadangan Daya: ${reservePower.toFixed(2)} MW\n\n`;

        // Add machine details
        const machines = section.querySelectorAll('tbody tr');
        machines.forEach(machine => {
            const cells = machine.querySelectorAll('td');
            if (cells.length >= 6) {
                const name = cells[1].textContent.trim();
                const dmn = cells[2].textContent.trim();
                const dmp = cells[3].textContent.trim();
                const load = cells[4].textContent.trim();
                const status = cells[5].textContent.trim();
                
                report += `- ${name} : ${dmn}/${dmp}/ ${status}`;
                if (load && load !== '-') {
                    report += ` ${load} MW`;
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
</script>

<style>
.loading {
    transition: all 0.3s ease-in-out;
}

.loading.hidden {
    opacity: 0;
    visibility: hidden;
    display: none;
}

.animate-spin {
    border-width: 4px;
    box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
}

.sidebar {
    transition: width 0.3s ease;
}

.sidebar.collapsed {
    width: 0;
    overflow: hidden;
}

#main-content {
    transition: margin-left 0.3s ease;
}

#main-content.sidebar-collapsed {
    margin-left: 0;
}

/* Override any potential center alignment */
.table-responsive td[class*="text-left"],
.table-responsive td div {
    text-align: left !important;
}

/* Specific overrides for the columns */
.table-responsive td div.max-h-[150px] {
    text-align: left !important;
    justify-content: flex-start !important;
}

/* Additional specific column overrides */
.table-responsive td[data-content-type="equipment"] div,
.table-responsive td div.whitespace-pre-wrap {
    text-align: left !important;
    justify-content: left !important;
}
</style>
@endsection 