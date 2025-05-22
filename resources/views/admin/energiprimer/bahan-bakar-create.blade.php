@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto">
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

                    <h1 class="text-xl font-semibold text-gray-800">Tambah Data Bahan Bakar</h1>
                </div>

                <!-- Profile Dropdown -->
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
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <form action="{{ route('admin.energiprimer.bahan-bakar.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('tanggal') border-red-500 @enderror"
                                           value="{{ old('tanggal') }}">
                                    @error('tanggal')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                                    <select name="unit_id" required
                                            class="p-2 mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('unit_id') border-red-500 @enderror">
                                        <option value="">Pilih Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('unit_id')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis BBM</label>
                                    <select name="jenis_bbm" required
                                            class=" p-2 mt-1 block w-full h-10 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('jenis_bbm') border-red-500 @enderror">
                                        <option value="">Pilih Jenis BBM</option>
                                        <option value="B40" {{ old('jenis_bbm') == 'B40' ? 'selected' : '' }}>B40</option>
                                        <option value="B35" {{ old('jenis_bbm') == 'B35' ? 'selected' : '' }}>B35</option>
                                        <option value="HSD" {{ old('jenis_bbm') == 'HSD' ? 'selected' : '' }}>HSD</option>
                                        <option value="MFO" {{ old('jenis_bbm') == 'MFO' ? 'selected' : '' }}>MFO</option>
                                    </select>
                                    @error('jenis_bbm')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div id="saldo-awal-container">
                                    <label class="block text-sm font-medium text-gray-700">Saldo Awal</label>
                                    <input type="number" step="0.01" name="saldo_awal" id="saldo_awal"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('saldo_awal') border-red-500 @enderror"
                                           value="{{ old('saldo_awal') }}">
                                    <p class="mt-1 text-sm text-gray-500">
                                        *Saldo awal hanya perlu diisi untuk data pertama
                                    </p>
                                    @error('saldo_awal')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Penerimaan</label>
                                    <input type="number" step="0.01" name="penerimaan" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('penerimaan') border-red-500 @enderror"
                                           value="{{ old('penerimaan') }}">
                                    @error('penerimaan')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pemakaian</label>
                                    <input type="number" step="0.01" name="pemakaian" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('pemakaian') border-red-500 @enderror"
                                           value="{{ old('pemakaian') }}">
                                    @error('pemakaian')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">HOP (Hari Operasi Persediaan)</label>
                                    <input type="number" step="0.01" name="hop" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('hop') border-red-500 @enderror"
                                           value="{{ old('hop') }}">
                                    @error('hop')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Catatan Transaksi</label>
                                    <textarea name="catatan_transaksi" rows="3"
                                              class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('catatan_transaksi') border-red-500 @enderror"
                                              placeholder="Masukkan catatan transaksi...">{{ old('catatan_transaksi') }}</textarea>
                                    @error('catatan_transaksi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Upload Dokumen</label>
                                    <div class="mt-1 flex items-center">
                                        <input type="file" name="document" 
                                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('document') border-red-500 @enderror"
                                               accept=".pdf,.doc,.docx,.xls,.xlsx">
                                    </div>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Format yang diizinkan: PDF, DOC, DOCX, XLS, XLSX (Maks. 2MB)
                                    </p>
                                    @error('document')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <a href="{{ route('admin.energiprimer.bahan-bakar') }}"
                                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">
                                    Batal
                                </a>
                                <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('unit_id');
    const jenisBbmSelect = document.getElementById('jenis_bbm');
    const tanggalInput = document.getElementById('tanggal');
    const saldoAwalContainer = document.getElementById('saldo-awal-container');
    const saldoAwalInput = document.getElementById('saldo_awal');

    async function checkPreviousBalance() {
        if (!unitSelect.value || !jenisBbmSelect.value || !tanggalInput.value) return;

        try {
            const response = await fetch(`/api/check-previous-balance?unit_id=${unitSelect.value}&jenis_bbm=${jenisBbmSelect.value}&tanggal=${tanggalInput.value}`);
            const data = await response.json();

            if (data.has_previous) {
                saldoAwalInput.value = data.previous_balance;
                saldoAwalInput.readOnly = true;
                saldoAwalContainer.classList.add('opacity-50');
            } else {
                saldoAwalInput.value = '';
                saldoAwalInput.readOnly = false;
                saldoAwalContainer.classList.remove('opacity-50');
            }
        } catch (error) {
            console.error('Error checking previous balance:', error);
        }
    }

    unitSelect.addEventListener('change', checkPreviousBalance);
    jenisBbmSelect.addEventListener('change', checkPreviousBalance);
    tanggalInput.addEventListener('change', checkPreviousBalance);
});
</script>
@endpush
@endsection 