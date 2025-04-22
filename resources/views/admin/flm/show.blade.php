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

                    <h1 class="text-xl font-semibold text-gray-900">Detail Pemeriksaan FLM</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ $flmDetail->tanggal }}</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        Shift {{ $flmDetail->shift }}
                    </span>
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
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Informasi Umum -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-info-circle mr-2 text-gray-400"></i>
                                Informasi Umum
                            </h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Operator</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->operator }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->created_at }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Mesin/Peralatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->mesin }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sistem Pembangkit</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->sistem }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Detail Pemeriksaan -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-clipboard-check mr-2 text-gray-400"></i>
                                Detail Pemeriksaan
                            </h2>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Masalah yang Ditemukan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->masalah }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kondisi Awal</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->kondisi_awal }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tindakan yang Dilakukan</dt>
                                    <dd class="mt-1">
                                        <ul class="grid grid-cols-2 gap-2">
                                            <li class="flex items-center text-sm">
                                                <span class="inline-flex items-center justify-center size-5 mr-2 {{ $flmDetail->tindakan_bersihkan ? 'text-green-500' : 'text-gray-300' }}">
                                                    <i class="fas {{ $flmDetail->tindakan_bersihkan ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                </span>
                                                Bersihkan
                                            </li>
                                            <li class="flex items-center text-sm">
                                                <span class="inline-flex items-center justify-center size-5 mr-2 {{ $flmDetail->tindakan_lumasi ? 'text-green-500' : 'text-gray-300' }}">
                                                    <i class="fas {{ $flmDetail->tindakan_lumasi ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                </span>
                                                Lumasi
                                            </li>
                                            <li class="flex items-center text-sm">
                                                <span class="inline-flex items-center justify-center size-5 mr-2 {{ $flmDetail->tindakan_kencangkan ? 'text-green-500' : 'text-gray-300' }}">
                                                    <i class="fas {{ $flmDetail->tindakan_kencangkan ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                </span>
                                                Kencangkan
                                            </li>
                                            <li class="flex items-center text-sm">
                                                <span class="inline-flex items-center justify-center size-5 mr-2 {{ $flmDetail->tindakan_setting ? 'text-green-500' : 'text-gray-300' }}">
                                                    <i class="fas {{ $flmDetail->tindakan_setting ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                </span>
                                                Setting
                                            </li>
                                            <li class="flex items-center text-sm">
                                                <span class="inline-flex items-center justify-center size-5 mr-2 {{ $flmDetail->tindakan_ganti ? 'text-green-500' : 'text-gray-300' }}">
                                                    <i class="fas {{ $flmDetail->tindakan_ganti ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                                </span>
                                                Ganti
                                            </li>
                                        </ul>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kondisi Akhir</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->kondisi_akhir }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Catatan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $flmDetail->catatan }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Eviden -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-images mr-2 text-gray-400"></i>
                                Eviden
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Foto Sebelum</h3>
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ asset($flmDetail->eviden_sebelum) }}" 
                                             alt="Foto Sebelum"
                                             class="w-full h-48 object-cover">
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-500 mb-2">Foto Sesudah</h3>
                                    <div class="border rounded-lg overflow-hidden">
                                        <img src="{{ asset($flmDetail->eviden_sesudah) }}" 
                                             alt="Foto Sesudah"
                                             class="w-full h-48 object-cover">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('admin.flm.list') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <a href="#" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <button type="button"
                        onclick="printFLM()"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-print mr-2"></i>
                        Cetak
                    </button>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function printFLM() {
    window.print();
}
</script>
@endpush

@endsection 