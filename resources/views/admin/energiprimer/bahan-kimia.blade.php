@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
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
                    <h1 class="text-xl font-semibold text-gray-900">Data Bahan Kimia</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Data Bahan Kimia', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Manajemen Data Bahan Kimia</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor penggunaan bahan kimia untuk optimasi operasional pembangkit listrik.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.energiprimer.bahan-kimia.export-excel', request()->query()) }}" 
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Excel
                            </a>
                            <a href="{{ route('admin.energiprimer.bahan-kimia.export-pdf', request()->query()) }}" 
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50">
                                <i class="fas fa-file-pdf mr-2"></i> PDF
                            </a>
                            <a href="{{ route('admin.energiprimer.bahan-kimia.create') }}" 
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-plus mr-2"></i> Tambah
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Table Header with Filters -->
                        <div class="mb-4">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    <h2 class="text-lg font-semibold text-gray-900">Data Bahan Kimia</h2>
                                    <button id="toggleFullTable" 
                                            class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                            onclick="toggleFullTableView()">
                                        <i class="fas fa-expand mr-1"></i> Full Table
                                    </button>
                                    @if(request()->has('unit_id') || request()->has('jenis_bahan') || request()->has('start_date') || request()->has('end_date'))
                                        <div class="flex flex-wrap gap-2" id="active-filters">
                                            @if(request('unit_id'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Unit: {{ $units->find(request('unit_id'))->name }}
                                                    <button onclick="removeFilter('unit_id')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('jenis_bahan'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Bahan: {{ request('jenis_bahan') }}
                                                    <button onclick="removeFilter('jenis_bahan')" class="ml-1 text-blue-600 hover:text-blue-800">
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
                                <form action="{{ route('admin.energiprimer.bahan-kimia') }}" method="GET" 
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                        <select name="unit_id" class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                                    {{ $unit->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis Bahan</label>
                                        <input type="text" name="jenis_bahan" 
                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                               value="{{ request('jenis_bahan') }}"
                                               placeholder="Cari jenis...">
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
                                        <a href="{{ route('admin.energiprimer.bahan-kimia') }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No.</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Unit</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis centerahan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Awal</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Penerimaan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Pemakaian</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Akhir</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Eviden</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bahanKimia as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $item->tanggal->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $item->unit->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ $item->jenis_bahan }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->saldo_awal, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->penerimaan, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->pemakaian, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            {{ number_format($item->saldo_akhir, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 border border-gray-200">
                                            {{ $item->catatan_transaksi }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border border-gray-200">
                                            @if($item->evidence)
                                                @php
                                                    $extension = pathinfo($item->evidence, PATHINFO_EXTENSION);
                                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                                    $isPdf = strtolower($extension) === 'pdf';
                                                    $isWord = in_array(strtolower($extension), ['doc', 'docx']);
                                                @endphp
                                                @if($isImage)
                                                    <a href="{{ Storage::url($item->evidence) }}" 
                                                       target="_blank"
                                                       class="group relative inline-block">
                                                        <img src="{{ Storage::url($item->evidence) }}" 
                                                             alt="Preview" 
                                                             class="h-20 w-32 object-cover rounded mx-auto"
                                                             onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                                        <div class="hidden group-hover:block absolute z-10 p-2 bg-gray-800 text-white text-xs rounded mt-1">
                                                            Klik untuk melihat gambar
                                                        </div>
                                                    </a>
                                                @elseif($isPdf)
                                                    <a href="{{ Storage::url($item->evidence) }}" target="_blank" class="flex items-center text-red-600 hover:text-red-800 justify-center">
                                                        <i class="fas fa-file-pdf fa-lg mr-1"></i> Lihat PDF
                                                    </a>
                                                @elseif($isWord)
                                                    <a href="{{ Storage::url($item->evidence) }}" target="_blank" class="flex items-center text-blue-700 hover:text-blue-900 justify-center">
                                                        <i class="fas fa-file-word fa-lg mr-1"></i> Download Word
                                                    </a>
                                                @else
                                                    <a href="{{ Storage::url($item->evidence) }}" target="_blank" class="flex items-center text-gray-600 hover:text-gray-900 justify-center">
                                                        <i class="fas fa-file-download fa-lg mr-1"></i> Download File
                                                    </a>
                                                @endif
                                            @else
                                                <span class="text-gray-400">Tidak ada dokumen</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-3">
                                                <a href="{{ route('admin.energiprimer.bahan-kimia.edit', $item->id) }}" 
                                                   class="inline-flex items-center px-3 py-1 text-sm font-medium text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit mr-1"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.energiprimer.bahan-kimia.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      class="inline-block"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection

@push('scripts')
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
    const welcomeCard = document.querySelector('.welcome-card')?.parentElement;
    const mainContent = document.querySelector('main');
    
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