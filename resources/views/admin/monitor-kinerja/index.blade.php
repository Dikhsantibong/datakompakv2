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
                    <button @click="$store.sidebar.toggleSidebar()" 
                            class="text-gray-500 hover:text-gray-600 focus:outline-none mr-4">
                        <i class="fas fa-grip-lines text-xl"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-900">Kinerja Pembangkit</h1>
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
                                <p class="text-lg font-semibold text-gray-800">85.5%</p>
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
                                <p class="text-lg font-semibold text-gray-800">10.2%</p>
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
                                <p class="text-lg font-semibold text-gray-800">4.3%</p>
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
                                <p class="text-lg font-semibold text-gray-800">2.1%</p>
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
                                <p class="text-lg font-semibold text-gray-800">78.9%</p>
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
                                <p class="text-lg font-semibold text-gray-800">720 jam</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Jam Standby</p>
                                <p class="text-lg font-semibold text-gray-800">24 jam</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Planned Outage</p>
                                <p class="text-lg font-semibold text-gray-800">16 jam</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Maintenance Outage</p>
                                <p class="text-lg font-semibold text-gray-800">8 jam</p>
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
                                <p class="text-lg font-semibold text-gray-800">2,600 MW</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Produksi Netto</p>
                                <p class="text-lg font-semibold text-gray-800">2,400 MW</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Beban Puncak</p>
                                <p class="text-lg font-semibold text-gray-800">2,800 MW</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-600">Beban Luar Puncak</p>
                                <p class="text-lg font-semibold text-gray-800">2,000 MW</p>
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
                                <span class="font-semibold text-gray-800">1,200</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">B35</span>
                                <span class="font-semibold text-gray-800">800</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">MFO</span>
                                <span class="font-semibold text-gray-800">400</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="font-semibold text-gray-800">2,400</span>
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
                                <span class="font-semibold text-gray-800">150</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Salyx 420</span>
                                <span class="font-semibold text-gray-800">100</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Salyx 430</span>
                                <span class="font-semibold text-gray-800">75</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t">
                                <span class="text-sm font-medium text-gray-600">Total</span>
                                <span class="font-semibold text-gray-800">325</span>
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
                                <span class="font-semibold text-gray-800">0.345</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">NPHR</span>
                                <span class="font-semibold text-gray-800">2.567</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">SLC</span>
                                <span class="font-semibold text-gray-800">1.234</span>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dummyData = [
        { eaf: 85.5, sof: 10.2, efor: 4.3, sdof: 2.1, ncf: 78.9 },
        { eaf: 87.2, sof: 9.8, efor: 3.9, sdof: 1.8, ncf: 80.1 },
        { eaf: 86.8, sof: 9.5, efor: 4.1, sdof: 2.0, ncf: 79.5 }
    ];
    
    const ctx = document.getElementById('kinerjaChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Data 1', 'Data 2', 'Data 3'],
            datasets: [
                {
                    label: 'EAF',
                    data: dummyData.map(item => item.eaf),
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                },
                {
                    label: 'SOF',
                    data: dummyData.map(item => item.sof),
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1
                },
                {
                    label: 'EFOR',
                    data: dummyData.map(item => item.efor),
                    borderColor: 'rgb(255, 205, 86)',
                    tension: 0.1
                },
                {
                    label: 'SdOF',
                    data: dummyData.map(item => item.sdof),
                    borderColor: 'rgb(54, 162, 235)',
                    tension: 0.1
                },
                {
                    label: 'NCF',
                    data: dummyData.map(item => item.ncf),
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
</script>
@endpush 