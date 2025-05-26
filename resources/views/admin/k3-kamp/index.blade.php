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
                    <h1 class="text-xl font-semibold text-gray-900">Input Data K3 Keamanan dan Lingkungan</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Input Data K3 Keamanan dan Lingkungan', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Input Data K3 Keamanan dan Lingkungan</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor implementasi K3 Keamanan dan Lingkungan untuk meningkatkan keselamatan dan kesehatan kerja.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.k3-kamp.view') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 bg-white rounded-md hover:bg-gray-50">
                                <i class="fas fa-list mr-2"></i> Lihat Data
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <form id="k3-form" action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border">Item</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border w-[100px]">Ada</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border w-[100px]">Tidak Ada</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border w-[100px]">Normal</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border w-[100px]">Abnormal</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border">Keterangan</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border-r border w-[200px]">Eviden</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <!-- K3 & Keamanan Section -->
                                        <tr>
                                            <td colspan="7" class="px-6 py-3 text-sm font-medium text-gray-900 bg-gray-50 border-r border">K3 & Keamanan</td>
                                        </tr>
                                        
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

                                        @foreach($items as $index => $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 border-r border">{{ $item }}</td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="status_{{ $index }}[]" value="ada" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="status_{{ $index }}[]" value="tidak_ada" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="kondisi_{{ $index }}[]" value="normal" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="kondisi_{{ $index }}[]" value="abnormal" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 border-r border">
                                                <textarea name="keterangan_{{ $index }}" rows="2" 
                                                          class="w-[300px] h-[100px] px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                                          placeholder="Masukkan keterangan..."></textarea>
                                            </td>
                                            <td class="px-6 py-4 border-r border">
                                                <input type="file" 
                                                       id="file_{{ $index }}"
                                                       class="hidden"
                                                       accept="image/*"
                                                       onchange="handleFileSelect(this, '{{ $index }}')">
                                                <input type="hidden" name="eviden_path_{{ $index }}" id="eviden_path_{{ $index }}">
                                                <button type="button" 
                                                        onclick="document.getElementById('file_{{ $index }}').click()"
                                                        class="w-full px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                    <i class="fas fa-upload mr-2"></i>
                                                    Upload Eviden
                                                </button>
                                                <div id="preview_{{ $index }}" class="mt-2 relative">
                                                    <!-- Preview will be shown here -->
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                        <!-- Lingkungan Section -->
                                        <tr>
                                            <td colspan="7" class="px-6 py-3 text-sm font-medium text-gray-900 bg-gray-50 border-r border">Lingkungan</td>
                                        </tr>
                                        
                                        @php
                                        $lingkunganItems = [
                                            'Unsafe action',
                                            'Unsafe condition',
                                            'Lainnya'
                                        ];
                                        @endphp

                                        @foreach($lingkunganItems as $index => $item)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 text-sm text-gray-900 border-r border">{{ $item }}</td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="status_lingkungan_{{ $index }}[]" value="ada" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="status_lingkungan_{{ $index }}[]" value="tidak_ada" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="kondisi_lingkungan_{{ $index }}[]" value="normal" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border">
                                                <input type="checkbox" name="kondisi_lingkungan_{{ $index }}[]" value="abnormal" 
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="px-6 py-4 border-r border">
                                                <textarea name="keterangan_lingkungan_{{ $index }}" rows="2" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                                          placeholder="Masukkan keterangan..."></textarea>
                                            </td>
                                            <td class="px-6 py-4 border-r border">
                                                <input type="file" 
                                                       id="file_lingkungan_{{ $index }}"
                                                       class="hidden"
                                                       accept="image/*"
                                                       onchange="handleFileSelect(this, 'lingkungan_{{ $index }}')">
                                                <input type="hidden" name="eviden_path_lingkungan_{{ $index }}" id="eviden_path_lingkungan_{{ $index }}">
                                                <button type="button" 
                                                        onclick="document.getElementById('file_lingkungan_{{ $index }}').click()"
                                                        class="w-full px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-200">
                                                    <i class="fas fa-upload mr-2"></i>
                                                    Upload Eviden
                                                </button>
                                                <div id="preview_lingkungan_{{ $index }}" class="mt-2 relative">
                                                    <!-- Preview will be shown here -->
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Add Save Button -->
                            <div class="mt-6 flex justify-end">
                                <button type="submit" form="k3-form"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Include the media upload modal component -->
<x-media-upload-modal />

<style>
/* Smooth transitions */
.bg-gradient-to-r {
    transition: all 0.3s ease-in-out;
    max-height: 1000px; /* Adjust this value based on your content */
    opacity: 1;
    overflow: hidden;
}

.bg-gradient-to-r.hidden-section {
    max-height: 0;
    opacity: 0;
    margin: 0;
    padding: 0;
}

main {
    transition: padding 0.3s ease-in-out;
}
</style>

<script src="{{ asset('js/toggle.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle checkbox exclusivity within groups
    const checkboxGroups = document.querySelectorAll('tr');
    checkboxGroups.forEach(row => {
        const statusCheckboxes = row.querySelectorAll('input[type="checkbox"][name^="status"]');
        const kondisiCheckboxes = row.querySelectorAll('input[type="checkbox"][name^="kondisi"]');

        function handleCheckboxGroup(checkboxes) {
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (this.checked) {
                        checkboxes.forEach(cb => {
                            if (cb !== this) cb.checked = false;
                        });
                    }
                });
            });
        }

        handleCheckboxGroup(statusCheckboxes);
        handleCheckboxGroup(kondisiCheckboxes);
    });
});

// Add full table view toggle functionality
function toggleFullTableView() {
    const button = document.getElementById('toggleFullTable');
    const welcomeCard = document.querySelector('.bg-gradient-to-r');
    const mainContent = document.querySelector('main');
    
    // Toggle full table mode
    const isFullTable = button.classList.contains('bg-blue-600');
    
    if (isFullTable) {
        // Restore normal view
        button.classList.remove('bg-blue-600', 'text-white');
        button.classList.add('bg-blue-50', 'text-blue-600');
        button.innerHTML = '<i class="fas fa-expand mr-1"></i> Full Table';
        
        if (welcomeCard) {
            welcomeCard.classList.remove('hidden-section');
            setTimeout(() => welcomeCard.style.display = '', 10);
        }
        if (mainContent) mainContent.classList.remove('pt-0');
        
    } else {
        // Enable full table view
        button.classList.remove('bg-blue-50', 'text-blue-600');
        button.classList.add('bg-blue-600', 'text-white');
        button.innerHTML = '<i class="fas fa-compress mr-1"></i> Normal View';
        
        if (welcomeCard) {
            welcomeCard.classList.add('hidden-section');
            setTimeout(() => welcomeCard.style.display = 'none', 300);
        }
        if (mainContent) mainContent.classList.add('pt-0');
    }
}

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

function handleFileSelect(input, rowId) {
    const file = input.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('media_file', file);
    formData.append('row_id', rowId);
    formData.append('_token', '{{ csrf_token() }}');

    // Show loading state
    const previewDiv = document.getElementById(`preview_${rowId}`);
    previewDiv.innerHTML = '<div class="text-center py-2">Uploading...</div>';

    fetch('{{ route('admin.k3-kamp.upload-media') }}', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Set file path to hidden input
            document.getElementById(`eviden_path_${rowId}`).value = data.data.file_path;
            
            // Show preview
            previewDiv.innerHTML = `
                <div class="relative">
                    <img src="${data.data.preview_url}" 
                         alt="Preview" 
                         class="w-full h-32 object-cover rounded-md">
                    <button onclick="deleteMedia('${data.data.id}', '${rowId}')"
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 m-1 hover:bg-red-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">${data.data.file_name}</p>
            `;
        } else {
            previewDiv.innerHTML = `<div class="text-red-500 text-sm">${data.message}</div>`;
        }
    })
    .catch(error => {
        previewDiv.innerHTML = '<div class="text-red-500 text-sm">Upload failed</div>';
        console.error('Error:', error);
    });
}

function deleteMedia(mediaId, rowId) {
    if (!confirm('Are you sure you want to delete this image?')) return;

    fetch(`{{ url('admin/k3-kamp/delete-media') }}/${mediaId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear preview and hidden input
            document.getElementById(`preview_${rowId}`).innerHTML = '';
            document.getElementById(`file_${rowId}`).value = '';
            document.getElementById(`eviden_path_${rowId}`).value = '';
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete media');
    });
}
</script>
@endsection 