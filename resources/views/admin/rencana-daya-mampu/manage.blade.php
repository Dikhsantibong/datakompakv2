@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto">
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

        <div class="p-6">
            <div class="bg-white rounded-lg shadow p-6">
                <!-- Filter Section -->
                <div class="mb-6">
                    <div class="flex flex-col gap-4">
                        <!-- Unit Filter (Only show for UP Kendari users) -->
                        @if(session('unit') === 'mysql')
                        <div class="w-full p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Pilih Sistem</h3>
                            <select name="unit_source" 
                                    id="unit-source" 
                                    class="w-full md:w-64 border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                    style="width: 130px;"
                                    onchange="updateFilter()">
                                <option value="mysql" {{ $unitSource == 'mysql' ? 'selected' : '' }}>UP Kendari</option>
                                <option value="mysql_wua_wua" {{ $unitSource == 'mysql_wua_wua' ? 'selected' : '' }}>Wua Wua</option>
                                <option value="mysql_poasia" {{ $unitSource == 'mysql_poasia' ? 'selected' : '' }}>Poasia</option>
                                <option value="mysql_kolaka" {{ $unitSource == 'mysql_kolaka' ? 'selected' : '' }}>Kolaka</option>
                                <option value="mysql_bau_bau" {{ $unitSource == 'mysql_bau_bau' ? 'selected' : '' }}>Bau Bau</option>
                            </select>
                        </div>
                        @endif

                        <!-- Date Filter and Export Buttons -->
                        <div class="flex flex-wrap gap-4 items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex flex-wrap items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <label for="month" class="font-medium min-w-24">Bulan:</label>
                                    <select name="month" 
                                            id="month" 
                                            class="border rounded px-3 py-2 w-40 focus:ring-2 focus:ring-blue-500"
                                            onchange="updateFilter()">
                                        @foreach(range(1, 12) as $month)
                                            <option value="{{ sprintf('%02d', $month) }}" 
                                                    {{ $selectedMonth == sprintf('%02d', $month) ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-center gap-2">
                                    <label for="year" class="font-medium min-w-24">Tahun:</label>
                                    <select name="year" 
                                            id="year" 
                                            class="border rounded px-3 py-2 w-32 focus:ring-2 focus:ring-blue-500"
                                            onchange="updateFilter()">
                                        @foreach(range(date('Y')-5, date('Y')+5) as $year)
                                            <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                                {{ $year }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Export Buttons -->
                            <div class="flex gap-2">
                                <button type="button" 
                                        onclick="exportData('pdf')" 
                                        class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 transition-colors duration-200 flex items-center gap-2">
                                    <i class="fas fa-file-pdf"></i> PDF
                                </button>
                                <button type="button" 
                                        onclick="exportData('excel')" 
                                        class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 transition-colors duration-200 flex items-center gap-2">
                                    <i class="fas fa-file-excel"></i> Excel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border-2">
                        <thead class="bg-gray-50">
                            <tr>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50 text-center border-r-2">No</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-16 bg-gray-50 text-center border-r-2">Sistem Kelistrikan</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r-2">Mesin Pembangkit</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r-2">Site Pembangkit</th>
                                <th colspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r-2">Rencana Realisasi</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r-2">Daya PJBTL SILM</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r-2">DMP Existing</th>
                                <th colspan="{{ date('t', strtotime($selectedYear.'-'.$selectedMonth.'-01')) }}" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">Rencana</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">Realisasi</th>
                                @for ($i = 1; $i <= date('t', strtotime($selectedYear.'-'.$selectedMonth.'-01')); $i++)
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @foreach($powerPlants as $plant)
                                @foreach($plant->machines as $machine)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white text-center">{{ $no++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $machine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $machine->rencana ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $machine->realisasi ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $machine->daya_pjbtl_silm ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">{{ $machine->dmp_existing ?? '-' }}</td>
                                        @for ($i = 1; $i <= date('t', strtotime($selectedYear.'-'.$selectedMonth.'-01')); $i++)
                                            @php
                                                $date = $selectedYear . '-' . $selectedMonth . '-' . sprintf('%02d', $i);
                                                $dailyValue = $machine->rencanaDayaMampu->first()?->getDailyValue($date, 'rencana');
                                            @endphp
                                            <td class="px-6 py-4 whitespace-nowrap text-center border-r">{{ $dailyValue ?? '-' }}</td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFilter() {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const unitSource = document.getElementById('unit-source')?.value || '';

    // Construct URL with current filter values
    let url = new URL(window.location.href);
    url.searchParams.set('month', month);
    url.searchParams.set('year', year);
    if (unitSource) {
        url.searchParams.set('unit_source', unitSource);
    }

    // Navigate to the new URL
    window.location.href = url.toString();
}

function exportData(format) {
    const month = document.getElementById('month').value;
    const year = document.getElementById('year').value;
    const unitSource = document.getElementById('unit-source')?.value || '';

    window.location.href = `{{ route('admin.rencana-daya-mampu.export') }}?format=${format}&month=${month}&year=${year}&unit_source=${unitSource}`;
}

document.addEventListener('DOMContentLoaded', function() {
    // Add any additional JavaScript functionality here
});
</script>
@endsection 