@extends('layouts.app')

@section('content')
    <div class="flex h-screen bg-gray-50 overflow-auto">
        @include('components.sidebar')
        
        <div id="main-content" class="flex-1 main-content">
            <!-- Header -->
            <header class="bg-white shadow-sm sticky top-0 z-10">
                <div class="flex justify-between items-center px-6 py-3">
                    <div class="flex items-center gap-x-3">
                        <button id="mobile-menu-toggle" class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                            <span class="sr-only">Open main menu</span>
                            <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>
                        </button>
                        <h1 class="text-xl font-semibold text-gray-800">Laporan Berita Acara</h1>
                    </div>
                    <div class="relative">
                        <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                            <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                                class="w-8 h-8 rounded-full mr-2">
                            <span class="text-gray-700">{{ Auth::user()->name }}</span>
                            <i class="fas fa-caret-down ml-2"></i>
                        </button>
                        <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                            <a href="{{ route('user.profile') }}"
                                class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Profile</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex items-center pt-2">
                <x-admin-breadcrumb :breadcrumbs="[
                    ['name' => 'Arsip Digital', 'url' => route('admin.library.index')],
                    ['name' => 'Laporan Berita Acara', 'url' => null]
                ]" />
            </div>

            <!-- Main Content -->
            <main class="px-6 py-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-lg font-semibold">Daftar Dokumen Berita Acara</h2>
                        <div class="flex gap-4">
                            <a href="{{ route('admin.library.index') }}" 
                               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                <i class="fas fa-arrow-left mr-2"></i>Kembali
                            </a>
                            <button onclick="showUploadModal('berita-acara')" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                                <i class="fas fa-upload mr-2"></i>Upload Dokumen
                            </button>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="flex gap-x-3">
                            <div class="flex-1">
                                <input type="text" 
                                       id="searchInput" 
                                       placeholder="Cari dokumen..." 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button onclick="clearSearch()" 
                                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-md hover:bg-gray-200">
                                <i class="fas fa-times"></i> Clear
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Dokumen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Upload</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($beritaAcaraFiles ?? [] as $file)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                                        <div class="flex items-center">
                                            <i class="fas fa-file-pdf text-red-500 mr-3"></i>
                                            <span class="text-sm text-gray-900">{{ $file->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border border-gray-200">
                                        <span class="text-sm text-gray-500">{{ $file->description }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
                                        <span class="text-sm text-gray-500">{{ $file->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap border border-gray-200">
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @include('components.upload-modal')

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function searchDocuments() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const table = document.querySelector('table');
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByTagName('td')[0];
                if (nameCell) {
                    const nameText = nameCell.textContent || nameCell.innerText;
                    if (nameText.toLowerCase().indexOf(filter) > -1) {
                        rows[i].style.display = '';
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }
        }

        function clearSearch() {
            const input = document.getElementById('searchInput');
            input.value = '';
            searchDocuments();
        }

        document.getElementById('searchInput').addEventListener('keyup', searchDocuments);

        function deleteFile(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Dokumen yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/library/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                'Terhapus!',
                                'Dokumen berhasil dihapus.',
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Terjadi kesalahan saat menghapus dokumen');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus dokumen.',
                            'error'
                        );
                    });
                }
            });
        }
    </script>
    @endpush
@endsection