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

                    <h1 class="text-xl font-semibold text-gray-800">5S5R</h1>
                </div>

                <div class="relative">
                    <button id="dropdownToggle" class="flex items-center" onclick="toggleDropdown()">
                        <img src="{{ Auth::user()->avatar ?? asset('foto_profile/admin1.png') }}"
                            class="w-7 h-7 rounded-full mr-2">
                        <span class="text-gray-700 text-sm">{{ Auth::user()->name }}</span>
                        <i class="fas fa-caret-down ml-2 text-gray-600"></i>
                    </button>
                    <div id="dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden z-10">
                        <a href="{{ route('logout') }}" 
                           class="block px-4 py-2 text-gray-800 hover:bg-gray-200"
                           onclick="event.preventDefault(); 
                                    document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ route('homepage') }}">
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => '5S5R', 'url' => null]]" />
        </div>

        <main class="px-6 py-8">
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="flex space-x-4" aria-label="Tabs">
                    <button class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-blue-500 text-blue-600" data-target="table1">
                        Tabel Pemeriksaan 5S5R
                    </button>
                    <button class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-target="table2">
                        Tabel Program Kerja 5R
                    </button>
                </nav>
            </div>

            <!-- Table 1 Content -->
            <div id="table1-content" class="tab-content">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-sm">No.</th>
                                    <th class="border px-4 py-2 text-sm">Uraian</th>
                                    <th class="border px-4 py-2 text-sm" style="min-width: 500px">Detail</th>
                                    <th class="border px-4 py-2 text-sm">kondisi awal</th>
                                    <th colspan="3" class="border px-4 py-2 text-sm text-center">Area</th>
                                    <th colspan="5" class="border px-4 py-2 text-sm text-center">Tindakan</th>
                                    <th class="border px-4 py-2 text-sm">kondisi akhir</th>
                                    <th class="border px-4 py-2 text-sm">eviden</th>
                                </tr>
                                <tr class="bg-gray-50">
                                    <th class="border px-4 py-2"></th>
                                    <th class="border px-4 py-2"></th>
                                    <th class="border px-4 py-2"></th>
                                    <th class="border px-4 py-2"></th>
                                    <th class="border px-4 py-2 text-sm">PIC</th>
                                    <th class="border px-4 py-2 text-sm">Area kerja</th>
                                    <th class="border px-4 py-2 text-sm">Area Produksi</th>
                                    <th class="border px-4 py-2 text-sm">membersihkan</th>
                                    <th class="border px-4 py-2 text-sm">merapikan</th>
                                    <th class="border px-4 py-2 text-sm">membuang sampah</th>
                                    <th class="border px-4 py-2 text-sm">mengecat</th>
                                    <th class="border px-4 py-2 text-sm">lainnya</th>
                                    <th class="border px-4 py-2"></th>
                                    <th class="border px-4 py-2"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['Ringkas', 'Rapi', 'Resik', 'Rawat', 'Rajin'] as $index => $item)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                    <td class="border px-4 py-2">{{ $item }}</td>
                                    <td class="border px-4 py-2 " style="width: 400px">
                                        @if($item == 'Ringkas')
                                            membedakan antara yang diperlukan dan yang tidak diperlukan serta membuang yang tidak diperlukan. Prinsip dan Ringkas (Seiri) yaitu dengan mengguanakan stratifikasi dan menangani sebab masalah.
                                        @elseif($item == 'Rapi')
                                            Menentukan tata letak yang tertata rapi sehingga kita selalu menemukan barang yang dibutuhkan. Prinsipnya adalah penyimpanan fungsional dan menghilangkan waktu untuk mencari barang.
                                        @elseif($item == 'Resik')
                                            Berarti menghilangkan sampah kotoran dan barang asing untuk memperoleh tempat kerja yang lebih bersih. Prinsipnya adalah pembersihan sebagai pemeriksaan dan tingkat kebersihan.
                                        @elseif($item == 'Rawat')
                                            Berarti memelihara barang dengan teratur, rapih, bersih dan dalam aspek personal serta kaitannya dengan polusi. Prinsipnya adalah manajemen visual dan pemantapan 5S.
                                        @else
                                            Berarti melakukan sesuatu yang benar sebagai kebiasaan. Prinsipnya adalah pembentukan kebiasaan dan tempat kerja yang mantap.
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        <textarea class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 300px"></textarea>
                                    </td>
                                    <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                    <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                    <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                    <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                    <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                    <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                    <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                    <td class="border px-4 py-2 text-center"><input type="checkbox" class="form-checkbox"></td>
                                    <td class="border px-4 py-2"><input type="text" class="w-full p-1 border-gray-300 rounded"></td>
                                    <td class="border px-4 py-2">
                                        <input type="file" class="hidden" id="eviden-{{ $index }}">
                                        <label for="eviden-{{ $index }}" class="cursor-pointer bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Table 2 Content -->
            <div id="table2-content" class="tab-content hidden">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-sm">NO</th>
                                    <th class="border px-4 py-2 text-sm">program kerja 5R</th>
                                    <th class="border px-4 py-2 text-sm">Goal</th>
                                    <th class="border px-4 py-2 text-sm">kondisi awal</th>
                                    <th colspan="4" class="border px-4 py-2 text-sm text-center">Progres</th>
                                    <th class="border px-4 py-2 text-sm">Kondisi akhir</th>
                                    <th class="border px-4 py-2 text-sm">Catatan</th>
                                    <th class="border px-4 py-2 text-sm">eviden</th>
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
                                @foreach(['A', 'B', 'C', 'D'] as $index => $letter)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $letter }}</td>
                                    <td class="border px-4 py-2">shift {{ $index + 1 }}</td>
                                    <td class="border px-4 py-2"><textarea class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                    <td class="border px-4 py-2"><textarea class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                    <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $letter }}" value="0-25"></td>
                                    <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $letter }}" value="26-50"></td>
                                    <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $letter }}" value="51-75"></td>
                                    <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $letter }}" value="76-100"></td>
                                    <td class="border px-4 py-2"><textarea class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                    <td class="border px-4 py-2"><textarea class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                    <td class="border px-4 py-2">
                                        <input type="file" class="hidden" id="eviden2-{{ $index }}">
                                        <label for="eviden2-{{ $index }}" class="cursor-pointer bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600">
                                            Upload
                                        </label>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-6 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    Simpan
                </button>
            </div>
        </main>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const target = button.dataset.target;

            // Hide all contents and remove active classes
            tabContents.forEach(content => content.classList.add('hidden'));
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected content and add active class
            document.getElementById(`${target}-content`).classList.remove('hidden');
            button.classList.remove('border-transparent', 'text-gray-500');
            button.classList.add('border-blue-500', 'text-blue-600');
        });
    });
});
</script>
@endsection 