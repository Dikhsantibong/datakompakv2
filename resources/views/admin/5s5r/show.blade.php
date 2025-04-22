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

                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Detail 5S5R</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ date('Y-m-d', strtotime($pemeriksaan->first()->created_at)) }}</span>
                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                        ID: {{ $pemeriksaan->first()->id }}
                    </span>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[
                ['name' => '5S5R', 'url' => route('admin.5s5r.index')],
                ['name' => 'Daftar', 'url' => route('admin.5s5r.list')],
                ['name' => 'Detail', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="space-y-6">
                    <!-- 5S5R Details -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-clipboard-list mr-2 text-gray-400"></i>
                                Detail Pemeriksaan 5S5R
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Awal</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area Kerja</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Area Produksi</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tindakan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Akhir</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eviden</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($pemeriksaan as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $item->kategori }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $item->kondisi_awal }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->pic }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->area_kerja }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item->area_produksi }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                <div class="flex flex-wrap gap-1 justify-center">
                                                    @if($item->membersihkan)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Membersihkan
                                                        </span>
                                                    @endif
                                                    @if($item->merapikan)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Merapikan
                                                        </span>
                                                    @endif
                                                    @if($item->membuang_sampah)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Membuang Sampah
                                                        </span>
                                                    @endif
                                                    @if($item->mengecat)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Mengecat
                                                        </span>
                                                    @endif
                                                    @if($item->lainnya)
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            Lainnya
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $item->kondisi_akhir }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @if($item->eviden)
                                                    <a href="{{ Storage::url($item->eviden) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                        Lihat Eviden
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Program Kerja 5R -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-medium text-gray-900">
                                <i class="fas fa-tasks mr-2 text-gray-400"></i>
                                Program Kerja 5R
                            </h2>
                        </div>
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program Kerja</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Goal</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Awal</th>
                                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Akhir</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eviden</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($programKerja as $program)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $program->program_kerja }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $program->goal }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $program->kondisi_awal }}
                                            </td>
                                            <td class="px-6 py-4 text-center text-sm text-gray-500">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    {{ $program->progress }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $program->kondisi_akhir }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $program->catatan }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                @if($program->eviden)
                                                    <a href="{{ Storage::url($program->eviden) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                        Lihat Eviden
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.5s5r.list') }}" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali
                        </a>
                        <button type="button"
                            onclick="window.print()"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
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