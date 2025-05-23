@extends('layouts.app')

@push('styles')
    <style>
        #timer {
            font-size: 2em;
            font-weight: bold;
            color: #333;
            margin: 10px 0;
            display: none;
            /* Sembunyikan timer secara default */
        }
        .table-responsive {
            overflow-x: auto; /* Mengizinkan scroll horizontal */
            width: 100%; /* Memaksimalkan lebar kolom */
        }
        table {
            width: 100%; /* Memastikan tabel mengambil lebar penuh */
            table-layout: auto; /* Mengizinkan kolom untuk menyesuaikan lebar */
        }
    </style>
@endpush

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="flex h-screen bg-gray-50 overflow-auto">
        <!-- Sidebar -->
      @include('components.sidebar')

        <!-- Main Content -->
        <div id="main-content" class="flex-1 overflow-auto">{{-- tes --}}
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

                        <h1 class="text-xl font-semibold text-gray-800">Penyusunan Data Pembangkit</h1>
                    </div>

                    <div id="timer" class="text-lg font-bold text-gray-800">00:00:00</div>
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
                <x-admin-breadcrumb :breadcrumbs="[['name' => 'Kesiapan Pembangkit', 'url' => null]]" />
            </div>
            <main class="px-6">
                <!-- Konten Kesiapan Pembangkit -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-xl font-semibold text-gray-800">Penyusunan Data Pembangkit</h1>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center sm:justify-end">
                            <button id="manageStatusButton" 
                                    onclick="window.location.href='{{ route('admin.machine-status.view') }}'"
                                    class="w-full sm:w-auto bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 text-center">
                                <i class="fas fa-eye mr-2"></i>Kelola Status
                            </button>
                            <button id="refreshButton" 
                                    onclick="loadData()"
                                    class="w-full sm:w-auto bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 text-center">
                                <i class="fas fa-redo mr-2"></i>Refresh Data
                            </button>
                            <button onclick="saveData()"
                                    class="w-full sm:w-auto bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 text-center">
                                <i class="fas fa-save mr-2"></i>Simpan
                            </button>
                        </div>
                    </div>
                    <div class="mb-4 flex flex-col gap-4">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-row gap-4">
                                    <div class="w-1/3">
                                        <input type="date" 
                                               id="filterDate" 
                                               value="{{ date('Y-m-d') }}" 
                                               class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div class="w-1/3">
                                        <select id="inputTime" 
                                                class="w-full px-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                            <option value="">Pilih Waktu Input</option>
                                            <option value="06:00">06:00 (Pagi)</option>
                                            <option value="11:00">11:00 (Siang)</option>
                                            <option value="14:00">14:00 (Siang)</option>
                                            <option value="18:00">18:00 (Malam)</option>
                                            <option value="19:00">19:00 (Malam)</option>
                                        </select>
                                    </div>

                                    <div class="w-1/3 relative">
                                        <input type="text" 
                                               id="searchInput" 
                                               placeholder="Cari mesin atau unit..."
                                               class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <!-- Search Bar -->
                    <h1 class="text-lg font-semibold uppercase mb-5">KESIAPAN PEMBANGKIT UP KENDARI ( MEGAWATT )</h1>

                    @foreach ($units as $unit)
                        <div class="bg-white rounded-lg shadow p-6 mb-4 unit-table">
                            <div class="overflow-auto">
                                <div class="flex justify-between items-center mb-4">
                                    <div class="flex flex-col">
                                        <h2 class="text-lg font-semibold text-gray-800">{{ $unit->name }}</h2>
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">Update Terakhir:</span>
                                            <span id="last_update_{{ $unit->id }}" class="ml-1">
                                                @php
                                                    $lastUpdate = $operations
                                                        ->where('unit_id', $unit->id)
                                                        ->where('tanggal', date('Y-m-d'))
                                                        ->max('updated_at');
                                                    
                                                    echo $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->format('d/m/Y H:i:s') : '-';
                                                @endphp
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Tambahkan input HOP di sini -->    
                                    <div class="flex items-center gap-x-2">
                                        @if(str_starts_with(trim(strtoupper($unit->name)), 'PLTM '))
                                            <div class="flex flex-col gap-y-2">
                                                <div class="flex items-center gap-x-2">
                                                    <label for="inflow_{{ $unit->id }}" class="text-sm font-medium text-gray-700">
                                                        Inflow:
                                                    </label>
                                                    <input type="number" 
                                                           id="inflow_{{ $unit->id }}" 
                                                           name="inflow_{{ $unit->id }}"
                                                           class="w-24 px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                           placeholder="Masukkan Inflow"
                                                           min="0"
                                                           step="0.01"
                                                           value="{{ old('inflow_' . $unit->id) }}">
                                                    <span class="text-sm text-gray-600">liter/detik</span>
                                                </div>
                                                <div class="flex items-center gap-x-2">
                                                    <label for="tma_{{ $unit->id }}" class="text-sm font-medium text-gray-700">
                                                        TMA:
                                                    </label>
                                                    <input type="number" 
                                                           id="tma_{{ $unit->id }}" 
                                                           name="tma_{{ $unit->id }}"
                                                           class="w-24 px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                           placeholder="Masukkan TMA"
                                                           min="0"
                                                           step="0.01"
                                                           value="{{ old('tma_' . $unit->id) }}">
                                                    <span class="text-sm text-gray-600">mdpl</span>
                                                </div>
                                            </div>
                                        @else
                                            <label for="hop_{{ $unit->id }}" class="text-sm font-medium text-gray-700">
                                                HOP:
                                            </label>
                                            <input type="number" 
                                                   id="hop_{{ $unit->id }}" 
                                                   name="hop_{{ $unit->id }}"
                                                   class="w-24 px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                                                   placeholder="Masukkan HOP"
                                                   min="0"
                                                   value="{{ old('hop_' . $unit->id) }}">
                                            <span class="text-sm text-gray-600">hari</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- Tabel Status Pembangkit -->
                                <div class="table-responsive">
                                    <table class="min-w-full bg-white">
                                        <thead>
                                            <tr>
                                                <th class="px-3 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center border-r border-[#0A749B]">
                                                    No
                                                </th>
                                                <th class="px-3 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center border-r border-[#0A749B]">
                                                    Mesin
                                                </th>
                                                <th class="px-2 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center border-r border-[#0A749B]">
                                                    DAYA TERPASANG (MW)
                                                </th>
                                                <th class="px-3 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center border-r border-[#0A749B]">
                                                    DMN SILM/SLO (MW)
                                                </th>
                                                <th class="px-3 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center border-r border-[#0A749B]">
                                                    DMP PERMORMANCE TEST (MW)
                                                </th>
                                                <th class="px-2 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center border-r border-[#0A749B]">
                                                    BEBAN AKTUAL (MW)
                                                </th>
                                                

                                                <th class="px-3 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center">
                                                    Status
                                                </th>
                                                <th class="px-3 py-2.5 bg-[#0A749B] text-white text-sm font-medium tracking-wider text-center">
                                                    Deskripsi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm">
                                           
                                            @foreach ($unit->machines as $machine)
                                                <tr class="hover:bg-gray-50 border border-gray-200" data-machine-id="{{ $machine->id }}">
                                                    <td class="px-3 py-2 border-r border-gray-200 text-center text-gray-800 w-12">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="px-3 py-2 border-r border-gray-200 text-gray-800" data-id="{{ $machine->id }}">
                                                        {{ $machine->name }}
                                                    </td> 
                                                    <td class="px-3 py-2 border-r border-gray-200 text-center text-gray-800 w-12">
                                                        <input type="number"
                                                               name="installed_power[{{ $machine->id }}]"
                                                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-400 text-gray-800"
                                                               style="width: 100px;"
                                                               value="{{ $operations->where('machine_id', $machine->id)->first()->installed_power ?? '0' }}"
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="Masukkan Daya Terpasang...">
                                                    </td>

                                                    <td class="px-3 py-2 border-r border-gray-200 text-center text-gray-800 w-12">
                                                        <input type="number"
                                                               name="dmn[{{ $machine->id }}]"
                                                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-400 text-gray-800"
                                                               style="width: 100px;"
                                                               value="{{ $operations->where('machine_id', $machine->id)->first()->dmn ?? '0' }}"
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="Masukkan DMN...">
                                                    </td>
                                                    <td class="px-3 py-2 border-r border-gray-200 text-center text-gray-800 w-12">
                                                        <input type="number"
                                                               name="dmp[{{ $machine->id }}]"
                                                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-400 text-gray-800"
                                                               style="width: 100px;"
                                                               value="{{ $operations->where('machine_id', $machine->id)->first()->dmp ?? '0' }}"
                                                               step="0.01"
                                                               min="0"
                                                               placeholder="Masukkan DMP...">
                                                    </td>
                                                    <td class="px-2 py-2 border-r border-gray-200">
                                                        <input type="number" 
                                                               name="load_value[{{ $machine->id }}]"
                                                               class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-400"
                                                               step="0.01"
                                                               min="0"
                                                               style="width: 100px;"
                                                               value="{{ $operations->where('machine_id', $machine->id)->first()->load_value ?? '0' }}"
                                                               placeholder="Masukkan beban..."
                                                               oninput="updateStatus(this)">
                                                    </td>
                                                    <td class="px-3 py-2 border-r border-gray-200">
                                                        <select name="status[{{ $machine->id }}]" 
                                                            style="width: 100px;"
                                                            class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-400">
                                                            <option value="" style="background-color: #FFFFFF">Pilih Status</option>
                                                            <option value="RSH" style="background-color: #FF9800">RSH</option>
                                                            <option value="FO" style="background-color: #f44336">FO</option>
                                                            <option value="MO" style="background-color: #4CAF50">MO</option>
                                                            <option value="P0" style="background-color: #FFFFFF">P0</option>
                                                            <option value="MB" style="background-color: #9E9E9E">MB</option>
                                                            <option value="OPS" style="background-color: #2196F3">OPS</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-3 py-2 border-r border-gray-200">
                                                        <textarea 
                                                            class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:border-blue-400 text-gray-800"
                                                            rows="2" 
                                                            placeholder="Masukkan deskripsi..." 
                                                            style="height: 100px; width: 300px;" 
                                                            name="deskripsi[{{ $machine->id }}]" 
                                                            oninput="autoResize(this)">{{ $operations->where('machine_id', $machine->id)->first()->deskripsi ?? '' }}</textarea>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>
    </div>

    <script>
        let timerInterval;
        let startTime;
        let elapsedTime = 0; // Menyimpan waktu yang telah berlalu
        let isRunning = false;

        // Cek apakah timer sedang berjalan saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const storedStartTime = localStorage.getItem('startTime');
            const storedElapsedTime = localStorage.getItem('elapsedTime');
            const storedIsRunning = localStorage.getItem('isRunning');

            if (storedStartTime && storedIsRunning === 'true') {
                startTime = new Date(parseInt(storedStartTime));
                elapsedTime = parseInt(storedElapsedTime) || 0; // Ambil waktu yang telah berlalu
                isRunning = true;
                updateTimerDisplay(); // Perbarui tampilan timer
                timerInterval = setInterval(updateTimer, 1000); // Mulai interval

                // Tampilkan timer
                document.getElementById('timer').style.display = 'block'; // Tampilkan timer
            } else {
                // Jika timer tidak berjalan, sembunyikan timer
                document.getElementById('timer').style.display = 'none';
            }
        });

        function updateTimer() {
            const now = new Date();
            elapsedTime += 1000; // Tambahkan 1 detik ke waktu yang telah berlalu
            localStorage.setItem('elapsedTime', elapsedTime); // Simpan waktu yang telah berlalu

            updateTimerDisplay(); // Perbarui tampilan timer
        }

        function updateTimerDisplay() {
            const totalElapsedTime = elapsedTime + (isRunning ? new Date() - startTime : 0);
            const hours = Math.floor(totalElapsedTime / (1000 * 60 * 60));
            const minutes = Math.floor((totalElapsedTime % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((totalElapsedTime % (1000 * 60)) / 1000);

            const timerDisplay = document.getElementById('timer');
            timerDisplay.textContent = `${padNumber(hours)}:${padNumber(minutes)}:${padNumber(seconds)}`;
        }

        function padNumber(number) {
            return number.toString().padStart(2, '0');
        }
    </script>
@endsection
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
    function searchTables() {
        const searchInput = document.getElementById('searchInput').value.toLowerCase();
        const filterDate = document.getElementById('filterDate').value;

        // Tampilkan loading indicator
        const mainContent = document.querySelector('.bg-white.rounded-lg.shadow.p-6');
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'text-center py-4';
        loadingIndicator.innerHTML = `
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            <div class="mt-2 text-gray-600">Memuat data...</div>
        `;
        mainContent.appendChild(loadingIndicator);

        // Sembunyikan semua tabel dan baris terlebih dahulu
        const unitTables = document.getElementsByClassName('unit-table');
        Array.from(unitTables).forEach(table => {
            const unitName = table.querySelector('h2').textContent.toLowerCase();
            const shouldShowUnit = unitName.includes(searchInput);
            let hasMatchingMachine = false;

            // Cek setiap baris mesin dalam tabel
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const machineName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const machineMatches = machineName.includes(searchInput);
                
                if (machineMatches || shouldShowUnit) {
                    row.style.display = '';
                    hasMatchingMachine = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Tampilkan/sembunyikan tabel berdasarkan hasil pencarian
            table.style.display = (hasMatchingMachine || shouldShowUnit) ? '' : 'none';
        });

        // Hapus loading indicator
        loadingIndicator.remove();

        // Tampilkan pesan jika tidak ada hasil
        const existingMessage = mainContent.querySelector('.no-results-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        const visibleTables = Array.from(unitTables).filter(table => table.style.display !== 'none');
        if (visibleTables.length === 0) {
            const noResultsMessage = document.createElement('div');
            noResultsMessage.className = 'text-center py-8 text-gray-600 font-semibold text-lg no-results-message';
            noResultsMessage.textContent = 'DATA TIDAK DITEMUKAN';
            mainContent.appendChild(noResultsMessage);
        }
    }

    // Event listener untuk input pencarian
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchValue = this.value.trim();
        if (searchValue.length >= 1) { // Mulai pencarian setelah 1 karakter
            searchTables();
        } else {
            // Tampilkan semua data jika input kosong
            const unitTables = document.getElementsByClassName('unit-table');
            Array.from(unitTables).forEach(table => {
                table.style.display = '';
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => row.style.display = '');
            });

            // Hapus pesan tidak ada hasil jika ada
            const noResultsMessage = document.querySelector('.no-results-message');
            if (noResultsMessage) {
                noResultsMessage.remove();
            }
        }
    });

    function saveData() {
        const inputTime = document.getElementById('inputTime').value;
        if (!inputTime) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih waktu input terlebih dahulu!'
            });
            return;
        }

        // Tampilkan loading indicator
        Swal.fire({
            title: 'Menyimpan Data',
            text: 'Mohon tunggu...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const data = {
            logs: [],
            hops: [],
            inputTime: inputTime
        };

        const tables = document.querySelectorAll('.unit-table table');
        const tanggal = document.getElementById('filterDate').value;

        tables.forEach(table => {
            const unitTable = table.closest('.unit-table');
            const powerPlantId = unitTable.querySelector('input[id^="hop_"], input[id^="inflow_"]').id.split('_')[1];
            const unitName = unitTable.querySelector('h2').textContent.trim().toUpperCase();
            const isPLTM = unitName.startsWith('PLTM');

            if (isPLTM) {
                const inflowValue = unitTable.querySelector(`input[id="inflow_${powerPlantId}"]`)?.value;
                const tmaValue = unitTable.querySelector(`input[id="tma_${powerPlantId}"]`)?.value;
                
                if (inflowValue || tmaValue) {
                    data.hops.push({
                        power_plant_id: powerPlantId,
                        tanggal: tanggal,
                        inflow: inflowValue || null,
                        tma: tmaValue || null
                    });
                }
            } else {
                const hopValue = unitTable.querySelector(`input[id="hop_${powerPlantId}"]`)?.value;
                
                if (hopValue) {
                    data.hops.push({
                        power_plant_id: powerPlantId,
                        tanggal: tanggal,
                        hop_value: hopValue
                    });
                }
            }

            // Tambahkan data status mesin
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const machineId = row.getAttribute('data-machine-id');
                const statusSelect = row.querySelector(`select[name="status[${machineId}]"]`);
                const dmnInput = row.querySelector(`input[name="dmn[${machineId}]"]`);
                const dmpInput = row.querySelector(`input[name="dmp[${machineId}]"]`);
                const loadInput = row.querySelector(`input[name="load_value[${machineId}]"]`);
                const deskripsiInput = row.querySelector(`textarea[name="deskripsi[${machineId}]"]`);

                // Pastikan nilai numerik valid
                const dmn = parseFloat(dmnInput?.value || 0);
                const dmp = parseFloat(dmpInput?.value || 0);
                const loadValue = parseFloat(loadInput?.value || 0);

                if (statusSelect && statusSelect.value) {
                    data.logs.push({
                        machine_id: machineId,
                        tanggal: tanggal,
                        status: statusSelect.value,
                        dmn: isNaN(dmn) ? 0 : dmn,
                        dmp: isNaN(dmp) ? 0 : dmp,
                        load_value: isNaN(loadValue) ? 0 : loadValue,
                        deskripsi: deskripsiInput ? deskripsiInput.value.trim() : null,
                        input_time: inputTime
                    });
                }
            });
        });

        // Debug log untuk melihat data yang akan dikirim
        console.log('Data yang akan dikirim:', data);

        fetch('{{ route('admin.pembangkit.save-status') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil disimpan!',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    loadData();
                });
            } else {
                throw new Error(result.message || 'Terjadi kesalahan saat menyimpan data');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message
            });
        });
    }

    function confirmReset() {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data akan direset kecuali status Gangguan, DMN, dan DMP!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                resetForm();
                Swal.fire(
                    'Reset!',
                    'Data telah direset kecuali status Gangguan, DMN, dan DMP.',
                    'success'
                );
            }
        });
    }

    function resetForm() {
        const tables = document.querySelectorAll('.unit-table table');
        tables.forEach(table => {
            const rows = table.querySelectorAll('tbody tr');
            rows.forEach(row => {
                // Cek status saat ini
                const statusSelect = row.querySelector('select');
                const currentStatus = statusSelect.value;
                const machineId = row.querySelector('td[data-id]').getAttribute('data-id');
                
                // Jika status bukan 'Gangguan', reset field-field yang diizinkan
                if (currentStatus !== 'Gangguan') {
                    // Reset status dropdown dan warnanya
                    statusSelect.value = '';
                    statusSelect.style.backgroundColor = '';
                    
                    // Reset input beban
                    const inputBeban = row.querySelector('td:nth-child(4) input');
                    if (inputBeban) inputBeban.value = '';
                    
                    // Reset komponen dropdown
                    const componentSelect = row.querySelector('.system-select');
                    if (componentSelect) componentSelect.value = '';
                    
                    // Reset equipment
                    const equipmentTextarea = row.querySelector('textarea[name="equipment"]');
                    if (equipmentTextarea) equipmentTextarea.value = '';
                    
                    // Reset deskripsi
                    const deskripsiTextarea = row.querySelector(`textarea[name="deskripsi[${machineId}]"]`);
                    if (deskripsiTextarea) deskripsiTextarea.value = '';
                    
                    // Reset kronologi
                    const kronologiTextarea = row.querySelector(`textarea[name="kronologi[${machineId}]"]`);
                    if (kronologiTextarea) kronologiTextarea.value = '';
                    
                    // Reset action plan
                    const actionPlanTextarea = row.querySelector(`textarea[name="action_plan[${machineId}]"]`);
                    if (actionPlanTextarea) actionPlanTextarea.value = '';
                    
                    // Reset progres
                    const progresTextarea = row.querySelector(`textarea[name="progres[${machineId}]"]`);
                    if (progresTextarea) progresTextarea.value = '';
                    
                    // Reset tanggal
                    const tanggalMulaiInput = row.querySelector(`input[name="tanggal_mulai[${machineId}]"]`);
                    if (tanggalMulaiInput) tanggalMulaiInput.value = '';
                    
                    const targetSelesaiInput = row.querySelector(`input[name="target_selesai[${machineId}]"]`);
                    if (targetSelesaiInput) targetSelesaiInput.value = '';
                }
            });
        });

        // Muat ulang data DMN dan DMP dari MachineOperation
        const tanggal = document.getElementById('filterDate').value;
        fetch(`{{ route('admin.pembangkit.get-status') }}?tanggal=${tanggal}`)
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Update DMN dan DMP dari data MachineOperation
                    result.data.forEach(log => {
                        const machineId = log.machine_id;
                        const row = document.querySelector(`td[data-id="${machineId}"]`)?.closest('tr');
                        
                        if (row) {
                            // Update DMN
                            const dmnCell = row.querySelector('td:nth-child(2)');
                            if (dmnCell) {
                                const dmnValue = log.dmn !== null ? log.dmn : 'N/A';
                                dmnCell.textContent = dmnValue;
                            }
                            
                            // Update DMP
                            const dmpCell = row.querySelector('td:nth-child(3)');
                            if (dmpCell) {
                                const dmpValue = log.dmp !== null ? log.dmp : 'N/A';
                                dmpCell.textContent = dmpValue;
                            }
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Jika gagal mengambil data baru, pertahankan nilai DMN dan DMP yang ada
            });
    }

    // Pastikan event listener untuk tombol refresh terpasang dengan benar
    document.getElementById('refreshButton').addEventListener('click', function() {
        loadData(); // Panggil fungsi loadData saat tombol diklik
    });

    // Perbaikan fungsi loadData
    function loadData() {
        const tanggal = document.getElementById('filterDate').value;
        const inputTime = document.getElementById('inputTime').value;
        
        Swal.fire({
            title: 'Memuat Data',
            text: 'Mohon tunggu...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`{{ route('admin.pembangkit.get-status') }}?tanggal=${tanggal}&input_time=${inputTime}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                updateFormWithData(result.data);
                Swal.close();
            } else {
                throw new Error(result.message || 'Gagal memuat data');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message
            });
        });
    }

    // Fungsi untuk mengupdate form dengan data baru
    function updateFormWithData(data) {
        if (!data || (!data.logs && !data.hops)) {
            console.error('Data tidak valid:', data);
            return;
        }

        // Update data HOP/Inflow/TMA
        if (data.hops) {
            data.hops.forEach(hop => {
                const powerPlantId = hop.power_plant_id;
                const isPLTM = hop.is_pltm;

                if (isPLTM) {
                    // Update Inflow dan TMA untuk PLTM
                    const inflowInput = document.querySelector(`input[id="inflow_${powerPlantId}"]`);
                    const tmaInput = document.querySelector(`input[id="tma_${powerPlantId}"]`);
                    
                    if (inflowInput) inflowInput.value = hop.inflow || '';
                    if (tmaInput) tmaInput.value = hop.tma || '';
                } else {
                    // Update HOP untuk unit non-PLTM
                    const hopInput = document.querySelector(`input[id="hop_${powerPlantId}"]`);
                    if (hopInput) hopInput.value = hop.hop_value || '';
                }
            });
        }

        // Update data mesin
        if (data.logs) {
            // Kelompokkan log berdasarkan unit_id
            const logsByUnit = {};
            data.logs.forEach(log => {
                if (!logsByUnit[log.unit_id]) {
                    logsByUnit[log.unit_id] = [];
                }
                logsByUnit[log.unit_id].push(log);
            });

            // Update waktu terakhir untuk setiap unit
            Object.keys(logsByUnit).forEach(unitId => {
                const logs = logsByUnit[unitId];
                const latestUpdate = logs.reduce((latest, log) => {
                    const updateTime = new Date(log.updated_at);
                    return latest < updateTime ? updateTime : latest;
                }, new Date(0));

                const lastUpdateElement = document.getElementById(`last_update_${unitId}`);
                if (lastUpdateElement) {
                    lastUpdateElement.textContent = latestUpdate.toLocaleString('id-ID', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
            });

            data.logs.forEach(log => {
                const row = document.querySelector(`tr[data-machine-id="${log.machine_id}"]`);
                if (row) {
                    // Update Status
                    const statusSelect = row.querySelector(`select[name="status[${log.machine_id}]"]`);
                    if (statusSelect) {
                        statusSelect.value = log.status || '';
                    }

                    // Update DMN dan DMP
                    const dmnInput = row.querySelector(`input[name="dmn[${log.machine_id}]"]`);
                    if (dmnInput) {
                        dmnInput.value = log.dmn || '0';
                    }

                    const dmpInput = row.querySelector(`input[name="dmp[${log.machine_id}]"]`);
                    if (dmpInput) {
                        dmpInput.value = log.dmp || '0';
                    }

                    // Update Load Value
                    const loadInput = row.querySelector(`input[name="load_value[${log.machine_id}]"]`);
                    if (loadInput) {
                        loadInput.value = log.load_value || '0';
                    }

                    // Update Deskripsi
                    const deskripsiInput = row.querySelector(`textarea[name="deskripsi[${log.machine_id}]"]`);
                    if (deskripsiInput) {
                        deskripsiInput.value = log.deskripsi || '';
                        autoResize(deskripsiInput);
                    }
                }
            });
        }
    }

    // Helper functions
    function updateInputValue(row, selector, value) {
        const element = row.querySelector(selector);
        if (element) {
            element.value = value || '';
            console.log('Updated input:', selector, 'with value:', value);
        } else {
            console.log('Element not found:', selector);
        }
    }

    function updateTextareaValue(row, selector, value) {
        const element = row.querySelector(selector);
        if (element) {
            element.value = value || '';
            autoResize(element);
            console.log('Updated textarea:', selector, 'with value:', value);
        } else {
            console.log('Element not found:', selector);
        }
    }

    function getStatusColor(status) {
        const statusMapping = {
            'RSH': '#FF9800', // Orange
            'FO': '#f44336', // Merah
            'MO': '#4CAF50', // Hijau
            'P0': '#FFFFFF', // Default tanpa warna
            'MB': '#9E9E9E', // Ungu
            'OPS': '#2196F3' // Biru
        };
        return statusMapping[status] || '#FFFFFF'; // Default tanpa warna
    }

    // Pastikan fungsi dijalankan saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadData();
    });

    // Event listener untuk perubahan tanggal
    document.getElementById('filterDate').addEventListener('change', loadData);
</script>
<script>
    function autoResize(textarea) {
        // Set tinggi minimum default
        const minHeight = 60;
        
        // Simpan tinggi scroll saat ini
        const currentScrollHeight = textarea.scrollHeight;
        const currentHeight = parseInt(window.getComputedStyle(textarea).height);
        
        // Hanya resize jika konten benar-benar melebihi area yang tersedia
        if (currentScrollHeight > currentHeight) {
            textarea.style.height = 'auto';
            textarea.style.height = Math.max(minHeight, textarea.scrollHeight) + 'px';
        }
    }

    // Event listener untuk textarea
    document.querySelectorAll('textarea').forEach(textarea => {
        // Set tinggi awal yang tetap
        textarea.style.height = '60px';
        textarea.style.overflow = 'hidden';
        
        // Tambahkan event listener
        textarea.addEventListener('input', function() {
            // Cek apakah scroll diperlukan
            if (this.scrollHeight > this.clientHeight) {
                autoResize(this);
            }
        });
    });
</script>
<script>
    // Tambahkan event listener untuk textarea
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.addEventListener('click', function() {
            this.style.width = '100%'; // Atur lebar menjadi 100% saat diklik
        });
    });
</script>
<script>
const componentOptions = {
    MESIN: [
        'Cylinder Head',
        'Blok Mesin'
    ],
    GENERATOR: [
        'Stator',
        'Generator',
        'Bearing',
        'Exciter'
    ],
    PANEL_SINKRON: [
        'MCB',
        'Relay',
        'Kontaktor',
        'Fuse'
    ],
    KUBIKAL: [
        'MCB',
        'CT',
        'PT',
        'Busbar',
        'Relay'
    ],
    AUXILIARY: [
        'AVR',
        'PCC'
    ]
};

function updateComponentOptions(systemSelect) {
    const componentSelect = systemSelect.closest('tr').querySelector('.component-select');
    const selectedSystem = systemSelect.value;
    
    componentSelect.innerHTML = '<option value="">Pilih Component</option>';
    componentSelect.disabled = !selectedSystem;
    
    if (selectedSystem) {
        componentOptions[selectedSystem].forEach(component => {
            const option = document.createElement('option');
            option.value = component;
            option.textContent = component;
            componentSelect.appendChild(option);
        });
    }
}

function addNewRow() {
    const tbody = document.getElementById('systemTableBody');
    const newRow = tbody.querySelector('.system-row').cloneNode(true);
    
    // Reset nilai-nilai pada baris baru
    newRow.querySelector('.system-select').value = '';
    newRow.querySelector('.component-select').value = '';
    newRow.querySelector('.component-select').disabled = true;
    newRow.querySelector('input[type="date"]').value = '';
    
    tbody.appendChild(newRow);
}

function deleteRow(button) {
    const tbody = document.getElementById('systemTableBody');
    if (tbody.children.length > 1) {
        button.closest('tr').remove();
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Minimal harus ada satu baris!'
        });
    }
}

// Event delegation untuk system-select
document.getElementById('systemTableBody').addEventListener('change', function(e) {
    if (e.target.classList.contains('system-select')) {
        updateComponentOptions(e.target);
    }
});
</script>

<script>
    function updateStatus(loadInput) {
        const row = loadInput.closest('tr');
        const statusSelect = row.querySelector('select[name^="status"]');
        const loadValue = parseFloat(loadInput.value);

        if (loadValue > 0) {
            statusSelect.value = 'OPS';
            statusSelect.style.backgroundColor = '#FF9800';
            statusSelect.style.color = 'black';
        }
    }
</script>
@push('scripts')
@endpush