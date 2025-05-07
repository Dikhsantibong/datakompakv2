@extends('layouts.app')

@section('content')
<style>
    .evidence-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
        width: 100%;
    }
    .evidence-image {
        width: 100%;
        height: 140px;
        object-fit: cover;
        border-radius: 0.375rem;
        display: block;
    }
    .evidence-container {
        position: relative;
        overflow: hidden;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }
    .zoom-button {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(0, 0, 0, 0.3);
        opacity: 0;
        transition: opacity 0.2s;
    }
    .evidence-container:hover .zoom-button {
        opacity: 1;
    }
</style>

<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-900">Edit Data FLM</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Data Pemeriksaan FLM', 'url' => route('admin.flm.list')],
                ['name' => 'Edit Data', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <form action="{{ route('admin.flm.update', $flm->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Form Header -->
                            <div class="mb-6">
                                <div class="w-full md:w-1/3">
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ $flm->tanggal }}" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="min-w-full border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border px-4 py-2 text-sm">Mesin/peralatan</th>
                                            <th class="border px-4 py-2 text-sm">Sistem pembangkit</th>
                                            <th class="border px-4 py-2 text-sm">Masalah awal yang ditemukan</th>
                                            <th class="border px-4 py-2 text-sm">kondisi awal</th>
                                            <th colspan="5" class="border px-4 py-2 text-sm text-center">Tindakan FLM</th>
                                            <th class="border px-4 py-2 text-sm">kondisi akhir</th>
                                            <th class="border px-4 py-2 text-sm">Catatan FLM</th>
                                            <th class="border px-4 py-2 text-sm">Eviden</th>
                                        </tr>
                                        <tr class="bg-gray-50">
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border px-4 py-2">
                                                <textarea name="mesin" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ $flm->mesin }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="sistem" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ $flm->sistem }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="masalah" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ $flm->masalah }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="kondisi_awal" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ $flm->kondisi_awal }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[]" value="bersihkan" {{ $flm->tindakan_bersihkan ? 'checked' : '' }}
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[]" value="lumasi" {{ $flm->tindakan_lumasi ? 'checked' : '' }}
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[]" value="kencangkan" {{ $flm->tindakan_kencangkan ? 'checked' : '' }}
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[]" value="perbaikan_koneksi" {{ $flm->tindakan_perbaikan_koneksi ? 'checked' : '' }}
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" name="tindakan[]" value="lainnya" {{ $flm->tindakan_lainnya ? 'checked' : '' }}
                                                    class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500">
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="kondisi_akhir" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500" required>{{ $flm->kondisi_akhir }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <textarea name="catatan" class="w-[200px] h-[100px] p-1 border-gray-300 rounded resize-none focus:border-blue-500 focus:ring-blue-500">{{ $flm->catatan }}</textarea>
                                            </td>
                                            <td class="border px-4 py-2">
                                                <div class="space-y-3 w-[300px] p-2">
                                                    <div class="evidence-grid">
                                                        <div class="evidence-container">
                                                            <p class="text-sm font-medium text-gray-700 mb-1">Foto Sebelum:</p>
                                                            @if($flm->eviden_sebelum)
                                                                <div class="relative">
                                                                    <img src="{{ asset('storage/' . $flm->eviden_sebelum) }}" 
                                                                         alt="Eviden Sebelum" 
                                                                         class="evidence-image"
                                                                         onerror="this.src='{{ asset('images/no-image.png') }}'">
                                                                    <button type="button" 
                                                                            onclick="openLightbox('{{ asset('storage/' . $flm->eviden_sebelum) }}', 'Foto Sebelum')"
                                                                            class="zoom-button">
                                                                        <span class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                                                                            <i class="fas fa-search-plus mr-2"></i>Perbesar
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                            <input type="file" 
                                                                   name="eviden_sebelum" 
                                                                   accept="image/*"
                                                                   class="hidden"
                                                                   onchange="previewImage(this, this.parentElement.querySelector('.preview-image'))"
                                                                   data-preview="sebelum">
                                                            <div class="preview-container mb-2 hidden">
                                                                <img src="#" alt="Preview" class="evidence-image">
                                                                <button type="button" 
                                                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                                                        onclick="removeImage(this)">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <button type="button"
                                                                    onclick="this.previousElementSibling.previousElementSibling.click()"
                                                                    class="upload-btn w-full mt-2 px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg border-2 border-blue-100 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                <i class="fas fa-camera mr-2"></i>
                                                                {{ $flm->eviden_sebelum ? 'Ganti Foto Sebelum' : 'Pilih Foto Sebelum' }}
                                                            </button>
                                                        </div>

                                                        <div class="evidence-container">
                                                            <p class="text-sm font-medium text-gray-700 mb-1">Foto Sesudah:</p>
                                                            @if($flm->eviden_sesudah)
                                                                <div class="relative">
                                                                    <img src="{{ asset('storage/' . $flm->eviden_sesudah) }}" 
                                                                         alt="Eviden Sesudah" 
                                                                         class="evidence-image"
                                                                         onerror="this.src='{{ asset('images/no-image.png') }}'">
                                                                    <button type="button" 
                                                                            onclick="openLightbox('{{ asset('storage/' . $flm->eviden_sesudah) }}', 'Foto Sesudah')"
                                                                            class="zoom-button">
                                                                        <span class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                                                                            <i class="fas fa-search-plus mr-2"></i>Perbesar
                                                                        </span>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                            <input type="file" 
                                                                   name="eviden_sesudah" 
                                                                   accept="image/*"
                                                                   class="hidden"
                                                                   onchange="previewImage(this, this.parentElement.querySelector('.preview-image'))"
                                                                   data-preview="sesudah">
                                                            <div class="preview-container mb-2 hidden">
                                                                <img src="#" alt="Preview" class="evidence-image">
                                                                <button type="button" 
                                                                        class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
                                                                        onclick="removeImage(this)">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </div>
                                                            <button type="button"
                                                                    onclick="this.previousElementSibling.previousElementSibling.click()"
                                                                    class="upload-btn w-full mt-2 px-4 py-2 text-sm text-blue-600 bg-blue-50 rounded-lg border-2 border-blue-100 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                                                <i class="fas fa-camera mr-2"></i>
                                                                {{ $flm->eviden_sesudah ? 'Ganti Foto Sesudah' : 'Pilih Foto Sesudah' }}
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <a href="{{ route('admin.flm.list') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-times mr-2"></i>
                                    Batal
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <i class="fas fa-save mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="lightboxTitle"></h3>
                        <div class="mt-2">
                            <img id="lightboxImage" src="" alt="" class="w-full max-h-[70vh] object-contain">
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        onclick="closeLightbox()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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

function openLightbox(imageUrl, title) {
    document.getElementById('lightboxImage').src = imageUrl;
    document.getElementById('lightboxTitle').textContent = title;
    document.getElementById('lightboxModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightboxModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close lightbox when clicking outside the image
document.getElementById('lightboxModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Close lightbox when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeLightbox();
    }
});
</script>
@endpush

@endsection 