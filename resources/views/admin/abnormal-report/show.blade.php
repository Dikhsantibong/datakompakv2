@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Detail Laporan Abnormal/Gangguan</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Laporan Abnormal/Gangguan', 'url' => route('admin.abnormal-report.index')],
                ['name' => 'Daftar', 'url' => route('admin.abnormal-report.list')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Kronologi Kejadian -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Kronologi Kejadian</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Pukul (WIB)</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Uraian Kejadian</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Visual Parameter</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 0; $i < 3; $i++)
                                        <tr>
                                            <td class="border px-4 py-2">{{ str_pad($i * 2, 2, '0', STR_PAD_LEFT) }}:00</td>
                                            <td class="border px-4 py-2">Uraian kejadian {{ $i + 1 }}</td>
                                            <td class="border px-4 py-2">Parameter {{ $i + 1 }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Abnormal
                                                </span>
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Mesin/Peralatan Terdampak -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Mesin/Peralatan Terdampak</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Nama Mesin/Peralatan</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 1; $i <= 3; $i++)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $i }}</td>
                                            <td class="border px-4 py-2">Mesin {{ $i }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $i % 2 == 0 ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                    {{ $i % 2 == 0 ? 'Rusak' : 'Abnormal' }}
                                                </span>
                                            </td>
                                            <td class="border px-4 py-2">Keterangan mesin {{ $i }}</td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Rekomendasi -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Rekomendasi</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-500 mr-3">1</span>
                                    <span class="text-gray-700">Ganti material/peralatan terdampak</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-500 mr-3">2</span>
                                    <span class="text-gray-700">Pemeriksaan kembali saat har rutin</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-500 mr-3">3</span>
                                    <span class="text-gray-700">Pemeliharaan dilaksanakan offline mesin</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.abnormal-report.list') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
                            <i class="fas fa-print mr-2"></i>
                            Cetak
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection 