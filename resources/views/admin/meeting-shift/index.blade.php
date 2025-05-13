@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 main-content">
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

                    <h1 class="text-xl font-semibold text-gray-800">Meeting dan Mutasi Shift</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Meeting dan Mutasi Shift', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
                    <div class="p-6">
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                <div class="max-w-3xl">
                    <h2 class="text-2xl font-bold mb-2">Meeting dan Mutasi Shift Operator</h2>
                    <p class="text-blue-100 mb-4">Kelola dan monitor aktivitas meeting dan mutasi shift operator untuk memastikan operasional yang optimal.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('admin.meeting-shift.list') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 bg-white rounded-md hover:bg-gray-50">
                            <i class="fas fa-list mr-2"></i> Lihat Daftar Meeting
                        </a>
                        
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form id="meetingShiftForm" action="{{ route('admin.meeting-shift.store') }}" method="POST" enctype="multipart/form-data" class="mt-8">
                @csrf
                <!-- Add error alert -->
                @if ($errors->any())
                <div class="mb-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">
                                    Mohon periksa kembali form anda
                                </p>
                                <ul class="mt-1 text-xs text-red-600 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Add success message -->
                @if(session('success'))
                <div class="mb-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="space-y-8">
                    <!-- Header Information -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div>
                                    <label for="current_shift" class="block text-sm font-medium text-gray-700">Shift Saat Ini</label>
                                    <select id="current_shift" name="current_shift" required
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm rounded-md">
                                        <option value="">Pilih Shift</option>
                                        <option value="A">Shift A</option>
                                        <option value="B">Shift B</option>
                                        <option value="C">Shift C</option>
                                        <option value="D">Shift D</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" required
                                        class="mt-1 focus:ring-[#009BB9] focus:border-[#009BB9] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kondisi Mesin -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Kondisi Mesin</h3>
                                <div class="mt-6">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nama Mesin
                                                        </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Status
                                                        </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Keterangan
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach($machines as $index => $machine)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                            {{ $machine->name }}
                                                <input type="hidden" name="machine_statuses[{{ $index }}][machine_id]" value="{{ $machine->id }}">
                                                        </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="space-y-2">
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="operasi"
                                                            class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                            data-machine-index="{{ $index }}"
                                                            onchange="validateMachineStatus({{ $index }})">
                                                        <label class="ml-2 text-sm text-gray-700">Operasi</label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="standby"
                                                            class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                            data-machine-index="{{ $index }}"
                                                            onchange="validateMachineStatus({{ $index }})">
                                                        <label class="ml-2 text-sm text-gray-700">Standby</label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="har_rutin"
                                                            class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                            data-machine-index="{{ $index }}"
                                                            onchange="validateMachineStatus({{ $index }})">
                                                        <label class="ml-2 text-sm text-gray-700">HAR Rutin</label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="har_nonrutin"
                                                            class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                            data-machine-index="{{ $index }}"
                                                            onchange="validateMachineStatus({{ $index }})">
                                                        <label class="ml-2 text-sm text-gray-700">HAR Non-Rutin</label>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="machine_statuses[{{ $index }}][status][]" value="gangguan"
                                                            class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded machine-status-checkbox"
                                                            data-machine-index="{{ $index }}"
                                                            onchange="validateMachineStatus({{ $index }})">
                                                        <label class="ml-2 text-sm text-gray-700">Gangguan</label>
                                                    </div>
                                                </div>
                                                <!-- Error message for machine status -->
                                                <div class="hidden text-red-500 text-xs mt-1" id="machine-status-error-{{ $index }}">
                                                    Pilih minimal satu status
                                                </div>
                                                        </td>
                                            <td class="px-6 py-4">
                                                <textarea name="machine_statuses[{{ $index }}][keterangan]" rows="3"
                                                    class="p-2 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                                                      placeholder="Masukkan keterangan..."></textarea>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                            </div>
                                        </div>
                                    </div>

                                    <!-- Kondisi Alat Bantu -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Kondisi Alat Bantu</h3>
                            <div class="mt-6">
                                <div class="space-y-4" id="alat-bantu-container">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 alat-bantu-item">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Alat</label>
                                            <input type="text" name="auxiliary_equipment[0][name]" required
                                                class="mt-1 focus:ring-[#009BB9] focus:border-[#009BB9] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <div class="mt-2 space-y-2">
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="auxiliary_equipment[0][status][]" value="normal"
                                                        class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded">
                                                    <label class="ml-2 text-sm text-gray-700">Normal</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="auxiliary_equipment[0][status][]" value="abnormal"
                                                        class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded">
                                                    <label class="ml-2 text-sm text-gray-700">Abnormal</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="auxiliary_equipment[0][status][]" value="gangguan"
                                                        class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded">
                                                    <label class="ml-2 text-sm text-gray-700">Gangguan</label>
                                                </div>
                                                <div class="flex items-center">
                                                    <input type="checkbox" name="auxiliary_equipment[0][status][]" value="flm"
                                                        class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded">
                                                    <label class="ml-2 text-sm text-gray-700">FLM</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                            <textarea name="auxiliary_equipment[0][keterangan]" rows="3"
                                                class="p-2 mt-1 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                                                      placeholder="Masukkan keterangan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" onclick="addAlatBantu()"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Alat Bantu
                                    </button>
                                </div>
                            </div>
                                        </div>
                                    </div>

                                    <!-- Kondisi Resource -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Kondisi Resource</h3>
                            <div class="mt-6">
                                <div class="space-y-4" id="resource-container">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 resource-item">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama Resource</label>
                                            <input type="text" name="resources[0][name]" required
                                                class="mt-1 focus:ring-[#009BB9] focus:border-[#009BB9] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                                            <select name="resources[0][category]" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm rounded-md">
                                                <option value="">Pilih Kategori</option>
                                                <option value="PELUMAS">PELUMAS</option>
                                                <option value="BBM">BBM</option>
                                                <option value="AIR PENDINGIN">AIR PENDINGIN</option>
                                                <option value="UDARA START">UDARA START</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="resources[0][status]" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm rounded-md">
                                                <option value="">Pilih Status</option>
                                                <option value="0-20">0-20%</option>
                                                <option value="21-40">21-40%</option>
                                                <option value="41-61">41-61%</option>
                                                <option value="61-80">61-80%</option>
                                                <option value="up-80">>80%</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                            <textarea name="resources[0][keterangan]" rows="3"
                                                class="p-2 mt-1 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                                                          placeholder="Masukkan keterangan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" onclick="addResource()"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Resource
                                    </button>
                                </div>
                            </div>
                                        </div>
                                    </div>

                                    <!-- Kondisi K3L -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Kondisi K3L</h3>
                            <div class="mt-6">
                                <div class="space-y-4" id="k3l-container">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 k3l-item">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Tipe</label>
                                            <select name="k3l[0][type]" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm rounded-md">
                                                <option value="">Pilih Tipe</option>
                                                <option value="unsafe_action">Unsafe Action</option>
                                                <option value="unsafe_condition">Unsafe Condition</option>
                                            </select>
                                                            </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Uraian</label>
                                            <textarea name="k3l[0][uraian]" required rows="3"
                                                class="p-2 mt-1 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="Masukkan uraian..."></textarea>
                                                            </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Saran</label>
                                            <textarea name="k3l[0][saran]" required rows="3"
                                                class="p-2 mt-1 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="Masukkan saran..."></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Eviden</label>
                                            <input type="file" name="k3l[0][eviden]" accept=".jpeg,.jpg,.png,.gif,.doc,.docx,.pdf"
                                                class="mt-1 focus:ring-[#009BB9] focus:border-[#009BB9] block w-full shadow-sm sm:text-sm border-gray-300">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" onclick="addK3L()"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah K3L
                                    </button>
                                </div>
                            </div>
                                        </div>
                                    </div>

                    <!-- Catatan -->
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Catatan Sistem -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Catatan Sistem</h3>
                                <div class="mt-2">
                                    <textarea name="catatan_sistem" rows="4" required
                                        class="p-2 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Masukkan catatan sistem..."></textarea>
                                </div>
                                        </div>
                                    </div>

                                    <!-- Catatan Umum -->
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:p-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Catatan Serah Terima</h3>
                                <div class="mt-2">
                                    <textarea name="catatan_umum" rows="4" required
                                        class="p-2 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                        placeholder="Masukkan catatan umum..."></textarea>
                                        </div>
                                    </div>
                                        </div>
                                    </div>

                    <!-- Resume Rapat -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Resume Rapat</h3>
                            <div class="mt-2">
                                <textarea name="resume" rows="4" required
                                    class="p-2 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                    placeholder="Masukkan resume rapat..."></textarea>
                                                    </div>
                                                    </div>
                                                    </div>

                    <!-- Absensi -->
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Absensi</h3>
                            <div class="mt-6">
                                <div class="space-y-4" id="absensi-container">
                                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 absensi-item">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                                            <input type="text" name="absensi[0][nama]" required
                                                class="mt-1 focus:ring-[#009BB9] focus:border-[#009BB9] block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Shift</label>
                                            <select name="absensi[0][shift]" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm rounded-md">
                                                                <option value="">Pilih Shift</option>
                                                                <option value="A">Shift A</option>
                                                                <option value="B">Shift B</option>
                                                                <option value="C">Shift C</option>
                                                                <option value="D">Shift D</option>
                                                                <option value="staf ops">Staf Ops</option>
                                                                <option value="TL OP">TL OP</option>
                                                                <option value="TL HAR">TL HAR</option>
                                                                <option value="TL OPHAR">TL OPHAR</option>
                                                                <option value="MUL">MUL</option>
                                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Status</label>
                                            <select name="absensi[0][status]" required
                                                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-[#009BB9] focus:border-[#009BB9] sm:text-sm rounded-md">
                                                                <option value="">Pilih Status</option>
                                                                <option value="hadir">Hadir</option>
                                                                <option value="izin">Izin</option>
                                                                <option value="sakit">Sakit</option>
                                                                <option value="cuti">Cuti</option>
                                                                <option value="alpha">Alpha</option>
                                                                <option value="terlambat">Terlambat</option>
                                                                <option value="ganti shift">Ganti Shift</option>
                                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                                            <textarea name="absensi[0][keterangan]" rows="3"
                                                class="p-2 mt-1 shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] block w-full sm:text-sm border-gray-300 rounded-md"
                                                placeholder="Masukkan keterangan..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <button type="button" onclick="addAbsensi()"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Absensi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" id="submitButton"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                            <i class="fas fa-save mr-2"></i>
                            <span>Simpan</span>
                            <span class="loading hidden ml-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
            </div>
    </div>
</div>

@push('scripts')
<script>
let alatBantuCount = 1;
let resourceCount = 1;
let k3lCount = 1;
let absensiCount = 1;

function addAlatBantu() {
    const container = document.getElementById('alat-bantu-container');
    const template = document.querySelector('.alat-bantu-item').cloneNode(true);
    
    // Update input names
    template.querySelectorAll('input, textarea').forEach(input => {
        input.name = input.name.replace('[0]', `[${alatBantuCount}]`);
        if (input.type !== 'checkbox') {
            input.value = '';
        } else {
            input.checked = false;
        }
    });
    
    container.appendChild(template);
    alatBantuCount++;
}

function addResource() {
    const container = document.getElementById('resource-container');
    const template = document.querySelector('.resource-item').cloneNode(true);
    
    // Update input names
    template.querySelectorAll('input, select, textarea').forEach(input => {
        input.name = input.name.replace('[0]', `[${resourceCount}]`);
        input.value = '';
    });
    
    container.appendChild(template);
    resourceCount++;
}

function addK3L() {
    const container = document.getElementById('k3l-container');
    const template = document.querySelector('.k3l-item').cloneNode(true);
    
    // Update input names
    template.querySelectorAll('input, select, textarea').forEach(input => {
        input.name = input.name.replace('[0]', `[${k3lCount}]`);
        input.value = '';
    });
    
    container.appendChild(template);
    k3lCount++;
}

function addAbsensi() {
    const container = document.getElementById('absensi-container');
    const template = document.querySelector('.absensi-item').cloneNode(true);
    
    // Update input names
    template.querySelectorAll('input, select, textarea').forEach(input => {
        input.name = input.name.replace('[0]', `[${absensiCount}]`);
        input.value = '';
    });
    
    container.appendChild(template);
    absensiCount++;
}

function validateMachineStatus(index) {
    const checkboxes = document.querySelectorAll(`input[name^="machine_statuses[${index}][status]"]`);
    const errorElement = document.getElementById(`machine-status-error-${index}`);
    let isChecked = false;

    checkboxes.forEach(checkbox => {
        if (checkbox.checked) {
            isChecked = true;
        }
    });

    if (!isChecked) {
        errorElement.classList.remove('hidden');
    } else {
        errorElement.classList.add('hidden');
    }
}

// Validate all machine statuses before form submission
document.getElementById('meetingShiftForm').addEventListener('submit', function(e) {
    let hasError = false;
    const machineCount = {{ count($machines) }};

    for (let i = 0; i < machineCount; i++) {
        const checkboxes = document.querySelectorAll(`input[name^="machine_statuses[${i}][status]"]`);
        let isChecked = false;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                isChecked = true;
            }
        });

        const errorElement = document.getElementById(`machine-status-error-${i}`);
        if (!isChecked) {
            errorElement.classList.remove('hidden');
            hasError = true;
        } else {
            errorElement.classList.add('hidden');
        }
    }

    if (hasError) {
        e.preventDefault();
        // Scroll to first error
        const firstError = document.querySelector('.text-red-500:not(.hidden)');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
});

// Add validation for K3L file upload
document.querySelectorAll('input[name$="[eviden]"]').forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        const errorElement = this.parentElement.querySelector('.file-error');
        
        if (errorElement) {
            errorElement.remove();
        }

        if (file) {
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/pdf'];
            if (!validTypes.includes(file.type)) {
                const error = document.createElement('p');
                error.className = 'text-red-500 text-xs mt-1 file-error';
                error.textContent = 'File harus berupa gambar (JPG, PNG, GIF) atau dokumen (DOC, DOCX, PDF)';
                this.parentElement.appendChild(error);
                this.value = ''; // Clear the file input
            } else if (file.size > 2 * 1024 * 1024) { // 2MB
                const error = document.createElement('p');
                error.className = 'text-red-500 text-xs mt-1 file-error';
                error.textContent = 'Ukuran file tidak boleh lebih dari 2MB';
                this.parentElement.appendChild(error);
                this.value = ''; // Clear the file input
            }
        }
    });
});
</script>
@endpush

@endsection 