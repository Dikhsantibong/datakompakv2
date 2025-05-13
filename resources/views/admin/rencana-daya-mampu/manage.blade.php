@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto">
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

                    <h1 class="text-xl font-semibold text-gray-800">Manajemen Data Rencana Daya Mampu</h1>
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

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Manajemen Data Rencana Daya Mampu</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor rencana daya mampu untuk optimasi operasional pembangkit listrik.</p>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" 
                                    onclick="exportData('pdf')" 
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </button>
                            <button type="button" 
                                    onclick="exportData('excel')" 
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </button>
                            <a href="{{ route('admin.rencana-daya-mampu') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <form class="flex flex-wrap items-end gap-4">
                        @if(session('unit') === 'mysql')
                        <div class="w-64">
                            <label for="unit-source" class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="unit_source" 
                                    id="unit-source" 
                                    class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    onchange="updateFilter()">
                                <option value="">-- Pilih Unit --</option>
                                @foreach($allPowerPlants as $plant)
                                    <option value="{{ $plant->unit_source }}" {{ $unitSource == $plant->unit_source ? 'selected' : '' }}>
                                        {{ $plant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="month" 
                                    id="month" 
                                    class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    onchange="updateFilter()">
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ sprintf('%02d', $month) }}" 
                                            {{ $selectedMonth == sprintf('%02d', $month) ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-40">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                            <select name="year" 
                                    id="year" 
                                    class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    onchange="updateFilter()">
                                @foreach(range(date('Y')-5, date('Y')+5) as $year)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="updateFilter()"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                <i class="fas fa-search mr-2"></i> Cari
                            </button>
                            <a href="{{ route('admin.rencana-daya-mampu.manage') }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                <i class="fas fa-undo mr-2"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Table Content -->
                @if(!$unitSource)
                <div class="flex items-center justify-center h-64">
                    <div class="text-center">
                        <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
                        <h2 class="text-xl font-semibold text-gray-600">Silakan Pilih Unit</h2>
                        <p class="text-gray-500 mt-2">Pilih unit untuk melihat data Rencana Daya Mampu</p>
                    </div>
                </div>
                @else
                <div class="overflow-x-auto border border-gray-200 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider sticky left-0 bg-gray-50 border-r-2 border-b z-20">No</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider sticky left-16 bg-gray-50 border-r-2 border-b z-20">Sistem Kelistrikan</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r-2 border-b">Mesin Pembangkit</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r-2 border-b">DMN SLO</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r-2 border-b">DMP PT</th>
                                @for ($i = 1; $i <= date('t', strtotime($selectedYear.'-'.$selectedMonth.'-01')); $i++)
                                    <th colspan="2" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 border-b">{{ $i }}</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($i = 1; $i <= date('t', strtotime($selectedYear.'-'.$selectedMonth.'-01')); $i++)
                                    <th class="px-2 py-2 text-center text-xs font-semibold text-blue-700 uppercase tracking-wider border-r">Rencana</th>
                                    <th class="px-2 py-2 text-center text-xs font-semibold text-green-700 uppercase tracking-wider border-r">Realisasi</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @foreach($powerPlants as $plant)
                                @foreach($plant->machines as $machine)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white border-r-2 text-center">{{ $no++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white border-r-2">
                                            <div class="truncate max-w-[150px]">{{ $plant->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r-2">{{ $machine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r-2 text-center">{{ $machine->dmn_slo ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r-2 text-center">{{ $machine->dmp_pt ?? '-' }}</td>
                                        @for ($i = 1; $i <= date('t', strtotime($selectedYear.'-'.$selectedMonth.'-01')); $i++)
                                            @php
                                                $date = $selectedYear . '-' . $selectedMonth . '-' . sprintf('%02d', $i);
                                                $data = $machine->rencanaDayaMampu->first()?->getDailyValue($date) ?? [];
                                            @endphp
                                            <td class="px-2 py-2 align-top border-r min-w-[180px]">
                                                <div class="border rounded-lg bg-blue-50 shadow-sm p-2">
                                                    <div class="text-xs font-bold text-blue-800 py-1 border-b border-blue-200 text-center tracking-wide uppercase">Rencana</div>
                                                    <table class="w-full text-xs border-separate border-spacing-0">
                                                        <thead>
                                                            <tr class="bg-blue-100 text-blue-900 font-semibold">
                                                                <th class="border px-2 py-1">Beban</th>
                                                                <th class="border px-2 py-1">On</th>
                                                                <th class="border px-2 py-1">Off</th>
                                                                <th class="border px-2 py-1">Durasi</th>
                                                                <th class="border px-2 py-1">Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $rencanaRows = $data['rencana'] ?? [];
                                                            @endphp
                                                            @forelse($rencanaRows as $row)
                                                            <tr class="transition-colors duration-150 hover:bg-blue-200">
                                                                <td class="border px-2 py-1 text-center">
                                                                    <span class="px-2 py-1 bg-blue-100 rounded-full text-blue-800">
                                                                        {{ $row['beban'] ?? '-' }}
                                                                    </span>
                                                                </td>
                                                                <td class="border px-2 py-1 text-center">{{ $row['on'] ? \Carbon\Carbon::parse($row['on'])->format('H:i') : '-' }}</td>
                                                                <td class="border px-2 py-1 text-center">{{ $row['off'] ? \Carbon\Carbon::parse($row['off'])->format('H:i') : '-' }}</td>
                                                                <td class="border px-2 py-1 text-center">{{ $row['durasi'] ? number_format($row['durasi'], 2) : '-' }}</td>
                                                                <td class="border px-2 py-1 text-center">
                                                                    <span class="inline-block max-w-[120px] truncate" title="{{ $row['keterangan'] ?? '' }}">
                                                                        {{ $row['keterangan'] ?? '-' }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                            @empty
                                                            <tr>
                                                                <td colspan="5" class="border px-2 py-2 text-center text-gray-500">
                                                                    <i class="fas fa-info-circle mr-1"></i> Belum ada data
                                                                </td>
                                                            </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                            <td class="px-2 py-2 align-top border-r min-w-[120px]">
                                                <div class="border rounded-lg bg-green-50 shadow-sm p-2">
                                                    <div class="text-xs font-bold text-green-800 py-1 border-b border-green-200 text-center tracking-wide uppercase">Realisasi</div>
                                                    <table class="w-full text-xs border-separate border-spacing-0">
                                                        <thead>
                                                            <tr class="bg-green-100 text-green-900 font-semibold">
                                                                <th class="border px-2 py-1">Beban</th>
                                                                <th class="border px-2 py-1">Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php
                                                                $realisasi = $data['realisasi'] ?? [];
                                                                if (!is_array($realisasi)) {
                                                                    $realisasi = [$realisasi];
                                                                }
                                                            @endphp
                                                            @forelse($realisasi as $row)
                                                                <tr class="transition-colors duration-150 hover:bg-green-200">
                                                                    <td class="border px-2 py-1 text-center">
                                                                        @if(!empty($row['beban']))
                                                                            <span class="px-2 py-1 bg-green-100 rounded-full text-green-800">
                                                                                {{ $row['beban'] }}
                                                                            </span>
                                                                        @else
                                                                            <span class="text-gray-400">-</span>
                                                                        @endif
                                                                    </td>
                                                                    <td class="border px-2 py-1 text-center">
                                                                        <span class="inline-block max-w-[120px] truncate" title="{{ $row['keterangan'] ?? '' }}">
                                                                            {{ $row['keterangan'] ?? '-' }}
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="2" class="border px-2 py-2 text-center text-gray-500">
                                                                        <i class="fas fa-info-circle mr-1"></i> Belum ada data
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </main>
    </div>
</div>

<script>
function updateFilter() {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const unitSource = document.getElementById('unit-source')?.value || '';

    let url = new URL(window.location.href);
    url.searchParams.set('month', month);
    url.searchParams.set('year', year);
    if (unitSource) {
        url.searchParams.set('unit_source', unitSource);
    }

    window.location.href = url.toString();
}

function exportData(format) {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const unitSource = document.getElementById('unit-source')?.value || '';

    window.location.href = `{{ route('admin.rencana-daya-mampu.export') }}?format=${format}&month=${month}&year=${year}&unit_source=${unitSource}`;
}

function formatTime(timeString) {
    if (!timeString) return '-';
    return timeString.substring(0, 5); // Format HH:mm
}

function formatDurasi(durasi) {
    if (!durasi) return '-';
    return parseFloat(durasi).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function() {
    // Format waktu yang ditampilkan
    document.querySelectorAll('td[data-time]').forEach(td => {
        td.textContent = formatTime(td.dataset.time);
    });

    // Format durasi yang ditampilkan
    document.querySelectorAll('td[data-durasi]').forEach(td => {
        td.textContent = formatDurasi(td.dataset.durasi);
    });

    // Tambahkan tooltip untuk keterangan yang panjang
    document.querySelectorAll('td[data-keterangan]').forEach(td => {
        if (td.textContent.length > 20) {
            td.title = td.textContent;
            td.textContent = td.textContent.substring(0, 20) + '...';
        }
    });
});
</script>
@endsection 