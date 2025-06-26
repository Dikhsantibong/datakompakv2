@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Edit Data Rapat</h1>
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
                ['name' => 'Rapat & Link Koordinasi RON', 'url' => route('admin.operasi-upkd.rapat.index')],
                ['name' => 'Edit Data', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Form Card -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                    <div class="p-6">
                        <form action="{{ route('admin.operasi-upkd.rapat.update', $rapat->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Pekerjaan -->
                            <div class="mb-6">
                                <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                                <textarea id="pekerjaan" name="pekerjaan" rows="3" class="w-full p-2 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>{{ old('pekerjaan', $rapat->pekerjaan) }}</textarea>
                            </div>

                            <!-- PIC -->
                            <div class="mb-6">
                                <label for="pic" class="block text-sm font-medium text-gray-700 mb-2">PIC</label>
                                <select id="pic" name="pic" class="w-full p-2 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                                    <option value="">Pilih PIC</option>
                                    <option value="Asman Operasi" {{ old('pic', $rapat->pic) === 'Asman Operasi' ? 'selected' : '' }}>Asman Operasi</option>
                                    <option value="TL RON" {{ old('pic', $rapat->pic) === 'TL RON' ? 'selected' : '' }}>TL RON</option>
                                    <option value="ROHMAT" {{ old('pic', $rapat->pic) === 'ROHMAT' ? 'selected' : '' }}>ROHMAT</option>
                                    <option value="IMAM" {{ old('pic', $rapat->pic) === 'IMAM' ? 'selected' : '' }}>IMAM</option>
                                    <option value="KASMAN" {{ old('pic', $rapat->pic) === 'KASMAN' ? 'selected' : '' }}>KASMAN</option>
                                    <option value="AMINAH" {{ old('pic', $rapat->pic) === 'AMINAH' ? 'selected' : '' }}>AMINAH</option>
                                </select>
                            </div>

                            <!-- Deadline -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label for="deadline_start" class="block text-sm font-medium text-gray-700 mb-2">Deadline Start</label>
                                    <input type="date" id="deadline_start" name="deadline_start" class="w-full p-2 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required value="{{ old('deadline_start', $rapat->deadline_start->format('Y-m-d')) }}">
                                </div>
                                <div>
                                    <label for="deadline_finish" class="block text-sm font-medium text-gray-700 mb-2">Deadline Finish</label>
                                    <input type="date" id="deadline_finish" name="deadline_finish" class="w-full p-2 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required value="{{ old('deadline_finish', $rapat->deadline_finish->format('Y-m-d')) }}">
                                </div>
                            </div>

                            <!-- Kondisi -->
                            <div class="mb-6">
                                <label for="kondisi" class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                                <textarea id="kondisi" name="kondisi" rows="3" class="w-full p-2 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>{{ old('kondisi', $rapat->kondisi) }}</textarea>
                            </div>

                            <!-- Status -->
                            <div class="mb-6">
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select id="status" name="status" class="w-full p-2 rounded-md shadow-sm border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200" required>
                                    <option value="">Pilih Status</option>
                                    <option value="open" {{ old('status', $rapat->status) === 'open' ? 'selected' : '' }}>Open</option>
                                    <option value="on progress" {{ old('status', $rapat->status) === 'on progress' ? 'selected' : '' }}>On Progress</option>
                                    <option value="closed" {{ old('status', $rapat->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex justify-end space-x-3">
                                <a href="{{ route('admin.operasi-upkd.rapat.index') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    Batal
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
