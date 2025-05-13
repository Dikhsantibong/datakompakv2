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
            @if(session('unit') === 'mysql')
            <div class="bg-gray-50 p-4 rounded-lg mb-4 border border-gray-200">
                <label for="unit-source" class="text-sm font-medium text-gray-700 mr-2">Pilih Unit:</label>
                <select id="unit-source" 
                        class="p-2 border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500"
                        style="width: 300px;"
                        onchange="updateTable()">
                    <option value="">-- Pilih Unit --</option>
                    @foreach($allPowerPlants as $plant)
                        <option value="{{ $plant->unit_source }}" {{ $unitSource == $plant->unit_source ? 'selected' : '' }}>
                            {{ $plant->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            @if(!$unitSource)
            <div class="flex items-center justify-center h-64">
                <div class="text-center">
                    <i class="fas fa-building text-gray-400 text-5xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-gray-600">Silakan Pilih Unit</h2>
                    <p class="text-gray-500 mt-2">Pilih unit untuk melihat data Rencana Daya Mampu</p>
                </div>
            </div>
            @else
            @if($powerPlants->isNotEmpty())
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
                                @foreach($plant->machines as $machine)
                                    @php
                                        $currentDate = now()->format('Y-m-d');
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors duration-150" 
                                        data-machine-id="{{ $machine->id }}" 
                                        data-date="{{ $currentDate }}">
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
                                                $rencanaRows = $data['rencana'] ?? [];
                                                $realisasiRows = $data['realisasi'] ?? [];
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
                                                                <th class="border px-2 py-1 action-column hidden">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="rencana-rows" data-date="{{ $date }}">
                                                            @foreach($rencanaRows as $index => $row)
                                                            <tr class="transition-colors duration-150 hover:bg-blue-200">
                                                                <td class="border px-2 py-1">
                                                                    <input type="number" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $index }}][beban]" class="data-input hidden w-24 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $row['beban'] ?? '' }}" step="0.01">
                                                                    <span class="data-display">{{ $row['beban'] ?? '-' }}</span>
                                                                </td>
                                                                <td class="border px-2 py-1">
                                                                    <input type="time" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $index }}][on]" class="data-input hidden w-24 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $row['on'] ?? '' }}">
                                                                    <span class="data-display">{{ $row['on'] ?? '-' }}</span>
                                                                </td>
                                                                <td class="border px-2 py-1">
                                                                    <input type="time" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $index }}][off]" class="data-input hidden w-24 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $row['off'] ?? '' }}">
                                                                    <span class="data-display">{{ $row['off'] ?? '-' }}</span>
                                                                </td>
                                                                <td class="border px-2 py-1">
                                                                    <input type="number" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $index }}][durasi]" class="data-input hidden w-20 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $row['durasi'] ?? '' }}" step="0.01">
                                                                    <span class="data-display">{{ $row['durasi'] ?? '-' }}</span>
                                                                </td>
                                                                <td class="border px-2 py-1">
                                                                    <input type="text" name="rencana[{{ $machine->id }}][{{ $date }}][{{ $index }}][keterangan]" class="data-input hidden w-28 text-center border rounded focus:ring-2 focus:ring-blue-400 focus:border-blue-500 transition-all duration-150" value="{{ $row['keterangan'] ?? '' }}">
                                                                    <span class="data-display">{{ $row['keterangan'] ?? '-' }}</span>
                                                                </td>
                                                                <td class="border px-2 py-1 action-column hidden">
                                                                    <button type="button" class="delete-row text-red-500 hover:text-red-700" onclick="deleteRow(this)">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <td colspan="6" class="text-center py-2">
                                                                    <button type="button" class="add-row-btn hidden bg-blue-500 text-white px-3 py-2 rounded text-xs hover:bg-blue-600" onclick="addNewRowBoth(this)">
                                                                        <i class="fas fa-plus mr-1"></i> Tambah Baris
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        </tfoot>
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
                                                                <th class="border px-2 py-1 action-column hidden">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="realisasi-rows" data-date="{{ $date }}">
                                                            @if(is_array($realisasiRows))
                                                                @foreach($realisasiRows as $index => $row)
                                                                <tr class="transition-colors duration-150 hover:bg-green-200">
                                                                    <td class="border px-2 py-1">
                                                                        <input type="number" name="realisasi[{{ $machine->id }}][{{ $date }}][{{ $index }}][beban]" class="data-input hidden w-28 text-center border rounded focus:ring-2 focus:ring-green-400 focus:border-green-500 transition-all duration-150" value="{{ $row['beban'] ?? '' }}" step="0.01">
                                                                        <span class="data-display">{{ $row['beban'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1">
                                                                        <input type="text" name="realisasi[{{ $machine->id }}][{{ $date }}][{{ $index }}][keterangan]" class="data-input hidden w-32 text-center border rounded focus:ring-2 focus:ring-green-400 focus:border-green-500 transition-all duration-150" value="{{ $row['keterangan'] ?? '' }}">
                                                                        <span class="data-display">{{ $row['keterangan'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1 action-column hidden">
                                                                        <button type="button" class="delete-row text-red-500 hover:text-red-700" onclick="deleteRow(this)">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                            @else
                                                                <tr class="transition-colors duration-150 hover:bg-green-200">
                                                                    <td class="border px-2 py-1">
                                                                        <input type="number" name="realisasi[{{ $machine->id }}][{{ $date }}][0][beban]" class="data-input hidden w-28 text-center border rounded focus:ring-2 focus:ring-green-400 focus:border-green-500 transition-all duration-150" value="{{ $realisasiRows['beban'] ?? '' }}" step="0.01">
                                                                        <span class="data-display">{{ $realisasiRows['beban'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1">
                                                                        <input type="text" name="realisasi[{{ $machine->id }}][{{ $date }}][0][keterangan]" class="data-input hidden w-32 text-center border rounded focus:ring-2 focus:ring-green-400 focus:border-green-500 transition-all duration-150" value="{{ $realisasiRows['keterangan'] ?? '' }}">
                                                                        <span class="data-display">{{ $realisasiRows['keterangan'] ?? '-' }}</span>
                                                                    </td>
                                                                    <td class="border px-2 py-1 action-column hidden">
                                                                        <button type="button" class="delete-row text-red-500 hover:text-red-700" onclick="deleteRow(this)">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endif
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
            @else
            <div class="flex items-center justify-center h-64 mt-4">
                <div class="text-center">
                    <i class="fas fa-database text-gray-400 text-5xl mb-4"></i>
                    <h2 class="text-xl font-semibold text-gray-600">Tidak Ada Data</h2>
                    <p class="text-gray-500 mt-2">Tidak ada data Rencana Daya Mampu untuk unit yang dipilih</p>
                </div>
            </div>
            @endif
            @endif
        </main>
    </div>
</div>


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
        if (!unitSource) {
            window.location.href = '{{ route('admin.rencana-daya-mampu') }}';
        } else {
            window.location.href = `{{ route('admin.rencana-daya-mampu') }}?unit_source=${unitSource}`;
        }
    }

    let isEditMode = false;

    function toggleEditMode() {
        isEditMode = !isEditMode;
        const editButton = document.getElementById('editModeButton');
        const saveButton = document.getElementById('saveButton');
        const displays = document.querySelectorAll('.data-display');
        const inputs = document.querySelectorAll('.data-input');
        const addRowBtns = document.querySelectorAll('.add-row-btn');
        const actionColumns = document.querySelectorAll('.action-column');
        const deleteButtons = document.querySelectorAll('.delete-row');

        if (isEditMode) {
            editButton.classList.add('bg-gray-500');
            editButton.classList.remove('bg-blue-500');
            editButton.innerHTML = '<i class="fas fa-times mr-2"></i>Batal';
            saveButton.classList.remove('hidden');
            displays.forEach(el => el.style.display = 'none');
            inputs.forEach(el => {
                el.style.display = 'inline-block';
                el.classList.remove('hidden');
            });
            addRowBtns.forEach(btn => btn.classList.remove('hidden'));
            actionColumns.forEach(col => col.classList.remove('hidden'));
            deleteButtons.forEach(btn => btn.classList.remove('hidden'));
        } else {
            editButton.classList.remove('bg-gray-500');
            editButton.classList.add('bg-blue-500');
            editButton.innerHTML = '<i class="fas fa-edit mr-2"></i>Mode Edit';
            saveButton.classList.add('hidden');
            displays.forEach(el => el.style.display = 'inline-block');
            inputs.forEach(el => {
                el.style.display = 'none';
                el.classList.add('hidden');
            });
            addRowBtns.forEach(btn => btn.classList.add('hidden'));
            actionColumns.forEach(col => col.classList.add('hidden'));
            deleteButtons.forEach(btn => btn.classList.add('hidden'));
        }
    }

    function addNewRowBoth(button) {
        const mainRow = button.closest('tr[data-machine-id]');
        const machineId = mainRow.dataset.machineId;
        const rencanaTable = mainRow.querySelector('.rencana-rows');
        const realisasiTable = mainRow.querySelector('.realisasi-rows');
        const date = rencanaTable.dataset.date;
        
        // Add row to Rencana
        const lastRencanaRow = rencanaTable.lastElementChild;
        const newRencanaRow = lastRencanaRow.cloneNode(true);
        const rencanaInputs = newRencanaRow.querySelectorAll('input');
        const rencanaRowIndex = rencanaTable.children.length;
        
        rencanaInputs.forEach(input => {
            const fieldName = input.name.match(/\[([^\]]*)\]$/)[1]; // Get the last field name (beban, durasi, etc.)
            input.name = `rencana[${machineId}][${date}][${rencanaRowIndex}][${fieldName}]`;
            input.value = '';
            // Also update the display span
            const displaySpan = input.parentElement.querySelector('.data-display');
            if (displaySpan) {
                displaySpan.textContent = '-';
            }
        });
        
        rencanaTable.appendChild(newRencanaRow);
        
        // Add row to Realisasi
        const lastRealisasiRow = realisasiTable.lastElementChild;
        const newRealisasiRow = lastRealisasiRow.cloneNode(true);
        const realisasiInputs = newRealisasiRow.querySelectorAll('input');
        const realisasiRowIndex = realisasiTable.children.length;
        
        realisasiInputs.forEach(input => {
            const fieldName = input.name.match(/\[([^\]]*)\]$/)[1]; // Get the last field name (beban, keterangan)
            input.name = `realisasi[${machineId}][${date}][${realisasiRowIndex}][${fieldName}]`;
            input.value = '';
            // Also update the display span
            const displaySpan = input.parentElement.querySelector('.data-display');
            if (displaySpan) {
                displaySpan.textContent = '-';
            }
        });
        
        realisasiTable.appendChild(newRealisasiRow);

        // Make sure the new inputs are visible if in edit mode
        if (isEditMode) {
            newRencanaRow.querySelectorAll('.data-input').forEach(input => {
                input.style.display = 'inline-block';
                input.classList.remove('hidden');
            });
            newRencanaRow.querySelectorAll('.data-display').forEach(span => {
                span.style.display = 'none';
            });
            newRealisasiRow.querySelectorAll('.data-input').forEach(input => {
                input.style.display = 'inline-block';
                input.classList.remove('hidden');
            });
            newRealisasiRow.querySelectorAll('.data-display').forEach(span => {
                span.style.display = 'none';
            });
        }
    }

    function deleteRow(button) {
        const row = button.closest('tr');
        const tbody = row.closest('tbody');
        const isRencana = tbody.classList.contains('rencana-rows');
        const mainRow = button.closest('tr[data-machine-id]');
        
        const rencanaRows = mainRow.querySelector('.rencana-rows').children;
        const realisasiRows = mainRow.querySelector('.realisasi-rows').children;
        
        if (rencanaRows.length > 1 && realisasiRows.length > 1) {
            const rencanaIndex = Array.from(rencanaRows).indexOf(row);
            const realisasiIndex = Array.from(realisasiRows).indexOf(row);
            
            if (isRencana) {
                rencanaRows[rencanaIndex].remove();
                realisasiRows[rencanaIndex]?.remove();
                reindexRows(mainRow.querySelector('.rencana-rows'));
                reindexRealisasiRows(mainRow.querySelector('.realisasi-rows'));
            } else {
                realisasiRows[realisasiIndex].remove();
                rencanaRows[realisasiIndex]?.remove();
                reindexRows(mainRow.querySelector('.rencana-rows'));
                reindexRealisasiRows(mainRow.querySelector('.realisasi-rows'));
            }
        } else {
            Swal.fire({
                title: 'Perhatian!',
                text: 'Minimal harus ada satu baris data!',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
        }
    }

    function reindexRows(tbody) {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                const nameParts = input.name.split('[');
                input.name = `${nameParts[0]}[${nameParts[1]}[${nameParts[2]}[${index}]${nameParts[3].substring(nameParts[3].indexOf(']'))}`;
            });
        });
    }

    function calculateDuration(row) {
        const onInput = row.querySelector('input[name*="[on]"]');
        const offInput = row.querySelector('input[name*="[off]"]');
        const durasiInput = row.querySelector('input[name*="[durasi]"]');
        
        if (onInput.value && offInput.value) {
            const onTime = new Date(`2000-01-01 ${onInput.value}`);
            let offTime = new Date(`2000-01-01 ${offInput.value}`);
            
            // Jika waktu off lebih kecil dari waktu on, berarti melewati tengah malam
            if (offTime < onTime) {
                offTime = new Date(`2000-01-02 ${offInput.value}`);
            }
            
            const diffHours = (offTime - onTime) / (1000 * 60 * 60);
            durasiInput.value = diffHours.toFixed(2);
        }
    }

    // Attach event listeners for time inputs
    document.addEventListener('input', function(e) {
        if (e.target.matches('input[type="time"]')) {
            calculateDuration(e.target.closest('tr'));
        }
    });

    function reindexRealisasiRows(tbody) {
        const rows = tbody.querySelectorAll('tr');
        rows.forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                const nameParts = input.name.split('[');
                input.name = `${nameParts[0]}[${nameParts[1]}[${nameParts[2]}[${index}]${nameParts[3].substring(nameParts[3].indexOf(']'))}`;
            });
        });
    }

    function saveData() {
        const saveButton = document.getElementById('saveButton');
        saveButton.disabled = true;
        saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';

        const data = {
            rencana: {},
            realisasi: {}
        };

        try {
            // Collect rencana data
            document.querySelectorAll('tr[data-machine-id]').forEach(tr => {
                const machineId = tr.dataset.machineId;
                const rencanaRows = tr.querySelector('.rencana-rows');
                const date = rencanaRows?.dataset.date;
                
                if (!machineId || !date) {
                    throw new Error('Data mesin atau tanggal tidak valid');
                }

                if (!data.rencana[machineId]) {
                    data.rencana[machineId] = {};
                }
                if (!data.rencana[machineId][date]) {
                    data.rencana[machineId][date] = [];
                }

                // Get all rows in the rencana table
                const rows = rencanaRows.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    console.log(`Processing rencana row ${index + 1}`); // Debug log

                    const beban = row.querySelector('input[name*="[beban]"]')?.value?.trim();
                    const durasi = row.querySelector('input[name*="[durasi]"]')?.value?.trim();
                    const keterangan = row.querySelector('input[name*="[keterangan]"]')?.value?.trim();
                    const on = row.querySelector('input[name*="[on]"]')?.value?.trim();
                    const off = row.querySelector('input[name*="[off]"]')?.value?.trim();

                    console.log('Row data:', { beban, durasi, keterangan, on, off }); // Debug log

                    // Skip completely empty rows
                    if (!beban && !durasi && !keterangan && !on && !off) {
                        console.log('Skipping empty row:', index + 1);
                        return;
                    }

                    // Validate required fields if any field is filled
                    if (beban || durasi || keterangan || on || off) {
                        if (!beban) {
                            throw new Error(`Beban harus diisi pada baris ${index + 1}`);
                        }
                        if (!on || !off) {
                            throw new Error(`Waktu ON dan OFF harus diisi pada baris ${index + 1}`);
                        }
                    }

                    // Validate time format if provided
                    if (on && !on.match(/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/)) {
                        throw new Error(`Format waktu ON tidak valid pada baris ${index + 1}`);
                    }
                    if (off && !off.match(/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/)) {
                        throw new Error(`Format waktu OFF tidak valid pada baris ${index + 1}`);
                    }

                    // Validate numeric values
                    if (beban && isNaN(beban)) {
                        throw new Error(`Beban harus berupa angka pada baris ${index + 1}`);
                    }
                    if (durasi && isNaN(durasi)) {
                        throw new Error(`Durasi harus berupa angka pada baris ${index + 1}`);
                    }

                    // Add row data
                    data.rencana[machineId][date].push({
                        beban: beban || '',
                        durasi: durasi || '',
                        keterangan: keterangan || '',
                        on: on || '',
                        off: off || ''
                    });
                });

                // Remove the date entry if no valid rows were added
                if (data.rencana[machineId][date].length === 0) {
                    delete data.rencana[machineId][date];
                }
            });

            // Collect realisasi data
            document.querySelectorAll('tr[data-machine-id]').forEach(tr => {
                const machineId = tr.dataset.machineId;
                const realisasiRows = tr.querySelector('.realisasi-rows');
                const date = realisasiRows?.dataset.date;
                
                if (!machineId || !date) {
                    throw new Error('Data mesin atau tanggal tidak valid');
                }

                if (!data.realisasi[machineId]) {
                    data.realisasi[machineId] = {};
                }
                if (!data.realisasi[machineId][date]) {
                    data.realisasi[machineId][date] = [];
                }

                // Get all rows in the realisasi table
                const rows = realisasiRows.querySelectorAll('tr');
                rows.forEach((row, index) => {
                    console.log(`Processing realisasi row ${index + 1}`); // Debug log

                    const beban = row.querySelector('input[name*="realisasi"][name*="[beban]"]')?.value?.trim();
                    const keterangan = row.querySelector('input[name*="realisasi"][name*="[keterangan]"]')?.value?.trim();

                    console.log('Realisasi row data:', { beban, keterangan }); // Debug log

                    // Skip completely empty rows
                    if (!beban && !keterangan) {
                        console.log('Skipping empty realisasi row:', index + 1);
                        return;
                    }

                    // Validate required fields if any field is filled
                    if (beban || keterangan) {
                        if (!beban) {
                            throw new Error(`Beban realisasi harus diisi pada baris ${index + 1}`);
                        }
                    }

                    // Validate numeric values
                    if (beban && isNaN(beban)) {
                        throw new Error(`Beban realisasi harus berupa angka pada baris ${index + 1}`);
                    }

                    // Add row data
                    data.realisasi[machineId][date].push({
                        beban: beban || '',
                        keterangan: keterangan || ''
                    });
                });

                // Remove the date entry if no valid rows were added
                if (data.realisasi[machineId][date].length === 0) {
                    delete data.realisasi[machineId][date];
                }
            });

            // Log the final data structure before sending
            console.log('Data to be sent:', JSON.stringify(data, null, 2));

            // Send data to server
            fetch('{{ route("admin.rencana-daya-mampu.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || 'Terjadi kesalahan pada server');
                    });
                }
                return response.json();
            })
            .then(result => {
                if (!result.success) {
                    throw new Error(result.message || 'Gagal menyimpan data');
                }
                
                Swal.fire({
                    title: result.title || 'Berhasil!',
                    text: result.message || 'Data berhasil disimpan',
                    icon: result.icon || 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            })
            .catch(error => {
                console.error('Error details:', error);
                Swal.fire({
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menyimpan data',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            })
            .finally(() => {
                saveButton.disabled = false;
                saveButton.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan';
            });

        } catch (error) {
            console.error('Error in data collection:', error);
            Swal.fire({
                title: 'Error!',
                text: error.message || 'Terjadi kesalahan saat mengumpulkan data',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            saveButton.disabled = false;
            saveButton.innerHTML = '<i class="fas fa-save mr-2"></i>Simpan';
        }
    }
</script>
@endsection 