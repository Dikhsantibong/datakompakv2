@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Daftar Laporan</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Laporan Abnormal/Gangguan', 'url' => route('admin.abnormal-report.index')], ['name' => 'Daftar', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto  px-8">
                <!-- Success Message -->
                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative welcome-card">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Laporan Abnormal/Gangguan</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor laporan abnormal atau gangguan untuk memastikan penanganan yang tepat dan cepat.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.abnormal-report.index') }}"
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-plus mr-2"></i> Tambah Laporan
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header with Filters -->
                        <div class="mb-4" id="table-controls">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-semibold text-gray-900">Daftar Laporan</h2>
                                    <button id="toggleFullTable"
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                            onclick="toggleFullTableView()">
                                        <i class="fas fa-expand mr-1"></i> Full Table
                                    </button>
                                    @if(request()->has('start_date') || request()->has('end_date') || request()->has('status') || request()->has('search'))
                                        <div class="flex flex-wrap gap-2" id="active-filters">
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
                                            @if(request('status'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Status: {{ request('status') }}
                                                    <button onclick="removeFilter('status')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('search'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Pencarian: {{ request('search') }}
                                                    <button onclick="removeFilter('search')" class="ml-1 text-blue-600 hover:text-blue-800">
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
                                <form action="{{ route('admin.abnormal-report.list') }}" method="GET"
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                        <select name="status"
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Status</option>
                                            <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                            <option value="Abnormal" {{ request('status') == 'Abnormal' ? 'selected' : '' }}>Abnormal</option>
                                            <option value="Normal" {{ request('status') == 'Normal' ? 'selected' : '' }}>Normal</option>
                                        </select>
                                    </div>

                                    {{-- <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Asal Unit</label>
                                        <select name="unit_origin"
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Unit</option>
                                            <option value="UP Kendari" {{ request('unit_origin') == 'UP Kendari' ? 'selected' : '' }}>UP Kendari</option>
                                            <option value="UP Sulsel" {{ request('unit_origin') == 'UP Sulsel' ? 'selected' : '' }}>UP Sulsel</option>
                                            <option value="UP Sulut" {{ request('unit_origin') == 'UP Sulut' ? 'selected' : '' }}>UP Sulut</option>
                                        </select>
                                    </div> --}}

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

                                    <div class="w-64">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Pencarian</label>
                                        <input type="text" name="search"
                                               placeholder="Cari mesin/peralatan..."
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('search') }}">
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            <i class="fas fa-search mr-2"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.abnormal-report.list') }}"
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
                                        <th class="px-6 py-3  text-xs font-medium text-gray-500 uppercase tracking-wider text-center">No</th>
                                        <th class="px-6 py-3  text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Tanggal</th>
                                        <th class="px-6 py-3  text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Mesin/Peralatan</th>
                                        <th class="px-6 py-3  text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Dibuat Oleh</th>
                                        <th class="px-6 py-3  text-xs font-medium text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($reports as $index => $report)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-200">
                                            {{ ($reports->currentPage() - 1) * $reports->perPage() + $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center border border-gray-200">
                                            {{ $report->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 text-center border border-gray-200">
                                            @foreach($report->affectedMachines as $machine)
                                                <div class="mb-1">{{ $machine->nama_mesin }}</div>
                                            @endforeach
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $report->sync_unit_origin ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center border border-gray-200">
                                            <a href="{{ route('admin.abnormal-report.show', $report->id) }}"
                                               class="text-blue-600 hover:text-blue-900 mr-3"
                                               title="Lihat Detail">
                                                <i class="fas fa-eye mr-1"></i>Detail
                                            </a>
                                            <a href="{{ route('admin.abnormal-report.edit', $report->id) }}"
                                               class="text-yellow-600 hover:text-yellow-900 mr-3"
                                               title="Edit">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <a href="{{ route('admin.abnormal-report.export-excel', $report->id) }}"
                                               class="text-green-600 hover:text-green-900 mr-3"
                                               title="Export Excel">
                                                <i class="fas fa-file-excel mr-1"></i>Excel
                                            </a>
                                            <a href="{{ route('admin.abnormal-report.export-pdf', $report->id) }}"
                                               class="text-red-600 hover:text-red-900 mr-3"
                                               title="Export PDF">
                                                <i class="fas fa-file-pdf mr-1"></i>PDF
                                            </a>
                                            <form action="{{ route('admin.abnormal-report.destroy', $report->id) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        title="Hapus">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data laporan
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4 flex justify-between items-center">
                            <div class="text-sm text-gray-500">
                                Menampilkan {{ $reports->firstItem() ?? 0 }} sampai {{ $reports->lastItem() ?? 0 }} dari {{ $reports->total() }} data
                            </div>
                            <div class="flex justify-end">
                                {{ $reports->links() }}
                            </div>
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
    const filterInputs = filterForm.querySelectorAll('select, input[type="date"], input[type="text"]');

    filterInputs.forEach(input => {
        input.addEventListener('change', () => {
            filterForm.submit();
        });
    });
});

function removeFilter(filterName) {
    const form = document.querySelector('form');
    const input = form.querySelector(`[name="${filterName}"]`);
    if (input) {
        input.value = '';
        form.submit();
    }
}

function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const filtersSection = document.getElementById('filters-section');
    const activeFilters = document.getElementById('active-filters');
    const welcomeCard = document.querySelector('.welcome-card');
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
</script>
@endpush

@endsection