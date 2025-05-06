@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Export Meeting Shift</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Meeting dan Mutasi Shift Operator', 'url' => route('admin.meeting-shift.list')],
                ['name' => 'Export', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="max-w-7xl mx-auto">
                    <!-- Export Options Card -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-6">Export Data Meeting Shift</h2>
                            
                            <form action="{{ route('admin.meeting-shift.download-excel', $meetingShift) }}" method="GET" class="space-y-6">
                                <!-- Sheet Selection -->
                                <div>
                                    <label class="text-sm font-medium text-gray-700 block mb-2">Pilih Sheet yang akan di-export</label>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="info" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">Informasi Dasar</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="machine_statuses" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">Status Mesin</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="auxiliary_equipment" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">Alat Bantu</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="resources" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">Resources</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="k3l" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">K3L</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="notes" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">Catatan</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="sheets[]" value="attendance" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300 rounded" checked>
                                            <label class="ml-2 text-sm text-gray-700">Absensi</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Preview Section -->
                                <div class="mt-6">
                                    <h3 class="text-sm font-medium text-gray-700 mb-2">Preview Data</h3>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Tanggal</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $meetingShift->tanggal->format('d F Y') }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Shift</dt>
                                                <dd class="mt-1 text-sm text-gray-900">Shift {{ $meetingShift->current_shift }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Total Mesin</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $meetingShift->machineStatuses->count() }} Mesin</dd>
                                            </div>
                                            <div>
                                                <dt class="text-sm font-medium text-gray-500">Total Personel</dt>
                                                <dd class="mt-1 text-sm text-gray-900">{{ $meetingShift->attendances->count() }} Orang</dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>

                                <!-- Export Format -->
                                <div class="mt-6">
                                    <label class="text-sm font-medium text-gray-700 block mb-2">Format Export</label>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <input type="radio" name="format" value="xlsx" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300" checked>
                                            <label class="ml-2 text-sm text-gray-700">Excel (.xlsx)</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" name="format" value="csv" class="h-4 w-4 text-[#009BB9] focus:ring-[#009BB9] border-gray-300">
                                            <label class="ml-2 text-sm text-gray-700">CSV (.csv)</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3 mt-6">
                                    <a href="{{ route('admin.meeting-shift.list') }}" 
                                       class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                        Batal
                                    </a>
                                    <button type="submit"
                                            class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#009BB9] hover:bg-[#009BB9]/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                        <i class="fas fa-file-export mr-2"></i>
                                        Export Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Validate that at least one sheet is selected
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const checkedSheets = document.querySelectorAll('input[name="sheets[]"]:checked');
        if (checkedSheets.length === 0) {
            e.preventDefault();
            alert('Pilih minimal satu sheet untuk di-export');
        }
    });
});
</script>
@endpush

@endsection 