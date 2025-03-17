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
            <main class="px-6">
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
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.library.berita-acara') }}" 
                                       class="text-blue-500 hover:text-blue-700">
                                        Lihat Semua
                                    </a>
                                    <button onclick="showUploadModal('berita-acara')" 
                                            class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                        <i class="fas fa-upload mr-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($beritaAcaraFiles->take(5) as $file)
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
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.library.standarisasi') }}" 
                                       class="text-green-500 hover:text-green-700">
                                        Lihat Semua
                                    </a>
                                    <button onclick="showUploadModal('standarisasi')" 
                                            class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                                        <i class="fas fa-upload mr-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($standarisasiFiles->take(5) as $file)
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
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.library.bacaan-digital') }}" 
                                       class="text-purple-500 hover:text-purple-700">
                                        Lihat Semua
                                    </a>
                                    <button onclick="showUploadModal('bacaan-digital')" 
                                            class="bg-purple-500 text-white px-4 py-2 rounded-md hover:bg-purple-600">
                                        <i class="fas fa-upload mr-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($bacaanDigitalFiles->take(5) as $file)
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
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.library.diklat') }}" 
                                       class="text-yellow-500 hover:text-yellow-700">
                                        Lihat Semua
                                    </a>
                                    <button onclick="showUploadModal('diklat')" 
                                            class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600">
                                        <i class="fas fa-upload mr-2"></i>Upload
                                    </button>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($diklatFiles->take(5) as $file)
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
    <div id="uploadModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-sm transition-all duration-300">
        <div class="relative top-20 mx-auto p-8 border w-[500px] shadow-2xl rounded-xl bg-white transform transition-all">
            <div class="mt-2">
                <!-- Header -->
                <div class="flex justify-between items-center pb-5 border-b">
                    <div class="flex items-center gap-3">
                        <div class="bg-blue-100 p-2 rounded-lg">
                            <i class="fas fa-cloud-upload-alt text-blue-600 text-xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800">Upload Dokumen</h3>
                    </div>
                    <button onclick="closeUploadModal()" 
                            class="text-gray-400 hover:text-gray-500 hover:rotate-90 transition-all duration-300">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Form -->
                <form id="uploadForm" action="{{ route('admin.library.upload') }}" method="POST" enctype="multipart/form-data" class="mt-6">
                    @csrf
                    <input type="hidden" name="category" id="uploadCategory">
                    
                    <!-- File Input -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-3" for="document">
                            Pilih File
                        </label>
                        <div class="relative">
                            <!-- Drag & Drop Zone -->
                            <div id="dropZone" 
                                 class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center
                                        transition-all duration-300 ease-in-out
                                        hover:border-blue-500 hover:bg-blue-50">
                                <input type="file" 
                                       name="document" 
                                       id="document" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx"
                                       required>
                                
                                <div class="space-y-4">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-blue-500"></i>
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-700">
                                            Drag & drop file disini atau
                                            <span class="text-blue-500">pilih file</span>
                                        </p>
                                        <p class="text-gray-500 mt-1">
                                            Format yang didukung: PDF, DOC, DOCX, XLS, XLSX (Max. 10MB)
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div id="filePreview" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <i class="fas fa-file-pdf text-red-500 text-xl"></i>
                                        <div>
                                            <p id="fileName" class="text-sm font-medium text-gray-700"></p>
                                            <p id="fileSize" class="text-xs text-gray-500"></p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            onclick="removeFile()" 
                                            class="text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Description Input -->
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-semibold mb-3" for="description">
                            Deskripsi Dokumen
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  class="w-full px-4 py-2.5 text-sm text-gray-700
                                         border border-gray-200 rounded-lg
                                         focus:outline-none focus:border-blue-500
                                         transition-all duration-300
                                         resize-none"
                                  rows="4"
                                  placeholder="Tambahkan deskripsi dokumen..."></textarea>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" 
                                onclick="closeUploadModal()" 
                                class="px-4 py-2.5 text-sm font-medium text-gray-700 
                                       bg-white border border-gray-300 rounded-lg
                                       hover:bg-gray-50 hover:border-gray-400
                                       focus:outline-none focus:ring-2 focus:ring-gray-200
                                       transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                        <button type="submit" 
                                class="px-4 py-2.5 text-sm font-medium text-white
                                       bg-blue-600 rounded-lg
                                       hover:bg-blue-700
                                       focus:outline-none focus:ring-2 focus:ring-blue-500
                                       transition-all duration-300">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>Upload
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/toggle.js') }}"></script>
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('document');
        const filePreview = document.getElementById('filePreview');
        const fileName = document.getElementById('fileName');
        const fileSize = document.getElementById('fileSize');

        // Prevent default drag behaviors
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
            document.body.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop zone when dragging over it
        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        // Handle dropped files
        dropZone.addEventListener('drop', handleDrop, false);

        // Handle file input change
        fileInput.addEventListener('change', handleFiles);

        function preventDefaults (e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('border-blue-500', 'bg-blue-50');
        }

        function unhighlight(e) {
            dropZone.classList.remove('border-blue-500', 'bg-blue-50');
        }

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles({ target: { files: files } });
        }

        function handleFiles(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file type
                const allowedTypes = ['.pdf', '.doc', '.docx', '.xls', '.xlsx'];
                const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                
                if (!allowedTypes.includes(fileExtension)) {
                    alert('Format file tidak didukung. Silakan pilih file PDF, DOC, DOCX, XLS, atau XLSX.');
                    return;
                }

                // Check file size (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 10MB.');
                    return;
                }

                // Update preview
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                dropZone.classList.add('hidden');
                filePreview.classList.remove('hidden');
            }
        }

        function removeFile() {
            fileInput.value = '';
            dropZone.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function showUploadModal(category) {
            const modal = document.getElementById('uploadModal');
            document.getElementById('uploadCategory').value = category;
            modal.classList.remove('hidden');
            // Reset file input
            removeFile();
            // Add animation
            setTimeout(() => {
                modal.querySelector('.relative').classList.add('translate-y-0', 'opacity-100');
                modal.querySelector('.relative').classList.remove('translate-y-4', 'opacity-0');
            }, 100);
        }

        function closeUploadModal() {
            const modal = document.getElementById('uploadModal');
            const modalContent = modal.querySelector('.relative');
            
            // Add closing animation
            modalContent.classList.add('translate-y-4', 'opacity-0');
            modalContent.classList.remove('translate-y-0', 'opacity-100');
            
            // Hide modal after animation
            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('document').value = '';
                document.getElementById('description').value = '';
                removeFile();
            }, 300);
        }

        // Close modal when clicking outside
        document.getElementById('uploadModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeUploadModal();
            }
        });

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

    <style>
        /* Animation classes */
        .relative {
            transition: all 0.3s ease-out;
        }
        
        #uploadModal:not(.hidden) .relative {
            animation: modalFadeIn 0.3s ease-out forwards;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Drag & Drop Styles */
        #dropZone.drag-over {
            border-color: #3B82F6;
            background-color: #EFF6FF;
        }
    </style>
@endsection 