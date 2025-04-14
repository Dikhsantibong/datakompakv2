@extends('layouts.app')

@section('content')
<div class="flex h-screen bg-gray-50 overflow-hidden">
    @include('components.sidebar')
    
    <div id="main-content" class="flex-1 overflow-auto">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-10">
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

                    <h1 class="text-xl font-semibold text-gray-800">Edit Jadwal Operasi</h1>
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
                ['name' => 'Kalender Operasi', 'url' => route('admin.kalender.calendar')],
                ['name' => 'Edit Jadwal', 'url' => null]
            ]" />
        </div>

        <!-- Main Content -->
        <main class="px-6">
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Edit Jadwal</h2>
                    <form action="{{ route('admin.kalender.update', $schedule->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="title" class="block text-sm font-medium text-gray-700">Judul Kegiatan</label>
                                <div class="relative">
                                    <input type="text" name="title" id="title" required
                                        value="{{ old('title', $schedule->title) }}"
                                        class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @else border-gray-300 @enderror transition-all duration-300"
                                        placeholder="Masukkan judul kegiatan">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-calendar-alt text-gray-400"></i>
                                    </div>
                                </div>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="location" class="block text-sm font-medium text-gray-700">Lokasi</label>
                                <div class="relative">
                                    <input type="text" name="location" id="location"
                                        value="{{ old('location', $schedule->location) }}"
                                        class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('location') border-red-500 @else border-gray-300 @enderror transition-all duration-300"
                                        placeholder="Masukkan lokasi kegiatan">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i>
                                    </div>
                                </div>
                                @error('location')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-2">
                                <label for="schedule_date" class="block text-sm font-medium text-gray-700">Tanggal</label>
                                <div class="relative">
                                    <input type="date" name="schedule_date" id="schedule_date" required
                                        value="{{ old('schedule_date', $schedule->schedule_date->format('Y-m-d')) }}"
                                        class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('schedule_date') border-red-500 @else border-gray-300 @enderror transition-all duration-300">
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                </div>
                                @error('schedule_date')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label for="start_time" class="block text-sm font-medium text-gray-700">Waktu Mulai</label>
                                    <div class="relative">
                                        <input type="time" name="start_time" id="start_time" required
                                            value="{{ old('start_time', $schedule->start_time) }}"
                                            class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_time') border-red-500 @else border-gray-300 @enderror transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="far fa-clock text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('start_time')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="space-y-2">
                                    <label for="end_time" class="block text-sm font-medium text-gray-700">Waktu Selesai</label>
                                    <div class="relative">
                                        <input type="time" name="end_time" id="end_time" required
                                            value="{{ old('end_time', $schedule->end_time) }}"
                                            class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_time') border-red-500 @else border-gray-300 @enderror transition-all duration-300">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="far fa-clock text-gray-400"></i>
                                        </div>
                                    </div>
                                    @error('end_time')
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <div class="relative">
                                <textarea name="description" id="description" rows="4" required
                                    class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @else border-gray-300 @enderror transition-all duration-300"
                                    placeholder="Masukkan deskripsi kegiatan">{{ old('description', $schedule->description) }}</textarea>
                                <div class="absolute top-3 right-3 pointer-events-none">
                                    <i class="fas fa-align-left text-gray-400"></i>
                                </div>
                            </div>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="participants" class="block text-sm font-medium text-gray-700">Peserta</label>
                            <div class="relative">
                                <input type="text" name="participants" id="participants"
                                    value="{{ old('participants', $schedule->participants ? implode(', ', $schedule->participants) : '') }}"
                                    class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('participants') border-red-500 @else border-gray-300 @enderror transition-all duration-300"
                                    placeholder="Contoh: John Doe, Jane Smith, ...">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-users text-gray-400"></i>
                                </div>
                            </div>
                            @error('participants')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="relative">
                                <select name="status" id="status" required
                                    class="w-full px-4 py-2.5 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('status') border-red-500 @else border-gray-300 @enderror transition-all duration-300">
                                    <option value="scheduled" {{ old('status', $schedule->status) === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                                    <option value="completed" {{ old('status', $schedule->status) === 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ old('status', $schedule->status) === 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-tasks text-gray-400"></i>
                                </div>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4 pt-4">
                            <a href="{{ route('admin.kalender.calendar') }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 shadow-lg">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/toggle.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');

    function validateTimes() {
        if (startTimeInput.value && endTimeInput.value) {
            if (startTimeInput.value >= endTimeInput.value) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Waktu mulai harus lebih awal dari waktu selesai',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3B82F6'
                });
                return false;
            }
        }
        return true;
    }

    startTimeInput.addEventListener('change', validateTimes);
    endTimeInput.addEventListener('change', validateTimes);

    // Form submission validation
    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateTimes()) {
            e.preventDefault();
        }
    });
});

// Handle flash messages
document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 1500
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: "{{ session('error') }}"
        });
    @endif
});
</script>

<style>
.animate-fade-in-down {
    animation: fadeInDown 0.3s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Custom styles for date and time inputs */
input[type="date"],
input[type="time"] {
    color-scheme: light;
}

input[type="date"]::-webkit-calendar-picker-indicator,
input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(0.5);
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.3s ease;
}

input[type="date"]::-webkit-calendar-picker-indicator:hover,
input[type="time"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}

/* Smooth transitions for form elements */
input:focus,
textarea:focus {
    transform: translateY(-1px);
}

.form-input-icon {
    transition: all 0.3s ease;
}

input:focus + .form-input-icon,
textarea:focus + .form-input-icon {
    color: #3B82F6;
}
</style>
@endpush

@endsection 