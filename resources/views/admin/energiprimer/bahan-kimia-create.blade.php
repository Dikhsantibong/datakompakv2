@extends('layouts.app')

@section('content')
<div class="flex h-screen">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 overflow-x-hidden overflow-y-auto">
        <header class="bg-white shadow-sm sticky top-0 z-10">
            <div class="flex justify-between items-center px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <!-- ... mobile menu toggle ... -->
                    <h1 class="text-xl font-semibold text-gray-800">Tambah Data Bahan Kimia</h1>
                </div>
                <!-- ... profile dropdown ... -->
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
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                           value="{{ old('pemakaian') }}"
                                           placeholder="0.00">
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