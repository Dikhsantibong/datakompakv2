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

                    <h1 class="text-xl font-semibold text-gray-800">Edit Laporan Abnormal/Gangguan</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Laporan Abnormal/Gangguan', 'url' => route('admin.abnormal-report.index')], ['name' => 'Edit', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 sm:px-6">
            <!-- Success Message -->
            @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form Content -->
            <form action="{{ route('admin.abnormal-report.update', $report->id) }}" method="POST" class="space-y-6" id="abnormalReportForm">
                @csrf
                @method('PUT')

                <!-- Kronologi Kejadian -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Kronologi Kejadian</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="kronologi-table">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Pukul (WIB)</th>
                                        <th rowspan="2" class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Uraian kejadian</th>
                                        <th rowspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Visual parameter terkait</th>
                                        <th colspan="3" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Tindakan Isolasi</th>
                                        <th rowspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Turun beban</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">CBG OFF</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Stop</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($report->chronologies as $kronologi)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            <input type="time" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]" 
                                                name="waktu[]" value="{{ $kronologi->waktu }}">
                                        </td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[200px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="uraian_kejadian[]">{{ $kronologi->uraian_kejadian }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[200px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="visual_parameter[]">{{ $kronologi->visual_parameter }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="turun_beban[]" value="1" {{ $kronologi->turun_beban ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="off_cbg[]" value="1" {{ $kronologi->off_cbg ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="stop[]" value="1" {{ $kronologi->stop ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="delete-row text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="border px-4 py-2 text-center text-gray-500">
                                            Belum ada data kronologi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <button type="button" id="add-kronologi" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Kronologi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Koordinasi -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tindak Lanjut Koordinasi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="koordinasi-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL Ophar</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL OP</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL HAR</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">MUL</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($report->chronologies as $chronology)
                                    <tr>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="tl_ophar[]" value="1" {{ $chronology->tl_ophar ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="tl_op[]" value="1" {{ $chronology->tl_op ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="tl_har[]" value="1" {{ $chronology->tl_har ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="mul[]" value="1" {{ $chronology->mul ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="delete-row text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="border px-4 py-2 text-center text-gray-500">
                                            Belum ada data koordinasi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <button type="button" id="add-koordinasi" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Koordinasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mesin/Peralatan Terdampak -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Mesin/Peralatan Terdampak</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="mesin-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Nama Mesin/Peralatan/Material</th>
                                        <th colspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Ket.</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="border"></th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Rusak</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Abnormal</th>
                                        <th colspan="2" class="border"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($report->affectedMachines as $index => $machine)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="nama_mesin[]">{{ $machine->nama_mesin }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="kondisi_rusak[]" value="1" {{ $machine->kondisi_rusak ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="kondisi_abnormal[]" value="1" {{ $machine->kondisi_abnormal ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="keterangan[]">{{ $machine->keterangan }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="delete-row text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="border px-4 py-2 text-center text-gray-500">
                                            Belum ada data mesin/peralatan
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <button type="button" id="add-mesin" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Mesin/Peralatan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tindak Lanjut Tindakan -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tindak Lanjut Tindakan</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="tindakan-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Usul MO rutin (PO-PS)</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">MO non rutin</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Lainnya</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($report->followUpActions as $tindakan)
                                    <tr>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="flm_tindakan[]" value="1" {{ $tindakan->flm_tindakan ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="usul_mo_rutin[]">{{ $tindakan->usul_mo_rutin }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="mo_non_rutin[]" value="1" {{ $tindakan->mo_non_rutin ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="lainnya[]">{{ $tindakan->lainnya }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="delete-row text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="border px-4 py-2 text-center text-gray-500">
                                            Belum ada data tindak lanjut
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <button type="button" id="add-tindakan" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Tindakan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Rekomendasi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="rekomendasi-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Uraian</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($report->recommendations as $index => $rekomendasi)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2">
                                            <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                                                name="rekomendasi[]">{{ $rekomendasi->rekomendasi }}</textarea>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="delete-row text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="border px-4 py-2 text-center text-gray-500">
                                            Belum ada data rekomendasi
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <button type="button" id="add-rekomendasi" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah Rekomendasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ADM -->
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Tindak Lanjut Administrasi</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200" id="adm-table">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">PM</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">CM</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">PtW</th>
                                        <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">SR</th>
                                        <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($report->admActions as $index => $adm)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="adm_flm[]" value="1" {{ $adm->flm ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="adm_pm[]" value="1" {{ $adm->pm ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="adm_em[]" value="1" {{ $adm->cm ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="adm_ptw[]" value="1" {{ $adm->ptw ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" 
                                                name="adm_sr[]" value="1" {{ $adm->sr ? 'checked' : '' }}>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <button type="button" class="delete-row text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="border px-4 py-2 text-center text-gray-500">
                                            Belum ada data ADM
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4">
                                <button type="button" id="add-adm" 
                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                    <i class="fas fa-plus mr-2"></i>
                                    Tambah ADM
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end mt-6">
                    <button type="submit" 
                        class="mb-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]"
                        onclick="return validateForm()">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .main-content {
        overflow-y: auto;
        height: calc(100vh - 64px);
    }
</style>
@endpush

@push('scripts')
<script>
function validateForm() {
    // Get all tables
    const tables = {
        'kronologi-table': 'Kronologi Kejadian',
        'mesin-table': 'Mesin/Peralatan',
        'tindakan-table': 'Tindak Lanjut',
        'rekomendasi-table': 'Rekomendasi',
        'adm-table': 'ADM'
    };

    let isValid = true;
    let errorMessage = '';

    // Check each table for at least one row with data
    for (let [tableId, tableName] of Object.entries(tables)) {
        const tbody = document.querySelector(`#${tableId} tbody`);
        if (!tbody || tbody.children.length === 0) {
            isValid = false;
            errorMessage += `- ${tableName} harus memiliki minimal satu data\n`;
        }
    }

    // Validate required fields in Kronologi
    const waktuInputs = document.querySelectorAll('input[name="waktu[]"]');
    const uraianInputs = document.querySelectorAll('textarea[name="uraian_kejadian[]"]');
    
    waktuInputs.forEach((input, index) => {
        if (!input.value) {
            isValid = false;
            errorMessage += `- Waktu pada Kronologi baris ${index + 1} harus diisi\n`;
        }
    });

    uraianInputs.forEach((input, index) => {
        if (!input.value.trim()) {
            isValid = false;
            errorMessage += `- Uraian Kejadian pada Kronologi baris ${index + 1} harus diisi\n`;
        }
    });

    if (!isValid) {
        alert('Mohon lengkapi data berikut:\n' + errorMessage);
        return false;
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    // Add row functions for each table
    const addRowFunctions = {
        'add-kronologi': {
            table: 'kronologi-table',
            template: `
                <td class="border px-4 py-2">
                    <input type="time" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9]" 
                        name="waktu[]">
                </td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[200px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="uraian_kejadian[]" 
                        placeholder="Masukkan uraian kejadian..."></textarea>
                </td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[200px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="visual_parameter[]" 
                        placeholder="Masukkan parameter visual..."></textarea>
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="turun_beban[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="off_cbg[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="stop[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="delete-row text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `
        },
        'add-koordinasi': {
            table: 'koordinasi-table',
            template: `
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="tl_ophar[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="tl_op[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="tl_har[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="mul[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="delete-row text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `
        },
        'add-mesin': {
            table: 'mesin-table',
            template: `
                <td class="border px-4 py-2">{number}</td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="nama_mesin[]" 
                        placeholder="Masukkan nama mesin/peralatan/material..."></textarea>
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="kondisi_rusak[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="kondisi_abnormal[]" value="1">
                </td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="keterangan[]" 
                        placeholder="Masukkan keterangan..."></textarea>
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="delete-row text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `
        },
        'add-tindakan': {
            table: 'tindakan-table',
            template: `
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="flm_tindakan[]" value="1">
                </td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="usul_mo_rutin[]" 
                        placeholder="Masukkan usul MO rutin..."></textarea>
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="mo_non_rutin[]" value="1">
                </td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="lainnya[]" 
                        placeholder="Masukkan keterangan lainnya..."></textarea>
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="delete-row text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `
        },
        'add-rekomendasi': {
            table: 'rekomendasi-table',
            template: `
                <td class="border px-4 py-2">{number}</td>
                <td class="border px-4 py-2">
                    <textarea class="p-2 w-[300px] h-[100px] border-gray-300 rounded-md shadow-sm focus:ring-[#009BB9] focus:border-[#009BB9] resize-none" 
                        name="rekomendasi[]" 
                        placeholder="Masukkan rekomendasi..."></textarea>
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="delete-row text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `
        },
        'add-adm': {
            table: 'adm-table',
            template: `
                <td class="border px-4 py-2">{number}</td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="adm_flm[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="adm_pm[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="adm_em[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="adm_ptw[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <input type="checkbox" class="w-4 h-4 text-[#009BB9] border-gray-300 rounded focus:ring-[#009BB9]" name="adm_sr[]" value="1">
                </td>
                <td class="border px-4 py-2 text-center">
                    <button type="button" class="delete-row text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `
        }
    };

    // Add click handlers for all add buttons
    for (let [buttonId, config] of Object.entries(addRowFunctions)) {
        document.getElementById(buttonId)?.addEventListener('click', function() {
            const table = document.getElementById(config.table).getElementsByTagName('tbody')[0];
            const newRow = document.createElement('tr');
            let template = config.template;
            
            // Replace {number} with actual row number if needed
            if (template.includes('{number}')) {
                const rowCount = table.getElementsByTagName('tr').length + 1;
                template = template.replace(/{number}/g, rowCount);
            }
            
            newRow.innerHTML = template;
            table.appendChild(newRow);
        });
    }

    // Handle row deletion for all tables
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-row')) {
            const row = e.target.closest('tr');
            const table = row.closest('table');
            row.remove();
            
            // Update row numbers if needed
            if (table.id === 'mesin-table' || table.id === 'rekomendasi-table' || table.id === 'adm-table') {
                const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
                for (let i = 0; i < rows.length; i++) {
                    rows[i].getElementsByTagName('td')[0].textContent = i + 1;
                }
            }
        }
    });
});
</script>
@endpush

@endsection 