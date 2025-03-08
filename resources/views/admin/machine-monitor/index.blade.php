@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-gray-50 overflow-auto">
        <!-- Sidebar -->
        @include('components.sidebar')

        <!-- Main Content -->
        <div id="main-content" class="flex-1 main-content">
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
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
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
                                stroke="currentColor" aria-hidden="true" data-slot="icon">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">Dasbor Pemantauan Mesin</h1>
                    </div>

                    @include('components.timer')
                    <div class="relative">
                        <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                            <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                                class="w-7 h-7 rounded-full mr-2">
                            <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                            <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                        </button>
                        <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">

                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>

            </header>
            <div class="flex items-center pt-2">
                <x-admin-breadcrumb :breadcrumbs="[['name' => 'Monitor Mesin', 'url' => null]]" />
            </div>

            <!-- Dashboard Content -->
            <main class="p-6">
                <!-- Indikator Kinerja -->
                <div class=" bg-white grid grid-cols-1 md:grid-cols-3 gap-6 mb-6 rounded-lg shadow-md" style="padding:20px">
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-blue-600 mb-2">
                                <i class="fas fa-cog"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Total Mesin</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ App\Models\Machine::count() }} unit</p>
                            <a href="{{ route('admin.machine-monitor.show', ['machine' => 1]) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-green-600 mb-2">
                                <i class="fas fa-building"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Total Unit</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ App\Models\PowerPlant::count() }} unit</p>
                            <a href="{{ route('admin.power-plants.index') }}" class="text-green-600 hover:text-green-800 font-medium text-sm">
                                Lihat Detail →
                            </a>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                        <div class="p-4">
                            <div class="text-3xl text-red-600 mb-2">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <h3 class="text-lg font-semibold mb-1">Masalah Aktif</h3>
                            <p class="text-gray-600 mb-2 text-sm">{{ $machines->sum(function ($machine) {
                                return $machine->issues->where('status', 'open')->count();
                            }) }} masalah</p>
                            <span class="text-red-600 font-medium text-sm">Perlu Perhatian</span>
                        </div>
                    </div>
                </div>

                <!-- Machine Status Overview -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4 text-gray-800 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Status Mesin Terkini
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Status Distribution Card -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-3 text-gray-700">Distribusi Status</h3>
                            <div class="space-y-3">
                                @php
                                    $statusCounts = $machineStatusLogs->groupBy('status');
                                    $totalLogs = $machineStatusLogs->count();
                                @endphp
                                
                                @foreach($statusCounts as $status => $logs)
                                    @php
                                        $percentage = ($logs->count() / $totalLogs) * 100;
                                        $colorClass = match($status) {
                                            'START' => 'bg-green-500',
                                            'PARALLEL' => 'bg-blue-500',
                                            'STOP' => 'bg-red-500',
                                            default => 'bg-gray-500'
                                        };
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm mb-1">
                                            <span>{{ $status }}</span>
                                            <span>{{ $logs->count() }} ({{ number_format($percentage, 1) }}%)</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Recent Status Changes -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-3 text-gray-700">Perubahan Status Terakhir</h3>
                            <div class="space-y-3">
                                @foreach($machineStatusLogs->take(5) as $log)
                                    <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                                        <div>
                                            <span class="font-medium">{{ $log->machine->name }}</span>
                                            <span class="text-sm text-gray-500 block">{{ $log->tanggal->format('d M Y H:i') }}</span>
                                        </div>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            {{ $log->status === 'START' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $log->status === 'STOP' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ $log->status === 'PARALLEL' ? 'bg-blue-100 text-blue-800' : '' }}">
                                            {{ $log->status }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Performance Metrics -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium mb-3 text-gray-700">Metrik Kinerja</h3>
                            <div class="space-y-4">
                                @php
                                    $avgDmn = $machineStatusLogs->avg('dmn');
                                    $avgDmp = $machineStatusLogs->avg('dmp');
                                    $avgLoad = $machineStatusLogs->avg('load_value');
                                @endphp
                                
                                <!-- DMN Metric -->
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">DMN Rata-rata</span>
                                        <span class="text-sm">{{ number_format($avgDmn, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-500 h-2 rounded-full" style="width: {{ min(($avgDmn/100) * 100, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- DMP Metric -->
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">DMP Rata-rata</span>
                                        <span class="text-sm">{{ number_format($avgDmp, 2) }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-500 h-2 rounded-full" style="width: {{ min(($avgDmp/100) * 100, 100) }}%"></div>
                                    </div>
                                </div>

                                <!-- Load Value Metric -->
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium">Beban Rata-rata</span>
                                        <span class="text-sm">{{ number_format($avgLoad, 2) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ min($avgLoad, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Status Table -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-800">Log Status Detail</h2>
                        <div class="flex gap-2">
                            <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <i class="fas fa-download mr-2"></i>Export
                            </button>
                            <button class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($machineStatusLogs->take(10) as $log)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->machine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $log->tanggal->format('d M Y H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $log->status === 'START' ? 'bg-green-100 text-green-800' : '' }}
                                                {{ $log->status === 'STOP' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $log->status === 'PARALLEL' ? 'bg-blue-100 text-blue-800' : '' }}">
                                                {{ $log->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->dmn, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->dmp, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ number_format($log->load_value, 2) }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

            <!-- Edit Machine Modal -->
            <div id="editMachineModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
                <!-- Similar structure to Add Machine Modal but with pre-filled values -->
            </div>
            <!-- Chart.js CDN -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="{{ asset('js/toggle.js') }}"></script>
            <script>
                // Data sementara untuk demonstrasi
                const monthlyIssuesData = [{
                        date: 'Januari',
                        count: 10
                    },
                    {
                        date: 'Februari',
                        count: 20
                    },
                    {
                        date: 'Maret',
                        count: 15
                    },
                    {
                        date: 'April',
                        count: 25
                    },
                    {
                        date: 'Mei',
                        count: 30
                    },
                    {
                        date: 'Juni',
                        count: 35
                    },
                    {
                        date: 'Juli',
                        count: 40
                    },
                    {
                        date: 'Agustus',
                        count: 45
                    },
                    {
                        date: 'September',
                        count: 50
                    },
                    {
                        date: 'Oktober',
                        count: 55
                    },
                    {
                        date: 'November',
                        count: 60
                    },
                    {
                        date: 'Desember',
                        count: 65
                    }
                ];

                // Ambil data dari PHP dan konversi ke format yang sesuai untuk Chart.js
                const powerPlantData = {!! json_encode($powerPlants->map(function($powerPlant) {
                    return [
                        'name' => $powerPlant->name,
                        'issues' => $powerPlant->machines->sum(function($machine) {
                            return $machine->statusLogs()->count();
                        }),
                        'operations' => $powerPlant->machines->sum(function($machine) {
                            return $machine->machineOperations()->count();
                        })
                    ];
                })) !!};

                // Siapkan data untuk grafik
                const datasets = [{
                    label: 'Jumlah Gangguan',
                    data: powerPlantData.map(plant => plant.issues),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2
                }, {
                    label: 'Jumlah Operasi',
                    data: powerPlantData.map(plant => plant.operations),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2
                }];

                // Inisialisasi grafik
                const ctx = document.getElementById('monthlyIssuesChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: powerPlantData.map(plant => plant.name),
                        datasets: datasets
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Unit'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Gangguan dan Operasi per Unit'
                            }
                        }
                    }
                });

                // Fungsi untuk membuka modal laporan masalah baru
                function openNewIssueModal() {
                    document.getElementById('newIssueModal').classList.remove('hidden');
                }

                // Fungsi untuk menutup modal laporan masalah baru
                function closeNewIssueModal() {
                    document.getElementById('newIssueModal').classList.add('hidden');
                }

                // Tutup modal saat mengklik di luar
                document.getElementById('newIssueModal').addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeNewIssueModal();
                    }
                });

                // Inisialisasi DataTable
                $(document).ready(function() {
                    $('table').DataTable({
                        responsive: true,
                        pageLength: 10,
                        order: [
                            [0, 'desc']
                        ]
                    });
                });

                // Fungsi untuk mengedit mesin
                function editMachine(machineId) {
                    // Fetch detail mesin dan buka modal edit
                    fetch(`/admin/machine-monitor/machines/${machineId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Isi form edit dengan data mesin
                            document.getElementById('editMachineModal').classList.remove('hidden');
                        });
                }

                // Fungsi untuk menghapus mesin
                function deleteMachine(machineId) {
                    if (confirm('Apakah Anda yakin ingin menghapus mesin ini?')) {
                        fetch(`/admin/machine-monitor/machines/${machineId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                }
                            });
                    }
                }

                // Fungsi untuk mengupdate status mesin
                function updateMachineStatus(machineId, status) {
                    fetch(`/admin/machine-monitor/machines/${machineId}/status`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: status
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }

                // Fungsi untuk refresh status mesin
                function refreshMachineStatus() {
                    location.reload();
                }

                // Data sementara untuk demonstrasi efisiensi mesin
                const efficiencyData = [{
                        name: 'Mesin 1',
                        efficiency: 80
                    },
                    {
                        name: 'Mesin 2',
                        efficiency: 75
                    },
                    {
                        name: 'Mesin 3',
                        efficiency: 90
                    },
                    {
                        name: 'Mesin 4',
                        efficiency: 85
                    },
                    {
                        name: 'Mesin 5',
                        efficiency: 95
                    }
                ];

                // Grafik Efisiensi Mesin
                const ctxEfficiency = document.getElementById('efficiencyChart').getContext('2d');

                // Ambil data mesin dari PHP
                const machineData = {!! json_encode($machines->map(function($machine) {
                    return [
                        'name' => $machine->name,
                        'status' => $machine->statusLogs()->latest()->first()->status ?? 'N/A',
                        'operations' => $machine->machineOperations()->count(),
                        'issues' => $machine->statusLogs()->count(),
                        'capacity' => $machine->capacity,
                        'efficiency' => $machine->machineOperations()
                            ->whereNotNull('load_value')
                            ->avg('load_value') ?? 0
                    ];
                })) !!};

                new Chart(ctxEfficiency, {
                    type: 'bar',
                    data: {
                        labels: machineData.map(machine => machine.name),
                        datasets: [{
                            label: 'Jumlah Gangguan',
                            data: machineData.map(machine => machine.issues),
                            type: 'line',
                            fill: false,
                            borderColor: 'rgba(255, 159, 64, 1)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Nilai'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Mesin'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            title: {
                                display: true,
                                text: 'Efisiensi dan Gangguan per Mesin'
                            }
                        }
                    }
                });

                function populateEditForm(machine) {
                    document.getElementById('name').value = machine.name;
                    document.getElementById('code').value = machine.code;
                    // Isi input lainnya sesuai kebutuhan
                }

                // Fungsi untuk toggle dropdown
                document.addEventListener('DOMContentLoaded', function() {
                    const dropdownButton = document.querySelector('#machine-monitor-dropdown');
                    const submenu = document.querySelector('#machine-monitor-submenu');
                    let isOpen = false;

                    // Check if current route is machine monitor or its children
                    const isMonitorRoute = window.location.pathname.includes('/machine-monitor');
                    if (isMonitorRoute) {
                        submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        dropdownButton.classList.add('rotate-180');
                        isOpen = true;
                    }

                    dropdownButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        isOpen = !isOpen;
                        
                        if (isOpen) {
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                            dropdownButton.classList.add('rotate-180');
                        } else {
                            submenu.style.maxHeight = '0';
                            dropdownButton.classList.remove('rotate-180');
                        }
                    });
                });
            </script>

            @push('scripts')
            @endpush
        </div>
    </div>

    <style>
        /* Sembunyikan scrollbar tapi tetap bisa scroll */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .main-content {
        .scrollbar-hide {
            /* -ms-overflow-style: none; */
            /* scrollbar-width: none; */
        }

        /* Animasi untuk rotasi icon dropdown */
        .rotate-180 {
            transform: rotate(180deg);
        }

        /* Transisi untuk submenu */
        #machine-monitor-submenu {
            transition: max-height 0.3s ease-in-out;
            overflow: hidden; /* Menyembunyikan konten yang tidak terlihat */
        }

        /* Style untuk submenu items */
        #machine-monitor-submenu a {
            font-size: 0.9rem;
            transition: all 0.2s ease;
        }

        #machine-monitor-submenu a:hover {
            padding-left: 1.5rem;
        }

        .bg-stripes {
            background-image: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.15) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0.15) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
        }

        .hover\:scale-\[1\.02\]:hover {
            transform: scale(1.02);
        }

        @keyframes move-stripes {
            from { background-position: 0 0; }
            to { background-position: 1rem 1rem; }
        }

        .animate-move-stripes {
            animation: move-stripes 1s linear infinite;
        }
    </style>

@endsection
