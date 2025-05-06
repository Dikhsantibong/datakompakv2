@extends('layouts.app')

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .welcome-card {
            background-size: cover;
            background-position: center;
            transition: background-image 1s ease-in-out;
            font-family: 'Poppins', sans-serif;
            min-height: 200px;
        }

        .typing-animation {
            overflow: hidden;
            white-space: nowrap;
            border-right: 3px solid white;
            animation: typing 3.5s steps(40, end), blink-caret .75s step-end infinite;
            margin: 0;
            width: 0;
        }

        @media (max-width: 768px) {
            .typing-animation {
                white-space: normal;
                border-right: none;
                width: 100%;
                font-size: 1.5rem;
                line-height: 1.2;
                animation: fadeIn 1s ease-in forwards;
            }
            
            .welcome-card {
                background-position: center;
                padding: 1.5rem;
                min-height: 180px;
            }
        }

        .fade-in {
            opacity: 0;
            animation: fadeIn 1s ease-in forwards;
            animation-delay: 1s;
        }

        @keyframes typing {
            from { width: 0 }
            to { width: 100% }
        }

        @keyframes blink-caret {
            from, to { border-color: transparent }
            50% { border-color: white }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .welcome-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0.2),
                rgba(0, 0, 0, 0.4)
            );
            border-radius: 0.5rem;
            z-index: 1;
        }

        .welcome-card > div {
            position: relative;
            z-index: 2;
        }
    </style>
@endpush

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')
    
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Header -->
        <header class="bg-white shadow-sm">
            <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                <div class="flex items-center">
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

                    <h1 class="text-xl font-semibold text-gray-900">Form Pemeriksaan 5S5R</h1>
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
            <x-admin-breadcrumb :breadcrumbs="[['name' => '5S5R', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Form Pemeriksaan 5S5R</h2>
                        <p class="text-blue-100 mb-4">Kelola dan monitor pemeriksaan 5S5R untuk memastikan kualitas dan keandalan sistem.</p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('admin.5s5r.list') }}" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-gray-600 bg-white rounded-md hover:bg-gray-50">
                                <i class="fas fa-list mr-2"></i> Lihat Data
                            </a>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.5s5r.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Form Header -->
                    <div class="mb-6">
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Main Content -->
                    <div class="space-y-6">
                        <!-- Tabel Pemeriksaan 5S5R -->
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
                                                <td class="border px-4 py-2" style="width: 400px">
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
                                                    <textarea name="kondisi_awal_pemeriksaan_{{ $item }}" class="w-full h-[100px] p-1 border-gray-300 rounded" rows="3" style="width: 300px"></textarea>
                                                </td>
                                                <td class="border px-4 py-2"><textarea type="text" name="pic_{{ $item }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded"></textarea></td>
                                                <td class="border px-4 py-2"><textarea type="text" name="area_kerja_{{ $item }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded"></textarea></td>
                                                <td class="border px-4 py-2"><textarea type="text" name="area_produksi_{{ $item }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded"></textarea></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="membersihkan_{{ $item }}" class="form-checkbox"></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="merapikan_{{ $item }}" class="form-checkbox"></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="membuang_sampah_{{ $item }}" class="form-checkbox"></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="mengecat_{{ $item }}" class="form-checkbox"></td>
                                                <td class="border px-4 py-2 text-center"><input type="checkbox" name="lainnya_{{ $item }}" class="form-checkbox"></td>
                                                <td class="border px-4 py-2"><textarea type="text" name="kondisi_akhir_pemeriksaan_{{ $item }}" class="w-[200px] h-[100px] p-1 border-gray-300 rounded"></textarea></td>
                                                <td class="border px-4 py-2">
                                                    <input type="file" name="eviden_pemeriksaan_{{ $item }}" class="hidden" id="eviden-{{ $index }}">
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

                        <!-- Tabel Program Kerja 5R -->
                        <div class="bg-white rounded-lg shadow-sm">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900 mb-4">Tabel Program Kerja 5R</h2>
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
                                                <td class="border px-4 py-2"><textarea name="goal_{{ $index + 1 }}" class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                                <td class="border px-4 py-2"><textarea name="kondisi_awal_program_{{ $index + 1 }}" class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="0-25"></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="26-50"></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="51-75"></td>
                                                <td class="border px-4 py-2 text-center"><input type="radio" name="progress_{{ $index + 1 }}" value="76-100"></td>
                                                <td class="border px-4 py-2"><textarea name="kondisi_akhir_program_{{ $index + 1 }}" class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                                <td class="border px-4 py-2"><textarea name="catatan_{{ $index + 1 }}" class="w-full p-1 border-gray-300 rounded" rows="3" style="width: 200px"></textarea></td>
                                                <td class="border px-4 py-2">
                                                    <input type="file" name="eviden_program_{{ $index + 1 }}" class="hidden" id="eviden2-{{ $index }}">
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
                        <div class="flex justify-end space-x-3">
                            <button type="submit" class="mb-4 bg-[#009BB9] text-white px-4 py-2 rounded hover:bg-[#009BB9]/80">
                                <i class="fas fa-save mr-2"></i>
                                Simpan
                            </button>
                            
                        </div>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script src="{{ asset('js/toggle.js') }}"></script>
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

const backgroundImages = [
    "{{ asset('images/welcome.webp') }}",
    "{{ asset('images/welcome2.jpeg') }}",
    "{{ asset('images/welcome3.jpg') }}",
    // Tambahkan path gambar lainnya sesuai kebutuhan
];

let currentImageIndex = 0;
const welcomeCard = document.querySelector('.welcome-card');

function changeBackground() {
    welcomeCard.style.backgroundImage = `url('${backgroundImages[currentImageIndex]}')`;
    currentImageIndex = (currentImageIndex + 1) % backgroundImages.length;
}

// Set gambar awal
changeBackground();

// Ganti gambar setiap 5 detik
setInterval(changeBackground, 5000);
</script>
@endsection 