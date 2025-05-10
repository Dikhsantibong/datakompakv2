@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
                    <h1 class="text-xl font-semibold text-gray-900">Detail Patrol Check KIT</h1>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => 'Patrol Check KIT', 'url' => route('admin.patrol-check.index')],
                ['name' => 'Daftar', 'url' => route('admin.patrol-check.list')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- Report Info -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Informasi Patrol Check</h2>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat Oleh</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $patrol->creator->name ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $patrol->created_at->format('d/m/Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Equipment Conditions -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Kondisi Umum Peralatan Bantu</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">NO</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">SISTEM</th>
                                            <th colspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi Umum</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2"></th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Normal</th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Abnormal</th>
                                            <th class="border px-4 py-2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach(($patrol->condition_systems ?? []) as $index => $condition)
                                        <tr>
                                            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="border px-4 py-2">{{ $condition['system'] ?? '' }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(($condition['condition'] ?? '') === 'normal')
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(($condition['condition'] ?? '') === 'abnormal')
                                                    <i class="fas fa-check text-red-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">{{ $condition['notes'] ?? '' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Abnormal Equipment Data -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">Data Kondisi Alat Bantu</h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">NO</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">ALAT BANTU</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">Kondisi Awal</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border" colspan="3">Tindak Lanjut</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">Kondisi Akhir</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border" rowspan="2">Keterangan</th>
                                        </tr>
                                        <tr>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">FLM</th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">SR</th>
                                            <th class="border px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase bg-gray-50">Lainnya</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($patrol->abnormal_equipments ?? []) as $index => $abnormal)
                                        <tr>
                                            <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                            <td class="border px-4 py-2">{{ $abnormal['equipment'] ?? '' }}</td>
                                            <td class="border px-4 py-2">{{ $abnormal['condition'] ?? '' }}</td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(!empty($abnormal['flm']))
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(!empty($abnormal['sr']))
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                @if(!empty($abnormal['other']))
                                                    <i class="fas fa-check text-green-500"></i>
                                                @endif
                                            </td>
                                            <td class="border px-4 py-2">
                                                {{ $patrol->condition_after[$index]['condition'] ?? '' }}
                                            </td>
                                            <td class="border px-4 py-2">
                                                {{ $patrol->condition_after[$index]['notes'] ?? '' }}
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="border px-4 py-2 text-center text-gray-500">
                                                Tidak ada data kondisi alat bantu
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.patrol-check.list') }}" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="button" 
                                onclick="window.print()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#009BB9] hover:bg-[#009BB9]/80">
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