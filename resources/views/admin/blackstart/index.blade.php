@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
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

                    <h1 class="text-xl font-semibold text-gray-800">Input Data Blackstart</h1>
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
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Operasi UL/Sentral', 'url' => '#'],
                ['name' => 'Blackstart', 'url' => null]
            ]" />
        </div>

        <!-- Main Content -->
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    
                    <!-- Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="tanggal" class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end items-center mb-4">
                        <!-- Left side: Add Row button -->
                      

                        <!-- Right side: View and Save buttons -->
                        <div class="flex gap-3">
                            <a href="{{ route('admin.blackstart.show') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Lihat Data
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Simpan Data
                            </button>
                        </div>
                    </div>

                    <!-- Tabel Input -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">No</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">Unit Layanan / sentral</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="2">collect single line diagram</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">SOP Black start</th>
                                    <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase border-r border-gray-200" colspan="3">status ketersediaan</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">PIC</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase border-r border-gray-200">STATUS</th>
                                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase ">Aksi</th>
                                    
                                </tr>
                                <tr>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">pembangkit</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">black start</th>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">load set</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">line energize</th>
                                    <th class="px-3 py-2 text-xs font-medium text-gray-500 uppercase border border-gray-200">status jaringan</th>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2"></th>
                                    <th class="px-3 py-2"></th>
                                </tr>
                            </thead>
                            <tbody id="blackstart-tbody" class="bg-white divide-y divide-gray-200">
                                <!-- Rows will be added here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Add Row Button -->
                    <div class="flex justify-start mb-4">
                        <button type="button" id="add-row" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Tambah Baris
                        </button>
                    </div>

                    <!-- Row Template (Hidden) -->
                    <template id="row-template">
                        <tr>
                            <td class="px-3 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="row-number"></span>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="unit[]" class="p-2 w-[100px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Unit</option>
                                    @foreach($powerPlants as $powerPlant)
                                        <option value="{{ $powerPlant->id }}">{{ $powerPlant->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="pembangkit[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak_tersedia">Tidak Tersedia</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="black_start[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak_tersedia">Tidak Tersedia</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="sop[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak_tersedia">Tidak Tersedia</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="load_set[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak_tersedia">Tidak Tersedia</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="line_energize[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="tersedia">Tersedia</option>
                                    <option value="tidak_tersedia">Tidak Tersedia</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="status_jaringan[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="normal">Normal</option>
                                    <option value="tidak_normal">Tidak Normal</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <input type="text" name="pic[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Nama PIC">
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <select name="status[]" class="p-2 w-[115px] rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                                    <option value="">Pilih Status</option>
                                    <option value="open">OPEN</option>
                                    <option value="close">CLOSE</option>
                                </select>
                            </td>
                            <td class="px-3 py-4 whitespace-nowrap">
                                <button type="button" class="delete-row text-red-500 hover:text-red-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tbody = document.getElementById('blackstart-tbody');
    const addRowButton = document.getElementById('add-row');
    const rowTemplate = document.getElementById('row-template');

    function updateRowNumbers() {
        const rows = tbody.getElementsByTagName('tr');
        for (let i = 0; i < rows.length; i++) {
            const numberCell = rows[i].querySelector('.row-number');
            if (numberCell) {
                numberCell.textContent = i + 1;
            }
        }
    }

    function addRow() {
        const clone = document.importNode(rowTemplate.content, true);
        
        // Add delete functionality to the new row
        const deleteButton = clone.querySelector('.delete-row');
        deleteButton.addEventListener('click', function() {
            this.closest('tr').remove();
            updateRowNumbers();
        });

        tbody.appendChild(clone);
        updateRowNumbers();
    }

    addRowButton.addEventListener('click', addRow);
});
</script>
@endsection 