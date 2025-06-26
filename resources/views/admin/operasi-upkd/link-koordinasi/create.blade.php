@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <h1 class="text-xl font-semibold text-gray-800">Tambah Link Koordinasi RON</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Link Koordinasi RON', 'url' => route('admin.operasi-upkd.link-koordinasi.index')],
                ['name' => 'Tambah Link', 'url' => null],
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-6">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <form action="{{ route('admin.operasi-upkd.link-koordinasi.store') }}" method="POST" class="p-6">
                        @csrf

                        <!-- Uraian -->
                        <div class="mb-6">
                            <label for="uraian" class="block text-sm font-medium text-gray-700 mb-2">Uraian</label>
                            <textarea id="uraian" name="uraian" rows="3"
                                class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Masukkan uraian">{{ old('uraian') }}</textarea>
                            @error('uraian')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Link -->
                        <div class="mb-6">
                            <label for="link" class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                            <input type="url" id="link" name="link"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="https://example.com" value="{{ old('link') }}">
                            @error('link')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Monitoring -->
                        <div class="mb-6">
                            <label for="monitoring" class="block text-sm font-medium text-gray-700 mb-2">Monitoring</label>
                            <select id="monitoring" name="monitoring"
                                class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Monitoring</option>
                                <option value="harian" {{ old('monitoring') == 'harian' ? 'selected' : '' }}>Harian</option>
                                <option value="mingguan" {{ old('monitoring') == 'mingguan' ? 'selected' : '' }}>Mingguan</option>
                                <option value="bulanan" {{ old('monitoring') == 'bulanan' ? 'selected' : '' }}>Bulanan</option>
                            </select>
                            @error('monitoring')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Koordinasi -->
                        <div class="mb-6">
                            <label for="koordinasi" class="block text-sm font-medium text-gray-700 mb-2">Koordinasi</label>
                            <select id="koordinasi" name="koordinasi"
                                class="block p-2 w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Pilih Koordinasi</option>
                                <option value="eng" {{ old('koordinasi') == 'eng' ? 'selected' : '' }}>ENG</option>
                                <option value="bs" {{ old('koordinasi') == 'bs' ? 'selected' : '' }}>BS</option>
                                <option value="ops" {{ old('koordinasi') == 'ops' ? 'selected' : '' }}>OPS</option>
                            </select>
                            @error('koordinasi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end gap-x-4">
                            <a href="{{ route('admin.operasi-upkd.link-koordinasi.index') }}"
                               class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection
