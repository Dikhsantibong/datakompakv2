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
            <div class="overflow-x-auto bg-white rounded-2xl shadow-2xl p-8 mb-4" style="max-width: 100%;">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h1 class="text-2xl font-bold text-[#009BB9]">Rencana Operasi Bulanan (ROB)</h1>
                        <p class="text-sm text-gray-600 mt-2">Data rencana operasi pembangkit listrik bulanan</p>
                    </div>
                    <div class="flex gap-2">
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

                <!-- Unit Filter -->
                @if(session('unit') === 'mysql')
                <div class="mb-6">
                    <select id="unit-source" 
                            class="border rounded-lg px-4 py-2 text-sm w-64 focus:ring-2 focus:ring-[#009BB9]/20 focus:border-[#009BB9] transition-all duration-200"
                            onchange="updateTable()">
                        <option value="mysql" {{ $unitSource == 'mysql' ? 'selected' : '' }}>UP Kendari</option>
                        <option value="mysql_wua_wua" {{ $unitSource == 'mysql_wua_wua' ? 'selected' : '' }}>Wua Wua</option>
                        <option value="mysql_poasia" {{ $unitSource == 'mysql_poasia' ? 'selected' : '' }}>Poasia</option>
                        <option value="mysql_kolaka" {{ $unitSource == 'mysql_kolaka' ? 'selected' : '' }}>Kolaka</option>
                        <option value="mysql_bau_bau" {{ $unitSource == 'mysql_bau_bau' ? 'selected' : '' }}>Bau Bau</option>
                    </select>
                </div>
                @endif

                <!-- Table Section with Enhanced Styling -->
                <div class="overflow-x-auto border rounded-xl shadow-inner bg-white">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th rowspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider sticky left-0 bg-white text-center border-r z-20">No</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider sticky left-16 bg-white text-center border-r z-20">Sistem Kelistrikan</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/10">Mesin Pembangkit</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/10">Site Pembangkit</th>
                                <th colspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/20">Rencana Realisasi</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/10">Daya PJBTL SILM</th>
                                <th rowspan="2" class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/10">DMP Existing</th>
                                <th colspan="{{ date('t') }}" class="px-6 py-4 text-center text-xs font-semibold text-[#1A3869] uppercase tracking-wider bg-[#009BB9]/30">Tanggal</th>
                            </tr>
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/20">Rencana</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-[#1A3869] uppercase tracking-wider text-center border-r bg-[#009BB9]/20">Realisasi</th>
                                @for ($i = 1; $i <= date('t'); $i++)
                                    <th class="px-6 py-4 text-center text-xs font-semibold text-[#1A3869] uppercase tracking-wider bg-[#009BB9]/30">{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $no = 1; @endphp
                            @foreach($powerPlants as $plant)
                                @foreach($plant->machines as $machine)
                                    <tr class="hover:bg-[#009BB9]/5 transition-all duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white text-center border-r z-10">{{ $no++ }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap sticky left-16 bg-white border-r z-10 font-medium">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r">{{ $machine->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap border-r">{{ $plant->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-[#009BB9]/5">
                                            <span class="data-display">{{ $machine->rencana ?? '-' }}</span>
                                            <textarea name="rencana[{{ $machine->id }}]"
                                                      style="width: 200px;"
                                                      class="data-input hidden w-full text-center border rounded-xl focus:ring-2 focus:ring-[#009BB9]/20 focus:border-[#009BB9] transition-all duration-200 resize-none"
                                                      rows="3">{{ $machine->rencana }}</textarea>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center bg-[#009BB9]/5">
                                            <span class="data-display">{{ $machine->realisasi ?? '-' }}</span>
                                            <textarea name="realisasi[{{ $machine->id }}]"
                                                      style="width: 200px;"
                                                      class="data-input hidden w-full text-center border rounded-xl focus:ring-2 focus:ring-[#009BB9]/20 focus:border-[#009BB9] transition-all duration-200 resize-none"
                                                      rows="3">{{ $machine->realisasi }}</textarea>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display font-medium">{{ $machine->daya_pjbtl_silm ?? '-' }}</span>
                                            <input type="number" 
                                                   name="daya_pjbtl[{{ $machine->id }}]"
                                                   class="data-input hidden w-24 text-center border rounded-xl focus:ring-2 focus:ring-[#009BB9]/20 focus:border-[#009BB9] transition-all duration-200"
                                                   value="{{ $machine->daya_pjbtl_silm }}"
                                                   step="0.01">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="data-display font-medium">{{ $machine->dmp_existing ?? '-' }}</span>
                                            <input type="number" 
                                                   name="dmp_existing[{{ $machine->id }}]"
                                                   class="data-input hidden w-24 text-center border rounded-xl focus:ring-2 focus:ring-[#009BB9]/20 focus:border-[#009BB9] transition-all duration-200"
                                                   value="{{ $machine->dmp_existing }}"
                                                   step="0.01">
                                        </td>
                                        @for ($i = 1; $i <= date('t'); $i++)
                                            @php
                                                $date = now()->format('Y-m-') . sprintf('%02d', $i);
                                                $dailyValue = $machine->rencanaDayaMampu->first()?->getDailyValue($date, 'rencana');
                                            @endphp
                                            <td class="px-6 py-4 whitespace-nowrap text-center border-r bg-[#009BB9]/30">
                                                <span class="data-display">{{ $dailyValue ?? '-' }}</span>
                                                <textarea name="days[{{ $machine->id }}][{{ $i }}]"
                                                          style="width: 200px;"
                                                          class="data-input hidden w-full text-center border rounded-xl focus:ring-2 focus:ring-[#009BB9]/20 focus:border-[#009BB9] transition-all duration-200 resize-none"
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