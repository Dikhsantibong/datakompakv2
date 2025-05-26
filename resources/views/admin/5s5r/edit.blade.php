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

                    <h1 class="text-xl font-semibold text-gray-900 ml-2">Edit 5S5R</h1>
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
                ['name' => 'Edit', 'url' => null]
            ]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <form action="{{ route('admin.5s5r.update', $pemeriksaan->first()->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- 5S5R Details -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Tabel Pemeriksaan 5S5R</h2>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border px-4 py-2 text-sm">No.</th>
                                                <th class="border px-4 py-2 text-sm">Uraian</th>
                                                <th class="border px-4 py-2 text-sm" style="min-width: 500px">Detail</th>
                                                <th class="border px-4 py-2 text-sm">Kondisi Awal</th>
                                                <th colspan="3" class="border px-4 py-2 text-sm text-center">Area</th>
                                                <th colspan="5" class="border px-4 py-2 text-sm text-center">Tindakan</th>
                                                <th class="border px-4 py-2 text-sm">Kondisi Akhir</th>
                                                <th class="border px-4 py-2 text-sm">Eviden</th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2 text-sm">PIC</th>
                                                <th class="border px-4 py-2 text-sm">Area Kerja</th>
                                                <th class="border px-4 py-2 text-sm">Area Produksi</th>
                                                <th class="border px-4 py-2 text-sm">Membersihkan</th>
                                                <th class="border px-4 py-2 text-sm">Merapikan</th>
                                                <th class="border px-4 py-2 text-sm">Membuang Sampah</th>
                                                <th class="border px-4 py-2 text-sm">Mengecat</th>
                                                <th class="border px-4 py-2 text-sm">Lainnya</th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pemeriksaan as $index => $item)
                                            <tr>
                                                <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                                <td class="border px-4 py-2">{{ $item->kategori }}</td>
                                                <td class="border px-4 py-2" style="width: 400px">{{ $item->detail }}</td>
                                                <td class="border px-4 py-2">
                                                    <textarea name="kondisi_awal_pemeriksaan_{{ $item->kategori }}" class="w-[300px] h-[100px] p-1 border-gray-300 rounded" rows="3">{{ $item->kondisi_awal }}</textarea>
                                                </td>
                                                <td class="border px-4 py-2"><textarea name="pic_{{ $item->kategori }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded">{{ $item->pic }}</textarea></td>
                                                <td class="border px-4 py-2"><textarea name="area_kerja_{{ $item->kategori }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded">{{ $item->area_kerja }}</textarea></td>
                                                <td class="border px-4 py-2"><textarea name="area_produksi_{{ $item->kategori }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded">{{ $item->area_produksi }}</textarea></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="membersihkan_{{ $item->kategori }}" class="form-checkbox" {{ $item->membersihkan ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="merapikan_{{ $item->kategori }}" class="form-checkbox" {{ $item->merapikan ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="membuang_sampah_{{ $item->kategori }}" class="form-checkbox" {{ $item->membuang_sampah ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="mengecat_{{ $item->kategori }}" class="form-checkbox" {{ $item->mengecat ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="lainnya_{{ $item->kategori }}" class="form-checkbox" {{ $item->lainnya ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2">
                                                    <textarea name="kondisi_akhir_pemeriksaan_{{ $item->kategori }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded">{{ $item->kondisi_akhir }}</textarea>
                                                </td>
                                                <td class="border px-4 py-2" style="min-width: 200px;">
                                                    @if($item->eviden)
                                                        <div class="mb-2">
                                                            <a href="{{ Storage::url($item->eviden) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                                Lihat Eviden Saat Ini
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="eviden_pemeriksaan_{{ $item->kategori }}" class="w-full">
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
                                @foreach($programKerja as $index => $program)
                                <div class="mb-8 border-b pb-6 last:border-b-0">
                                    <h3 class="text-lg font-medium mb-4">{{ $program->program_kerja }}</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Goal</label>
                                            <input type="text" name="goal_{{ $index + 1 }}" value="{{ $program->goal }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Awal</label>
                                            <textarea name="kondisi_awal_program_{{ $index + 1 }}" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $program->kondisi_awal }}</textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Progress</label>
                                            <input type="text" name="progress_{{ $index + 1 }}" value="{{ $program->progress }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Akhir</label>
                                            <textarea name="kondisi_akhir_program_{{ $index + 1 }}" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $program->kondisi_akhir }}</textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                            <textarea name="catatan_{{ $index + 1 }}" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $program->catatan }}</textarea>
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Eviden</label>
                                            @if($program->eviden)
                                                <div class="mb-2">
                                                    <a href="{{ Storage::url($program->eviden) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                        Lihat Eviden Saat Ini
                                                    </a>
                                                </div>
                                            @endif
                                            <input type="file" name="eviden_program_{{ $index + 1 }}" class="w-full">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.5s5r.list') }}" 
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#009BB9]">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>
@endsection 