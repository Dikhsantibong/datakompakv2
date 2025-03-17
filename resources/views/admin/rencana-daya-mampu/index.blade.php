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
          
        <main class="px-6">
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

       

               
                
            <div class="overflow-x-auto bg-white rounded-lg shadow p-6 mb-4 mt-4" style="max-width: 100%;">
                <div class="flex justify-between items-center mb-4">
                    <h1 class="text-2xl text-gray-800 font-bold">Rencana Operasi Bulanan (ROB)</h1>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.rencana-daya-mampu.manage') }}" 
                           class="bg-purple-500 text-white px-4 py-2 rounded hover:bg-purple-600 flex items-center">
                            <i class="fas fa-cogs mr-2"></i> Kelola Data
                        </a>
                        <button id="editModeButton" 
                                onclick="toggleEditMode()"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 flex items-center">
                            <i class="fas fa-edit mr-2"></i> Mode Edit
                        </button>
                        <button id="saveButton" 
                                onclick="saveData()"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 hidden">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

                <!-- Unit Filter (Only show for UP Kendari users) -->
                @if(session('unit') === 'mysql')
                <div class="p-4 border-b flex items-center">
                    <label for="unit-source" class="block text-sm font-medium text-gray-700 mr-2">Pilih Sumber Unit : </label>
                    <select id="unit-source" 
                            class="border rounded px-3 py-2 text-sm"
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

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border-2">
                        <thead class="bg-gray-50">
                            <tr>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50  border-r-2">No</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-16 bg-gray-50  border-r-2">Sistem Kelistrikan</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  border-r-2">Mesin Pembangkit</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  border-r-2">Site Pembangkit</th>
                                <th colspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  border-r-2">Rencana Realisasi</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider  border-r-2">Daya PJBTL SILM</th>
                                <th rowspan="2" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r-2">DMP Existing</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $machine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap " style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display">{{ $machine->rencana ?? '-' }}</span>
                                            <textarea name="rencana[{{ $machine->id }}]"
                                                      style="width: 200px;"
                                                      class="data-input hidden w-full text-center border rounded"
                                                      rows="3">{{ $machine->rencana }}</textarea>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display">{{ $machine->realisasi ?? '-' }}</span>
                                            <textarea name="realisasi[{{ $machine->id }}]"
                                                      style="width: 200px;"
                                                      class="data-input hidden w-full text-center border rounded"
                                                      rows="3">{{ $machine->realisasi }}</textarea>
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
                                                <textarea name="days[{{ $machine->id }}][{{ $i }}]"
                                                          style="width: 200px;"
                                                          class="data-input hidden w-full text-center border rounded"
                                                          rows="3"
                                                          data-date="{{ $date }}">{{ $dailyValue }}</textarea>
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
</main>


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

    function saveData() {
        // Show loading state
        const saveButton = document.getElementById('saveButton');
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';

        // Collect form data
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // Add rencana and realisasi data
        document.querySelectorAll('input[name^="rencana["]').forEach(input => {
            formData.append(input.name, input.value);
        });
        document.querySelectorAll('input[name^="realisasi["]').forEach(input => {
            formData.append(input.name, input.value);
        });
        document.querySelectorAll('input[name^="daya_pjbtl["]').forEach(input => {
            formData.append(input.name, input.value);
        });
        document.querySelectorAll('input[name^="dmp_existing["]').forEach(input => {
            formData.append(input.name, input.value);
        });

        // Make the AJAX request
        fetch('{{ route("admin.rencana-daya-mampu.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Show success message
            Swal.fire({
                title: data.title,
                text: data.message,
                icon: data.icon,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload(); // Reload page after clicking OK
                }
            });
        })
        .catch(error => {
            // Show error message
            Swal.fire({
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan data',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            console.error('Error:', error);
        })
        .finally(() => {
            // Reset button state
            saveButton.disabled = false;
            saveButton.innerHTML = 'Simpan';
        });
    }

    // Attach the save function to the button
    document.getElementById('saveButton').addEventListener('click', saveData);
</script>
@endsection 