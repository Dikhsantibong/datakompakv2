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

                            <div class="space-y-6">
                                <div>
                                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" value="{{ $flm->tanggal }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="mesin" class="block text-sm font-medium text-gray-700">Mesin/Peralatan</label>
                                    <input type="text" name="mesin" id="mesin" value="{{ $flm->mesin }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="sistem" class="block text-sm font-medium text-gray-700">Sistem Pembangkit</label>
                                    <input type="text" name="sistem" id="sistem" value="{{ $flm->sistem }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="masalah" class="block text-sm font-medium text-gray-700">Masalah</label>
                                    <textarea name="masalah" id="masalah" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $flm->masalah }}</textarea>
                                </div>

                                <div>
                                    <label for="kondisi_awal" class="block text-sm font-medium text-gray-700">Kondisi Awal</label>
                                    <textarea name="kondisi_awal" id="kondisi_awal" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $flm->kondisi_awal }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tindakan</label>
                                    <div class="mt-2 space-y-2">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tindakan[]" value="bersihkan" id="tindakan_bersihkan" 
                                                {{ $flm->tindakan_bersihkan ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="tindakan_bersihkan" class="ml-2 text-sm text-gray-700">Bersihkan</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tindakan[]" value="lumasi" id="tindakan_lumasi"
                                                {{ $flm->tindakan_lumasi ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="tindakan_lumasi" class="ml-2 text-sm text-gray-700">Lumasi</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tindakan[]" value="kencangkan" id="tindakan_kencangkan"
                                                {{ $flm->tindakan_kencangkan ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="tindakan_kencangkan" class="ml-2 text-sm text-gray-700">Kencangkan</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tindakan[]" value="perbaikan_koneksi" id="tindakan_perbaikan_koneksi"
                                                {{ $flm->tindakan_perbaikan_koneksi ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="tindakan_perbaikan_koneksi" class="ml-2 text-sm text-gray-700">Perbaikan Koneksi</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="tindakan[]" value="lainnya" id="tindakan_lainnya"
                                                {{ $flm->tindakan_lainnya ? 'checked' : '' }}
                                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                            <label for="tindakan_lainnya" class="ml-2 text-sm text-gray-700">Lainnya</label>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label for="kondisi_akhir" class="block text-sm font-medium text-gray-700">Kondisi Akhir</label>
                                    <textarea name="kondisi_akhir" id="kondisi_akhir" rows="3" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $flm->kondisi_akhir }}</textarea>
                                </div>

                                <div>
                                    <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan</label>
                                    <textarea name="catatan" id="catatan" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $flm->catatan }}</textarea>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Eviden Sebelum</label>
                                        @if($flm->eviden_sebelum)
                                            <div class="mt-2">
                                                <img src="{{ asset($flm->eviden_sebelum) }}" alt="Eviden Sebelum" class="max-h-40">
                                            </div>
                                        @endif
                                        <input type="file" name="eviden_sebelum" accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Eviden Sesudah</label>
                                        @if($flm->eviden_sesudah)
                                            <div class="mt-2">
                                                <img src="{{ asset($flm->eviden_sesudah) }}" alt="Eviden Sesudah" class="max-h-40">
                                            </div>
                                        @endif
                                        <input type="file" name="eviden_sesudah" accept="image/*"
                                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    </div>
                                </div>

                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('admin.flm.list') }}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Batal
                                    </a>
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@endsection 