@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-auto">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 main-content">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-20">
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

                    <h1 class="text-xl font-semibold text-gray-800">Laporan Abnormal/Gangguan</h1>
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

        <!-- Main Content -->
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6">
                    <!-- Tab Navigation -->
                    <div class="mb-6">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button class="tab-btn active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="kronologi">
                                    Kronologi Kejadian
                                </button>
                                <button class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="mesin">
                                    Mesin/Peralatan Terdampak
                                </button>
                                <button class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="tindakan">
                                    Tindak Lanjut Tindakan
                                </button>
                                <button class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="rekomendasi">
                                    Rekomendasi
                                </button>
                            </nav>
                        </div>
                    </div>

                    <form action="{{ route('admin.abnormal-report.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Kronologi Kejadian Table -->
                        <div id="kronologi" class="tab-content active">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Pukul (WIB)</th>
                                            <th rowspan="2" class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Uraian kejadian</th>
                                            <th colspan="4" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Pengamatan</th>
                                            <th colspan="4" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Koordinasi</th>
                                            <th colspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Tindak lanjut</th>
                                            <th colspan="4" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Adm. SR</th>
                                        </tr>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Visual parameter terkait</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Turun beba</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Off cba</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Stop</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL Ophar</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL OP</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">TL HAR</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">MUL</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">MO rutin (PO-PS)</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">PM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">EM</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">PHW</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @for ($i = 0; $i < 7; $i++)
                                        <tr>
                                            <td class="border px-4 py-2">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm" name="uraian_kejadian[]"></td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm" name="visual_parameter[]"></td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="turun_beban[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="off_cba[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="stop[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="tl_ophar[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="tl_op[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="tl_har[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="mul[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="flm[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="mo_rutin[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="flm_sr[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="pm[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="em[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="phw[]" value="1">
                                            </td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Mesin/Peralatan Terdampak Table -->
                        <div id="mesin" class="tab-content hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Nama Mesin/Peralatan/Material</th>
                                            <th colspan="2" class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Kondisi</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Ket.</th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="border"></th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Rusak</th>
                                            <th class="px-4 py-3 bg-gray-50 text-center text-xs font-medium text-gray-500 uppercase tracking-wider border">Abnormal</th>
                                            <th class="border"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @for ($i = 1; $i <= 3; $i++)
                                        <tr>
                                            <td class="border px-4 py-2">{{ $i }}</td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm" name="nama_mesin[]"></td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="kondisi_rusak[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2 text-center">
                                                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="kondisi_abnormal[]" value="1">
                                            </td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm" name="keterangan[]"></td>
                                        </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Tindak Lanjut Tindakan Table -->
                        <div id="tindakan" class="tab-content hidden">
                            <div class="grid grid-cols-3 gap-6">
                                <!-- Tindak Lanjut Tindakan -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Usul MO rutin (PO-PS)</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">MO non rutin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @for ($i = 1; $i <= 3; $i++)
                                            <tr>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="flm_tindakan[]" value="1">
                                                </td>
                                                <td class="border px-4 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm" name="usul_mo_rutin[]"></td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="mo_non_rutin[]" value="1">
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tindak Lanjut Koordinasi -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">TL Ophar</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">TLOP</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">TL HAR</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">MUL</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @for ($i = 1; $i <= 3; $i++)
                                            <tr>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="tl_ophar_koordinasi[]" value="1">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="tlop_koordinasi[]" value="1">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="tl_har_koordinasi[]" value="1">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="mul_koordinasi[]" value="1">
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Tindak Lanjut Adm -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead>
                                            <tr>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">FLM</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">PM</th>
                                                <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">CM</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @for ($i = 1; $i <= 3; $i++)
                                            <tr>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="flm_adm[]" value="1">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="pm_adm[]" value="1">
                                                </td>
                                                <td class="border px-4 py-2 text-center">
                                                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" name="cm_adm[]" value="1">
                                                </td>
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Rekomendasi Table -->
                        <div id="rekomendasi" class="tab-content hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">No</th>
                                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border">Uraian</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td class="border px-4 py-2">1</td>
                                            <td class="border px-4 py-2">ganti material/ peralatan terdampak</td>
                                        </tr>
                                        <tr>
                                            <td class="border px-4 py-2">2</td>
                                            <td class="border px-4 py-2">pemeriksaan kembali saat har rutin</td>
                                        </tr>
                                        <tr>
                                            <td class="border px-4 py-2">3</td>
                                            <td class="border px-4 py-2">pemeliharaan dilaksanakan offline mesin</td>
                                        </tr>
                                        <tr>
                                            <td class="border px-4 py-2">4</td>
                                            <td class="border px-4 py-2"><input type="text" class="w-full border-gray-300 rounded-md shadow-sm" name="rekomendasi_tambahan"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
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

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class from all tabs and contents
            tabs.forEach(t => t.classList.remove('active', 'border-blue-500', 'text-blue-600'));
            contents.forEach(c => c.classList.add('hidden'));

            // Add active class to clicked tab and show corresponding content
            tab.classList.add('active', 'border-blue-500', 'text-blue-600');
            const content = document.getElementById(tab.dataset.tab);
            content.classList.remove('hidden');
        });
    });
});
</script>
@endpush
@endsection 