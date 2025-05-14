@extends('layouts.app')

@section('content')
<!-- Add SweetAlert2 CSS and JS in the head section -->
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <header class="bg-white shadow-sm sticky top-0 z-20">
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

                    <h1 class="text-xl font-semibold text-gray-800">Update Data Engine</h1>
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

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Update Data Engine</h2>
                        <p class="text-blue-100 mb-4">Kelola dan perbarui data operasional mesin pembangkit listrik secara efisien.</p>
                    </div>
                </div>

                <form action="{{ route('admin.data-engine.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('admin.data-engine.index', ['date' => $date]) }}" 
                                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                                    </a>
                                    <button type="button"
                                            id="loadLatestDataBtn"
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                                        <i class="fas fa-sync-alt mr-2"></i>Load Data Terakhir
                                    </button>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="relative flex items-center gap-2">
                                        <label for="timeSelector" class="block text-sm font-medium text-gray-700">
                                            Pilih Jam:
                                        </label>
                                        <select id="timeSelector" 
                                                class="w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50 transition duration-150 ease-in-out h-10">
                                            @for ($hour = 0; $hour < 24; $hour++)
                                                <option value="{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00" class="text-center">
                                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                   
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <i class="fas fa-save mr-2"></i>Simpan Data
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @foreach($powerPlants as $powerPlant)
                            @if(!str_contains(strtolower($powerPlant->name), 'moramo') && !str_contains(strtolower($powerPlant->name), 'baruta'))
                            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                                <div class="p-6 bg-gradient-to-r from-blue-50 to-white border-b">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-900">{{ $powerPlant->name }}</h2>
                                        </div>
                                        
                                        <!-- Input fields based on power plant type -->
                                        <div class="flex items-center gap-4">
                                            @if(str_starts_with(strtoupper($powerPlant->name), 'PLTM'))
                                                <div class="flex items-center gap-2">
                                                    <label for="inflow_{{ $powerPlant->id }}" class="text-sm font-medium text-gray-700">
                                                        Inflow:
                                                    </label>
                                                    <input type="number" 
                                                           name="power_plants[{{ $powerPlant->id }}][inflow]" 
                                                           step="0.01"
                                                           class="w-24 px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                           placeholder="Inflow"
                                                           min="0">
                                                    <span class="text-sm text-gray-600">liter/detik</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <label for="tma_{{ $powerPlant->id }}" class="text-sm font-medium text-gray-700">
                                                        TMA:
                                                    </label>
                                                    <input type="number" 
                                                           name="power_plants[{{ $powerPlant->id }}][tma]" 
                                                           step="0.01"
                                                           class="w-24 px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                           placeholder="TMA"
                                                           min="0">
                                                    <span class="text-sm text-gray-600">mdpl</span>
                                                </div>
                                            @else
                                                <div class="flex items-center gap-2">
                                                    <label for="hop_{{ $powerPlant->id }}" class="text-sm font-medium text-gray-700">
                                                        HOP:
                                                    </label>
                                                    <input type="number" 
                                                           name="power_plants[{{ $powerPlant->id }}][hop]" 
                                                           step="0.01"
                                                           class="w-24 px-3 py-1.5 text-sm border border-gray-300 rounded-md focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                           placeholder="HOP"
                                                           min="0">
                                                    <span class="text-sm text-gray-600">hari</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr class="bg-gray-50">
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mesin</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Daya Terpasang (kW)</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SILM/SLO</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DMP Performance Test</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Beban (kW)</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">kVAR</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cos φ</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($powerPlant->machines as $index => $machine)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-4 py-3 text-sm text-gray-500 border-r border-gray-200">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 min-w-[150px]">
                                                        <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                                        <input type="hidden" name="machines[{{ $machine->id }}][machine_id]" value="{{ $machine->id }}">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[120px] border-r border-gray-200">
                                                        <input type="time" 
                                                               name="machines[{{ $machine->id }}][time]" 
                                                               value="{{ now()->format('H:i') }}"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[150px] border-r border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][daya_terpasang]" 
                                                               step="0.01"
                                                               value="{{ $machine->daya_terpasang }}"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan daya">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[180px] border-r border-gray-200">
                                                        <input type="text" 
                                                               name="machines[{{ $machine->id }}][silm_slo]" 
                                                               value="{{ $machine->dmn }}"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan SILM/SLO">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[180px] border-r border-gray-200">
                                                        <input type="text" 
                                                               name="machines[{{ $machine->id }}][dmp_performance]" 
                                                               value="{{ $machine->dmp }}"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan DMP Performance">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[150px] border-r border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][kw]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan beban">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[150px] border-r border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][kvar]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan kVAR">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[150px] border-r border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][cos_phi]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan Cos φ">
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[150px] border-r border-gray-200">
                                                        <select name="machines[{{ $machine->id }}][status]" 
                                                                class="p-2 w-[120px] rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50">
                                                            <option value="">Pilih Status</option>
                                                            <option value="RSH">RSH</option>
                                                            <option value="FO">FO</option>
                                                            <option value="MO">MO</option>
                                                            <option value="P0">P0</option>
                                                            <option value="MB">MB</option>
                                                            <option value="OPS">OPS</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-4 py-3 min-w-[250px] border-r border-gray-200">
                                                        <textarea name="machines[{{ $machine->id }}][keterangan]" 
                                                               class="p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan keterangan"
                                                               rows="2"></textarea>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="11" class="px-4 py-8 text-center text-sm text-gray-500">
                                                        Tidak ada data mesin untuk unit ini
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>

<script>
function updateAllTimes() {
    const selectedTime = document.getElementById('timeSelector').value;
    const timeInputs = document.querySelectorAll('input[type="time"]');
    
    timeInputs.forEach(input => {
        input.value = selectedTime;
    });
}

// Optional: Update times immediately when dropdown changes
document.getElementById('timeSelector').addEventListener('change', function() {
    updateAllTimes();
});

// Updated JavaScript for loading latest data with SweetAlert
document.getElementById('loadLatestDataBtn').addEventListener('click', function() {
    // Show loading state
    Swal.fire({
        title: 'Memuat Data...',
        html: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch(`/admin/data-engine/latest-data?date={{ $date }}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Object.keys(data.machineLogs).forEach(machineId => {
                const log = data.machineLogs[machineId];
                
                // Update form fields with the latest data
                const fields = ['kw', 'kvar', 'cos_phi', 'status', 'keterangan'];
                fields.forEach(field => {
                    const input = document.querySelector(`[name="machines[${machineId}][${field}]"]`);
                    if (input && log[field] !== null) {
                        input.value = log[field];
                    }
                });
            });

            // Show success message with SweetAlert2
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data terakhir berhasil dimuat',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            // Show error message with SweetAlert2
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: data.message || 'Terjadi kesalahan saat memuat data',
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show error message with SweetAlert2
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan saat memuat data',
        });
    });
});
</script>
@endsection