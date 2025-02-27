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
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl font-bold">Rencana Operasi Bulanan (ROB)</h1>
                    <div class="flex gap-2">
                        <button id="editModeButton" 
                                onclick="toggleEditMode()"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Mode Edit
                        </button>
                        <button id="saveButton" 
                                onclick="saveChanges()"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 hidden">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>

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
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display">{{ $machine->rencana ?? '-' }}</span>
                                            <input type="text" 
                                                   name="rencana[{{ $machine->id }}]"
                                                   class="data-input hidden w-20 text-center border rounded"
                                                   value="{{ $machine->rencana }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display">{{ $machine->realisasi ?? '-' }}</span>
                                            <input type="text" 
                                                   name="realisasi[{{ $machine->id }}]"
                                                   class="data-input hidden w-20 text-center border rounded"
                                                   value="{{ $machine->realisasi }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display">{{ $machine->daya_pjbtl_silm ?? '-' }}</span>
                                            <input type="number" 
                                                   name="daya_pjbtl[{{ $machine->id }}]"
                                                   class="data-input hidden w-20 text-center border rounded"
                                                   value="{{ $machine->daya_pjbtl_silm }}"
                                                   step="0.01">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display">{{ $machine->dmp_existing ?? '-' }}</span>
                                            <input type="number" 
                                                   name="dmp_existing[{{ $machine->id }}]"
                                                   class="data-input hidden w-20 text-center border rounded"
                                                   value="{{ $machine->dmp_existing }}"
                                                   step="0.01">
                                        </td>
                                        @for ($i = 1; $i <= date('t'); $i++)
                                            @php
                                                $date = now()->format('Y-m-') . sprintf('%02d', $i);
                                                $dailyValue = $machine->rencanaDayaMampu->first()?->getDailyValue($date, 'rencana');
                                            @endphp
                                            <td class="px-6 py-4 whitespace-nowrap text-center border-r-2">
                                                <span class="data-display">{{ $dailyValue ?? '-' }}</span>
                                                <input type="text" 
                                                       name="days[{{ $machine->id }}][{{ $i }}]"
                                                       class="data-input hidden w-16 text-center border rounded"
                                                       value="{{ $dailyValue }}"
                                                       data-date="{{ $date }}">
                                            </td>
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

<!-- Add this script at the bottom of your file -->
<script src="{{asset('js/toggle.js')}}"></script>

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
        
        // Ambil referensi elemen
        const editButton = document.getElementById('editModeButton');
        const saveButton = document.getElementById('saveButton');
        const displays = document.querySelectorAll('.data-display');
        const inputs = document.querySelectorAll('.data-input');

        console.log('Toggle Edit Mode:', {
            isEditMode,
            displays: displays.length,
            inputs: inputs.length
        });

        if (isEditMode) {
            // Mode Edit aktif
            editButton.classList.add('bg-gray-500');
            editButton.classList.remove('bg-blue-500');
            editButton.textContent = 'Batal';
            saveButton.classList.remove('hidden');
            
            // Sembunyikan display, tampilkan input
            displays.forEach(el => el.style.display = 'none');
            inputs.forEach(el => {
                el.style.display = 'inline-block';
                el.classList.remove('hidden');
            });
        } else {
            // Mode Edit non-aktif
            editButton.classList.remove('bg-gray-500');
            editButton.classList.add('bg-blue-500');
            editButton.textContent = 'Mode Edit';
            saveButton.classList.add('hidden');
            
            // Tampilkan display, sembunyikan input
            displays.forEach(el => el.style.display = 'inline-block');
            inputs.forEach(el => {
                el.style.display = 'none';
                el.classList.add('hidden');
            });
        }
    }

    function saveChanges() {
        const saveButton = document.getElementById('saveButton');
        saveButton.textContent = 'Menyimpan...';
        saveButton.disabled = true;

        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');

        // Kumpulkan data summary
        document.querySelectorAll('input[name^="rencana"], input[name^="realisasi"], input[name^="daya_pjbtl"], input[name^="dmp_existing"]').forEach(input => {
            if (!input.classList.contains('hidden') && input.value !== '') {
                formData.append(input.name, input.value);
            }
        });

        // Kumpulkan data harian
        const dailyData = {};
        document.querySelectorAll('input[name^="days"]').forEach(input => {
            if (!input.classList.contains('hidden') && input.value !== '') {
                const machineId = input.name.match(/days\[(\d+)\]/)[1];
                const date = input.dataset.date;
                
                if (!dailyData[machineId]) {
                    dailyData[machineId] = {};
                }
                if (!dailyData[machineId][date]) {
                    dailyData[machineId][date] = {};
                }
                
                dailyData[machineId][date]['rencana'] = input.value;
                dailyData[machineId][date]['realisasi'] = input.value;
            }
        });

        // Tambahkan daily_data ke formData
        formData.append('daily_data', JSON.stringify(dailyData));

        fetch('{{ route("admin.rencana-daya-mampu.update") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil disimpan');
                location.reload();
            } else {
                alert(data.message || 'Terjadi kesalahan saat menyimpan data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        })
        .finally(() => {
            saveButton.textContent = 'Simpan Perubahan';
            saveButton.disabled = false;
        });
    }
</script>
@endsection 