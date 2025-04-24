@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
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

                    <h1 class="text-xl font-semibold text-gray-800">RJPP-DPR</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Operasi UPKD', 'url' => null], ['name' => 'RJPP-DPR', 'url' => null]]" />
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">RJPP-DPR</h2>
                        <p class="text-blue-100 mb-4">Rencana Jangka Panjang Perusahaan - Dewan Perwakilan Rakyat</p>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50" onclick="exportToExcel()">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50" onclick="exportToPDF()">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700" onclick="addNewRow()">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </button>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Data RJPP-DPR</h2>
                            <button id="toggleFullTable" 
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                    onclick="toggleFullTableView()">
                                <i class="fas fa-expand mr-1"></i> Full Table
                            </button>
                        </div>

                        <form id="rjppDprForm" action="{{ route('admin.operasi-upkd.rjpp-dpr.store') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">TAHUN</th>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">URAIAN</th>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">GOAL</th>
                                            <th colspan="3" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">DEADLINE</th>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">PIC</th>
                                            <th colspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">ANGGARAN</th>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">HARGA</th>
                                            <th colspan="7" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">PROGRESS</th>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">KONDISI EKSISTING</th>
                                            <th rowspan="2" class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">STATUS</th>
                                        </tr>
                                        <tr>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">PERENCANAAN</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">PELAKSANAAN</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">NAGIHA/LAIN</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">AI</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">AO</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">LIST</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">RAB</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">TOR</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">ND DRP</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">MUP</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">RENDAN</th>
                                            <th class="px-3 py-3 bg-[#009BB9] text-center text-xs font-medium text-white uppercase tracking-wider border">KP</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rjppDprTableBody" class="bg-white divide-y divide-gray-200">
                                        @foreach([2025, 2026, 2027, 2028, 2029] as $year)
                                        <tr class="bg-gray-50">
                                            <td class="px-3 py-2 text-sm font-medium text-gray-900 border text-center">{{ $year }}<br>1</td>
                                            <td class="px-3 py-2 border" style="min-width: 200px;">
                                                <textarea name="data[{{ $year }}][1][uraian]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 200px;">
                                                <textarea name="data[{{ $year }}][1][goal]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="date" name="data[{{ $year }}][1][deadline_perencanaan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="date" name="data[{{ $year }}][1][deadline_pelaksanaan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="date" name="data[{{ $year }}][1][deadline_nagiha]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="text" name="data[{{ $year }}][1][pic]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 120px;">
                                                <input type="number" name="data[{{ $year }}][1][anggaran_ai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 120px;">
                                                <input type="number" name="data[{{ $year }}][1][anggaran_ao]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="number" name="data[{{ $year }}][1][harga]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_list]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_rab]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_tor]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_nd_drp]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_mup]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_rendan]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][1][progress_kp]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 200px;">
                                                <textarea name="data[{{ $year }}][1][kondisi_eksisting]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 120px;">
                                                <select name="data[{{ $year }}][1][status]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Pilih Status</option>
                                                    <option value="On Track">On Track</option>
                                                    <option value="Delayed">Delayed</option>
                                                    <option value="Completed">Completed</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="px-3 py-2 text-sm font-medium text-gray-900 border text-center">{{ $year }}<br>2</td>
                                            <td class="px-3 py-2 border" style="min-width: 200px;">
                                                <textarea name="data[{{ $year }}][2][uraian]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 200px;">
                                                <textarea name="data[{{ $year }}][2][goal]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="date" name="data[{{ $year }}][2][deadline_perencanaan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="date" name="data[{{ $year }}][2][deadline_pelaksanaan]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="date" name="data[{{ $year }}][2][deadline_nagiha]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="text" name="data[{{ $year }}][2][pic]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 120px;">
                                                <input type="number" name="data[{{ $year }}][2][anggaran_ai]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 120px;">
                                                <input type="number" name="data[{{ $year }}][2][anggaran_ao]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 150px;">
                                                <input type="number" name="data[{{ $year }}][2][harga]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_list]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_rab]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_tor]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_nd_drp]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_mup]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_rendan]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border text-center" style="min-width: 80px;">
                                                <input type="checkbox" name="data[{{ $year }}][2][progress_kp]" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 200px;">
                                                <textarea name="data[{{ $year }}][2][kondisi_eksisting]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                                            </td>
                                            <td class="px-3 py-2 border" style="min-width: 120px;">
                                                <select name="data[{{ $year }}][2][status]" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                                    <option value="">Pilih Status</option>
                                                    <option value="On Track">On Track</option>
                                                    <option value="Delayed">Delayed</option>
                                                    <option value="Completed">Completed</option>
                                                </select>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Link Back Up Monitoring Section -->
                                <div class="mt-6 border-t pt-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">LINK BACK UP MONITORING :</h3>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-500 mr-2">1.</span>
                                            <input type="text" name="link_monitoring[1]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-500 mr-2">2.</span>
                                            <input type="text" name="link_monitoring[2]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-right">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                    <i class="fas fa-save mr-2"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
let rowCounter = 1;

function addNewRow() {
    // Remove this function since we're using a fixed structure
}

function deleteRow(button) {
    // Remove this function since we're using a fixed structure
}

function updateRowNumbers() {
    // Remove this function since we're using a fixed structure
}

function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const welcomeCard = document.querySelector('.bg-gradient-to-r').closest('.mb-6');
    const successAlert = document.querySelector('.bg-green-100');
    
    const isFullTable = button.classList.contains('bg-blue-600');
    
    if (isFullTable) {
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';
        if (welcomeCard) welcomeCard.style.display = '';
        if (successAlert) successAlert.style.display = '';
    } else {
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';
        if (welcomeCard) welcomeCard.style.display = 'none';
        if (successAlert) successAlert.style.display = 'none';
    }
}

document.getElementById('rjppDprForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            // Optionally refresh the page or clear the form
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    });
});

function exportToPDF() {
    // TODO: Implement PDF export functionality
    alert('Export to PDF functionality will be implemented here');
}

function exportToExcel() {
    // TODO: Implement Excel export functionality
    alert('Export to Excel functionality will be implemented here');
}

document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
    const sidebar = document.querySelector('aside');
    
    mobileMenuBtn?.addEventListener('click', () => {
        sidebar?.classList.toggle('hidden');
    });

    // Add initial row
    addNewRow();
});
</script>
@endpush
@endsection 