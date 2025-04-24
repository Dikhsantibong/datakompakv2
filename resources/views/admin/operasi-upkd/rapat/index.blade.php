@extends('layouts.app')

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
                        class="md:hidden relative inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-[#0A749B] hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Buka menu utama</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <button id="desktop-menu-toggle"
                        class="hidden md:block relative items-center justify-center rounded-md text-gray-400 hover:bg-[#0A749B] p-2 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <span class="sr-only">Buka menu utama</span>
                        <svg class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <h1 class="text-xl font-semibold text-gray-900">Rapat & Link Koordinasi RON</h1>
                </div>

                <!-- Profile and Actions -->
                <div class="flex items-center space-x-4">
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
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex items-center pt-2">
            <x-admin-breadcrumb :breadcrumbs="[['name' => 'Rapat & Link Koordinasi RON', 'url' => null]]" />
        </div>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-sm p-6 mb-6 text-white relative">
                    <div class="max-w-3xl">
                        <h2 class="text-2xl font-bold mb-2">Rapat & Link Koordinasi RON</h2>
                        <p class="text-blue-100 mb-4">Kelola dan pantau rapat serta koordinasi RON untuk memastikan komunikasi yang efektif antar tim.</p>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 bg-white rounded-md hover:bg-blue-50">
                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 bg-white rounded-md hover:bg-red-50">
                                <i class="fas fa-file-pdf mr-2"></i> Export PDF
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800">
                                <i class="fas fa-print mr-2"></i> Print
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <!-- A. PEKERJAAN TENTATIF -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            A. PEKERJAAN TENTATIF
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi Eksisting</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tindak Lanjut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kondisi Akhir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Goal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">Pekerjaan A</td>
                                        <td class="px-6 py-4">Detail pekerjaan A</td>
                                        <td class="px-6 py-4">Tim A</td>
                                        <td class="px-6 py-4">Kondisi awal A</td>
                                        <td class="px-6 py-4">Tindak lanjut A</td>
                                        <td class="px-6 py-4">Kondisi akhir A</td>
                                        <td class="px-6 py-4">Target A</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Selesai tepat waktu</td>
                                    </tr>
                                </tbody>

                                <!-- B. ISU MATURITY LEVEL -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            B. ISU MATURITY LEVEL
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">Isu B</td>
                                        <td class="px-6 py-4">Detail isu B</td>
                                        <td class="px-6 py-4">Tim B</td>
                                        <td class="px-6 py-4">Kondisi awal B</td>
                                        <td class="px-6 py-4">Tindak lanjut B</td>
                                        <td class="px-6 py-4">Kondisi akhir B</td>
                                        <td class="px-6 py-4">Target B</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                In Progress
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Sedang berjalan</td>
                                    </tr>
                                </tbody>

                                <!-- C. OPERASI -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            C. PROGRAM KERJA
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">Operasi C</td>
                                        <td class="px-6 py-4">Detail operasi C</td>
                                        <td class="px-6 py-4">Tim C</td>
                                        <td class="px-6 py-4">Kondisi awal C</td>
                                        <td class="px-6 py-4">Tindak lanjut C</td>
                                        <td class="px-6 py-4">Kondisi akhir C</td>
                                        <td class="px-6 py-4">Target C</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                On Track
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Berjalan lancar</td>
                                    </tr>
                                </tbody>

                                <!-- D. PEMELIHARAAN -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            D. MONITORING PENGADAAN BARANG DAN JASA 
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">Pemeliharaan D</td>
                                        <td class="px-6 py-4">Detail pemeliharaan D</td>
                                        <td class="px-6 py-4">Tim D</td>
                                        <td class="px-6 py-4">Kondisi awal D</td>
                                        <td class="px-6 py-4">Tindak lanjut D</td>
                                        <td class="px-6 py-4">Kondisi akhir D</td>
                                        <td class="px-6 py-4">Target D</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                Done
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Selesai</td>
                                    </tr>
                                </tbody>

                                <!-- E. ENGINEERING -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            E. MONITORING PENGAWASAN APLIKASI
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">Engineering E</td>
                                        <td class="px-6 py-4">Detail engineering E</td>
                                        <td class="px-6 py-4">Tim E</td>
                                        <td class="px-6 py-4">Kondisi awal E</td>
                                        <td class="px-6 py-4">Tindak lanjut E</td>
                                        <td class="px-6 py-4">Kondisi akhir E</td>
                                        <td class="px-6 py-4">Target E</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">
                                                Ongoing
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Dalam proses</td>
                                    </tr>
                                </tbody>

                                <!-- F. HSE -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            F. HSE
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">HSE F</td>
                                        <td class="px-6 py-4">Detail HSE F</td>
                                        <td class="px-6 py-4">Tim F</td>
                                        <td class="px-6 py-4">Kondisi awal F</td>
                                        <td class="px-6 py-4">Tindak lanjut F</td>
                                        <td class="px-6 py-4">Kondisi akhir F</td>
                                        <td class="px-6 py-4">Target F</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Aktif</td>
                                    </tr>
                                </tbody>

                                <!-- G. ADMINISTRASI -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            G. LAPORAN PEMBANGKIT DAN TRANSAKSI ENERGI
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">1</td>
                                        <td class="px-6 py-4">Administrasi G</td>
                                        <td class="px-6 py-4">Detail administrasi G</td>
                                        <td class="px-6 py-4">Tim G</td>
                                        <td class="px-6 py-4">Kondisi awal G</td>
                                        <td class="px-6 py-4">Tindak lanjut G</td>
                                        <td class="px-6 py-4">Kondisi akhir G</td>
                                        <td class="px-6 py-4">Target G</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                Running
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">Berjalan</td>
                                    </tr>
                                </tbody>

                                <!-- H. RAPAT -->
                                <thead class=" mt-10">
                                    <tr class="bg-gray-50">
                                        <th colspan="7" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            H. RAPAT
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rapat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Resume</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notulen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Eviden</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4">INTERNAL RON</td>
                                        <td class="px-6 py-4">Rapat koordinasi internal</td>
                                        <td class="px-6 py-4">10 Jan 2024 09:00</td>
                                        <td class="px-6 py-4">Online</td>
                                        <td class="px-6 py-4">Pembahasan progress Q1</td>
                                        <td class="px-6 py-4">Tersedia</td>
                                        <td class="px-6 py-4">
                                            <a href="#" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4">INTERNAL UPKD</td>
                                        <td class="px-6 py-4">Evaluasi kinerja</td>
                                        <td class="px-6 py-4">15 Jan 2024 13:00</td>
                                        <td class="px-6 py-4">Hybrid</td>
                                        <td class="px-6 py-4">Evaluasi target</td>
                                        <td class="px-6 py-4">Tersedia</td>
                                        <td class="px-6 py-4">
                                            <a href="#" class="text-blue-600 hover:text-blue-800">Lihat</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
    // Toggle dropdown
    window.toggleDropdown = function() {
        document.getElementById('dropdown').classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#dropdownToggle')) {
            document.getElementById('dropdown').classList.add('hidden');
        }
    });

    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
    const sidebar = document.querySelector('aside');
    
    mobileMenuBtn.addEventListener('click', () => {
        sidebar.classList.toggle('hidden');
    });
});
</script>
@endpush

@endsection