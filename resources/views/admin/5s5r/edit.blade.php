@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-sm sticky top-0 z-20
        ">
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

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#009BB9] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-800">Edit Data Pemeriksaan 5S5R</h1>
                </div>

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
                            <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                        </form>
                    </div>
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
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Tabel Program Kerja 5R</h2>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full border border-gray-300">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border px-4 py-2 text-sm">NO</th>
                                                <th class="border px-4 py-2 text-sm">Program Kerja 5R</th>
                                                <th class="border px-4 py-2 text-sm">Goal</th>
                                                <th class="border px-4 py-2 text-sm">Kondisi Awal</th>
                                                <th colspan="4" class="border px-4 py-2 text-sm text-center">Progres</th>
                                                <th class="border px-4 py-2 text-sm">Kondisi Akhir</th>
                                                <th class="border px-4 py-2 text-sm">Catatan</th>
                                                <th class="border px-4 py-2 text-sm">Eviden</th>
                                            </tr>
                                            <tr class="bg-gray-50">
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2 text-sm">0-25%</th>
                                                <th class="border px-4 py-2 text-sm">26-50%</th>
                                                <th class="border px-4 py-2 text-sm">51-75%</th>
                                                <th class="border px-4 py-2 text-sm">76-100%</th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                                <th class="border px-4 py-2"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($programKerja as $index => $program)
                                            <tr>
                                                <td class="border px-4 py-2 text-center">{{ chr(65 + $index) }}</td>
                                                <td class="border px-4 py-2">{{ $program->program_kerja }}</td>
                                                <td class="border px-4 py-2"><textarea name="goal_{{ $index + 1 }}" class="w-[200px] p-1 border-gray-300 rounded" rows="3">{{ $program->goal }}</textarea></td>
                                                <td class="border px-4 py-2"><textarea name="kondisi_awal_program_{{ $index + 1 }}" class="w-[200px] p-1 border-gray-300 rounded" rows="3">{{ $program->kondisi_awal }}</textarea></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="0-25" {{ $program->progress == '0-25' ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="26-50" {{ $program->progress == '26-50' ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="51-75" {{ $program->progress == '51-75' ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="76-100" {{ $program->progress == '76-100' ? 'checked' : '' }}></td>
                                                <td class="border px-4 py-2"><textarea name="kondisi_akhir_program_{{ $index + 1 }}" class="w-[200px] p-1 border-gray-300 rounded" rows="3">{{ $program->kondisi_akhir }}</textarea></td>
                                                <td class="border px-4 py-2"><textarea name="catatan_{{ $index + 1 }}" class="w-[200px] p-1 border-gray-300 rounded" rows="3">{{ $program->catatan }}</textarea></td>
                                                <td class="border px-4 py-2" style="min-width: 200px;">
                                                    @if($program->eviden)
                                                        <div class="mb-2">
                                                            <a href="{{ Storage::url($program->eviden) }}" target="_blank" class="text-blue-600 hover:text-blue-900">
                                                                Lihat Eviden Saat Ini
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <input type="file" name="eviden_program_{{ $index + 1 }}" class="w-full">
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