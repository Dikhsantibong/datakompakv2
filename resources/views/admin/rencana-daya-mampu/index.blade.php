@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
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
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <!--  Menu Toggle Sidebar-->
                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Rencana Daya Mampu</h1>
                </div>

                <div class="relative">
                    <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                        <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                            class="w-7 h-7 rounded-full mr-2">
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                    </button>
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                        <a href="{{ route('logout') }}" 
                           class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                           onclick="event.preventDefault(); 
                                    document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Rencana Daya Mampu', 'url' => null]
            ]" />
        </div>

        <!-- Table Container -->
        <div class="p-6">
            <div class="overflow-x-auto bg-white rounded-lg shadow p-6 mb-4" style="max-width: 100%;">
                <h1 class="text-2xl font-bold mb-4">Rencana Operasi Bulanan (ROB)</h1>
                <!-- Unit Filter (Only show for UP Kendari users) -->
                @if(session('unit') === 'mysql')
                <div class="p-4 border-b">
                    <select id="unit-source" 
                            class="border rounded px-3 py-2 text-sm"
                            onchange="updateTable()">
                        <option value="mysql" {{ $unitSource == 'mysql' ? 'selected' : '' }}>UP Kendari</option>
                        <option value="mysql_wua_wua" {{ $unitSource == 'mysql_wua_wua' ? 'selected' : '' }}>Wua Wua</option>
                        <option value="mysql_poasia" {{ $unitSource == 'mysql_poasia' ? 'selected' : '' }}>Poasia</option>
                        <option value="mysql_kolaka" {{ $unitSource == 'mysql_kolaka' ? 'selected' : '' }}>Kolaka</option>
                        <option value="mysql_bau_bau" {{ $unitSource == 'mysql_bau_bau' ? 'selected' : '' }}>Bau Bau</option>
                    </select>
                </div>
                @endif

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
                                <th colspan="{{ date('t') }}" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">Rencana</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center border-r">Realisasi</th>
                                @for ($i = 1; $i <= date('t'); $i++)
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
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $machine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">-</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">-</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">-</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">-</td>
                                        @for ($i = 1; $i <= date('t'); $i++)
                                            <td class="px-6 py-4 whitespace-nowrap text-center border-r-2">-</td>
                                        @endfor
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                </div>
            </div>
        </div>

<!-- Add this script at the bottom of your file -->
<script src="{{asset('js/toggle.js')}}"></script>

<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        document.querySelector('[sidebar]').classList.toggle('hidden');
    });

    function updateTable() {
        const unitSource = document.getElementById('unit-source').value;
        window.location.href = `{{ route('admin.rencana-daya-mampu') }}?unit_source=${unitSource}`;
    }
</script>
@endsection 