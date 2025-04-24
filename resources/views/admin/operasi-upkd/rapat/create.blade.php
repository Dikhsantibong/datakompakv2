@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Tambah Data Rapat & Koordinasi</h1>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <form action="{{ route('admin.operasi-upkd.rapat.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Tabs Navigation -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex flex-wrap gap-4" aria-label="Tabs">
                                <button type="button" class="tab-btn border-[#009BB9] text-[#009BB9] whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="pekerjaan-tentatif">
                                    A. Pekerjaan Tentatif
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="operation-management">
                                    B.1 Operation Management
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="efisiensi-management">
                                    B.2 Efisiensi Management
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="program-kerja">
                                    C. Program Kerja
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="monitoring-pengadaan">
                                    D. Monitoring Pengadaan
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="monitoring-aplikasi">
                                    E. Monitoring Aplikasi
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="pengawasan-kontrak">
                                    F. Pengawasan Kontrak
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="laporan">
                                    G. Laporan
                                </button>
                                <button type="button" class="tab-btn text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm" data-tab="rapat">
                                    H. Rapat
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Contents -->
                        <!-- A. Pekerjaan Tentatif -->
                        <div class="tab-content" id="pekerjaan-tentatif">
                            <div class="space-y-6" id="pekerjaan-tentatif-container">
                                <div class="pekerjaan-tentatif-row">
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Uraian</label>
                                            <textarea name="pekerjaan_tentatif[0][uraian]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Detail</label>
                                            <textarea name="pekerjaan_tentatif[0][detail]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">PIC</label>
                                            <input type="text" name="pekerjaan_tentatif[0][pic]" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                            <select name="pekerjaan_tentatif[0][status]" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50">
                                                <option value="">Pilih Status</option>
                                                <option value="open">Open</option>
                                                <option value="closed">Closed</option>
                                                <option value="in_progress">In Progress</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-6 mt-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Eksisting</label>
                                            <textarea name="pekerjaan_tentatif[0][kondisi_eksisting]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Tindak Lanjut</label>
                                            <textarea name="pekerjaan_tentatif[0][tindaklanjut]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Akhir</label>
                                            <textarea name="pekerjaan_tentatif[0][kondisi_akhir]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Goal</label>
                                            <textarea name="pekerjaan_tentatif[0][goal]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                                            <textarea name="pekerjaan_tentatif[0][keterangan]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Row Button -->
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- B.1 Operation Management -->
                        <div class="tab-content hidden" id="operation-management">
                            <div class="space-y-6" id="operation-management-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-operation inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- B.2 Efisiensi Management -->
                        <div class="tab-content hidden" id="efisiensi-management">
                            <div class="space-y-6" id="efisiensi-management-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-efisiensi inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- C. Program Kerja -->
                        <div class="tab-content hidden" id="program-kerja">
                            <div class="space-y-6" id="program-kerja-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-program inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- D. Monitoring Pengadaan -->
                        <div class="tab-content hidden" id="monitoring-pengadaan">
                            <div class="space-y-6" id="monitoring-pengadaan-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-pengadaan inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- E. Monitoring Aplikasi -->
                        <div class="tab-content hidden" id="monitoring-aplikasi">
                            <div class="space-y-6" id="monitoring-aplikasi-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-aplikasi inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- F. Pengawasan Kontrak -->
                        <div class="tab-content hidden" id="pengawasan-kontrak">
                            <div class="space-y-6" id="pengawasan-kontrak-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-kontrak inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- G. Laporan -->
                        <div class="tab-content hidden" id="laporan">
                            <!-- G.1 Laporan Pembangkit -->
                            <h3 class="text-lg font-medium text-gray-900 mb-4">G.1 Laporan Pembangkit</h3>
                            <div class="space-y-6" id="laporan-pembangkit-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-pembangkit inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>

                            <!-- G.2 Laporan Transaksi -->
                            <h3 class="text-lg font-medium text-gray-900 mt-8 mb-4">G.2 Laporan Transaksi Energi</h3>
                            <div class="space-y-6" id="laporan-transaksi-container">
                                <!-- Similar structure as pekerjaan-tentatif -->
                            </div>
                            <div class="flex justify-end mt-4">
                                <button type="button" class="add-row-btn-transaksi inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                    <i class="fas fa-plus mr-2"></i> Tambah Baris
                                </button>
                            </div>
                        </div>

                        <!-- H. Rapat -->
                        <div class="tab-content hidden" id="rapat">
                            <div class="space-y-8">
                                @foreach(['internal_ron' => 'INTERNAL RON', 'internal_upkd' => 'INTERNAL UPKD', 'eksternal_np1' => 'EKSTERNAL NP 1', 'eksternal_np2' => 'EKSTERNAL NP 2'] as $key => $label)
                                <div class="border rounded-lg p-6">
                                    <h4 class="text-lg font-medium text-gray-900 mb-4">{{ $label }}</h4>
                                    <div class="grid grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Uraian</label>
                                            <textarea name="rapat[{{ $key }}][uraian]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Jadwal</label>
                                            <input type="datetime-local" name="rapat[{{ $key }}][jadwal]" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50">
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Mode Rapat</label>
                                            <select name="rapat[{{ $key }}][online_offline]" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50">
                                                <option value="">Pilih Mode</option>
                                                <option value="online">Online</option>
                                                <option value="offline">Offline</option>
                                                <option value="hybrid">Hybrid</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Resume</label>
                                            <textarea name="rapat[{{ $key }}][resume]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Notulen</label>
                                            <textarea name="rapat[{{ $key }}][notulen]" rows="4" 
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 resize-none"></textarea>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Eviden</label>
                                            <input type="file" name="rapat[{{ $key }}][eviden]" 
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#009BB9] file:text-white hover:file:bg-[#009BB9]/80">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end mt-6 pt-6 border-t border-gray-200">
                            <a href="{{ route('admin.operasi-upkd.rapat.index') }}" 
                               class="mr-3 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                <i class="fas fa-times mr-2"></i> Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                                <i class="fas fa-save mr-2"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab Switching Logic
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.dataset.tab;
            
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('border-[#009BB9]', 'text-[#009BB9]');
                btn.classList.add('text-gray-500', 'border-transparent');
            });
            button.classList.remove('text-gray-500', 'border-transparent');
            button.classList.add('border-[#009BB9]', 'text-[#009BB9]');

            // Show active tab content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(tabId).classList.remove('hidden');
        });
    });

    // Dynamic Row Addition Functions
    function createAddRowHandler(containerId, prefix) {
        const container = document.getElementById(containerId);
        if (!container) return;
        
        let rowIndex = 0;
        const addRowBtn = document.querySelector(`.add-row-btn-${prefix}`);
        if (!addRowBtn) return;

        addRowBtn.addEventListener('click', function() {
            rowIndex++;
            const template = container.querySelector('div').cloneNode(true);
            
            // Update all name attributes with new index
            template.querySelectorAll('[name]').forEach(input => {
                input.name = input.name.replace(/\[\d+\]/, `[${rowIndex}]`);
                if (input.type !== 'file') {
                    input.value = '';
                }
            });

            container.appendChild(template);
        });
    }

    // Initialize row handlers for each section
    createAddRowHandler('pekerjaan-tentatif-container', 'tentatif');
    createAddRowHandler('operation-management-container', 'operation');
    createAddRowHandler('efisiensi-management-container', 'efisiensi');
    createAddRowHandler('program-kerja-container', 'program');
    createAddRowHandler('monitoring-pengadaan-container', 'pengadaan');
    createAddRowHandler('monitoring-aplikasi-container', 'aplikasi');
    createAddRowHandler('pengawasan-kontrak-container', 'kontrak');
    createAddRowHandler('laporan-pembangkit-container', 'pembangkit');
    createAddRowHandler('laporan-transaksi-container', 'transaksi');
});
</script>
@endpush

@endsection 