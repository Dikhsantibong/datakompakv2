@extends('layouts.app')

@section('content')
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
                    <h1 class="text-xl font-semibold text-gray-900">Edit Data Pengadaan</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Operasi UPKD', 'url' => null], ['name' => 'Pengadaan Barang dan Jasa', 'url' => route('admin.operasi-upkd.pengadaan.index')], ['name' => 'Edit', 'url' => null]]" />
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Edit Data Pengadaan</h2>
                            <a href="{{ route('admin.operasi-upkd.pengadaan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Kembali
                            </a>
                        </div>

                        <form action="{{ route('admin.operasi-upkd.pengadaan.update', $pengadaan->id) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="judul" class="block text-sm font-medium text-gray-700">Item Pekerjaan</label>
                                    <input type="text" name="judul" id="judul" value="{{ old('judul', $pengadaan->judul) }}" 
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('judul')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                                    <input type="number" name="tahun" id="tahun" value="{{ old('tahun', $pengadaan->tahun) }}" 
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('tahun')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="jenis" class="block text-sm font-medium text-gray-700">Jenis</label>
                                    <select name="jenis" id="jenis" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Rutin" {{ old('jenis', $pengadaan->jenis) == 'Rutin' ? 'selected' : '' }}>Rutin</option>
                                        <option value="Non Rutin" {{ old('jenis', $pengadaan->jenis) == 'Non Rutin' ? 'selected' : '' }}>Non Rutin</option>
                                    </select>
                                    @error('jenis')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="intensitas" class="block text-sm font-medium text-gray-700">Intensitas</label>
                                    <input type="text" name="intensitas" id="intensitas" value="{{ old('intensitas', $pengadaan->intensitas) }}" 
                                           class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                    @error('intensitas')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pengusulan" class="block text-sm font-medium text-gray-700">Pengusulan</label>
                                    <select name="pengusulan" id="pengusulan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Open" {{ old('pengusulan', $pengadaan->pengusulan) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Close" {{ old('pengusulan', $pengadaan->pengusulan) == 'Close' ? 'selected' : '' }}>Close</option>
                                        <option value="On Progress" {{ old('pengusulan', $pengadaan->pengusulan) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                    </select>
                                    @error('pengusulan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="proses_kontrak" class="block text-sm font-medium text-gray-700">Proses Kontrak</label>
                                    <select name="proses_kontrak" id="proses_kontrak" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Open" {{ old('proses_kontrak', $pengadaan->proses_kontrak) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Close" {{ old('proses_kontrak', $pengadaan->proses_kontrak) == 'Close' ? 'selected' : '' }}>Close</option>
                                        <option value="On Progress" {{ old('proses_kontrak', $pengadaan->proses_kontrak) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                    </select>
                                    @error('proses_kontrak')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pengadaan" class="block text-sm font-medium text-gray-700">Pengadaan</label>
                                    <select name="pengadaan" id="pengadaan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Open" {{ old('pengadaan', $pengadaan->pengadaan) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Close" {{ old('pengadaan', $pengadaan->pengadaan) == 'Close' ? 'selected' : '' }}>Close</option>
                                        <option value="On Progress" {{ old('pengadaan', $pengadaan->pengadaan) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                    </select>
                                    @error('pengadaan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pekerjaan_fisik" class="block text-sm font-medium text-gray-700">Pekerjaan Fisik</label>
                                    <select name="pekerjaan_fisik" id="pekerjaan_fisik" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Open" {{ old('pekerjaan_fisik', $pengadaan->pekerjaan_fisik) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Close" {{ old('pekerjaan_fisik', $pengadaan->pekerjaan_fisik) == 'Close' ? 'selected' : '' }}>Close</option>
                                        <option value="On Progress" {{ old('pekerjaan_fisik', $pengadaan->pekerjaan_fisik) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                    </select>
                                    @error('pekerjaan_fisik')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pemberkasan" class="block text-sm font-medium text-gray-700">Pemberkasan</label>
                                    <select name="pemberkasan" id="pemberkasan" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Open" {{ old('pemberkasan', $pengadaan->pemberkasan) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Close" {{ old('pemberkasan', $pengadaan->pemberkasan) == 'Close' ? 'selected' : '' }}>Close</option>
                                        <option value="On Progress" {{ old('pemberkasan', $pengadaan->pemberkasan) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                    </select>
                                    @error('pemberkasan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="pembayaran" class="block text-sm font-medium text-gray-700">Pembayaran</label>
                                    <select name="pembayaran" id="pembayaran" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        <option value="Open" {{ old('pembayaran', $pengadaan->pembayaran) == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="Close" {{ old('pembayaran', $pengadaan->pembayaran) == 'Close' ? 'selected' : '' }}>Close</option>
                                        <option value="On Progress" {{ old('pembayaran', $pengadaan->pembayaran) == 'On Progress' ? 'selected' : '' }}>On Progress</option>
                                    </select>
                                    @error('pembayaran')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
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

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
@endpush
@endsection 