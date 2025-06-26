@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Data Rapat</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Rapat & Link Koordinasi RON', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Rapat & Link Koordinasi RON</h2>
                        <p class="text-blue-100 mb-4">Kelola dan pantau rapat serta koordinasi RON untuk memastikan komunikasi yang efektif antar tim.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.operasi-upkd.rapat.create') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </a>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header with Filters -->
                        <div class="mb-4" id="table-controls">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-semibold text-gray-900">Data Rapat</h2>
                                    <button id="toggleFullTable"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                            onclick="toggleFullTableView()">
                                        <i class="fas fa-expand mr-1"></i> Full Table
                                    </button>
                                    @if(request()->has('pic') || request()->has('status') || request()->has('start_date') || request()->has('end_date'))
                                        <div class="flex flex-wrap gap-2" id="active-filters">
                                            @if(request('pic'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    PIC: {{ request('pic') }}
                                                    <button onclick="removeFilter('pic')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('status'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Status: {{ request('status') }}
                                                    <button onclick="removeFilter('status')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('start_date'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Periode: {{ \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') }}
                                                    @if(request('end_date'))
                                                        - {{ \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') }}
                                                    @endif
                                                    <button onclick="removeFilter('start_date'); removeFilter('end_date')" class="ml-1 text-blue-600 hover:text-blue-800">
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
                                <form action="{{ route('admin.operasi-upkd.rapat.index') }}" method="GET"
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">PIC</label>
                                        <select name="pic"
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua PIC</option>
                                            <option value="Asman Operasi" {{ request('pic') == 'Asman Operasi' ? 'selected' : '' }}>Asman Operasi</option>
                                            <option value="TL RON" {{ request('pic') == 'TL RON' ? 'selected' : '' }}>TL RON</option>
                                            <option value="ROHMAT" {{ request('pic') == 'ROHMAT' ? 'selected' : '' }}>ROHMAT</option>
                                            <option value="IMAM" {{ request('pic') == 'IMAM' ? 'selected' : '' }}>IMAM</option>
                                            <option value="KASMAN" {{ request('pic') == 'KASMAN' ? 'selected' : '' }}>KASMAN</option>
                                            <option value="AMINAH" {{ request('pic') == 'AMINAH' ? 'selected' : '' }}>AMINAH</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status"
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="on progress" {{ request('status') == 'on progress' ? 'selected' : '' }}>On Progress</option>
                                            <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="start_date"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('start_date') }}">
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                        <input type="date" name="end_date"
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('end_date') }}">
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            <i class="fas fa-search mr-2"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.operasi-upkd.rapat.index') }}"
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pekerjaan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline Start</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline Finish</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($meetings ?? [] as $index => $meeting)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 border border-gray-200">{{ $meeting->pekerjaan ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 border border-gray-200">{{ $meeting->pic ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-center">{{ $meeting->deadline_start ? date('d/m/Y', strtotime($meeting->deadline_start)) : '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-center">{{ $meeting->deadline_finish ? date('d/m/Y', strtotime($meeting->deadline_finish)) : '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 border border-gray-200">{{ $meeting->kondisi ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $meeting->status === 'open' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $meeting->status === 'on progress' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ $meeting->status === 'closed' ? 'bg-red-100 text-red-800' : '' }}">
                                                {{ $meeting->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium border border-gray-200">
                                            <a href="{{ route('admin.operasi-upkd.rapat.edit', $meeting->id ?? 0) }}"
                                               class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form action="{{ route('admin.operasi-upkd.rapat.destroy', $meeting->id ?? 0) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('Are you sure you want to delete this item?')">
                                                    <i class="fas fa-trash-alt mr-1"></i>Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Tidak ada data yang tersedia
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when changing filters
    const filterForm = document.querySelector('form');
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"]');

    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            filterForm.submit();
        });
    });
});

function removeFilter(filterName) {
    // Get the current URL
    let url = new URL(window.location.href);
    let params = new URLSearchParams(url.search);

    // Remove the specified parameter
    params.delete(filterName);

    // If removing date range, remove both start and end dates
    if (filterName === 'start_date' || filterName === 'end_date') {
        params.delete('start_date');
        params.delete('end_date');
    }

    // Rebuild the URL without the removed parameter
    url.search = params.toString();

    // Navigate to the new URL
    window.location.href = url.toString();
}

// Add full table view toggle functionality
function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const filtersSection = document.getElementById('filters-section');
    const activeFilters = document.getElementById('active-filters');
    const welcomeCard = document.querySelector('.bg-gradient-to-r').parentElement;
    const tableControls = document.getElementById('table-controls');
    const mainContent = document.querySelector('main');

    // Toggle full table mode
    const isFullTable = button.classList.contains('bg-blue-600');

    if (isFullTable) {
        // Restore normal view
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';

        welcomeCard.style.display = 'block';
        tableControls.style.display = 'block';
        if (filtersSection) filtersSection.style.display = 'block';
        if (activeFilters) activeFilters.style.display = 'flex';
        if (mainContent) mainContent.classList.remove('pt-0');

    } else {
        // Enable full table view
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';

        welcomeCard.style.display = 'none';
        tableControls.style.display = 'none';
        if (filtersSection) filtersSection.style.display = 'none';
        if (activeFilters) activeFilters.style.display = 'none';
        if (mainContent) mainContent.classList.add('pt-0');
    }
}
</script>
@endpush

@endsection
