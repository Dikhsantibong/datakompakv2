@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
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
                    <h1 class="text-xl font-semibold text-gray-900">Patrol Check KIT</h1>
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
                            <input type="hidden" name="redirect" value="{{ route('login') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Patrol Check KIT', 'url' => route('admin.patrol-check.index')]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
                @endif

                <!-- Welcoming Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Patrol Check KIT</h2>
                        <p class="text-blue-100 mb-4">Pantau dan catat kondisi peralatan bantu secara berkala untuk memastikan keandalan operasional pembangkit.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.patrol-check.list') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 bg-white rounded-md hover:bg-gray-50">
                                <i class="fas fa-list mr-2"></i> Lihat Daftar Patrol Check
                            </a>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('admin.patrol-check.store') }}" method="POST">
                            @csrf
                            
                            <!-- Equipment Conditions Table -->
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Kondisi Umum Peralatan Bantu</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NO</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">SISTEM</th>
                                                <th colspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi Umum</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Keterangan</th>
                                            </tr>
                                            <tr>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Normal</th>
                                                <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Abnormal</th>
                                                <th class="border px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $systems = ['Exhaust', 'Pelumas', 'BBM', 'JCW/HT', 'CW/LT'];
                                            @endphp

                                            @foreach($systems as $index => $system)
                                            <tr>
                                                <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                                <td class="border px-4 py-2">{{ $system }}</td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="radio" name="condition[{{ $index }}]" value="normal" class="form-radio h-4 w-4 text-blue-600">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="radio" name="condition[{{ $index }}]" value="abnormal" class="form-radio h-4 w-4 text-red-600">
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <input type="text" name="notes[{{ $index }}]" class="form-input w-full rounded-md shadow-sm border-gray-300 focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Abnormal Equipment Data -->
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Data Kondisi Alat Bantu</h3>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200" id="abnormalTable">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NO</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">ALAT BANTU</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi Awal</th>
                                                <th colspan="3" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Tindak Lanjut</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi Akhir</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Keterangan</th>
                                                <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Aksi</th>
                                            </tr>
                                            <tr>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">FLM</th>
                                                <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">SR</th>
                                                <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Lainnya</th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border px-4 py-2 text-center">1</td>
                                                <td class="border px-4 py-2">
                                                    <input type="text" name="abnormal[0][equipment]" class="form-input w-full rounded-md">
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <textarea 
                                                        name="abnormal[0][condition]" 
                                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        rows="3"
                                                        placeholder="Masukkan kondisi awal">{{ $abnormal['condition'] ?? '' }}</textarea>
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" 
                                                           name="abnormal[0][flm]" 
                                                           class="form-checkbox h-5 w-5 text-blue-600"
                                                           {{ !empty($abnormal['flm']) ? 'checked' : '' }}>
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" 
                                                           name="abnormal[0][sr]" 
                                                           class="form-checkbox h-5 w-5 text-blue-600"
                                                           {{ !empty($abnormal['sr']) ? 'checked' : '' }}>
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" 
                                                           name="abnormal[0][other]" 
                                                           class="form-checkbox h-5 w-5 text-blue-600"
                                                           {{ !empty($abnormal['other']) ? 'checked' : '' }}>
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <textarea 
                                                        name="condition_after[0][condition]" 
                                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        rows="3"
                                                        placeholder="Masukkan kondisi akhir">{{ $patrol->condition_after[$index]['condition'] ?? '' }}</textarea>
                                                </td>
                                                <td class="border px-4 py-2">
                                                    <textarea 
                                                        name="condition_after[0][notes]" 
                                                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                                                        rows="3"
                                                        placeholder="Masukkan keterangan">{{ $patrol->condition_after[$index]['notes'] ?? '' }}</textarea>
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <button type="button" onclick="deleteRow(this)" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <button type="button" onclick="addRow()" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Baris
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
    function addRow() {
        const tbody = document.querySelector('#abnormalTable tbody');
        const rowCount = tbody.children.length;
        const newRow = document.createElement('tr');
        
        newRow.innerHTML = `
            <td class="border px-4 py-2 text-center">${rowCount + 1}</td>
            <td class="border px-4 py-2">
                <input type="text" name="abnormal[${rowCount}][equipment]" class="form-input w-full rounded-md">
            </td>
            <td class="border px-4 py-2">
                <textarea 
                    name="abnormal[${rowCount}][condition]" 
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Masukkan kondisi awal"></textarea>
            </td>
            <td class="border px-4 py-2 text-center">
                <input type="checkbox" 
                       name="abnormal[${rowCount}][flm]" 
                       class="form-checkbox h-5 w-5 text-blue-600">
            </td>
            <td class="border px-4 py-2 text-center">
                <input type="checkbox" 
                       name="abnormal[${rowCount}][sr]" 
                       class="form-checkbox h-5 w-5 text-blue-600">
            </td>
            <td class="border px-4 py-2 text-center">
                <input type="checkbox" 
                       name="abnormal[${rowCount}][other]" 
                       class="form-checkbox h-5 w-5 text-blue-600">
            </td>
            <td class="border px-4 py-2">
                <textarea 
                    name="condition_after[${rowCount}][condition]" 
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Masukkan kondisi akhir"></textarea>
            </td>
            <td class="border px-4 py-2">
                <textarea 
                    name="condition_after[${rowCount}][notes]" 
                    class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    rows="3"
                    placeholder="Masukkan keterangan"></textarea>
            </td>
            <td class="border px-4 py-2 text-center">
                <button type="button" onclick="deleteRow(this)" class="text-red-600 hover:text-red-900">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(newRow);
    }

    function deleteRow(button) {
        const row = button.closest('tr');
        row.remove();
        
        // Renumber the rows
        const tbody = document.querySelector('#abnormalTable tbody');
        Array.from(tbody.children).forEach((row, index) => {
            row.children[0].textContent = index + 1;
            Array.from(row.querySelectorAll('input')).forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    input.setAttribute('name', name.replace(/\d+/, index));
                }
            });
        });
    }
</script>
@endpush

@endsection 