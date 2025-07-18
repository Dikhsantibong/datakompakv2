@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <!-- Add Modal for Time Selection -->
    <div id="timeSelectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pilih Jam Laporan</h3>
            <div class="grid grid-cols-4 gap-2 mb-4">
                @for ($hour = 0; $hour <= 24; $hour++)
                    <button
                        class="time-button px-3 py-2 text-sm font-medium rounded-md hover:bg-blue-50 border"
                        data-time="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00">
                        {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                    </button>
                @endfor
            </div>
            <div class="flex justify-end gap-2">
                <button
                    onclick="closeTimeModal()"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Batal
                </button>
                <button
                    onclick="confirmTimeSelection()"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Pilih
                </button>
            </div>
        </div>
    </div>

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

                    <h1 class="text-xl font-semibold text-gray-800">Data Engine</h1>
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
            <div class=" px-2">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Data Engine Management</h2>
                        <p class="text-blue-100 mb-4">Monitor dan kelola data operasional mesin pembangkit listrik secara efisien.</p>
                        <div class="flex flex-wrap gap-3">
                            <button id="copyFormattedData"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                                Salin Laporan
                            </button>
                            <button id="shareWhatsApp"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-600 bg-white rounded-md hover:bg-green-50">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Kirim ke WhatsApp
                            </button>
                            <a href="{{ route('admin.data-engine.export-excel', request()->query()) }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </a>
                            <a href="{{ route('admin.data-engine.export-pdf', request()->query()) }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </a>
                            <a href="{{ route('admin.data-engine.edit', ['date' => now()->format('Y-m-d')]) }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-plus mr-2"></i> Update Data
                            </a>
                            @if(session('unit') == 'mysql')
                            <a href="{{ route('admin.data-engine.daily-list', ['date' => request('date', now()->format('Y-m-d'))]) }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                <i class="fas fa-tasks mr-2"></i> Manage Input
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header with Filters -->
                        <div class="mb-4" id="table-controls">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-semibold text-gray-900">Data Engine</h2>
                                    <button id="toggleFullTable"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                            onclick="toggleFullTableView()">
                                        <i class="fas fa-expand mr-1"></i> Full Table
                                    </button>

                                    @if(request()->has('power_plant_id') || request()->has('date') || request()->has('time'))
                                        <div class="flex flex-wrap gap-2" id="active-filters">
                                            @if(request('power_plant_id'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Unit: {{ $allPowerPlants->find(request('power_plant_id'))->name }}
                                                    <button onclick="removeFilter('power_plant_id')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('date'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Tanggal: {{ \Carbon\Carbon::parse(request('date'))->format('d/m/Y') }}
                                                    <button onclick="removeFilter('date')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('time'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Jam: {{ \Carbon\Carbon::parse(request('time'))->format('H:i') }}
                                                    <button onclick="removeFilter('time')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Horizontal Filters -->
                            <div class="mt-2 border-b border-gray-200 pb-4" id="filters-section">
                                <form id="dateFilterForm" action="{{ route('admin.data-engine.index') }}" method="GET"
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                        <select name="power_plant_id"
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Unit</option>
                                            @foreach($allPowerPlants as $powerPlant)
                                                <option value="{{ $powerPlant->id }}" {{ request('power_plant_id') == $powerPlant->id ? 'selected' : '' }}>
                                                    {{ $powerPlant->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal</label>
                                        <input type="date"
                                               name="date"
                                               value="{{ request('date', now()->format('Y-m-d')) }}"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jam</label>
                                        <select name="time"
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Jam</option>
                                            @for ($hour = 0; $hour <= 24; $hour++)
                                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00:00"
                                                        {{ request('time') == str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00' ? 'selected' : '' }}>
                                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                id="submitBtn"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            <span class="inline-flex items-center">
                                                <svg id="loadingIcon" class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <i class="fas fa-search mr-2"></i> Tampilkan Data
                                            </span>
                                        </button>
                                        <a href="{{ route('admin.data-engine.index') }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div id="tableContainer">
                            @include('admin.data-engine._table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('dateFilterForm');
    const filterInputs = form.querySelectorAll('select, input[type="date"]');
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

    // Auto-submit form when changing filters
    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            showLoading();
            form.submit();
        });
    });

    // Handle form submission
    form.addEventListener('submit', function() {
        showLoading();
    });
});

function removeFilter(filterName) {
    const form = document.getElementById('dateFilterForm');
    const input = form.querySelector(`[name="${filterName}"]`);
    if (input) {
        input.value = '';
        form.submit();
    }
}

// Add full table view toggle functionality
function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const filtersSection = document.getElementById('filters-section');
    const activeFilters = document.getElementById('active-filters');
    const welcomeCard = document.querySelector('.welcome-card')?.parentElement;
    const mainContent = document.querySelector('main');

    // Toggle full table mode
    const isFullTable = button.classList.contains('bg-blue-600');

    if (isFullTable) {
        // Restore normal view
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';

        if (filtersSection) filtersSection.style.display = '';
        if (activeFilters) activeFilters.style.display = '';
        if (welcomeCard) welcomeCard.style.display = '';
        if (mainContent) mainContent.classList.remove('pt-0');

    } else {
        // Enable full table view
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';

        if (filtersSection) filtersSection.style.display = 'none';
        if (activeFilters) activeFilters.style.display = 'none';
        if (welcomeCard) welcomeCard.style.display = 'none';
        if (mainContent) mainContent.classList.add('pt-0');
    }
}

function formatDate(date) {
    const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const d = new Date(date);
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`;
}

// Add new functions for time selection modal
let selectedTime = null;

function showTimeModal() {
    const modal = document.getElementById('timeSelectionModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Reset previously selected button
    document.querySelectorAll('.time-button').forEach(btn => {
        btn.classList.remove('bg-blue-600', 'text-white');
        btn.classList.add('text-gray-700');
    });

    // If there's a previously selected time, highlight it
    if (selectedTime) {
        const btn = document.querySelector(`[data-time="${selectedTime}"]`);
        if (btn) {
            btn.classList.add('bg-blue-600', 'text-white');
            btn.classList.remove('text-gray-700');
        }
    }
}

function closeTimeModal() {
    const modal = document.getElementById('timeSelectionModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Add click handlers for time buttons
document.querySelectorAll('.time-button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove highlight from all buttons
        document.querySelectorAll('.time-button').forEach(btn => {
            btn.classList.remove('bg-blue-600', 'text-white');
            btn.classList.add('text-gray-700');
        });

        // Add highlight to selected button
        this.classList.add('bg-blue-600', 'text-white');
        this.classList.remove('text-gray-700');
        selectedTime = this.dataset.time;
    });
});

function confirmTimeSelection() {
    if (selectedTime) {
        shareToWhatsApp(selectedTime);
        closeTimeModal();
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Jam',
            text: 'Silakan pilih jam terlebih dahulu',
            timer: 2000,
            showConfirmButton: false
        });
    }
}

function getFormattedReport(selectedTime) {
    const date = document.querySelector('input[name="date"]').value;
    const currentSession = '{{ session('unit') }}';

    let report = `Assalamu Alaikum Wr.Wb\n`;
    report += `Laporan Data Engine PLN\ Nusantara Power\n`;

    if (currentSession !== 'mysql') {
        const powerPlantName = document.querySelector('.bg-white.rounded-xl.shadow-sm.overflow-hidden.border.border-gray-100 h2')?.textContent.trim() || '';
        report += `${powerPlantName}, ${formatDate(date)}\n`;
    } else {
        report += `Unit Pembangkitan Kendari, ${formatDate(date)}\n`;
    }

    report += `Pukul : ${selectedTime} Wita\n\n`;

    // Get all power plant sections
    const powerPlantSections = document.querySelectorAll('.bg-white.rounded-xl.shadow-sm.overflow-hidden.border.border-gray-100');

    powerPlantSections.forEach(section => {
        const title = section.querySelector('h2').textContent.trim();
        const powerPlantInfo = section.querySelector('.flex.items-center.gap-4');

        report += `\n${title}\n`;

        // Add power plant specific info (HOP/TMA/Inflow)
        if (powerPlantInfo) {
            const infoElements = powerPlantInfo.querySelectorAll('.flex.items-center.gap-2');
            infoElements.forEach(info => {
                const label = info.querySelector('.text-sm.font-medium').textContent.trim();
                const value = info.querySelector('.text-sm.text-gray-900').textContent.trim();
                const unit = info.querySelector('.text-sm.text-gray-600').textContent.trim();
                report += `${label}: ${value} ${unit}\n`;
            });
        }

        // Add machine details
        const machines = section.querySelectorAll('tbody tr');
        machines.forEach(machine => {
            const cells = machine.querySelectorAll('td');
            if (cells.length >= 11) {
                const name = cells[1].querySelector('div').textContent.trim();
                const dayaTerpasang = cells[3].textContent.trim();
                const dmn = cells[4].textContent.trim();
                const dmp = cells[5].textContent.trim();
                const beban = cells[6].textContent.trim();
                const kvar = cells[7].textContent.trim();
                const cosPhi = cells[8].textContent.trim();
                const status = cells[9].querySelector('span')?.textContent.trim() || '-';
                const description = cells[10].textContent.trim();

                report += `- ${name}:\n`;
                report += `  Daya Terpasang: ${dayaTerpasang} kW\n`;
                report += `  DMN: ${dmn}\n`;
                report += `  DMP: ${dmp}\n`;
                report += `  Beban: ${beban} kW\n`;
                report += `  kVAR: ${kvar}\n`;
                report += `  Cos φ: ${cosPhi}\n`;
                report += `  Status: ${status}\n`;
                report += `  Keterangan: ${description}\n\n`;
            }
        });
    });

    report += '\nBarakallahu Fikhum dan Terima Kasih';
    return report;
}

function shareToWhatsApp(selectedTime) {
    const formattedReport = getFormattedReport(selectedTime);
    const encodedMessage = encodeURIComponent(formattedReport);
    const whatsappMessage = encodedMessage.replace(/\n/g, '%0A');
    window.open(`https://wa.me/?text=${whatsappMessage}`, '_blank');
}

document.getElementById('shareWhatsApp').addEventListener('click', function() {
    showTimeModal();
});

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

// Helper untuk validasi tanggal dan jam
function validateDateTimeSelection() {
    const dateInput = document.querySelector('input[name="date"]');
    const timeSelect = document.querySelector('select[name="time"]');
    let valid = true;

    // Remove previous highlight
    dateInput.classList.remove('input-error');
    timeSelect.classList.remove('input-error');

    if (!dateInput.value) {
        dateInput.classList.add('input-error');
        valid = false;
    }
    if (!timeSelect.value) {
        timeSelect.classList.add('input-error');
        valid = false;
    }
    return valid;
}

// Ubah event WhatsApp
const shareBtn = document.getElementById('shareWhatsApp');
shareBtn.addEventListener('click', function(e) {
    const valid = validateDateTimeSelection();
    if (!valid) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Tanggal & Jam',
            text: 'Silakan pilih tanggal dan jam terlebih dahulu sebelum mengirim ke WhatsApp.',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }
    showTimeModal();
});

// Ubah event Salin Laporan
const copyBtn = document.getElementById('copyFormattedData');
copyBtn.addEventListener('click', function(e) {
    const valid = validateDateTimeSelection();
    if (!valid) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Tanggal & Jam',
            text: 'Silakan pilih tanggal dan jam terlebih dahulu sebelum menyalin laporan.',
            timer: 2000,
            showConfirmButton: false
        });
        return;
    }
    const formattedReport = getFormattedReport();
    navigator.clipboard.writeText(formattedReport).then(() => {
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

// Hilangkan highlight saat user memilih
const dateInput = document.querySelector('input[name="date"]');
const timeSelect = document.querySelector('select[name="time"]');
dateInput.addEventListener('change', function() {
    if (dateInput.value) dateInput.classList.remove('input-error');
});
timeSelect.addEventListener('change', function() {
    if (timeSelect.value) timeSelect.classList.remove('input-error');
});
</script>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection

<style>
    .input-error {
        border-color: #dc2626 !important; /* Tailwind red-600 */
        box-shadow: 0 0 0 1px #dc2626;
    }
</style>
