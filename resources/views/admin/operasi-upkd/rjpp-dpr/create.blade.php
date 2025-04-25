@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-100">
    @include('components.sidebar')

    <div class="flex-1 flex flex-col overflow-hidden">
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

                    <h1 class="text-xl font-semibold text-gray-800">Tambah Data RJPP-DPR</h1>
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
                ['name' => 'Operasi UPKD', 'url' => null],
                ['name' => 'RJPP-DPR', 'url' => route('admin.operasi-upkd.rjpp-dpr.index')],
                ['name' => 'Tambah Data', 'url' => null]
            ]" />
        </div>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mt-6">
                    <div class="p-6">
                        <form id="rjppDprForm" action="{{ route('admin.operasi-upkd.rjpp-dpr.store') }}" method="POST">
                            @csrf
                            
                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                                    <select name="tahun" class="p-2 w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        @foreach([2025, 2026, 2027, 2028, 2029] as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                                    <select name="semester" class="p-2 w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Description and Goals -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Uraian</label>
                                    <textarea name="uraian" rows="3" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Goal</label>
                                    <textarea name="goal" rows="3" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                </div>
                            </div>

                            <!-- Deadlines -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deadline Perencanaan</label>
                                    <input type="date" name="deadline_perencanaan" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deadline Pelaksanaan</label>
                                    <input type="date" name="deadline_pelaksanaan" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deadline Nagiha/Lain</label>
                                    <input type="date" name="deadline_nagiha" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>

                            <!-- PIC and Budget -->
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                                <div>
                                    <label for="pic" class="block text-sm font-medium text-gray-700">PIC</label>
                                    <select name="pic" id="pic" class="p-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">-- Pilih PIC --</option>
                                        <option value="asman operasi">Asman Operasi</option>
                                        <option value="TL RON">TL RON</option>
                                        <option value="ROHMAT">ROHMAT</option>
                                        <option value="IMAM">IMAM</option>
                                        <option value="KASMAN">KASMAN</option>
                                        <option value="AMINAH">AMINAH</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Anggaran</label>
                                    <div class="flex flex-col space-y-2">
                                        <div class="flex items-center">
                                            <input id="anggaran_ai" name="anggaran[]" type="checkbox" value="AI" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="anggaran_ai" class="ml-2 block text-sm text-gray-700">Anggaran AI</label>
                                        </div>
                                        <div class="flex items-center">
                                            <input id="anggaran_ao" name="anggaran[]" type="checkbox" value="AO" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <label for="anggaran_ao" class="ml-2 block text-sm text-gray-700">Anggaran AO</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                                    <input type="number" name="harga" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                            </div>

                            <!-- Progress Checkboxes -->
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Progress</label>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_list" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">LIST</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_rab" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">RAB</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_tor" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">TOR</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_nd_drp" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">ND DRP</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_mup" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">MUP</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_rendan" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">RENDAN</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="progress_kp" class="w-5 h-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                        <label class="ml-2 text-sm text-gray-700">KP</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing Condition and Status -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Eksisting</label>
                                    <textarea name="kondisi_eksisting" rows="3" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" class="p-2 w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Pilih Status</option>
                                        <option value="On Track">On Track</option>
                                        <option value="Delayed">Delayed</option>
                                        <option value="Completed">Completed</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Link Back Up Monitoring -->
                            <div class="mb-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-2">LINK BACK UP MONITORING :</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 mr-2">1.</span>
                                        <input type="text" name="link_monitoring[1]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                    <div class="flex items-center">
                                        <span class="text-sm text-gray-500 mr-2">2.</span>
                                        <input type="text" name="link_monitoring[2]" class="flex-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex justify-end gap-4">
                                <a href="{{ route('admin.operasi-upkd.rjpp-dpr.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                    <i class="fas fa-times mr-2"></i> Batal
                                </a>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#0A749B] hover:bg-[#0A749B]/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0A749B]">
                                    <i class="fas fa-save mr-2"></i> Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script>
function toggleDropdown() {
    const dropdown = document.getElementById('dropdown');
    dropdown.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-toggle');
    const sidebar = document.querySelector('aside');
    
    mobileMenuBtn?.addEventListener('click', () => {
        sidebar?.classList.toggle('hidden');
    });

    // Form submission handling
    const form = document.getElementById('rjppDprForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit form using fetch
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                window.location.href = "{{ route('admin.operasi-upkd.rjpp-dpr.index') }}";
            } else {
                alert(data.message || 'Terjadi kesalahan saat menyimpan data');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat menyimpan data');
        });
    });
});
</script>
@endpush
@endsection
