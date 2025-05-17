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

                    <h1 class="text-xl font-semibold text-gray-800">Laporan KIT 00.00</h1>
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

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Info Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6 border border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Laporan KIT 00.00</h2>
                    <div class="flex flex-wrap gap-4 items-center text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-calendar mr-2 text-blue-500"></i>
                            {{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-industry mr-2 text-green-500"></i>
                            Unit: {{ $laporan->powerPlant->name ?? '-' }}
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-user mr-2 text-purple-500"></i>
                            {{ $laporan->creator->name ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Jam Operasi Mesin -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Jam Operasi Mesin</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ops</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Har</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ggn</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stby/Rsh</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam/Hari</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->jamOperasi as $row)
                                    <tr>
                                        <td class="px-6 py-4 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->ops }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->har }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->ggn }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->stby }}</td>
                                        <td class="px-6 py-4">{{ $row->jam_hari }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Jenis Gangguan Mesin -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Jenis Gangguan Mesin</h3>
                        </div>
                        <div class="overflow-x-auto">
                            @php
                            $filteredGangguan = $laporan->gangguan->filter(function($g) {
                                return !is_null($g->mekanik) || !is_null($g->elektrik);
                            });
                            @endphp
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mekanik</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Elektrik</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($filteredGangguan as $row)
                                    <tr>
                                        <td class="px-6 py-4 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->mekanik }}</td>
                                        <td class="px-6 py-4 border-r">{{ $row->elektrik }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Data Pemeriksaan BBM -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan BBM</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Storage Tank 1 (cm)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Storage Tank 1 (liter)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Storage Tank 2 (cm)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Storage Tank 2 (liter)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Tank 1 (liter)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Tank 1 (%)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Tank 2 (liter)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Service Tank 2 (%)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok Tangki</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Terima BBM</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter 1 Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter 1 Akhir</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter 1 Pakai</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter 2 Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter 2 Akhir</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Flowmeter 2 Pakai</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pakai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->bbm as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $row->storage_tank_1_cm }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->storage_tank_1_liter }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->storage_tank_2_cm }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->storage_tank_2_liter }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_stok }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->service_tank_1_liter }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->service_tank_1_percentage }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->service_tank_2_liter }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->service_tank_2_percentage }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_stok_tangki }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->terima_bbm }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->flowmeter_1_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->flowmeter_1_akhir }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->flowmeter_1_pakai }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->flowmeter_2_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->flowmeter_2_akhir }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->flowmeter_2_pakai }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_pakai }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="18" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Data Pemeriksaan KWH -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan KWH</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Prod Panel1 Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Prod Panel1 Akhir</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Prod Panel2 Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Prod Panel2 Akhir</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Prod Total</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">PS Panel1 Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">PS Panel1 Akhir</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">PS Panel2 Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">PS Panel2 Akhir</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">PS Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->kwh as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $row->prod_panel1_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->prod_panel1_akhir }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->prod_panel2_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->prod_panel2_akhir }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->prod_total }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->ps_panel1_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->ps_panel1_akhir }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->ps_panel2_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->ps_panel2_akhir }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->ps_total }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="10" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Data Pemeriksaan Pelumas -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Data Pemeriksaan Pelumas</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tank1 (cm)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tank1 (liter)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tank2 (cm)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tank2 (liter)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Tank Total Stok</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Drum Area1</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Drum Area2</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Drum Total Stok</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Stok Tangki</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Terima Pelumas</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pakai</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->pelumas as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $row->tank1_cm }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->tank1_liter }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->tank2_cm }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->tank2_liter }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->tank_total_stok }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->drum_area1 }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->drum_area2 }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->drum_total_stok }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_stok_tangki }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->terima_pelumas }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_pakai }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->jenis }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="12" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pemeriksaan Bahan Kimia -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Pemeriksaan Bahan Kimia</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Bahan Kimia</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Stok Awal</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Terima</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pakai</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->bahanKimia as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->jenis }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->stok_awal }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->terima }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->total_pakai }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Beban Tertinggi Harian -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Beban Tertinggi Harian</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Siang (07:00-17:00)</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Malam (18:00-06:00)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($laporan->bebanTertinggi as $row)
                                    <tr>
                                        <td class="px-4 py-2 border-r">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->machine->name ?? '-' }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->siang }}</td>
                                        <td class="px-4 py-2 border-r">{{ $row->malam }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-2 text-center text-gray-400">Tidak ada data</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection
