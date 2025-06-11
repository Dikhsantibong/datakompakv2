@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20
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

                    <h1 class="text-xl font-semibold text-gray-800">Detail K3 KAMP</h1>
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
                ['name' => 'Data K3 KAMP dan Lingkungan', 'url' => route('admin.k3-kamp.view')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Report Details Section -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                Informasi Laporan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <dl class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Tanggal Laporan</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Asal Unit</dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    {{ $report->sync_unit_origin }}
                                                </span>
                                            </dd>
                                        </div>
                                    </dl>
                                </div>
                                <div>
                                    <dl class="space-y-4">
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Dibuat Pada</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($report->created_at)->format('d/m/Y H:i:s') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                                            <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($report->updated_at)->format('d/m/Y H:i:s') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- K3 & Keamanan Section -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-shield-alt mr-2 text-gray-400"></i>
                                K3 & Keamanan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Eviden</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($report->items->where('item_type', 'k3_keamanan') as $item)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900 border-r border-gray-200">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status === 'ada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->kondisi === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($item->kondisi) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 border-r border-gray-200">{{ $item->keterangan }}</td>
                                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                                @if($item->media->count() > 0)
                                                    <div class="flex flex-wrap gap-2 justify-center">
                                                        @foreach($item->media as $media)
                                                            <div class="relative group">
                                                                <img src="{{ Storage::url($media->file_path) }}" 
                                                                     alt="Eviden {{ $item->item_name }}"
                                                                     class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                                     onclick="openImageModal('{{ Storage::url($media->file_path) }}', '{{ $item->item_name }}')">
                                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <div class="bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs">
                                                                        Klik untuk memperbesar
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">
                                                        <i class="fas fa-ban"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Lingkungan Section -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-tree mr-2 text-gray-400"></i>
                                Lingkungan
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Eviden</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($report->items->where('item_type', 'lingkungan') as $item)
                                        <tr>
                                            <td class="px-6 py-4 text-sm text-gray-900 border-r border-gray-200">{{ $item->item_name }}</td>
                                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->status === 'ada' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $item->kondisi === 'normal' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ ucfirst($item->kondisi) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 border-r border-gray-200">{{ $item->keterangan }}</td>
                                            <td class="px-6 py-4 text-center border-r border-gray-200">
                                                @if($item->media->count() > 0)
                                                    <div class="flex flex-wrap gap-2 justify-center">
                                                        @foreach($item->media as $media)
                                                            <div class="relative group">
                                                                <img src="{{ Storage::url($media->file_path) }}" 
                                                                     alt="Eviden {{ $item->item_name }}"
                                                                     class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                                                     onclick="openImageModal('{{ Storage::url($media->file_path) }}', '{{ $item->item_name }}')">
                                                                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <div class="bg-black bg-opacity-50 text-white px-2 py-1 rounded-full text-xs">
                                                                        Klik untuk memperbesar
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400">
                                                        <i class="fas fa-ban"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.k3-kamp.view') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <a href="{{ route('admin.k3-kamp.edit', $report->id) }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    {{-- <a href="{{ route('admin.k3-kamp.export-pdf', $report->id) }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="fas fa-file-pdf mr-2"></i>
                        Export PDF
                    </a>
                    <a href="{{ route('admin.k3-kamp.export-excel', $report->id) }}" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-file-excel mr-2"></i>
                        Export Excel
                    </a> --}}
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Media Modal -->
<div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto h-full w-full bg-black bg-opacity-75 flex items-center justify-center">
    <div class="relative max-w-4xl w-full mx-4">
        <div class="bg-white rounded-lg shadow-xl">
            <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle"></h3>
                <button onclick="closeImageModal()" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4">
                <img id="modalImage" src="" alt="Preview" class="max-w-full h-auto mx-auto rounded-lg">
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
function openImageModal(imageUrl, title) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    
    modalImage.src = imageUrl;
    modalTitle.textContent = 'Eviden ' + title;
    modal.classList.remove('hidden');
    
    // Close modal when clicking outside
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeImageModal();
        }
    };

    // Add keyboard support for closing
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}
</script>
@endpush

@endsection 