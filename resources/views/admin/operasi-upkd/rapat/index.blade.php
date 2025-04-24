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
            <div class="container mx-auto px-4 sm:px-6 py-6">
                <!-- Welcome Card -->
                <div class="bg-gradient-to-r from-[#0A749B] to-[#0A749B]/80 rounded-lg shadow-sm p-6 mb-6 text-white">
                    <div class="max-w-3xl">
                        <p class="text-blue-100 mb-4">Kelola dan pantau rapat serta koordinasi RON untuk memastikan komunikasi yang efektif antar tim.</p>
                        <div class="flex flex-wrap gap-3">
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#0A749B] bg-white rounded-md hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-excel mr-2"></i> Ekspor Excel
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-[#0A749B] bg-white rounded-md hover:bg-gray-50 transition-colors duration-200">
                                <i class="fas fa-file-pdf mr-2"></i> Ekspor PDF
                            </button>
                            <button type="button" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-[#0A749B]/90 rounded-md hover:bg-[#0A749B] transition-colors duration-200 border border-white/20">
                                <i class="fas fa-print mr-2"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6">
                        <!-- Success Message -->
                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Error Messages -->
                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <!-- A. Pekerjaan Tentatif -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            A. PEKERJAAN TENTATIF
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Eksisting</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tindak Lanjut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kondisi Akhir</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Goal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pekerjaan_tentatif as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="pekerjaan_tentatif[{{ $index }}][uraian]" value="{{ $item['uraian'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="pekerjaan_tentatif[{{ $index }}][detail]" value="{{ $item['detail'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="pekerjaan_tentatif[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][kondisi_akhir]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['kondisi_akhir'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="pekerjaan_tentatif[{{ $index }}][goal]" value="{{ $item['goal'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="pekerjaan_tentatif[{{ $index }}][status]"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="">Pilih Status</option>
                                                <option value="open" {{ $item['status'] == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ $item['status'] == 'closed' ? 'selected' : '' }}>Closed</option>
                                                <option value="in_progress" {{ $item['status'] == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="pekerjaan_tentatif[{{ $index }}][keterangan]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['keterangan'] }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- B. ISU MATURITY LEVEL -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            B. ISU MATURITY LEVEL
                                        </th>
                                    </tr>
                                </thead>
                                
                                <!-- B.1 Operation Management -->
                                <thead>
                                    <tr>
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-10">
                                            B.1 OPERATION MANAGEMENT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($operation_management as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="operation_management[{{ $index }}][uraian]" value="{{ $item['uraian'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="operation_management[{{ $index }}][detail]" value="{{ $item['detail'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <!-- Add other fields similar to pekerjaan_tentatif -->
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- B.2 EFISIENSI MANAGEMENT -->
                                <thead>
                                    <tr>
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider pl-10">
                                            B.2 EFISIENSI MANAGEMENT
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($efisiensi_management as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="efisiensi_management[{{ $index }}][uraian]" value="{{ $item['uraian'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <!-- Add other fields similar to pekerjaan_tentatif -->
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- Add sections C through G with similar structure -->

                                <!-- H. RAPAT -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="7" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            H. RAPAT
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rapat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uraian</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Online/Offline</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Resume</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notulen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eviden</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach(['internal_ron' => 'INTERNAL RON', 'internal_upkd' => 'INTERNAL UPKD', 'eksternal_np1' => 'EKSTERNAL NP', 'eksternal_np2' => 'EKSTERNAL NP'] as $key => $label)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $label }}</td>
                                        <td class="px-6 py-4">
                                            <textarea name="rapat[{{ $key }}][uraian]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $rapat_data[$key]['uraian'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="datetime-local" name="rapat[{{ $key }}][jadwal]" value="{{ $rapat_data[$key]['jadwal'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <select name="rapat[{{ $key }}][online_offline]"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                                <option value="online" {{ $rapat_data[$key]['online_offline'] == 'online' ? 'selected' : '' }}>Online</option>
                                                <option value="offline" {{ $rapat_data[$key]['online_offline'] == 'offline' ? 'selected' : '' }}>Offline</option>
                                                <option value="hybrid" {{ $rapat_data[$key]['online_offline'] == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="rapat[{{ $key }}][resume]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $rapat_data[$key]['resume'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="rapat[{{ $key }}][notulen]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $rapat_data[$key]['notulen'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="file" name="rapat[{{ $key }}][eviden]"
                                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#009BB9] file:text-white hover:file:bg-[#009BB9]/80">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <!-- Link Back Up Monitoring -->
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th colspan="10" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-gray-100">
                                            LINK BACK UP MONITORING
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($link_monitoring as $index => $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item['no'] }}</td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="link_monitoring[{{ $index }}][uraian]" value="{{ $item['uraian'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="link_monitoring[{{ $index }}][detail]" value="{{ $item['detail'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="link_monitoring[{{ $index }}][pic]" value="{{ $item['pic'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="link_monitoring[{{ $index }}][kondisi_eksisting]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['kondisi_eksisting'] }}</textarea>
                                        </td>
                                        <td class="px-6 py-4">
                                            <textarea name="link_monitoring[{{ $index }}][tindaklanjut]" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#009BB9] focus:ring focus:ring-[#009BB9] focus:ring-opacity-50 sm:text-sm">{{ $item['tindaklanjut'] }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                <i class="fas fa-save mr-2"></i>
                                Simpan Data
                            </button>
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