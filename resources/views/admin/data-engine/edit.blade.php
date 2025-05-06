@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 overflow-x-hidden overflow-y-auto">
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
                <form action="{{ route('admin.data-engine.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div class="flex justify-end gap-4 mb-6">
                        <a href="{{ route('admin.data-engine.index', ['date' => $date]) }}" 
                           class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
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
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Simpan Data
                            </button>
                        </div>
                    </div>

                    <div class="space-y-8">
                        @foreach($powerPlants as $powerPlant)
                            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100">
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
                                                           class="w-24 px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                                                           class="w-24 px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                                                           class="w-24 px-3 py-1 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                                                    <td class="px-4 py-3 text-sm text-gray-500 border border-gray-200">{{ $index + 1 }}</td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <div class="text-sm font-medium text-gray-900">{{ $machine->name }}</div>
                                                        <input type="hidden" name="machines[{{ $machine->id }}][machine_id]" value="{{ $machine->id }}">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="time" 
                                                               name="machines[{{ $machine->id }}][time]" 
                                                               value="{{ now()->format('H:i') }}"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][kw]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][kvar]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <input type="number" 
                                                               name="machines[{{ $machine->id }}][cos_phi]" 
                                                               step="0.01"
                                                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <select name="machines[{{ $machine->id }}][status]" 
                                                                class="p-2 w-[120px] rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                            <option value="">Pilih Status</option>
                                                            <option value="RSH">RSH</option>
                                                            <option value="FO">FO</option>
                                                            <option value="MO">MO</option>
                                                            <option value="P0">P0</option>
                                                            <option value="MB">MB</option>
                                                            <option value="OPS">OPS</option>
                                                        </select>
                                                    </td>
                                                    <td class="px-4 py-3 border border-gray-200">
                                                        <textarea type="text" 
                                                               name="machines[{{ $machine->id }}][keterangan]" 
                                                               class="w-[200px] rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                               placeholder="Masukkan keterangan"></textarea>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 border border-gray-200">
                                                        Tidak ada data mesin untuk unit ini
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
</script>
@endsection