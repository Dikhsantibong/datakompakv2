@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <x-sidebar />
    
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

                    <h1 class="text-xl font-semibold text-gray-800">Dashboard</h1>
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
        

        <!-- Content -->
        <div class="container mx-auto px-6 py-8">
            <div class="p-6 bg-white rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6">Laporan K3 KAMP dan Lingkungan</h2>

                <form action="#" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left w-1/4">K3 & Keamanan</th>
                                    <th class="border px-4 py-2 text-center w-[80px]">Ada</th>
                                    <th class="border px-4 py-2 text-center w-[80px]">Tidak Ada</th>
                                    <th class="border px-4 py-2 text-center w-[80px]">Normal</th>
                                    <th class="border px-4 py-2 text-center w-[80px]">Abnormal</th>
                                    <th class="border px-4 py-2 text-left w-1/4">Keterangan</th>
                                    <th class="border px-4 py-2 text-left w-[200px]">Eviden</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $items = [
                                        'Potensi gangguan keamanan',
                                        'Potensi gangguan kebakaran',
                                        'Peralatan K3 KAM (CCTV, etc)',
                                        'Peralatan Fire Fighting',
                                        'Peralatan safety',
                                        'Lainnya'
                                    ];
                                @endphp

                                <!-- K3 & Keamanan Section -->
                                <tr>
                                    <td class="border px-4 py-2 font-medium bg-gray-50" colspan="7">K3 & Keamanan</td>
                                </tr>
                                
                                @foreach($items as $index => $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="status_{{ $index }}" value="ada" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="status_{{ $index }}" value="tidak_ada" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="kondisi_{{ $index }}" value="normal" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="kondisi_{{ $index }}" value="abnormal" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <textarea name="keterangan_{{ $index }}" class="w-full p-2 border rounded resize-y min-h-[80px]" placeholder="Masukkan keterangan..."></textarea>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <button type="button" 
                                                onclick="showMediaModal('row_{{ $index }}')"
                                                class="w-full px-3 py-2 text-sm font-medium text-blue-600 
                                                       bg-blue-50 rounded-lg hover:bg-blue-100
                                                       focus:outline-none focus:ring-2 focus:ring-blue-200
                                                       transition-all duration-300">
                                            <i class="fas fa-upload mr-2"></i>
                                            Upload Media
                                        </button>
                                        <div id="preview_row_{{ $index }}" class="hidden mt-2">
                                            <!-- Preview will be shown here after upload -->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach

                                <!-- Lingkungan Section -->
                                <tr>
                                    <td class="border px-4 py-2 font-medium bg-gray-50" colspan="7">Lingkungan</td>
                                </tr>
                                @php
                                    $lingkunganItems = [
                                        'Unsafe action',
                                        'Unsafe condition',
                                        'Lainnya'
                                    ];
                                @endphp

                                @foreach($lingkunganItems as $index => $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item }}</td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="status_lingkungan_{{ $index }}" value="ada" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="status_lingkungan_{{ $index }}" value="tidak_ada" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="kondisi_lingkungan_{{ $index }}" value="normal" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2 text-center">
                                        <input type="radio" name="kondisi_lingkungan_{{ $index }}" value="abnormal" class="w-4 h-4 cursor-pointer">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <textarea name="keterangan_lingkungan_{{ $index }}" class="w-full p-2 border rounded resize-y min-h-[80px]" placeholder="Masukkan keterangan..."></textarea>
                                    </td>
                                    <td class="border px-4 py-2">
                                        <button type="button" 
                                                onclick="showMediaModal('lingkungan_{{ $index }}')"
                                                class="w-full px-3 py-2 text-sm font-medium text-blue-600 
                                                       bg-blue-50 rounded-lg hover:bg-blue-100
                                                       focus:outline-none focus:ring-2 focus:ring-blue-200
                                                       transition-all duration-300">
                                            <i class="fas fa-upload mr-2"></i>
                                            Upload Media
                                        </button>
                                        <div id="preview_lingkungan_{{ $index }}" class="hidden mt-2">
                                            <!-- Preview will be shown here after upload -->
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Simpan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include the media upload modal component -->
<x-media-upload-modal />

<style>
    /* Table Styles */
    .table-container {
        overflow-x: auto;
        margin-top: 1rem;
    }
    
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid #e2e8f0;
    }
    
    th {
        background-color: #f8fafc;
        font-weight: 600;
    }
    
    /* Form Element Styles */
    textarea {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    
    textarea:focus {
        outline: none;
        border-color: #3b82f6;
        ring: 2px;
        ring-color: #93c5fd;
    }
    
    select {
        background-color: white;
        border-radius: 0.375rem;
    }
    
    select:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    /* File Input Styles */
    input[type="file"] {
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 0.25rem;
    }
    
    input[type="file"]::-webkit-file-upload-button {
        background-color: #f3f4f6;
        border: 1px solid #d1d5db;
        border-radius: 0.25rem;
        padding: 0.25rem 0.75rem;
        margin-right: 0.5rem;
        cursor: pointer;
    }
    
    /* Radio Button Styles */
    input[type="radio"] {
        cursor: pointer;
        width: 1rem;
        height: 1rem;
    }
</style>

<script>
function toggleFileInput(select) {
    const fileInput = select.parentElement.querySelector('.file-input');
    if (!fileInput) return;

    fileInput.classList.remove('hidden');
    
    // Set accept attribute based on selected type
    switch(select.value) {
        case 'image':
            fileInput.accept = '.jpg,.jpeg,.png';
            break;
        case 'document':
            fileInput.accept = '.pdf,.doc,.docx';
            break;
        case 'video':
            fileInput.accept = '.mp4,.mov';
            break;
        default:
            fileInput.classList.add('hidden');
            break;
    }
}

// Preview file if it's an image
document.querySelectorAll('.file-input').forEach(input => {
    input.addEventListener('change', function(e) {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You can add preview functionality here if needed
                    console.log('Image loaded:', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        }
    });
});

function showMediaModal(rowId) {
    const modal = document.getElementById('mediaUploadModal');
    document.getElementById('mediaRowId').value = rowId;
    modal.classList.remove('hidden');
}

function closeMediaModal() {
    const modal = document.getElementById('mediaUploadModal');
    modal.classList.add('hidden');
    document.getElementById('mediaType').value = '';
}
</script>
@endsection 