@extends('layouts.app')

@section('content')
<div class="flex h-screen">
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
          
        <main class="bg-white px-6">
            <!-- Highlight Cards -->
            <div class=" bg-white shadow-md rounded-md grid grid-cols-1 md:grid-cols-4 gap-6 p-6">
                <!-- Card 1: Total Daya PJBTL -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-blue-600 mb-2">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Daya PJBTL</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalDayaPJBTL, 2) }} MW</p>
                        <span class="text-blue-600 text-sm font-medium">
                            Seluruh Unit
                        </span>
                    </div>
                </div>

                <!-- Card 2: Total DMP Existing -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-green-600 mb-2">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total DMP Existing</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalDMPExisting, 2) }} MW</p>
                        <span class="text-green-600 text-sm font-medium">
                            Kapasitas Terkini
                        </span>
                    </div>
                </div>

                <!-- Card 3: Total Rencana -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-purple-600 mb-2">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Rencana</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalRencana, 2) }} MW</p>
                        <span class="text-purple-600 text-sm font-medium">
                            Target Bulanan
                        </span>
                    </div>
                </div>

                <!-- Card 4: Total Realisasi -->
                <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                    <div class="p-4">
                        <div class="text-3xl text-yellow-600 mb-2">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-1">Total Realisasi</h3>
                        <p class="text-gray-600 mb-2 text-sm">{{ number_format($totalRealisasi, 2) }} MW</p>
                        <span class="text-yellow-600 text-sm font-medium">
                            Pencapaian Aktual
                        </span>
                    </div>
                </div>
            </div>

           

                   
                    
                <div class="overflow-x-auto bg-white rounded-lg shadow-lg p-6 mb-4 mt-4" style="max-width: 100%;">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl text-gray-800 font-bold">Rencana Operasi Bulanan (ROB)</h1>
                            <p class="text-sm text-gray-600 mt-1">{{ now()->format('F Y') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.rencana-daya-mampu.manage') }}" 
                               class="bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-cogs mr-2"></i> Kelola Data
                            </a>
                            <button id="editModeButton" 
                                    onclick="toggleEditMode()"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 flex items-center">
                                <i class="fas fa-edit mr-2"></i> Mode Edit
                            </button>
                            <button id="saveButton" type="button" onclick="saveData()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200 flex items-center hidden">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>

                    @if(session('unit') === 'mysql')
                    <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
                        <label for="unit-source" class="text-sm font-medium text-gray-700 mr-2">Pilih Sumber Unit:</label>
                        <select id="unit-source" 
                                class="p-2 border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                                style="width: 120px;"
                                onchange="updateTable()">
                            <option value="mysql" {{ $unitSource == 'mysql' ? 'selected' : '' }}>UP Kendari</option>
                            <option value="mysql_wua_wua" {{ $unitSource == 'mysql_wua_wua' ? 'selected' : '' }}>Wua Wua</option>
                            <option value="mysql_poasia" {{ $unitSource == 'mysql_poasia' ? 'selected' : '' }}>Poasia</option>
                            <option value="mysql_kolaka" {{ $unitSource == 'mysql_kolaka' ? 'selected' : '' }}>Kolaka</option>
                            <option value="mysql_bau_bau" {{ $unitSource == 'mysql_bau_bau' ? 'selected' : '' }}>Bau Bau</option>
                        </select>
                    </div>
                    @endif

                    <div class="overflow-x-auto border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider sticky left-0 bg-gray-50 border-r-2 border-b z-20">No</th>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider sticky left-16 bg-gray-50 border-r-2 border-b z-20">Sistem Kelistrikan</th>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r-2 border-b">Mesin Pembangkit</th>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r-2 border-b">DMN SLO</th>
                                    <th rowspan="2" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-r-2 border-b">DMP PT</th>
                                    @for ($i = 1; $i <= date('t'); $i++)
                                        <th colspan="2" class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider bg-gray-50 border-b">{{ $i }}</th>
                                    @endfor
                                </tr>
                                <tr>
                                    @for ($i = 1; $i <= date('t'); $i++)
                                        <th class="px-2 py-2 text-center text-xs font-semibold text-blue-700 uppercase tracking-wider border-r">Rencana</th>
                                        <th class="px-2 py-2 text-center text-xs font-semibold text-green-700 uppercase tracking-wider border-r">Realisasi</th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php $no = 1; @endphp
                                @foreach($powerPlants as $plant)
                                    @foreach($plant->machines->take(3) as $machine)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white border-r-2 text-center">{{ $no++ }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white border-r-2">
                                                <div class="truncate max-w-[150px]">{{ $plant->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r-2">{{ $machine->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r-2 text-center">{{ $machine->dmn_slo ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap border-r-2 text-center">{{ $machine->dmp_pt ?? '-' }}</td>
                                            @for ($i = 1; $i <= date('t'); $i++)
                                                @php
                                                    $date = now()->format('Y-m-') . sprintf('%02d', $i);
                                                    $data = $machine->rencanaDayaMampu->first()?->getDailyValue($date) ?? [];
                                                    $rencanaRows = $data['rencana'] ?? array_fill(0, 5, ['beban'=>'','durasi'=>'','keterangan'=>'']);
                                                    $realisasi = $data['realisasi'] ?? ['beban'=>'','keterangan'=>''];
                                                    $onArr = [0,12,15,19,21];
                                                    $offArr = [8,13,18,21,0];
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
                                                                @for ($j = 0; $j < 5; $j++)
                                                                <tr class="transition-colors duration-150 hover:bg-blue-200">
                                                                    <td class="border px-2 py-1">
                                                                        <input type="number" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $j }}][beban]" class="data-input hidden w-24 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $rencanaRows[$j]['beban'] ?? '' }}" step="0.01">
                                                                        <span class="data-display">{{ $rencanaRows[$j]['beban'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1 bg-gray-50">{{ $onArr[$j] }}</td>
                                                                    <td class="border px-2 py-1 bg-gray-50">{{ $offArr[$j] }}</td>
                                                                    <td class="border px-2 py-1">
                                                                        <input type="number" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $j }}][durasi]" class="data-input hidden w-20 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $rencanaRows[$j]['durasi'] ?? '' }}" step="0.01">
                                                                        <span class="data-display">{{ $rencanaRows[$j]['durasi'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1">
                                                                        <input type="text" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $j }}][keterangan]" class="data-input hidden w-28 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $rencanaRows[$j]['keterangan'] ?? '' }}">
                                                                        <span class="data-display">{{ $rencanaRows[$j]['keterangan'] ?? '-' }}</span>
                                                                    </td>
                                                                </tr>
                                                                @endfor
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
                                                                <tr class="transition-colors duration-150 hover:bg-green-200">
                                                                    <td class="border px-2 py-1">
                                                                        <input type="number" name="realisasi[{{ $machine->id }}][{{ $date }}][beban]" class="data-input hidden w-28 text-center border rounded focus:ring-2 focus:ring-green-400 focus:border-green-500 transition-all duration-150" value="{{ $realisasi['beban'] ?? '' }}" step="0.01">
                                                                        <span class="data-display">{{ $realisasi['beban'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1">
                                                                        <input type="text" name="realisasi[{{ $machine->id }}][{{ $date }}][keterangan]" class="data-input hidden w-32 text-center border rounded focus:ring-2 focus:ring-green-400 focus:border-green-500 transition-all duration-150" value="{{ $realisasi['keterangan'] ?? '' }}">
                                                                        <span class="data-display">{{ $realisasi['keterangan'] ?? '-' }}</span>
                                                                    </td>
                                                                </tr>
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
                </div>
            </main>
        </div>
    </div>
</main>


<!-- Add this script at the bottom of your file -->
{{-- <script src="{{asset('js/toggle.js')}}"></script> --}}

<script>
    // Pastikan DOM sudah dimuat sepenuhnya
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi event listener untuk sidebar toggle
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('[sidebar]').classList.toggle('hidden');
        });
    });

    function updateTable() {
        const unitSource = document.getElementById('unit-source').value;
        window.location.href = `{{ route('admin.rencana-daya-mampu') }}?unit_source=${unitSource}`;
    }

    let isEditMode = false;

    function toggleEditMode() {
        isEditMode = !isEditMode;
        const editButton = document.getElementById('editModeButton');
        const saveButton = document.getElementById('saveButton');
        const displays = document.querySelectorAll('.data-display');
        const inputs = document.querySelectorAll('.data-input');

        if (isEditMode) {
            editButton.classList.add('bg-gray-500');
            editButton.classList.remove('bg-blue-500');
            editButton.textContent = 'Batal';
            saveButton.classList.remove('hidden');
            displays.forEach(el => el.style.display = 'none');
            inputs.forEach(el => {
                el.style.display = 'inline-block';
                el.classList.remove('hidden');
            });
        } else {
            editButton.classList.remove('bg-gray-500');
            editButton.classList.add('bg-blue-500');
            editButton.textContent = 'Mode Edit';
            saveButton.classList.add('hidden');
            displays.forEach(el => el.style.display = 'inline-block');
            inputs.forEach(el => {
                el.style.display = 'none';
                el.classList.add('hidden');
            });
        }
    }

    function saveData() {
        const saveButton = document.getElementById('saveButton');
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        // Kumpulkan data input ke dalam satu objek
        const data = {
            rencana: {},
            realisasi: {},
            keterangan: {}
        };

        document.querySelectorAll('input[name^="rencana["]').forEach(input => {
            const matches = input.name.match(/rencana\[(\d+)\]\[(\d{4}-\d{2}-\d{2})\]/);
            if (matches) {
                const machineId = matches[1];
                const date = matches[2];
                if (!data.rencana[machineId]) data.rencana[machineId] = {};
                data.rencana[machineId][date] = input.value;
            }
        });
        document.querySelectorAll('input[name^="realisasi["]').forEach(input => {
            const matches = input.name.match(/realisasi\[(\d+)\]\[(\d{4}-\d{2}-\d{2})\]/);
            if (matches) {
                const machineId = matches[1];
                const date = matches[2];
                if (!data.realisasi[machineId]) data.realisasi[machineId] = {};
                data.realisasi[machineId][date] = input.value;
            }
        });
        document.querySelectorAll('input[name^="keterangan["]').forEach(input => {
            const matches = input.name.match(/keterangan\[(\d+)\]\[(\d{4}-\d{2}-\d{2})\]/);
            if (matches) {
                const machineId = matches[1];
                const date = matches[2];
                if (!data.keterangan[machineId]) data.keterangan[machineId] = {};
                data.keterangan[machineId][date] = input.value;
            }
        });

        fetch('{{ route("admin.rencana-daya-mampu.update") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan data',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            console.error('Error:', error);
        })
        .finally(() => {
            saveButton.disabled = false;
            saveButton.innerHTML = 'Simpan';
        });
    }

    // Attach the save function to the button
    document.getElementById('saveButton').addEventListener('click', saveData);
</script>
@endsection 