@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
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

                    <h1 class="text-xl font-semibold text-gray-900">Form Pemeriksaan FLM</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Form Pemeriksaan FLM', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Add success message display -->
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Add error message display -->
                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Form Pemeriksaan FLM</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor pemeriksaan FLM untuk memastikan kualitas dan keandalan sistem.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.flm.list') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 bg-white rounded-md hover:bg-gray-50">
                                <i class="fas fa-list mr-2"></i> Lihat Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="bg-white rounded-lg shadow-sm">
                    <div class="p-6">
                        <form action="{{ route('admin.flm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="flmForm">
                            @csrf
                            
                            <!-- Form Header -->
                            <div class="mb-6 flex flex-wrap gap-4">
                                <div class="w-full md:w-1/4">
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                                
                                <div class="w-full md:w-1/4">
                                    <label class="block text-sm font-medium text-gray-700">Shift</label>
                                    <select name="shift" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Pilih Shift</option>
                                        <option value="A" {{ old('shift') == 'A' ? 'selected' : '' }}>Shift A</option>
                                        <option value="B" {{ old('shift') == 'B' ? 'selected' : '' }}>Shift B</option>
                                        <option value="C" {{ old('shift') == 'C' ? 'selected' : '' }}>Shift C</option>
                                        <option value="D" {{ old('shift') == 'D' ? 'selected' : '' }}>Shift D</option>
                                    </select>
                                </div>

                                <div class="w-full md:w-1/4">
                                    <label class="block text-sm font-medium text-gray-700">Waktu</label>
                                    <input type="time" 
                                           name="time" 
                                           value="{{ old('time') }}" 
                                           class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                                           required>
                                </div>
                                
                                <div class="w-full md:w-1/4">
                                    <label class="block text-sm font-medium text-gray-700">Operator</label>
                                    <input type="text" name="operator" value="{{ old('operator') }}" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Masukkan nama operator"
                                           required>
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border px-4 py-2 text-sm">No.</th>
                                            <th class="border px-4 py-2 text-sm">Sistem pembangkit</th>
                                            <th class="border px-4 py-2 text-sm">Mesin/peralatan</th>
                                            <th class="border px-4 py-2 text-sm">Masalah awal yang ditemukan</th>
                                            <th class="border px-4 py-2 text-sm">kondisi awal</th>
                                            <th colspan="5" class="border px-4 py-2 text-sm text-center">Tindakan FLM</th>
                                            <th class="border px-4 py-2 text-sm">kondisi akhir</th>
                                            <th class="border px-4 py-2 text-sm">Catatan FLM</th>
                                            <th class="border px-4 py-2 text-sm">Eviden</th>
                                            <th class="border px-4 py-2 text-sm">Aksi</th>
                                        </tr>
                                        <tr class="bg-gray-50">
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2 text-sm">bersihkan</th>
                                            <th class="border px-4 py-2 text-sm">lumasi</th>
                                            <th class="border px-4 py-2 text-sm">kencangkan</th>
                                            <th class="border px-4 py-2 text-sm">perbaikan koneksi</th>
                                            <th class="border px-4 py-2 text-sm">lainnya</th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="flmTableBody">
                                        <!-- Template row that will be cloned -->
                                        <tr class="flm-row">
                                            <td class="border px-4 py-2 text-center row-number">1</td>
                                            <td class="border px-4 py-2">
                                                <textarea name="sistem[]" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ old('sistem.0') }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="mesin[]" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ old('mesin.0') }}</textarea>
                                            </td>
                                            
                                            <td class="border px-4 py-2">
                                                <textarea name="masalah[]" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ old('masalah.0') }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="kondisi_awal[]" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ old('kondisi_awal.0') }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[0][]" value="bersihkan" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ in_array('bersihkan', old('tindakan.0', [])) ? 'checked' : '' }}>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[0][]" value="lumasi" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ in_array('lumasi', old('tindakan.0', [])) ? 'checked' : '' }}>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[0][]" value="kencangkan" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ in_array('kencangkan', old('tindakan.0', [])) ? 'checked' : '' }}>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[0][]" value="perbaikan_koneksi" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ in_array('perbaikan_koneksi', old('tindakan.0', [])) ? 'checked' : '' }}>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[0][]" value="lainnya" class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500" {{ in_array('lainnya', old('tindakan.0', [])) ? 'checked' : '' }}>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="kondisi_akhir[]" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ old('kondisi_akhir.0') }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="catatan[]" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500">{{ old('catatan.0') }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <div class="space-y-3 w-[300px] p-2">
                                                    <div class="eviden-upload">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Sebelum:</label>
                                                        <div class="relative">
                                                            <input type="file" 
                                                                   name="eviden_sebelum[]" 
                                                                   accept="image/*"
                                                                   class="hidden"
                                                                   onchange="previewImage(this, this.parentElement.querySelector('.preview-image'))"
                                                                   data-preview="sebelum">
                                                            <div class="preview-container mb-2 hidden">
                                                                <img src="#" alt="Preview" class="preview-image w-full h-32 object-cover rounded-lg border border-gray-200">
                                                                <button type="button" 
                                                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                                                        onclick="removeImage(this)">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <button type="button"
                                                                    onclick="this.previousElementSibling.previousElementSibling.click()"
                                                                    class="upload-btn w-full px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg border-2 border-blue-100 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                <i class="fas fa-camera mr-2"></i>
                                                                Pilih Foto Sebelum
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="eviden-upload">
                                                        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Sesudah:</label>
                                                        <div class="relative">
                                                            <input type="file" 
                                                                   name="eviden_sesudah[]" 
                                                                   accept="image/*"
                                                                   class="hidden"
                                                                   onchange="previewImage(this, this.parentElement.querySelector('.preview-image'))"
                                                                   data-preview="sesudah">
                                                            <div class="preview-container mb-2 hidden">
                                                                <img src="#" alt="Preview" class="preview-image w-full h-32 object-cover rounded-lg border border-gray-200">
                                                                <button type="button" 
                                                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                                                        onclick="removeImage(this)">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <button type="button"
                                                                    onclick="this.previousElementSibling.previousElementSibling.click()"
                                                                    class="upload-btn w-full px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg border-2 border-blue-100 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                <i class="fas fa-camera mr-2"></i>
                                                                Pilih Foto Sesudah
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <button type="button" onclick="deleteRow(this)" class="text-red-600 hover:text-red-800">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="mt-4 flex justify-start">
                                    <button type="button" onclick="addNewRow()" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                        <i class="fas fa-plus mr-2"></i>
                                        Tambah Baris
                                    </button>
                                </div>
                            </div>
                            <div class="flex justify-end mt-6">
                                <button type="submit" 
                                        id="submitBtn"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-save mr-2"></i>
                                        <span>Simpan</span>
                                    </span>
                                    <span class="loader hidden ml-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('styles')
<style>
    /* Loader styles */
    .loader {
        display: inline-flex;
        align-items: center;
    }

    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>
@endpush

@push('scripts')
<script>
function addNewRow() {
    const tbody = document.getElementById('flmTableBody');
    const template = tbody.querySelector('.flm-row').cloneNode(true);
    const rowCount = tbody.children.length;
    
    // Update row number
    template.querySelector('.row-number').textContent = rowCount + 1;
    
    // Clear all input values
    template.querySelectorAll('textarea').forEach(textarea => {
        textarea.value = '';
    });
    
    template.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
        checkbox.name = checkbox.name.replace('[0]', `[${rowCount}]`);
    });
    
    // Reset file inputs and previews
    template.querySelectorAll('.eviden-upload').forEach(upload => {
        const input = upload.querySelector('input[type="file"]');
        const previewContainer = upload.querySelector('.preview-container');
        const previewImage = upload.querySelector('.preview-image');
        const uploadBtn = upload.querySelector('.upload-btn');
        
        input.value = '';
        previewContainer.classList.add('hidden');
        previewImage.src = '#';
        uploadBtn.classList.remove('hidden');
    });
    
    tbody.appendChild(template);
    updateRowNumbers();
}

function deleteRow(button) {
    const tbody = document.getElementById('flmTableBody');
    if (tbody.children.length > 1) {
        button.closest('tr').remove();
        updateRowNumbers();
    } else {
        alert('Minimal harus ada satu baris data!');
    }
}

function updateRowNumbers() {
    const rows = document.getElementById('flmTableBody').getElementsByClassName('flm-row');
    Array.from(rows).forEach((row, index) => {
        row.querySelector('.row-number').textContent = index + 1;
        
        // Update checkbox names
        row.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
            checkbox.name = checkbox.name.replace(/\[\d+\]/, `[${index}]`);
        });
        
        // Update file input names if needed
        row.querySelectorAll('input[type="file"]').forEach(input => {
            const currentName = input.getAttribute('name');
            if (currentName) {
                input.setAttribute('name', currentName.replace(/\[\d*\]$/, `[${index}]`));
            }
        });
    });
}

function previewImage(input, previewImg) {
    const previewContainer = input.parentElement.querySelector('.preview-container');
    const uploadBtn = input.parentElement.querySelector('.upload-btn');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
            uploadBtn.classList.add('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(button) {
    const container = button.closest('.relative');
    const input = container.querySelector('input[type="file"]');
    const previewContainer = container.querySelector('.preview-container');
    const uploadBtn = container.querySelector('.upload-btn');
    
    input.value = '';
    previewContainer.classList.add('hidden');
    uploadBtn.classList.remove('hidden');
}

// Prevent form submission on enter key
document.getElementById('flmForm').addEventListener('keypress', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
    }
});

document.getElementById('flmForm').addEventListener('submit', function() {
    const submitBtn = document.getElementById('submitBtn');
    const loader = submitBtn.querySelector('.loader');
    const btnText = submitBtn.querySelector('span:not(.loader)');
    
    submitBtn.disabled = true;
    loader.classList.remove('hidden');
    btnText.classList.add('opacity-50');
});
</script>
@endpush

@endsection