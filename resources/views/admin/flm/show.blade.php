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
        <header class="bg-white shadow-sm sticky top-0 z-10
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

                    <h1 class="text-xl font-semibold text-gray-800">Laporan FLM</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Data Pemeriksaan FLM', 'url' => route('admin.flm.list')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-4">
                <!-- Info Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-4">
                    <div class="p-4 border-b">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500">ID Pemeriksaan</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $mainData->flm_id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Operator</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $mainData->operator }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Tanggal</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $mainData->tanggal->format('d/m/Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Shift</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $mainData->shift }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Waktu</p>
                                <p class="mt-1 text-sm text-gray-900">{{ $mainData->time->format('H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Mesin/peralatan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Sistem pembangkit</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Masalah yang ditemukan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi awal</th>
                                        <th colspan="5" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Tindakan FLM</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi akhir</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Catatan FLM</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Eviden</th>
                                    </tr>
                                    <tr class="bg-gray-50">
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2 text-xs font-medium text-gray-500">bersihkan</th>
                                        <th class="border px-4 py-2 text-xs font-medium text-gray-500">lumasi</th>
                                        <th class="border px-4 py-2 text-xs font-medium text-gray-500">kencangkan</th>
                                        <th class="border px-4 py-2 text-xs font-medium text-gray-500">perbaikan koneksi</th>
                                        <th class="border px-4 py-2 text-xs font-medium text-gray-500">lainnya</th>
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2"></th>
                                        <th class="border px-4 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($flmDetails as $index => $detail)
                                    <tr>
                                        <td class="border px-4 py-2 text-center">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="text-sm text-gray-900 w-[200px]">{{ $detail->mesin }}</div>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="text-sm text-gray-900 w-[200px]">{{ $detail->sistem }}</div>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="text-sm text-gray-900 w-[200px]">{{ $detail->masalah }}</div>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="text-sm text-gray-900 w-[200px]">{{ $detail->kondisi_awal }}</div>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <span class="inline-flex items-center justify-center">
                                                <i class="fas fa-{{ $detail->tindakan_bersihkan ? 'check text-green-500' : 'times text-red-500' }} text-lg"></i>
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <span class="inline-flex items-center justify-center">
                                                <i class="fas fa-{{ $detail->tindakan_lumasi ? 'check text-green-500' : 'times text-red-500' }} text-lg"></i>
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <span class="inline-flex items-center justify-center">
                                                <i class="fas fa-{{ $detail->tindakan_kencangkan ? 'check text-green-500' : 'times text-red-500' }} text-lg"></i>
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <span class="inline-flex items-center justify-center">
                                                <i class="fas fa-{{ $detail->tindakan_perbaikan_koneksi ? 'check text-green-500' : 'times text-red-500' }} text-lg"></i>
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2 text-center">
                                            <span class="inline-flex items-center justify-center">
                                                <i class="fas fa-{{ $detail->tindakan_lainnya ? 'check text-green-500' : 'times text-red-500' }} text-lg"></i>
                                            </span>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="text-sm text-gray-900 w-[200px]">{{ $detail->kondisi_akhir }}</div>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="text-sm text-gray-900 w-[200px]">{{ $detail->catatan ?: '-' }}</div>
                                        </td>
                                        <td class="border px-4 py-2">
                                            <div class="space-y-4 w-[300px]">
                                                @if($detail->eviden_sebelum || $detail->eviden_sesudah)
                                                <div class="evidence-grid">
                                                    @if($detail->eviden_sebelum)
                                                    <div class="evidence-container">
                                                        <p class="text-sm font-medium text-gray-500 mb-2">Foto Sebelum:</p>
                                                        <img src="{{ asset('storage/'.$detail->eviden_sebelum) }}" 
                                                             alt="Foto Sebelum"
                                                             class="evidence-image"
                                                             onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                                        <button type="button" 
                                                                onclick="openLightbox('{{ asset('storage/'.$detail->eviden_sebelum) }}', 'Foto Sebelum')"
                                                                class="zoom-button">
                                                            <span class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                                                                <i class="fas fa-search-plus mr-2"></i>Perbesar
                                                            </span>
                                                        </button>
                                                    </div>
                                                    @endif

                                                    @if($detail->eviden_sesudah)
                                                    <div class="evidence-container">
                                                        <p class="text-sm font-medium text-gray-500 mb-2">Foto Sesudah:</p>
                                                        <img src="{{ asset('storage/'.$detail->eviden_sesudah) }}" 
                                                             alt="Foto Sesudah"
                                                             class="evidence-image"
                                                             onerror="this.src='{{ asset('images/no-image.png') }}'; this.onerror=null;">
                                                        <button type="button" 
                                                                onclick="openLightbox('{{ asset('storage/'.$detail->eviden_sesudah) }}', 'Foto Sesudah')"
                                                                class="zoom-button">
                                                            <span class="bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm">
                                                                <i class="fas fa-search-plus mr-2"></i>Perbesar
                                                            </span>
                                                        </button>
                                                    </div>
                                                    @endif
                                                </div>
                                                @else
                                                <div class="text-center py-4">
                                                    <p class="text-sm text-gray-500">Tidak ada eviden</p>
                                                </div>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('admin.flm.list') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9] transition duration-150">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                            <button type="button"
                                onclick="printFLM()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150">
                                <i class="fas fa-print mr-2"></i>
                                Cetak
                            </button>
                        </div>
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
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
function printFLM() {
    window.print();
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