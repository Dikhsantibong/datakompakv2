@extends('layouts.app')

@push('styles')
    <style>
        .main-content {
            overflow-y: auto;
            height: calc(100vh - 64px);
        }
    </style>
@endpush

@section('content')
    <div class="flex h-screen bg-gray-50 overflow-auto">
        @include('components.sidebar')
        
        <div id="main-content" class="flex-1 main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-3">
                    <div class="flex items-center gap-x-3">
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

                        <button id="desktop-menu-toggle"
                            class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>

                        <h1 class="text-xl font-semibold text-gray-800">Arsip Digital</h1>
                    </div>

                    <!-- User Dropdown -->
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
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex items-center pt-2">
                <x-admin-breadcrumb :breadcrumbs="[
                    
                    ['name' => 'Arsip Digital', 'url' => null]
                ]" />
            </div>

            <!-- Main Content -->
            <main class="px-6 py-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" style="margin-bottom: 20px;">
                    <div class="p-4 bg-white border-b border-gray-200">
                        <!-- Card Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <!-- Card 1: Laporan Berita Acara -->
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <div class="p-4">
                                    <div class="text-3xl text-blue-600 mb-2">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold mb-1">Laporan Berita Acara</h3>
                                    <p class="text-gray-600 mb-2 text-sm">Kelola laporan berita acara operasional</p>
                                    <a href="{{ route('admin.library.berita-acara') }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>

                            <!-- Card 2: Logsheet -->
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <div class="p-4">
                                    <div class="text-3xl text-green-600 mb-2">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold mb-1">Standarisasi dan Peraturan</h3>
                                    <p class="text-gray-600 mb-2 text-sm">Akses dokumen Standarisasi dan Peraturan</p>
                                    <a href="{{ route('admin.library.standarisasi') }}" class="text-green-600 hover:text-green-800 font-medium text-sm">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>

                            <!-- Card 3: Dokumen SOP -->
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <div class="p-4">
                                    <div class="text-3xl text-purple-600 mb-2">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold mb-1">Bacaan Digital</h3>
                                    <p class="text-gray-600 mb-2 text-sm">Akses dokumen Bacaan Digital</p>
                                    <a href="{{ route('admin.library.bacaan-digital') }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                            <!-- Card 4: Diklat -->
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow">
                                <div class="p-4">
                                    <div class="text-3xl text-indigo-600 mb-2">
                                        <i class="fas fa-graduation-cap"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold mb-1">Diklat</h3>
                                    <p class="text-gray-600 mb-2 text-sm">Akses dokumen Diklat</p>
                                    <a href="{{ route('admin.library.diklat') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                                        Lihat Detail →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto">
                    <!-- Document Categories -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Berita Acara Lapkit Section -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-file-alt text-blue-500 mr-2"></i>
                                    Berita Acara Lapkit
                                </h2>
                                <button onclick="showUploadModal('berita-acara')" 
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                    <i class="fas fa-upload mr-2"></i>Upload
                                </button>
                            </div>
                            <div class="space-y-4">
                                @foreach($beritaAcaraFiles as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                        <div>
                                            <p class="font-medium">{{ $file->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $file->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.library.download', $file->id) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button onclick="deleteFile({{ $file->id }})" 
                                                class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Standarisasi dan Peraturan Section -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-book-open text-green-500 mr-2"></i>
                                    Standarisasi dan Peraturan
                                </h2>
                                <button onclick="showUploadModal('standarisasi')" 
                                        class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                    <i class="fas fa-upload mr-2"></i>Upload
                                </button>
                            </div>
                            <div class="space-y-4">
                                @foreach($standarisasiFiles as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                        <div>
                                            <p class="font-medium">{{ $file->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $file->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.library.download', $file->id) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button onclick="deleteFile({{ $file->id }})" 
                                                class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bacaan Digital Section -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-tablet-alt text-purple-500 mr-2"></i>
                                    Bacaan Digital
                                </h2>
                                <button onclick="showUploadModal('bacaan-digital')" 
                                        class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600">
                                    <i class="fas fa-upload mr-2"></i>Upload
                                </button>
                            </div>
                            <div class="space-y-4">
                                @foreach($bacaanDigitalFiles as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                        <div>
                                            <p class="font-medium">{{ $file->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $file->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.library.download', $file->id) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button onclick="deleteFile({{ $file->id }})" 
                                                class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Diklat Section -->
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    <i class="fas fa-graduation-cap text-yellow-500 mr-2"></i>
                                    Diklat
                                </h2>
                                <button onclick="showUploadModal('diklat')" 
                                        class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                    <i class="fas fa-upload mr-2"></i>Upload
                                </button>
                            </div>
                            <div class="space-y-4">
                                @foreach($diklatFiles as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                        <div>
                                            <p class="font-medium">{{ $file->name }}</p>
                                            <p class="text-sm text-gray-500">{{ $file->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.library.download', $file->id) }}" 
                                           class="text-blue-500 hover:text-blue-700">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button onclick="deleteFile({{ $file->id }})" 
                                                class="text-red-500 hover:text-red-700">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Upload Dokumen</h3>
                <form id="uploadForm" action="{{ route('admin.library.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="category" id="uploadCategory">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="document">
                            Pilih File
                        </label>
                        <input type="file" name="document" id="document" 
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">
                            Deskripsi
                        </label>
                        <textarea name="description" id="description" rows="3" 
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeUploadModal()"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/toggle.js') }}"></script>
    <script>
        function showUploadModal(category) {
            document.getElementById('uploadCategory').value = category;
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
        }

        function deleteFile(fileId) {
            if (confirm('Apakah Anda yakin ingin menghapus file ini?')) {
                fetch(`/admin/library/${fileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                }).then(response => {
                    if (response.ok) {
                        window.location.reload();
                    }
                });
            }
        }
    </script>
@endsection 