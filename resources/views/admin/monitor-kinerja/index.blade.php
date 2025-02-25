@extends('layouts.app')

@section('title', 'Kinerja Pembangkit')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
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
                    <h1 class="text-xl font-semibold text-gray-900">Kinerja Pembangkit</h1>
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
                            <input type="hidden" name="redirect" value="{{ route('login') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-6 py-8">
                <!-- Performance Indicators -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                    <!-- EAF Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-blue-100">
                                <i class="fas fa-percentage text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">EAF</p>
                                <p class="text-lg font-semibold text-gray-800">{{ number_format($performance['eaf'], 1) }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- SOF Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-red-100">
                                <i class="fas fa-percentage text-red-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">SOF</p>
                                <p class="text-lg font-semibold text-gray-800">{{ number_format($performance['sof'], 1) }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- EFOR Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-yellow-100">
                                <i class="fas fa-percentage text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">EFOR</p>
                                <p class="text-lg font-semibold text-gray-800">{{ number_format($performance['efor'], 1) }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- SdOF Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-indigo-100">
                                <i class="fas fa-percentage text-indigo-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">SdOF</p>
                                <p class="text-lg font-semibold text-gray-800">{{ number_format($performance['sdof'], 1) }}%</p>
                            </div>
                        </div>
                    </div>

                    <!-- NCF Card -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 p-3 rounded-full bg-green-100">
                                <i class="fas fa-percentage text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">NCF</p>
                                <p class="text-lg font-semibold text-gray-800">{{ number_format($performance['ncf'], 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Statistics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Operating Statistics -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-clock mr-2 text-gray-600"></i>
                            Statistik Operasi
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Jam Operasi</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $operatingStats['operating_hours'] }} jam</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Jam Standby</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $operatingStats['standby_hours'] }} jam</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Planned Outage</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $operatingStats['planned_outage'] }} jam</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Maintenance Outage</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $operatingStats['maintenance_outage'] }} jam</p>
                            </div>
                        </div>
                    </div>

                    <!-- Production Statistics -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-bolt mr-2 text-gray-600"></i>
                            Statistik Produksi
                        </h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Produksi Bruto</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $productionStats['gross_production'] }} MW</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Produksi Netto</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $productionStats['net_production'] }} MW</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Beban Puncak</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $productionStats['peak_load_day'] }} MW</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Beban Luar Puncak</p>
                                <p class="text-lg font-semibold text-gray-800">{{ $productionStats['peak_load_night'] }} MW</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <!-- Fuel Usage -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-gas-pump mr-2 text-gray-600"></i>
                            Penggunaan Bahan Bakar
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">HSD</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['hsd'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">B35</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['b35'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">MFO</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['mfo'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="font-semibold text-gray-800">{{ $fuelUsage['total'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Oil Usage -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-oil-can mr-2 text-gray-600"></i>
                            Penggunaan Pelumas
                        </h3>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Meditran</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['meditran'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Salyx 420</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['salyx_420'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Salyx 430</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['salyx_430'] }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="font-semibold text-gray-800">{{ $oilUsage['total'] }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Technical Parameters -->
                    <div class="bg-white rounded-lg shadow p-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-4">
                            <i class="fas fa-cogs mr-2 text-gray-600"></i>
                            Parameter Teknis
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">SFC/SCC</span>
                                <span class="font-semibold text-gray-800">{{ $technicalParams['sfc_scc'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">NPHR</span>
                                <span class="font-semibold text-gray-800">{{ $technicalParams['nphr'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">SLC</span>
                                <span class="font-semibold text-gray-800">{{ $technicalParams['slc'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="bg-white rounded-lg shadow mb-6">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-chart-line mr-2 text-gray-600"></i>
                            Grafik Kinerja Pembangkit
                        </h3>
                    </div>
                    <div class="p-4">
                        <canvas id="kinerjaChart" class="w-full" style="min-height: 400px;"></canvas>
                    </div>
                </div>

                <!-- Keterangan Section -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-800">
                            <i class="fas fa-info-circle mr-2 text-gray-600"></i>
                            Keterangan Indikator
                        </h3>
                    </div>
                    <div class="p-4">
                        <dl class="grid grid-cols-1 gap-4">
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">EAF (Equivalent Availability Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor kesiapan pembangkit untuk beroperasi.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">SOF (Scheduled Outage Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor pemeliharaan terjadwal pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">EFOR (Equivalent Forced Outage Rate)</dt>
                                <dd class="col-span-2 text-gray-600">Tingkat gangguan paksa pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">SdOF (Sudden Outage Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor gangguan mendadak pembangkit.</dd>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <dt class="font-medium text-gray-700">NCF (Net Capacity Factor)</dt>
                                <dd class="col-span-2 text-gray-600">Faktor kapasitas bersih pembangkit.</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    
    const ctx = document.getElementById('kinerjaChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.map(item => new Date(item.created_at).toLocaleDateString()),
            datasets: [
                {
                    label: 'EAF',
                    data: chartData.map(item => item.eaf),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'SOF',
                    data: chartData.map(item => item.sof),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
                {
                    label: 'EFOR',
                    data: chartData.map(item => item.efor),
                    borderColor: 'rgb(255, 205, 86)',
                    tension: 0.1
                },
                {
                    label: 'SdOF',
                    data: chartData.map(item => item.sdof),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                },
                {
                    label: 'NCF',
                    data: chartData.map(item => item.ncf),
                    borderColor: 'rgb(153, 102, 255)',
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Grafik Kinerja Pembangkit'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            }
        }
    });
});
</>
@endpush 