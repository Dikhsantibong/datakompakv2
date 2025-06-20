@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <x-sidebar />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header/Breadcrumb -->
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

                    <h1 class="text-xl font-semibold text-gray-800">Akumulasi Input Datakompak</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Akumulasi Input Datakompak', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class=" px-2 ">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Monitoring Pengisian Datakompak.com</h2>
                    <p class="text-gray-600">Periode: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</p>

                </div>

                <!-- Hidden textarea for copying -->
                <textarea id="reportText" class="hidden"></textarea>

                <!-- Date Range Filter -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <form method="GET" class="flex gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                            <select name="unit" class="mt-1 p-2  block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Semua Unit</option>
                                @foreach($powerPlants as $plant)
                                    <option value="{{ $plant->id }}" {{ $selectedUnit == $plant->id ? 'selected' : '' }}>
                                        {{ $plant->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            Filter
                        </button>
                        <button id="copyReportBtn" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-copy mr-2"></i> Salin Laporan
                        </button>
                    </form>
                </div>

                @foreach($data as $powerPlant)
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">{{ $powerPlant['name'] }}</h2>

                    <!-- Charts Container -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <!-- Operator KIT Chart -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Operator KIT Performance</h3>
                            <canvas id="operatorKitChart{{ str_replace(' ', '', $powerPlant['name']) }}" class="w-full"></canvas>
                        </div>

                        <!-- Operasi UL Chart -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Operasi UL/Central Performance</h3>
                            <canvas id="operasiUlChart{{ str_replace(' ', '', $powerPlant['name']) }}" class="w-full "></canvas>
                        </div>
                    </div>

                    <!-- Detailed Tables -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Operator KIT Table -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Operator KIT</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Input Type</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Completion</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Missing Days</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach(['meeting_shift', 'abnormal_report', 'flm_inspection', 'five_s5r', 'patrol_check', 'laporan_kit'] as $key)
                                            @if(isset($powerPlant['operator_kit'][$key]))
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm border-r border-gray-200 text">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $powerPlant['operator_kit'][$key]['percentage'] >= 80 ? 'bg-green-100 text-green-800' : ($powerPlant['operator_kit'][$key]['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $powerPlant['operator_kit'][$key]['percentage'] }}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 border-r border-gray-200">
                                                    {{ $powerPlant['operator_kit'][$key]['missing_days'] }} hari
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Operasi UL Table -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Operasi UL/Central</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Input Type</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Completion</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Missing Days</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach(['bahan_bakar', 'pelumas', 'bahan_kimia', 'daily_summary', 'rencana_daya_mampu'] as $key)
                                            @if(isset($powerPlant['operasi_ul'][$key]))
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-200">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm border-r border-gray-200">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $powerPlant['operasi_ul'][$key]['percentage'] >= 80 ? 'bg-green-100 text-green-800' : ($powerPlant['operasi_ul'][$key]['percentage'] >= 50 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        {{ $powerPlant['operasi_ul'][$key]['percentage'] }}%
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500 border-r border-gray-200">
                                                    {{ $powerPlant['operasi_ul'][$key]['missing_days'] }} hari
                                                </td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($data as $powerPlant)
        // Operator KIT Chart
        new Chart(document.getElementById('operatorKitChart{{ str_replace(' ', '', $powerPlant['name']) }}'), {
            type: 'bar',
            data: {
                labels: [
                    'Meeting Shift',
                    'Data Engine',
                    'Abnormal Report',
                    'FLM Inspection',
                    '5S 5R',
                    'Patrol Check',
                    'Laporan KIT'
                ],
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: [
                        {{ $powerPlant['operator_kit']['meeting_shift']['percentage'] ?? 0 }},
                        {{ $powerPlant['operator_kit']['data_engine']['percentage'] ?? 0 }},
                        {{ $powerPlant['operator_kit']['abnormal_report']['percentage'] ?? 0 }},
                        {{ $powerPlant['operator_kit']['flm_inspection']['percentage'] ?? 0 }},
                        {{ $powerPlant['operator_kit']['five_s5r']['percentage'] ?? 0 }},
                        {{ $powerPlant['operator_kit']['patrol_check']['percentage'] ?? 0 }},
                        {{ $powerPlant['operator_kit']['laporan_kit']['percentage'] ?? 0 }}
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Operasi UL Chart
        new Chart(document.getElementById('operasiUlChart{{ str_replace(' ', '', $powerPlant['name']) }}'), {
            type: 'bar',
            data: {
                labels: [
                    'Bahan Bakar',
                    'Pelumas',
                    'Bahan Kimia',
                    'Daily Summary',
                    'Rencana Daya Mampu'
                ],
                datasets: [{
                    label: 'Completion Rate (%)',
                    data: [
                        {{ $powerPlant['operasi_ul']['bahan_bakar']['percentage'] ?? 0 }},
                        {{ $powerPlant['operasi_ul']['pelumas']['percentage'] ?? 0 }},
                        {{ $powerPlant['operasi_ul']['bahan_kimia']['percentage'] ?? 0 }},
                        {{ $powerPlant['operasi_ul']['daily_summary']['percentage'] ?? 0 }},
                        {{ $powerPlant['operasi_ul']['rencana_daya_mampu']['percentage'] ?? 0 }}
                    ],
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    @endforeach

    // Copy Report Functionality
    document.getElementById('copyReportBtn').addEventListener('click', function() {
        const powerPlants = @json($data);
        let reportText = '';

        // Format date range
        const startDate = '{{ \Carbon\Carbon::parse($startDate)->isoFormat("D") }}';
        const endDate = '{{ \Carbon\Carbon::parse($endDate)->isoFormat("D MMMM Y") }}';

        reportText += `MONITORING PENGISIAN DATAKOMPAK.COM TANGGAL ${startDate} S/D ${endDate}\n\n`;

        powerPlants.forEach(powerPlant => {
            if (powerPlant.name === 'UP KENDARI') return;

            reportText += `${powerPlant.name}\n\n`;

            // OPERATOR KIT
            reportText += 'OPERATOR KIT :\n';
            const operatorKitData = {
                'data_engine': 'INPUT DATA ENGINE PERJAM',
                'meeting_shift': 'INPUT MEETING DAN MUTASI SHIFT',
                'patrol_check': 'INPUT PATROL CHECK',
                'flm_inspections' :'INPUT FLM '
                'five_s5r': 'INPUT 5S5R',
                'flm': 'INPUT FLM',
                'abnormal_report': 'INPUT LAPORAN ABNORMAL',
                'k3_kam': 'INPUT K3 KAM & LINGKUNGAN'
            };

            let counter = 1;
            for (const [key, label] of Object.entries(operatorKitData)) {
                if (powerPlant.operator_kit[key]) {
                    const data = powerPlant.operator_kit[key];
                    const tentative = ['abnormal_report', 'k3_kam'].includes(key) ? ' (TENTATIVE)' : '';
                    reportText += `${counter}. ${label} : ${data.percentage}%, ${data.missing_days} kali tidak menginput${tentative}\n`;
                    counter++;
                }
            }

            reportText += '\n';

            // OPERASI UL/CENTRAL
            reportText += 'OPERASI UL/CENTRAL\n';
            const operasiUlData = {
                'bahan_bakar': 'INPUT BAHAN BAKAR',
                'pelumas': 'INPUT PELUMAS',
                'daily_summary': 'INPUT IKHTISAR HARIAN',
                'bahan_kimia': 'INPUT BAHAN KIMIA',
                'rencana_daya_mampu': 'INPUT RENCANA DAYA MAMPU BULANAN'
            };

            counter = 1;
            for (const [key, label] of Object.entries(operasiUlData)) {
                if (powerPlant.operasi_ul[key]) {
                    const data = powerPlant.operasi_ul[key];
                    reportText += `${counter}. ${label} : ${data.percentage}%, ${data.missing_days} kali tidak menginput\n`;
                    counter++;
                }
            }

            reportText += '\n\n';
        });

        // Copy to clipboard
        const textarea = document.getElementById('reportText');
        textarea.value = reportText;
        textarea.classList.remove('hidden');
        textarea.select();
        document.execCommand('copy');
        textarea.classList.add('hidden');

        // Show success message
        alert('Laporan berhasil disalin ke clipboard!');
    });
});
</script>
@endpush
