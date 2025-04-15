@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="flex-1 overflow-x-hidden overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
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

                    <h1 class="text-xl font-semibold text-gray-800">Meeting dan Mutasi Shift Operator</h1>
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
        

        <!-- Main Content Area -->
        <div class="container mx-auto px-6 py-8">
            <div x-data="{ activeTab: 'kondisi-mesin' }" class="bg-white rounded-lg shadow-md">
                <!-- Tabs Navigation -->
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-4 px-4" aria-label="Tabs">
                        <button @click="activeTab = 'kondisi-mesin'" 
                                :class="{'border-blue-500 text-blue-600': activeTab === 'kondisi-mesin',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-mesin'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Kondisi Mesin
                        </button>
                        
                        <button @click="activeTab = 'kondisi-alat-bantu'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'kondisi-alat-bantu',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-alat-bantu'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Kondisi Alat Bantu
                        </button>
                        
                        <button @click="activeTab = 'kondisi-resource'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'kondisi-resource',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-resource'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Kondisi Resource
                        </button>
                        
                        <button @click="activeTab = 'kondisi-k3l'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'kondisi-k3l',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'kondisi-k3l'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Kondisi K3L
                        </button>
                        
                        <button @click="activeTab = 'catatan-sistem'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'catatan-sistem',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'catatan-sistem'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Catatan Kondisi Sistem
                        </button>
                        
                        <button @click="activeTab = 'catatan-validasi'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'catatan-validasi',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'catatan-validasi'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Catatan Umum Shift Validasi Serah Terima
                        </button>
                        
                        <button @click="activeTab = 'absensi-resume'"
                                :class="{'border-blue-500 text-blue-600': activeTab === 'absensi-resume',
                                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'absensi-resume'}"
                                class="py-4 px-1 border-b-2 font-medium text-sm">
                            Absensi dan Resume Rapat
                        </button>
                    </nav>
                </div>

                <!-- Tab Panels -->
                <div class="p-4">
                    <!-- Kondisi Mesin -->
                    <div x-show="activeTab === 'kondisi-mesin'" class="space-y-4">
                        <form action="{{ route('admin.meeting-shift.store') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Mesin</th>
                                            <th colspan="5" class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Status</th>
                                            <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Operasi</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Stand By</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">HAR Rutin</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">HAR Non Rutin</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Gangguan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($machines as $index => $machine)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2">
                                                {{ $machine->name }}
                                                <input type="hidden" name="machine_status[{{ $index }}][machine_id]" value="{{ $machine->id }}">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="machine_status[{{ $index }}][status]" value="operasi" class="form-radio" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="machine_status[{{ $index }}][status]" value="standby" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="machine_status[{{ $index }}][status]" value="har_rutin" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="machine_status[{{ $index }}][status]" value="har_nonrutin" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="machine_status[{{ $index }}][status]" value="gangguan" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="text" name="machine_status[{{ $index }}][keterangan]" class="w-full p-1 border rounded" placeholder="Masukkan keterangan">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tombol Submit -->
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Kondisi Alat Bantu -->
                    <div x-show="activeTab === 'kondisi-alat-bantu'" class="space-y-4">
                        <form action="{{ route('admin.meeting-shift.store-alat-bantu') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Alat Bantu</th>
                                            <th colspan="4" class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Status</th>
                                            <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Normal</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Abnormal</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Gangguan</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">FLM</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $alatBantu = [
                                            'system pelumas',
                                            'system bbm',
                                            'system jcw/HT',
                                            'system cw/LT',
                                            'system start'
                                        ];
                                        @endphp

                                        @foreach($alatBantu as $index => $alat)
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2">
                                                {{ $alat }}
                                                <input type="hidden" name="alat_bantu[{{ $index }}][name]" value="{{ $alat }}">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="alat_bantu[{{ $index }}][status]" value="normal" class="form-radio" required>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="alat_bantu[{{ $index }}][status]" value="abnormal" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="alat_bantu[{{ $index }}][status]" value="gangguan" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2 text-center">
                                                <input type="radio" name="alat_bantu[{{ $index }}][status]" value="flm" class="form-radio">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <input type="text" name="alat_bantu[{{ $index }}][keterangan]" class="w-full p-1 border rounded" placeholder="Masukkan keterangan">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tombol Submit -->
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Kondisi Resource -->
                    <div x-show="activeTab === 'kondisi-resource'" class="space-y-4">
                        <form action="{{ route('admin.meeting-shift.store-resource') }}" method="POST">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Stok</th>
                                            <th colspan="5" class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Status</th>
                                            <th rowspan="2" class="border border-gray-300 px-4 py-2 text-center align-middle bg-gray-50">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">0%-20%</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">21%-40%</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">41%-61%</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">61%-80%</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">up to 80%</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                        $resources = [
                                            ['name' => 'PELUMAS', 'is_category' => true],
                                            ['name' => 'stok pelumas', 'is_category' => false],
                                            ['name' => 'BBM', 'is_category' => true],
                                            ['name' => 'storage tank', 'is_category' => false],
                                            ['name' => 'service tank', 'is_category' => false],
                                            ['name' => 'AIR PENDINGIN', 'is_category' => true],
                                            ['name' => 'storage/bak air', 'is_category' => false],
                                            ['name' => 'service tank', 'is_category' => false],
                                            ['name' => 'UDARA START', 'is_category' => true],
                                            ['name' => 'storage tank', 'is_category' => false],
                                            ['name' => 'service tank', 'is_category' => false],
                                        ];
                                        @endphp

                                        @foreach($resources as $index => $resource)
                                        <tr class="{{ $resource['is_category'] ? 'bg-gray-100 font-semibold' : 'pl-8' }}">
                                            <td class="border border-gray-300 px-4 py-2 {{ $resource['is_category'] ? 'text-lg' : 'pl-8' }}">
                                                {{ $resource['name'] }}
                                                <input type="hidden" name="resources[{{ $index }}][name]" value="{{ $resource['name'] }}">
                                            </td>
                                            @if(!$resource['is_category'])
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    <input type="radio" name="resources[{{ $index }}][status]" value="0-20" class="form-radio" required>
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    <input type="radio" name="resources[{{ $index }}][status]" value="21-40" class="form-radio">
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    <input type="radio" name="resources[{{ $index }}][status]" value="41-61" class="form-radio">
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    <input type="radio" name="resources[{{ $index }}][status]" value="61-80" class="form-radio">
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2 text-center">
                                                    <input type="radio" name="resources[{{ $index }}][status]" value="up-80" class="form-radio">
                                                </td>
                                                <td class="border border-gray-300 px-4 py-2">
                                                    <input type="text" name="resources[{{ $index }}][keterangan]" class="w-full p-1 border rounded" placeholder="Masukkan keterangan">
                                                </td>
                                            @else
                                                <td colspan="6" class="border border-gray-300"></td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tombol Submit -->
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Kondisi K3L -->
                    <div x-show="activeTab === 'kondisi-k3l'" class="space-y-4">
                        <form action="{{ route('admin.meeting-shift.store-k3l') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-300">
                                    <thead>
                                        <tr>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Potensi Bahaya</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Uraian</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Saran & Tindak Lanjut</th>
                                            <th class="border border-gray-300 px-4 py-2 text-center bg-gray-50">Eviden</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Unsafe Action -->
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">
                                                Unsafe Action
                                                <input type="hidden" name="k3l[0][type]" value="unsafe_action">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <textarea name="k3l[0][uraian]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan uraian unsafe action..."></textarea>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <textarea name="k3l[0][saran]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan saran dan tindak lanjut..."></textarea>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <div class="flex flex-col space-y-2">
                                                    <input type="file" name="k3l[0][eviden]" class="p-1 border rounded" accept="image/*,.pdf">
                                                    <span class="text-sm text-gray-500">Format: JPG, PNG, PDF (Max 2MB)</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Unsafe Condition -->
                                        <tr>
                                            <td class="border border-gray-300 px-4 py-2 font-semibold bg-gray-100">
                                                Unsafe Condition
                                                <input type="hidden" name="k3l[1][type]" value="unsafe_condition">
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <textarea name="k3l[1][uraian]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan uraian unsafe condition..."></textarea>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <textarea name="k3l[1][saran]" rows="3" class="w-full p-2 border rounded" placeholder="Masukkan saran dan tindak lanjut..."></textarea>
                                            </td>
                                            <td class="border border-gray-300 px-4 py-2">
                                                <div class="flex flex-col space-y-2">
                                                    <input type="file" name="k3l[1][eviden]" class="p-1 border rounded" accept="image/*,.pdf">
                                                    <span class="text-sm text-gray-500">Format: JPG, PNG, PDF (Max 2MB)</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <!-- Tombol Submit -->
                            <div class="flex justify-end mt-4">
                                <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Catatan Kondisi Sistem -->
                    <div x-show="activeTab === 'catatan-sistem'" class="space-y-4">
                        <div class="text-gray-500 text-center py-8">
                            Konten untuk Catatan Kondisi Sistem akan ditampilkan di sini
                        </div>
                    </div>

                    <!-- Catatan Umum Shift Validasi -->
                    <div x-show="activeTab === 'catatan-validasi'" class="space-y-4">
                        <div class="text-gray-500 text-center py-8">
                            Konten untuk Catatan Umum Shift Validasi Serah Terima akan ditampilkan di sini
                        </div>
                    </div>

                    <!-- Absensi dan Resume -->
                    <div x-show="activeTab === 'absensi-resume'" class="space-y-4">
                        <div class="text-gray-500 text-center py-8">
                            Konten untuk Absensi dan Resume Rapat akan ditampilkan di sini
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 