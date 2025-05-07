@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">Pengadaan Barang dan Jasa</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Operasi UPKD', 'url' => null], ['name' => 'Pengadaan Barang dan Jasa', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Pengadaan Barang dan Jasa</h2>
                        <p class="text-blue-100 mb-4">Kelola proses pengadaan barang dan jasa dengan efisien dan transparan untuk mendukung operasional UPKD.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.operasi-upkd.pengadaan.export') }}" 
                               class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </a>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                            <a href="{{ route('admin.operasi-upkd.pengadaan.create') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                                <i class="fas fa-plus mr-2"></i> Tambah Data
                            </a>
                        </div>
                    </div>
                </div>

                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
                @endif

                <!-- Table Section -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6">
                        <!-- Table Header -->
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-900">Data Pengadaan</h2>
                            <button id="toggleFullTable" 
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100"
                                    onclick="toggleFullTableView()">
                                <i class="fas fa-expand mr-1"></i> Full Table
                            </button>
                        </div>

                        <!-- Filter Controls -->
                        <div id="table-controls">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center gap-2">
                                    @if(request()->has('tahun') || request()->has('jenis') || request()->has('status'))
                                        <div class="flex flex-wrap gap-2" id="active-filters">
                                            @if(request('tahun'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Tahun: {{ request('tahun') }}
                                                    <button onclick="removeFilter('tahun')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @if(request('jenis'))
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Jenis: {{ request('jenis') }}
                                                    <button onclick="removeFilter('jenis')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </span>
                                            @endif
                                            @foreach(['pengusulan', 'proses_kontrak', 'pengadaan', 'pekerjaan_fisik', 'pemberkasan', 'pembayaran'] as $status)
                                                @if(request($status))
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ ucfirst(str_replace('_', ' ', $status)) }}: {{ request($status) }}
                                                        <button onclick="removeFilter('{{ $status }}')" class="ml-1 text-blue-600 hover:text-blue-800">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Horizontal Filters -->
                            <div class="mt-2 border-b border-gray-200 pb-4" id="filters-section">
                                <form action="{{ route('admin.operasi-upkd.pengadaan.index') }}" method="GET" 
                                      class="flex flex-wrap items-end gap-4">
                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Tahun</label>
                                        <select name="tahun" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Tahun</option>
                                            @for($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                                    {{ $year }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="w-40">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Jenis</label>
                                        <select name="jenis" 
                                                class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                            <option value="">Semua Jenis</option>
                                            <option value="Rutin" {{ request('jenis') == 'Rutin' ? 'selected' : '' }}>Rutin</option>
                                            <option value="Non Rutin" {{ request('jenis') == 'Non Rutin' ? 'selected' : '' }}>Non Rutin</option>
                                        </select>
                                    </div>

                                    @foreach([
                                        'pengusulan' => 'Status Pengusulan',
                                        'proses_kontrak' => 'Status Kontrak',
                                        'pengadaan' => 'Status Pengadaan',
                                        'pekerjaan_fisik' => 'Status Pekerjaan',
                                        'pemberkasan' => 'Status Berkas',
                                        'pembayaran' => 'Status Pembayaran'
                                    ] as $field => $label)
                                        <div class="w-40">
                                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ $label }}</label>
                                            <select name="{{ $field }}" 
                                                    class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                                <option value="">Semua Status</option>
                                                <option value="Open" {{ request($field) == 'Open' ? 'selected' : '' }}>Open</option>
                                                <option value="Close" {{ request($field) == 'Close' ? 'selected' : '' }}>Close</option>
                                                <option value="On Progress" {{ request($field) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                            </select>
                                        </div>
                                    @endforeach

                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            <i class="fas fa-search mr-2"></i> Cari
                                        </button>
                                        <a href="{{ route('admin.operasi-upkd.pengadaan.index') }}" 
                                           class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">No</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Item Pekerjaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Tahun</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Nilai Kontrak</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">No. PRK</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Jenis</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Intensitas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Pengusulan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Proses Kontrak</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Pengadaan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Pekerjaan Fisik</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Pemberkasan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Pembayaran</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Keterangan</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border border-gray-200">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($pengadaan as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center border border-gray-200">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $item->judul }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $item->tahun }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ number_format($item->nilai_kontrak, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $item->no_prk }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $item->jenis }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $item->intensitas }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->pengusulan == 'Close' ? 'bg-gray-100 text-gray-800' : 
                                                   ($item->pengusulan == 'On Progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $item->pengusulan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->proses_kontrak == 'Close' ? 'bg-gray-100 text-gray-800' : 
                                                   ($item->proses_kontrak == 'On Progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $item->proses_kontrak }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->pengadaan == 'Close' ? 'bg-gray-100 text-gray-800' : 
                                                   ($item->pengadaan == 'On Progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $item->pengadaan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->pekerjaan_fisik == 'Close' ? 'bg-gray-100 text-gray-800' : 
                                                   ($item->pekerjaan_fisik == 'On Progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $item->pekerjaan_fisik }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->pemberkasan == 'Close' ? 'bg-gray-100 text-gray-800' : 
                                                   ($item->pemberkasan == 'On Progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $item->pemberkasan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->pembayaran == 'Close' ? 'bg-gray-100 text-gray-800' : 
                                                   ($item->pembayaran == 'On Progress' ? 'bg-yellow-100 text-yellow-800' : 
                                                   'bg-green-100 text-green-800') }}">
                                                {{ $item->pembayaran }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ $item->keterangan }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('admin.operasi-upkd.pengadaan.edit', $item->id) }}" 
                                                   class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.operasi-upkd.pengadaan.destroy', $item->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="14" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center border border-gray-200">Tidak ada data</td>
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
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when changing filters
    const filterForm = document.querySelector('form');
    const filterInputs = filterForm.querySelectorAll('select');

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

// Add full table view toggle functionality
function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const tableControls = document.getElementById('table-controls');
    const welcomeCard = document.querySelector('.bg-gradient-to-r').closest('.mb-6');
    const successAlert = document.querySelector('.bg-green-100');
    
    // Toggle full table mode
    const isFullTable = button.classList.contains('bg-blue-600');
    
    if (isFullTable) {
        // Restore normal view
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';
        
        if (tableControls) tableControls.style.display = '';
        if (welcomeCard) welcomeCard.style.display = '';
        if (successAlert) successAlert.style.display = '';
        
    } else {
        // Enable full table view
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';
        
        if (tableControls) tableControls.style.display = 'none';
        if (welcomeCard) welcomeCard.style.display = 'none';
        if (successAlert) successAlert.style.display = 'none';
    }
}
</script>
@endpush
@endsection 