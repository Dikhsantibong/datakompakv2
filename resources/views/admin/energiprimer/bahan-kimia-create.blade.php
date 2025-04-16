@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto">
        <header class="bg-white shadow-sm sticky top-0">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <!-- Mobile Menu Toggle -->
                    <button id="mobile-menu-toggle"
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#009BB9] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <!--  Menu Toggle Sidebar-->
                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true" data-slot="icon">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-800">Tambah Data Bahan Kimia</h1>
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


        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md">
                    <div class="p-6">
                        <form action="{{ route('admin.energiprimer.bahan-kimia.store') }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" name="tanggal" id="tanggal" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('tanggal') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Unit</label>
                                    <select name="unit_id" id="unit_id" required
                                            class="mt-1 block w-full rounded-md px-2 border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Pilih Unit</option>
                                        @foreach($units as $unit)
                                            <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Bahan</label>
                                    <input type="text" name="jenis_bahan" id="jenis_bahan" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('jenis_bahan') }}"
                                           placeholder="Masukkan jenis bahan kimia">
                                </div>

                                <div id="saldo-awal-container">
                                    <label class="block text-sm font-medium text-gray-700">Saldo Awal</label>
                                    <input type="number" step="0.01" name="saldo_awal" id="saldo_awal"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('saldo_awal') }}"
                                           placeholder="0.00">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Penerimaan</label>
                                    <input type="number" step="0.01" name="penerimaan" required
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('penerimaan') }}"
                                           placeholder="0.00">
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

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Catatan Transaksi</label>
                                    <textarea name="catatan_transaksi" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('catatan_transaksi') border-red-500 @enderror"
                                              placeholder="Masukkan catatan transaksi...">{{ old('catatan_transaksi') }}</textarea>
                                    @error('catatan_transaksi')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end gap-3">
                                <a href="{{ route('admin.energiprimer.bahan-kimia') }}"
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
    const jenisBahanInput = document.getElementById('jenis_bahan');
    const tanggalInput = document.getElementById('tanggal');
    const saldoAwalContainer = document.getElementById('saldo-awal-container');
    const saldoAwalInput = document.getElementById('saldo_awal');

    async function checkPreviousBalance() {
        if (!unitSelect.value || !jenisBahanInput.value || !tanggalInput.value) return;

        try {
            const response = await fetch(`/api/check-previous-balance-kimia?unit_id=${unitSelect.value}&jenis_bahan=${jenisBahanInput.value}&tanggal=${tanggalInput.value}`);
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
    jenisBahanInput.addEventListener('change', checkPreviousBalance);
    tanggalInput.addEventListener('change', checkPreviousBalance);
});
</script>
@endpush
@endsection 