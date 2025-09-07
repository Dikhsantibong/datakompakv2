@extends('layouts.app')
@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    <div id="main-content" class="flex-1 main-content">
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

                    <h1 class="text-xl font-semibold text-gray-800">Hasil Ikhtisar Harian</h1>
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
        <div class="px-6 py-8">
            <form method="GET" action="" class="mb-6 flex flex-col md:flex-row gap-4 items-end">
                <div>
                    <label for="bulan" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Bulan</label>
                    <input type="month" name="bulan" id="bulan" value="{{ $bulan }}" class="border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>
                @if($isMain)
                <div>
                    <label for="unit" class="block text-sm font-semibold text-gray-700 mb-1">Pilih Unit</label>
                    <select name="unit" id="unit" class="border border-gray-300 rounded-md px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <option value="">Semua Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ $unitFilter == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-blue-600 text-white text-base font-semibold rounded-lg shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                    <i class="fas fa-search"></i> Filter
                </button>
            <a href="{{ route('daily-summary.import-excel') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-green-600 text-white text-base font-semibold rounded-lg shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all">
                <i class="fas fa-file-excel"></i> Import Data Excel
            </a>
            </form>
            <h3 class="text-lg font-semibold mb-2">Data per Hari:</h3>
            @if(!$isMain)
            <div class="flex flex-wrap gap-2 mb-4">
                @for($d=1; $d<=$daysInMonth; $d++)
                    @php $tabName = str_pad($d, 2, '0', STR_PAD_LEFT); @endphp
                    <button type="button"
                        class="tab-btn border px-3 py-1 rounded {{ $d==1 ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500' }}"
                        data-tab="tab-{{ $tabName }}">
                        {{ $tabName . '-' . $carbonBulan->format('m-Y') }}
                    </button>
                @endfor
            </div>
            @endif
            <div>
                @if($isMain)
                    @foreach(($unitFilter ? [$unitFilter] : array_keys($summaries)) as $unitId)
                        @php $unit = $units->firstWhere('id', $unitId); @endphp
                        <div class="mb-8">
                            <div class="font-bold text-lg mb-2 text-blue-700">{{ $unit ? $unit->name : 'Unit Tidak Diketahui' }}</div>
                            @for($d=1; $d<=$daysInMonth; $d++)
                                @php $tabName = str_pad($d, 2, '0', STR_PAD_LEFT); $rows = $summaries[$unitId][$tabName] ?? collect(); @endphp
                                @if(count($rows))
                                    <div class="mb-2 font-semibold text-gray-600">Tanggal: {{ $tabName . '-' . $carbonBulan->format('m-Y') }}</div>
                                    @include('admin.daily-summary._result_table', ['rows' => $rows, 'tabName' => $tabName])
                                @endif
                            @endfor
                        </div>
                    @endforeach
                @else
                    <div>
                        @for($d=1; $d<=$daysInMonth; $d++)
                            @php $tabName = str_pad($d, 2, '0', STR_PAD_LEFT); @endphp
                            <div class="tab-content" id="tab-{{ $tabName }}" style="display: {{ $d==1 ? 'block' : 'none' }};">
                                @include('admin.daily-summary._result_table', ['rows' => $summaries[$tabName] ?? collect(), 'tabName' => $tabName])
                            </div>
                        @endfor
                    </div>
                @endif
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const tabBtns = document.querySelectorAll('.tab-btn');
                    const tabContents = document.querySelectorAll('.tab-content');
                    tabBtns.forEach(btn => {
                        btn.addEventListener('click', function() {
                            tabBtns.forEach(b => b.classList.remove('border-blue-600', 'text-blue-600'));
                            tabBtns.forEach(b => b.classList.add('border-transparent', 'text-gray-500'));
                            tabContents.forEach(tc => tc.style.display = 'none');
                            this.classList.add('border-blue-600', 'text-blue-600');
                            this.classList.remove('border-transparent', 'text-gray-500');
                            document.getElementById(this.dataset.tab).style.display = 'block';
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>
@endsection
