@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')

    <div id="main-content" class="flex-1 main-content">
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

                    <h1 class="text-xl font-semibold text-gray-800">Tambah Data Laporan KIT 00.00</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Laporan KIT 00.00', 'url' => route('admin.laporan-kit.index')],
                ['name' => 'Tambah Data', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <div class="container mx-auto px-4 sm:px-6 py-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <form action="#" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- DATA PEMERIKSAAN BBM -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold border-b pb-2">DATA PEMERIKSAAN BBM</h3>
                        
                        <!-- Storage Tank -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium mb-4">Storage Tank 1</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CM</label>
                                        <input type="number" name="bbm_storage_tank_1_cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Liter</label>
                                        <input type="number" name="bbm_storage_tank_1_liter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium mb-4">Storage Tank 2</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CM</label>
                                        <input type="number" name="bbm_storage_tank_2_cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Liter</label>
                                        <input type="number" name="bbm_storage_tank_2_liter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Service Tank -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h4 class="font-medium mb-4">Service Tank 1</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Liter</label>
                                        <input type="number" name="bbm_service_tank_1_liter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Persentase</label>
                                        <input type="number" name="bbm_service_tank_1_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium mb-4">Service Tank 2</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Liter</label>
                                        <input type="number" name="bbm_service_tank_2_liter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Persentase</label>
                                        <input type="number" name="bbm_service_tank_2_percentage" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Flowmeter -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h4 class="font-medium mb-4">Flowmeter 1</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Awal</label>
                                        <input type="number" name="flowmeter_1_awal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Akhir</label>
                                        <input type="number" name="flowmeter_1_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium mb-4">Flowmeter 2</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Awal</label>
                                        <input type="number" name="flowmeter_2_awal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Akhir</label>
                                        <input type="number" name="flowmeter_2_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA PEMERIKSAAN KWH -->
                    <div class="space-y-4 pt-8">
                        <h3 class="text-lg font-semibold border-b pb-2">DATA PEMERIKSAAN KWH</h3>
                        
                        <!-- KWH PRODUKSI -->
                        <div class="space-y-6">
                            <h4 class="font-medium">KWH PRODUKSI</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-medium mb-4">Panel 1</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Awal</label>
                                            <input type="number" name="kwh_prod_panel1_awal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Akhir</label>
                                            <input type="number" name="kwh_prod_panel1_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h5 class="font-medium mb-4">Panel 2</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Awal</label>
                                            <input type="number" name="kwh_prod_panel2_awal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Akhir</label>
                                            <input type="number" name="kwh_prod_panel2_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- KWH Pemakaian Sendiri -->
                        <div class="space-y-6 pt-6">
                            <h4 class="font-medium">KWH Pemakaian Sendiri (PS)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h5 class="font-medium mb-4">Panel 1</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Awal</label>
                                            <input type="number" name="kwh_ps_panel1_awal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Akhir</label>
                                            <input type="number" name="kwh_ps_panel1_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <h5 class="font-medium mb-4">Panel 2</h5>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Awal</label>
                                            <input type="number" name="kwh_ps_panel2_awal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Akhir</label>
                                            <input type="number" name="kwh_ps_panel2_akhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DATA PEMERIKSAAN PELUMAS -->
                    <div class="space-y-4 pt-8">
                        <h3 class="text-lg font-semibold border-b pb-2">DATA PEMERIKSAAN PELUMAS</h3>
                        
                        <!-- Storage Tank -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium mb-4">Storage Tank 1</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CM</label>
                                        <input type="number" name="pelumas_storage_tank_1_cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Liter</label>
                                        <input type="number" name="pelumas_storage_tank_1_liter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium mb-4">Storage Tank 2</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CM</label>
                                        <input type="number" name="pelumas_storage_tank_2_cm" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Liter</label>
                                        <input type="number" name="pelumas_storage_tank_2_liter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Drum Pelumas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <h4 class="font-medium mb-4">Area 1</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Liter</label>
                                    <input type="number" name="drum_pelumas_area1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                            
                            <div>
                                <h4 class="font-medium mb-4">Area 2</h4>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Liter</label>
                                    <input type="number" name="drum_pelumas_area2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">Jenis Pelumas</label>
                            <input type="text" name="jenis_pelumas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Pemeriksaan Bahan Kimia -->
                    <div class="space-y-4 pt-8">
                        <h3 class="text-lg font-semibold border-b pb-2">Pemeriksaan Bahan Kimia</h3>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jenis Bahan Kimia</label>
                                <input type="text" name="jenis_bahan_kimia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Stok Awal</label>
                                <input type="number" name="stok_awal_bahan_kimia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Terima Bahan Kimia</label>
                                <input type="number" name="terima_bahan_kimia" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="pt-8 flex justify-end space-x-4">
                        <a href="{{ route('admin.laporan-kit.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .main-content {
        overflow-y: auto;
        height: calc(100vh - 64px);
    }
</style>
@endpush
@endsection 