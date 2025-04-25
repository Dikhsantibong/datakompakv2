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
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#0A749B] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Buka menu utama</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-900">Tambah Data Rapat & Link Koordinasi RON</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Rapat & Link Koordinasi RON', 'url' => route('admin.operasi-upkd.rapat.index')],
                ['name' => 'Tambah Data', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <form action="{{ route('admin.operasi-upkd.rapat.store') }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                    @csrf

                    <!-- Section Selector -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="section" class="block text-sm font-medium text-gray-700">Pilih Bagian</label>
                                <select id="section" name="section" class=" p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Bagian</option>
                                    <option value="A">A. PEKERJAAN TENTATIF</option>
                                    <option value="B1">B1. OPERATION MANAGEMENT</option>
                                    <option value="B2">B2. EFISIENSI MANAGEMENT</option>
                                    <option value="C">C. PROGRAM KERJA</option>
                                    <option value="D">D. MONITORING PENGADAAN BARANG DAN JASA</option>
                                    <option value="E">E. MONITORING PENGAWASAN APLIKASI</option>
                                    <option value="F">F. PENGAWASAN KONTRAK</option>
                                    <option value="G1">G1. LAPORAN PEMBANGKIT</option>
                                    <option value="G2">G2. LAPORAN TRANSAKSI ENERGI</option>
                                    <option value="H">H. RAPAT</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="uraian" class="block text-sm font-medium text-gray-700">Uraian</label>
                                <input type="text" name="uraian" id="uraian" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="detail" class="block text-sm font-medium text-gray-700">Detail</label>
                                <input type="text" name="detail" id="detail" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="pic" class="block text-sm font-medium text-gray-700">PIC</label>
                                <select name="pic" id="pic" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">-- Pilih PIC --</option>
                                    <option value="asman operasi">Asman Operasi</option>
                                    <option value="TL RON">TL RON</option>
                                    <option value="ROHMAT">ROHMAT</option>
                                    <option value="IMAM">IMAM</option>
                                    <option value="KASMAN">KASMAN</option>
                                    <option value="AMINAH">AMINAH</option>
                                </select>
                            </div>
                            

                            <div>
                                <label for="kondisi_eksisting" class="block text-sm font-medium text-gray-700">Kondisi Eksisting</label>
                                <textarea name="kondisi_eksisting" id="kondisi_eksisting" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <div>
                                <label for="tindak_lanjut" class="block text-sm font-medium text-gray-700">Tindak Lanjut</label>
                                <textarea name="tindak_lanjut" id="tindak_lanjut" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <div>
                                <label for="kondisi_akhir" class="block text-sm font-medium text-gray-700">Kondisi Akhir</label>
                                <textarea name="kondisi_akhir" id="kondisi_akhir" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>

                            <div>
                                <label for="goal" class="block text-sm font-medium text-gray-700">Goal</label>
                                <input type="text" name="goal" id="goal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih Status</option>
                                    <option value="completed">Completed</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="pending">pending</option>
                                    
                                </select>
                            </div>

                            <div>
                                <label for="keterangan" class="block text-sm font-medium text-gray-700">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                            </div>
                        </div>

                        <!-- Additional Fields for Section H (Rapat) -->
                        <div id="rapatFields" class="hidden mt-6 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Tambahan Rapat</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="jadwal" class="block text-sm font-medium text-gray-700">Jadwal</label>
                                    <input type="datetime-local" name="jadwal" id="jadwal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>

                                <div>
                                    <label for="mode" class="block text-sm font-medium text-gray-700">Mode</label>
                                    <select name="mode" id="mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Mode</option>
                                        <option value="online">Online</option>
                                        <option value="offline">Offline</option>
                                        <option value="hybrid">Hybrid</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="resume" class="block text-sm font-medium text-gray-700">Resume</label>
                                    <textarea name="resume" id="resume" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>

                                <div>
                                    <label for="notulen" class="block text-sm font-medium text-gray-700">Notulen</label>
                                    <input type="file" name="notulen" id="notulen" class="mt-1 block w-full">
                                </div>

                                <div>
                                    <label for="eviden" class="block text-sm font-medium text-gray-700">Eviden</label>
                                    <input type="file" name="eviden" id="eviden" class="mt-1 block w-full">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.operasi-upkd.rapat.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sectionSelect = document.getElementById('section');
    const rapatFields = document.getElementById('rapatFields');

    sectionSelect.addEventListener('change', function() {
        if (this.value === 'H') {
            rapatFields.classList.remove('hidden');
        } else {
            rapatFields.classList.add('hidden');
        }
    });
});
</script>
@endpush

@endsection 